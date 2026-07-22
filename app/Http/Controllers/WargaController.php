<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Antrean;
use App\Models\Jadwal;
use App\Models\Pengumuman;
use App\Models\Notifikasi;
use App\Models\AnggotaKeluarga;
use App\Models\LogAudit;
use Carbon\Carbon;

class WargaController extends Controller
{
    /**
     * Show Warga dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get family members
        $members = $user->anggotaKeluarga()->with('pengguna')->get();

        // Get today's queue for this family
        $todayQueue = Antrean::where('pengguna_id', $user->id)
            ->whereDate('tanggal_antrean', Carbon::today())
            ->first();

        // Get active serving queue today
        $activeQueue = Antrean::whereDate('tanggal_antrean', Carbon::today())
            ->where('status', 'dilayani')
            ->first();

        // Get next schedules
        $schedules = Jadwal::whereDate('tanggal_kegiatan', '>=', Carbon::today())
            ->orderBy('tanggal_kegiatan', 'asc')
            ->take(5)
            ->get();

        // Get recent announcements
        $announcements = Pengumuman::orderBy('tanggal_terbit', 'desc')
            ->take(5)
            ->get();

        // Get notifications
        $notifications = Notifikasi::where(function ($q) use ($user) {
                $q->where('penerima_pengguna_id', $user->id)
                  ->orWhereNull('penerima_pengguna_id'); // broadcast
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $readNotificationIds = \DB::table('notifikasi_dibaca')
            ->where('pengguna_id', $user->id)
            ->pluck('notifikasi_id')
            ->toArray();

        foreach ($notifications as $n) {
            if ($n->penerima_pengguna_id) {
                $n->is_read = $n->dibaca;
            } else {
                $n->is_read = in_array($n->id, $readNotificationIds);
            }
        }

        // Query all verified family members health checkup history for the timeline
        $verifiedMemberIds = $members->where('status_verifikasi', 'disetujui')->pluck('id');
        $healthHistories = \App\Models\CatatanKesehatan::with('anggotaKeluarga')
            ->whereIn('anggota_keluarga_id', $verifiedMemberIds)
            ->orderBy('tanggal_periksa', 'desc')
            ->get();

        // Query active Kaders for WhatsApp contacts
        $kaders = \App\Models\Pengguna::whereHas('peran', function ($q) {
            $q->where('nama', 'kader');
        })->get();

        // Construct dynamic reminders
        $reminders = [];
        if ($members->whereIn('kategori', ['bayi', 'balita'])->count() > 0) {
            $reminders[] = 'Jangan lupa membawa Buku KIA (Kesehatan Ibu dan Anak) dan vitamin untuk timbang bulanan anak.';
        }
        if ($members->where('kategori', 'ibu_hamil')->count() > 0) {
            $reminders[] = 'Ingat untuk memantau lingkar lengan dan minum tablet tambah darah (TTD) secara rutin.';
        }
        if ($members->where('kategori', 'lansia')->count() > 0) {
            $reminders[] = 'Bagi lansia, batasi makanan tinggi garam dan rutin periksa tekanan darah di Posyandu.';
        }
        $reminders[] = 'Harap bawa fotokopi Kartu Keluarga (KK) jika ingin mendaftarkan anggota keluarga baru.';

        return view('warga.dashboard', compact(
            'user',
            'members',
            'todayQueue',
            'activeQueue',
            'schedules',
            'announcements',
            'notifications',
            'healthHistories',
            'kaders',
            'reminders'
        ));
    }

    /**
     * Request queue number.
     */
    public function takeQueue()
    {
        $user = Auth::user();

        // Check if already has queue today
        $existingQueue = Antrean::where('pengguna_id', $user->id)
            ->whereDate('tanggal_antrean', Carbon::today())
            ->first();

        if ($existingQueue) {
            return back()->with('error', 'Keluarga Anda sudah mengambil nomor antrean hari ini.');
        }

        // Get next number
        $nextNumber = Antrean::whereDate('tanggal_antrean', Carbon::today())->max('nomor_antrean') + 1;
        $queueCode = 'A-' . $nextNumber;

        $queue = Antrean::create([
            'pengguna_id' => $user->id,
            'tanggal_antrean' => Carbon::today(),
            'nomor_antrean' => $nextNumber,
            'kode_antrean' => $queueCode,
            'status' => 'menunggu',
        ]);

        LogAudit::log('ambil_antrean', "Keluarga Bapak '{$user->kepala_keluarga}' mengambil nomor antrean: {$queueCode}", $user->id);

        return back()->with('success', "Nomor antrean {$queueCode} berhasil diambil!");
    }

