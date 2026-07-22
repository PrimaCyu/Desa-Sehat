<?php

namespace Tests\Feature;

use App\Models\AnggotaKeluarga;
use App\Models\Antrean;
use App\Models\Notifikasi;
use App\Models\Pengguna;
use App\Models\Peran;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PosyanduServiceTest extends TestCase
{
    use RefreshDatabase;

    private $kaderRole;
    private $wargaRole;
    private $kaderUser;
    private $wargaUser1;
    private $wargaUser2;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Roles
        $this->kaderRole = Peran::create([
            'nama' => 'kader',
            'display_peran' => 'Kader Posyandu',
        ]);

        $this->wargaRole = Peran::create([
            'nama' => 'warga',
            'display_peran' => 'Warga',
        ]);

        // 2. Create Users
        $this->kaderUser = Pengguna::create([
            'name' => 'Kader Ani',
            'username' => 'kader',
            'password' => bcrypt('password'),
            'peran_id' => $this->kaderRole->id,
        ]);

        $this->wargaUser1 = Pengguna::create([
            'name' => 'Keluarga Budi',
            'username' => '1234567890123456',
            'password' => bcrypt('password'),
            'peran_id' => $this->wargaRole->id,
            'kepala_keluarga' => 'Budi Utomo',
        ]);

        $this->wargaUser2 = Pengguna::create([
            'name' => 'Keluarga Slamet',
            'username' => '6543210987654321',
            'password' => bcrypt('password'),
            'peran_id' => $this->wargaRole->id,
            'kepala_keluarga' => 'Slamet',
        ]);
    }

    /** @test */
    public function test_guests_are_redirected_to_login()
    {
        $response = $this->get('/warga/dashboard');
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function test_users_cannot_access_unauthorized_dashboards()
    {
        // Warga trying to access Kader dashboard -> 403
        $this->actingAs($this->wargaUser1);
        $response = $this->get('/kader/dashboard');
        $response->assertStatus(403);

        // Kader trying to access Warga dashboard -> 403
        $this->actingAs($this->kaderUser);
        $response = $this->get('/warga/dashboard');
        $response->assertStatus(403);
    }

    /** @test */
    public function test_warga_cannot_view_other_family_member_details()
    {
        // Create family member for Warga 1
        $member = AnggotaKeluarga::create([
            'pengguna_id' => $this->wargaUser1->id,
            'nik' => '3201010304050001',
            'nama' => 'Andi Utomo',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2023-01-01',
            'kategori' => 'balita',
        ]);

        // Warga 2 trying to view Warga 1 member -> 404
        $this->actingAs($this->wargaUser2);
        $response = $this->get("/warga/member/{$member->id}");
        $response->assertStatus(404);
    }

    /** @test */
    public function test_kader_cannot_view_non_warga_details_as_family()
    {
        // Kader trying to open showFamily page for a Kader account -> 404 (Security fix validation)
        $this->actingAs($this->kaderUser);
        $response = $this->get("/kader/families/{$this->kaderUser->id}");
        $response->assertStatus(404);

        // Kader opening showFamily for a Warga account -> 200
        $response = $this->get("/kader/families/{$this->wargaUser1->id}");
        $response->assertStatus(200);
    }

    /** @test */
    public function test_queue_taking_and_management_flows_correctly()
    {
        // 1. Warga 1 takes queue
        $this->actingAs($this->wargaUser1);
        $response = $this->post(route('warga.queue.take'));
        $response->assertStatus(302); // Redirect back
        
        $this->assertDatabaseHas('antrean', [
            'pengguna_id' => $this->wargaUser1->id,
            'nomor_antrean' => 1,
            'kode_antrean' => 'A-1',
            'status' => 'menunggu',
        ]);

        // 2. Warga 1 tries to take double queue today -> error redirect
        $response = $this->post(route('warga.queue.take'));
        $response->assertSessionHas('error');

        // 3. Kader manages the queue (Call, Skip, Complete)
        $this->actingAs($this->kaderUser);
        $queue = Antrean::where('pengguna_id', $this->wargaUser1->id)->firstOrFail();

        // 3.1 Call queue
        $response = $this->post(route('kader.queue.call', $queue->id));
        $response->assertStatus(302);
        $this->assertEquals('dilayani', $queue->fresh()->status);

        // 3.2 Skip queue
        $response = $this->post(route('kader.queue.skip', $queue->id));
        $response->assertStatus(302);
        $this->assertEquals('dilewati', $queue->fresh()->status);

        // 3.3 Complete queue
        $response = $this->post(route('kader.queue.complete', $queue->id));
        $response->assertStatus(302);
        $this->assertEquals('selesai', $queue->fresh()->status);
    }

    /** @test */
    public function test_broadcast_notifications_are_tracked_independently_per_user()
    {
        // Create broadcast notification
        $broadcast = Notifikasi::create([
            'penerima_pengguna_id' => null, // broadcast
            'judul' => 'Agenda Imunisasi Besar',
            'pesan' => 'Hari Sabtu depan Posyandu buka mulai jam 07:00.',
        ]);

        // Warga 1 marks notifications as read
        $this->actingAs($this->wargaUser1);
        $response = $this->post(route('warga.notifications.read'));
        $response->assertJson(['success' => true]);

        // Verify in pivot table that Warga 1 has read it
        $this->assertDatabaseHas('notifikasi_dibaca', [
            'pengguna_id' => $this->wargaUser1->id,
            'notifikasi_id' => $broadcast->id,
        ]);

        // Verify that Warga 2 has NOT read it (pivot table shouldn't have record for Warga 2)
        $this->assertDatabaseMissing('notifikasi_dibaca', [
            'pengguna_id' => $this->wargaUser2->id,
            'notifikasi_id' => $broadcast->id,
        ]);
    }

    /** @test */
    public function test_reports_export_csv_returns_stream_response()
    {
        $this->actingAs($this->kaderUser);
        $response = $this->get(route('kader.reports.export'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    /** @test */
    public function test_warga_can_register_new_member_with_pending_status()
    {
        $this->actingAs($this->wargaUser1);

        $response = $this->post(route('warga.member.store'), [
            'nik' => '3201010304050002',
            'name' => 'Caca Utomo',
            'gender' => 'P',
            'birth_date' => '2025-01-01',
            'category' => 'bayi',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('anggota_keluarga', [
            'pengguna_id' => $this->wargaUser1->id,
            'nik' => '3201010304050002',
            'nama' => 'Caca Utomo',
            'status_verifikasi' => 'pending',
        ]);
    }

    /** @test */
    public function test_warga_cannot_register_duplicate_nik()
    {
        $this->actingAs($this->wargaUser1);

        // Pre-create member
        AnggotaKeluarga::create([
            'pengguna_id' => $this->wargaUser1->id,
            'nik' => '3201010304050002',
            'nama' => 'Caca Utomo',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2025-01-01',
            'kategori' => 'bayi',
            'status_verifikasi' => 'disetujui',
        ]);

        // Attempt duplicate registration
        $response = $this->post(route('warga.member.store'), [
            'nik' => '3201010304050002',
            'name' => 'Caca Utomo KW',
            'gender' => 'P',
            'birth_date' => '2025-01-01',
            'category' => 'bayi',
        ]);

        $response->assertSessionHasErrors('nik');
    }

    /** @test */
    public function test_kader_can_approve_pending_member()
    {
        // 1. Create a pending member for Warga 1
        $member = AnggotaKeluarga::create([
            'pengguna_id' => $this->wargaUser1->id,
            'nik' => '3201010304050003',
            'nama' => 'Dodi Utomo',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2020-05-05',
            'kategori' => 'balita',
            'status_verifikasi' => 'pending',
        ]);

        // 2. Kader logs in and approves
        $this->actingAs($this->kaderUser);
        $response = $this->post(route('kader.members.verify', [$member->id, 'disetujui']));

        $response->assertStatus(302);
        $this->assertEquals('disetujui', $member->fresh()->status_verifikasi);
    }

    /** @test */
    public function test_kader_can_reject_pending_member()
    {
        // 1. Create a pending member for Warga 1
        $member = AnggotaKeluarga::create([
            'pengguna_id' => $this->wargaUser1->id,
            'nik' => '3201010304050004',
            'nama' => 'Eka Utomo',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2021-06-06',
            'kategori' => 'balita',
            'status_verifikasi' => 'pending',
        ]);

        // 2. Kader logs in and rejects
        $this->actingAs($this->kaderUser);
        $response = $this->post(route('kader.members.verify', [$member->id, 'ditolak']));

        $response->assertStatus(302);
        $this->assertEquals('ditolak', $member->fresh()->status_verifikasi);
    }
}