    /**
     * Check real-time queue status (JSON for short polling).
     */
    public function queueStatus()
    {
        $user = Auth::user();

        $todayQueue = Antrean::where('pengguna_id', $user->id)
            ->whereDate('tanggal_antrean', Carbon::today())
            ->first();

        $activeQueue = Antrean::whereDate('tanggal_antrean', Carbon::today())
            ->where('status', 'dilayani')
            ->first();

        $waitingCount = Antrean::whereDate('tanggal_antrean', Carbon::today())
            ->where('status', 'menunggu')
            ->where('nomor_antrean', '<', $todayQueue ? $todayQueue->nomor_antrean : 9999)
            ->count();

        return response()->json([
            'has_queue' => !is_null($todayQueue),
            'queue_code' => $todayQueue ? $todayQueue->kode_antrean : null,
            'queue_status' => $todayQueue ? $todayQueue->status : null,
            'queue_status_label' => $todayQueue ? $todayQueue->status_label : null,
            'active_queue_code' => $activeQueue ? $activeQueue->kode_antrean : 'Belum ada',
            'active_queue_number' => $activeQueue ? $activeQueue->nomor_antrean : 0,
            'waiting_behind' => $waitingCount,
        ]);
    }

    /**
     * Update family profile details.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'head_of_family' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $user->update([
            'kepala_keluarga' => $data['head_of_family'],
            'nomor_telepon' => $data['phone'],
            'alamat' => $data['address'],
            'name' => 'Keluarga ' . $data['head_of_family'],
        ]);

        LogAudit::log('perbarui_profil', "Keluarga Bapak '{$user->kepala_keluarga}' memperbarui informasi profil.", $user->id);

        return back()->with('success', 'Informasi keluarga berhasil diperbarui.');
    }

    /**
     * View family member health history & growth details.
     */
    public function memberDetail($id)
    {
        $user = Auth::user();

        $member = AnggotaKeluarga::where('id', $id)
            ->where('pengguna_id', $user->id)
            ->firstOrFail();

        $histories = $member->catatanKesehatan;

        // Baby / Balita growth chart data setup
        $chartData = null;
        if (in_array($member->kategori, ['bayi', 'balita'])) {
            $babyRecords = $member->catatanKesehatan()
                ->whereIn('kategori', ['bayi', 'balita'])
                ->orderBy('tanggal_periksa', 'asc')
                ->get();

            $chartData = [
                'labels' => $babyRecords->map(fn($r) => Carbon::parse($r->tanggal_periksa)->format('d/m/y'))->toArray(),
                'weights' => $babyRecords->map(fn($r) => floatval($r->berat_badan))->toArray(),
                'heights' => $babyRecords->map(fn($r) => floatval($r->tinggi_badan))->toArray(),
                'heads' => $babyRecords->map(fn($r) => $r->lingkar_kepala ? floatval($r->lingkar_kepala) : null)->toArray(),
            ];
        }

        return view('warga.member-detail', compact('member', 'histories', 'chartData'));
    }

    /**
     * Store a new family member registered by the Warga.
     */
    public function storeMember(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nik' => 'required|string|size:16|unique:anggota_keluarga,nik|regex:/^[0-9]+$/',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date|before_or_equal:today',
            'category' => 'required|in:bayi,balita,anak,remaja,dewasa,ibu_hamil,lansia',
        ], [
            'nik.unique' => 'NIK sudah terdaftar di sistem Posyandu.',
            'nik.size' => 'NIK harus berukuran 16 karakter angka.',
            'nik.regex' => 'NIK harus berupa angka.',
            'birth_date.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',
        ]);

        $member = AnggotaKeluarga::create([
            'pengguna_id' => $user->id,
            'nik' => $data['nik'],
            'nama' => $data['name'],
            'jenis_kelamin' => $data['gender'],
            'tanggal_lahir' => $data['birth_date'],
            'kategori' => $data['category'],
            'status_verifikasi' => 'pending',
        ]);

        LogAudit::log(
            'tambah_anggota_warga',
            "Warga mendaftarkan anggota keluarga baru mandiri: {$member->nama} (NIK: {$member->nik}). Status: Menunggu Verifikasi.",
            $user->id
        );

        return back()->with('success', 'Anggota keluarga baru berhasil diajukan. Status saat ini menunggu verifikasi dari Kader.');
    }

    /**
     * Mark notifications as read.
     */
    public function readNotifications()
    {
        $user = Auth::user();
        
        // 1. Mark personal notifications as read
        Notifikasi::where('penerima_pengguna_id', $user->id)
            ->update(['dibaca' => true]);

        // 2. Mark broadcast notifications as read for this user
        $broadcastIds = Notifikasi::whereNull('penerima_pengguna_id')->pluck('id');
        
        foreach ($broadcastIds as $id) {
            \DB::table('notifikasi_dibaca')->insertOrIgnore([
                'pengguna_id' => $user->id,
                'notifikasi_id' => $id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }
}
