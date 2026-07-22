<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\AnggotaKeluarga;
use App\Models\CatatanKesehatan;
use App\Models\Antrean;
use App\Models\Jadwal;
use App\Models\Pengumuman;
use App\Models\Notifikasi;
use App\Models\LogAudit;
use Carbon\Carbon;

class KaderController extends Controller
{
    /**
     * Show Kader dashboard.
     */
    public function dashboard()
    {
        // 1. Calculate statistics
        $totalFamilies = Pengguna::whereHas('peran', function ($q) {
            $q->where('nama', 'warga');
        })->count();

        $totalBumil = AnggotaKeluarga::where('kategori', 'ibu_hamil')->count();
        $totalBayi = AnggotaKeluarga::where('kategori', 'bayi')->count();
        $totalBalita = AnggotaKeluarga::where('kategori', 'balita')->count();
        $totalLansia = AnggotaKeluarga::where('kategori', 'lansia')->count();

        // Kehadiran & Pemeriksaan Hari Ini
        $todayAttendance = Antrean::whereDate('tanggal_antrean', Carbon::today())->count();
        $todayChecks = CatatanKesehatan::whereDate('tanggal_periksa', Carbon::today())->count();

        // 2. Queues for today
        $todayQueues = Antrean::with('pengguna')->whereDate('tanggal_antrean', Carbon::today())
            ->orderBy('nomor_antrean', 'asc')
            ->get();

        $activeQueue = Antrean::with('pengguna')->whereDate('tanggal_antrean', Carbon::today())
            ->where('status', 'dilayani')
            ->first();

        // 3. Recent health checkups
        $recentChecks = CatatanKesehatan::with('anggotaKeluarga.pengguna')
            ->orderBy('tanggal_periksa', 'desc')
            ->take(5)
            ->get();

        // 4. Schedules
        $schedules = Jadwal::whereDate('tanggal_kegiatan', '>=', Carbon::today())
            ->orderBy('tanggal_kegiatan', 'asc')
            ->take(5)
            ->get();

        // 5. Smart Priority Panel Queries
        $prioritasBumil = AnggotaKeluarga::with('pengguna')->where('kategori', 'ibu_hamil')
            ->where('created_at', '<=', Carbon::now()->subDays(30))
            ->whereDoesntHave('catatanKesehatan', function ($q) {
                $q->where('tanggal_periksa', '>=', Carbon::now()->subDays(30));
            })->take(3)->get();

        $prioritasAnak = AnggotaKeluarga::with('pengguna')->whereIn('kategori', ['bayi', 'balita'])
            ->where('created_at', '<=', Carbon::now()->subDays(30))
            ->whereDoesntHave('catatanKesehatan', function ($q) {
                $q->where('tanggal_periksa', '>=', Carbon::now()->subDays(30));
            })->take(3)->get();

        $prioritasLansia = AnggotaKeluarga::with('pengguna')->where('kategori', 'lansia')
            ->where('created_at', '<=', Carbon::now()->subDays(60))
            ->whereDoesntHave('catatanKesehatan', function ($q) {
                $q->where('tanggal_periksa', '>=', Carbon::now()->subDays(60));
            })->take(3)->get();

        $prioritasKeluargaPasif = Pengguna::whereHas('peran', function ($q) {
                $q->where('nama', 'warga');
            })
            ->where('created_at', '<=', Carbon::now()->subDays(90))
            ->whereDoesntHave('anggotaKeluarga.catatanKesehatan', function ($q) {
                $q->where('tanggal_periksa', '>=', Carbon::now()->subDays(90));
            })->take(3)->get();

        // 6. Chart.js Monthly Visitation coordinates (last 6 months)
        $monthlyLabels = [];
        $monthlyVisits = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $monthlyLabels[] = $monthDate->translatedFormat('F');
            $monthlyVisits[] = CatatanKesehatan::whereMonth('tanggal_periksa', $monthDate->month)
                ->whereYear('tanggal_periksa', $monthDate->year)
                ->count();
        }

        // 7. Pending Verifications from Warga
        $pendingVerifications = AnggotaKeluarga::with('pengguna')
            ->pending()
            ->orderBy('created_at', 'asc')
            ->get();

        return view('kader.dashboard', compact(
            'totalFamilies',
            'totalBumil',
            'totalBayi',
            'totalBalita',
            'totalLansia',
            'todayAttendance',
            'todayChecks',
            'todayQueues',
            'activeQueue',
            'recentChecks',
            'schedules',
            'prioritasBumil',
            'prioritasAnak',
            'prioritasLansia',
            'prioritasKeluargaPasif',
            'monthlyLabels',
            'monthlyVisits',
            'pendingVerifications'
        ));
    }

    /**
     * Real-time global search for Posyandu Command Center.
     */
    public function globalSearch(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
        ]);

        $search = trim(strip_tags($request->search));
        if (!$search) {
            return response()->json([]);
        }

        // Search Families (Pengguna)
        $families = Pengguna::whereHas('peran', function ($q) {
                $q->where('nama', 'warga');
            })
            ->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('kepala_keluarga', 'like', "%{$search}%")
                  ->orWhere('nomor_telepon', 'like', "%{$search}%");
            })
            ->take(5)
            ->get();

        // Search Anggota Keluarga
        $members = AnggotaKeluarga::with('pengguna')
            ->where('nama', 'like', "%{$search}%")
            ->orWhere('nik', 'like', "%{$search}%")
            ->take(5)
            ->get();

        $results = [];

        foreach ($families as $f) {
            $results[] = [
                'type' => 'Keluarga',
                'title' => 'Keluarga Bpk. ' . $f->kepala_keluarga,
                'subtitle' => 'No. KK: ' . $f->username . ' | Telp: ' . $f->nomor_telepon,
                'url' => route('kader.families.show', $f->id)
            ];
        }

        foreach ($members as $m) {
            $results[] = [
                'type' => $m->kategori_formatted,
                'title' => $m->nama,
                'subtitle' => 'NIK: ' . $m->nik . ' | KK: ' . ($m->pengguna ? $m->pengguna->username : '-'),
                'url' => $m->pengguna ? route('kader.families.show', $m->pengguna->id) : '#'
            ];
        }

        return response()->json($results);
    }

    /**
     * Poll queue data in real-time.
     */
    public function queuePollData()
    {
        $todayQueues = Antrean::with('pengguna')
            ->whereDate('tanggal_antrean', Carbon::today())
            ->orderBy('nomor_antrean', 'asc')
            ->get();

        $activeQueue = Antrean::with('pengguna')
            ->whereDate('tanggal_antrean', Carbon::today())
            ->where('status', 'dilayani')
            ->first();

        $queuesData = $todayQueues->map(function ($q) {
            return [
                'id' => $q->id,
                'kode_antrean' => $q->kode_antrean,
                'kepala_keluarga' => $q->pengguna ? $q->pengguna->kepala_keluarga : '-',
                'pengguna_id' => $q->pengguna_id,
                'jam_daftar' => $q->created_at->format('H:i'),
                'status' => $q->status,
                'status_label' => $q->status_label,
                'badge_class' => $q->badge_class,
            ];
        });

        return response()->json([
            'active_queue' => $activeQueue ? [
                'id' => $activeQueue->id,
                'kode_antrean' => $activeQueue->kode_antrean,
                'kepala_keluarga' => $activeQueue->pengguna ? $activeQueue->pengguna->kepala_keluarga : '-',
                'username' => $activeQueue->pengguna ? $activeQueue->pengguna->username : '-',
            ] : null,
            'queues' => $queuesData,
        ]);
    }

    /**
     * Change queue status to "dilayani" (Call).
     */
    public function callQueue($id)
    {
        $queue = Antrean::findOrFail($id);
        
        // Reset any other "serving" queues to "waiting"
        Antrean::whereDate('tanggal_antrean', Carbon::today())
            ->where('status', 'dilayani')
            ->update(['status' => 'menunggu']);

        $queue->update([
            'status' => 'dilayani',
            'dipanggil_at' => Carbon::now(),
            'dilayani_at' => Carbon::now(),
        ]);

        LogAudit::log('panggil_antrean', "Kader memanggil antrean {$queue->kode_antrean} ({$queue->pengguna->kepala_keluarga})");

        return back()->with('success', "Memanggil antrean {$queue->kode_antrean}!");
    }

    /**
     * Change queue status to "skipped".
     */
    public function skipQueue($id)
    {
        $queue = Antrean::findOrFail($id);
        $queue->update([
            'status' => 'dilewati',
        ]);

        LogAudit::log('lewati_antrean', "Kader melewatin antrean {$queue->kode_antrean}");

        return back()->with('success', "Antrean {$queue->kode_antrean} dilewati.");
    }

    /**
     * Change queue status to "completed".
     */
    public function completeQueue($id)
    {
        $queue = Antrean::findOrFail($id);
        $queue->update([
            'status' => 'selesai',
            'selesai_at' => Carbon::now(),
        ]);

        LogAudit::log('selesai_antrean', "Kader menyelesaikan antrean {$queue->kode_antrean}");

        return back()->with('success', "Antrean {$queue->kode_antrean} selesai dilayani.");
    }

    /**
     * List families.
     */
    public function families(Request $request)
    {
        $query = Pengguna::whereHas('peran', function ($q) {
            $q->where('nama', 'warga');
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('kepala_keluarga', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $families = $query->withCount('anggotaKeluarga')->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('kader.families.index', compact('families'));
    }

    /**
     * Store new Family (Pengguna accounts with role warga).
     */
    public function storeFamily(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|string|size:16|unique:pengguna,username',
            'head_of_family' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $wargaPeran = Peran::where('nama', 'warga')->firstOrFail();
        
        $pengguna = Pengguna::create([
            'name' => 'Keluarga ' . $request->head_of_family,
            'username' => $request->no_kk,
            'password' => $request->password,
            'peran_id' => $wargaPeran->id,
            'kepala_keluarga' => $request->head_of_family,
            'nomor_telepon' => $request->phone,
            'alamat' => $request->address,
        ]);

        LogAudit::log('tambah_keluarga', "Kader mendaftarkan keluarga baru dengan No. KK: {$pengguna->username}");

        return back()->with('success', 'Akun Keluarga berhasil dibuat.');
    }

    /**
     * Show family profile details & members CRUD.
     */
    public function showFamily($id)
    {
        $family = Pengguna::whereHas('peran', function ($q) {
            $q->where('nama', 'warga');
        })->findOrFail($id);

        $members = $family->anggotaKeluarga;

        return view('kader.families.show', compact('family', 'members'));
    }

    /**
     * Store new family member.
     */
    public function storeMember(Request $request, $familyId)
    {
        $family = Pengguna::findOrFail($familyId);

        $request->validate([
            'nik' => 'required|string|size:16|unique:anggota_keluarga,nik',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'category' => 'required|in:ibu_hamil,bayi,balita,anak,remaja,dewasa,lansia',
        ]);

        $member = AnggotaKeluarga::create([
            'pengguna_id' => $family->id,
            'nik' => $request->nik,
            'nama' => $request->name,
            'jenis_kelamin' => $request->gender,
            'tanggal_lahir' => $request->birth_date,
            'kategori' => $request->category,
        ]);

        LogAudit::log('tambah_anggota', "Kader mendaftarkan anggota keluarga baru: {$member->nama} (NIK: {$member->nik})");

        return back()->with('success', "Anggota keluarga {$member->nama} berhasil ditambahkan.");
    }

    /**
     * Edit family member category or details.
     */
    public function updateMember(Request $request, $id)
    {
        $member = AnggotaKeluarga::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'category' => 'required|in:ibu_hamil,bayi,balita,anak,remaja,dewasa,lansia',
        ]);

        $oldCategory = $member->kategori;
        
        $member->update([
            'nama' => $request->name,
            'jenis_kelamin' => $request->gender,
            'tanggal_lahir' => $request->birth_date,
            'kategori' => $request->category,
        ]);

        LogAudit::log('ubah_anggota', "Kader mengubah profil anggota: {$member->nama} (Kategori: {$oldCategory} -> {$member->kategori})");

        return back()->with('success', 'Profil anggota keluarga berhasil diperbarui.');
    }

    /**
     * Delete family member.
     */
    public function deleteMember($id)
    {
        $member = AnggotaKeluarga::findOrFail($id);
        $name = $member->nama;
        $member->delete();

        LogAudit::log('hapus_anggota', "Kader menghapus anggota keluarga: {$name}");

        return back()->with('success', "Anggota keluarga {$name} berhasil dihapus.");
    }

    /**
     * Verify family member registered by the Warga.
     */
    public function verifyMember($id, $action)
    {
        if (!in_array($action, ['disetujui', 'ditolak'])) {
            abort(400, 'Aksi tidak valid.');
        }

        $member = AnggotaKeluarga::findOrFail($id);
        $member->update([
            'status_verifikasi' => $action
        ]);

        $logAction = $action === 'disetujui' ? 'setujui_verifikasi' : 'tolak_verifikasi';
        $logMsg = $action === 'disetujui' 
            ? "Kader menyetujui pendaftaran anggota baru: {$member->nama} (NIK: {$member->nik})"
            : "Kader menolak pendaftaran anggota baru: {$member->nama} (NIK: {$member->nik})";

        LogAudit::log($logAction, $logMsg);

        $statusText = $action === 'disetujui' ? 'disetujui' : 'ditolak';
        return back()->with('success', "Pendaftaran anggota keluarga {$member->nama} telah {$statusText}.");
    }

    /**
     * Form to add health checkup.
     */
    public function createHealthCheck($memberId)
    {
        $member = AnggotaKeluarga::findOrFail($memberId);
        return view('kader.health.check', compact('member'));
    }

    /**
     * Store health checkup details.
     */
    public function storeHealthCheck(Request $request, $memberId)
    {
        $member = AnggotaKeluarga::findOrFail($memberId);
        $category = $member->kategori;

        $checkDate = Carbon::today()->format('Y-m-d');
        $summary = '';

        if ($category === 'ibu_hamil') {
            $data = $request->validate([
                'weight' => 'required|numeric',
                'blood_pressure' => 'required|string',
                'pregnancy_age_weeks' => 'required|integer|min:1',
                'fundal_height' => 'nullable|numeric',
                'complaints' => 'nullable|string',
                'immunizations' => 'nullable|string',
                'vitamins' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            $record = CatatanKesehatan::create([
                'anggota_keluarga_id' => $member->id,
                'kategori' => $category,
                'tanggal_periksa' => $checkDate,
                'berat_badan' => $data['weight'],
                'tekanan_darah' => $data['blood_pressure'],
                'usia_kehamilan' => $data['pregnancy_age_weeks'],
                'tinggi_fundus' => $data['fundal_height'],
                'keluhan' => $data['complaints'],
                'imunisasi' => $data['immunizations'],
                'vitamin' => $data['vitamins'],
                'catatan' => $data['notes'],
            ]);

            $summary = "Pemeriksaan Ibu Hamil: BB {$data['weight']} kg, TD {$data['blood_pressure']} mmHg, UK {$data['pregnancy_age_weeks']} minggu.";

        } elseif ($category === 'bayi' || $category === 'balita') {
            $data = $request->validate([
                'weight' => 'required|numeric',
                'height' => 'required|numeric',
                'head_circumference' => 'nullable|numeric',
                'immunizations' => 'nullable|string',
                'vitamins' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            $record = CatatanKesehatan::create([
                'anggota_keluarga_id' => $member->id,
                'kategori' => $category,
                'tanggal_periksa' => $checkDate,
                'berat_badan' => $data['weight'],
                'tinggi_badan' => $data['height'],
                'lingkar_kepala' => $data['head_circumference'],
                'imunisasi' => $data['immunizations'],
                'vitamin' => $data['vitamins'],
                'catatan' => $data['notes'],
            ]);

            $summary = "Penimbangan {$member->category_formatted}: BB {$data['weight']} kg, TB {$data['height']} cm.";

        } elseif ($category === 'lansia') {
            $data = $request->validate([
                'weight' => 'required|numeric',
                'blood_pressure' => 'required|string',
                'blood_sugar' => 'nullable|integer',
                'complaints' => 'nullable|string',
                'medicines' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            $record = CatatanKesehatan::create([
                'anggota_keluarga_id' => $member->id,
                'kategori' => $category,
                'tanggal_periksa' => $checkDate,
                'berat_badan' => $data['weight'],
                'tekanan_darah' => $data['blood_pressure'],
                'gula_darah' => $data['blood_sugar'],
                'keluhan' => $data['complaints'],
                'obat' => $data['medicines'],
                'catatan' => $data['notes'],
            ]);

            $summary = "Pemeriksaan Lansia: BB {$data['weight']} kg, TD {$data['blood_pressure']} mmHg.";
            if ($data['blood_sugar']) {
                $summary .= " Gula Darah: {$data['blood_sugar']} mg/dL.";
            }

        } else {
            // General Category (Anak/Remaja/Dewasa)
            $data = $request->validate([
                'weight' => 'required|numeric',
                'height' => 'nullable|numeric',
                'blood_pressure' => 'nullable|string',
                'complaints' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            $record = CatatanKesehatan::create([
                'anggota_keluarga_id' => $member->id,
                'kategori' => $category,
                'tanggal_periksa' => $checkDate,
                'berat_badan' => $data['weight'],
                'tinggi_badan' => $data['height'],
                'tekanan_darah' => $data['blood_pressure'],
                'keluhan' => $data['complaints'],
                'catatan' => $data['notes'],
            ]);

            $summary = "Pemeriksaan Kesehatan Umum ({$member->category_formatted}): BB {$data['weight']} kg.";
        }

        LogAudit::log('catat_pemeriksaan', "Kader mencatat pemeriksaan kesehatan untuk {$member->nama}: {$summary}");

        return redirect()->route('kader.families.show', $member->pengguna_id)->with('success', 'Catatan pemeriksaan berhasil disimpan.');
    }

    /**
     * View reports & list checkups history.
     */
    public function reports(Request $request)
    {
        $query = CatatanKesehatan::with('anggotaKeluarga.pengguna');

        if ($request->filled('category')) {
            $query->where('kategori', $request->category);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_periksa', [$request->start_date, $request->end_date]);
        }

        $histories = $query->orderBy('tanggal_periksa', 'desc')->paginate(20)->withQueryString();

        return view('kader.reports', compact('histories'));
    }

    /**
     * Export reports data to CSV file.
     */
    public function exportCsv(Request $request)
    {
        $query = CatatanKesehatan::with('anggotaKeluarga.pengguna');

        if ($request->filled('category')) {
            $query->where('kategori', $request->category);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_periksa', [$request->start_date, $request->end_date]);
        }

        $histories = $query->orderBy('tanggal_periksa', 'desc')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Laporan_Posyandu_" . date('Ymd_His') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($histories) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header Row
            fputcsv($file, ['Tanggal Periksa', 'No KK', 'Nama Kepala Keluarga', 'Nama Anggota', 'NIK', 'Kategori', 'Ringkasan Pemeriksaan']);

            foreach ($histories as $history) {
                // Get summary
                $summaryText = "BB {$history->berat_badan} kg";
                if ($history->tinggi_badan) $summaryText .= ", TB {$history->tinggi_badan} cm";
                if ($history->tekanan_darah) $summaryText .= ", TD {$history->tekanan_darah}";
                if ($history->gula_darah) $summaryText .= ", Gula Darah {$history->gula_darah} mg/dL";
                if ($history->keluhan) $summaryText .= ". Keluhan: {$history->keluhan}";
                if ($history->catatan) $summaryText .= ". Catatan: {$history->catatan}";

                fputcsv($file, [
                    $history->tanggal_periksa->format('Y-m-d'),
                    $history->anggotaKeluarga->pengguna->username,
                    $history->anggotaKeluarga->pengguna->kepala_keluarga,
                    $history->anggotaKeluarga->nama,
                    $history->anggotaKeluarga->nik,
                    $history->anggotaKeluarga->category_formatted,
                    $summaryText
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Schedules Management views.
     */
    public function schedules()
    {
        $schedules = Jadwal::orderBy('tanggal_kegiatan', 'desc')->paginate(10);
        return view('kader.schedules', compact('schedules'));
    }

    public function storeSchedule(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Jadwal::create([
            'judul' => $data['title'],
            'tanggal_kegiatan' => $data['event_date'],
            'jam_mulai' => $data['start_time'],
            'jam_selesai' => $data['end_time'],
            'tempat' => $data['location'],
            'deskripsi' => $data['description'],
        ]);

        LogAudit::log('tambah_jadwal', "Kader menambahkan jadwal kegiatan baru: {$data['title']}");

        return back()->with('success', 'Jadwal kegiatan berhasil disimpan.');
    }

    public function deleteSchedule($id)
    {
        $schedule = Jadwal::findOrFail($id);
        $title = $schedule->judul;
        $schedule->delete();

        LogAudit::log('hapus_jadwal', "Kader menghapus jadwal kegiatan: {$title}");
        return back()->with('success', 'Jadwal kegiatan berhasil dihapus.');
    }

    /**
     * Announcements Management views.
     */
    public function announcements()
    {
        $announcements = Pengumuman::orderBy('tanggal_terbit', 'desc')->paginate(10);
        return view('kader.announcements', compact('announcements'));
    }

    public function storeAnnouncement(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Pengumuman::create([
            'judul' => $data['title'],
            'konten' => $data['content'],
            'tanggal_terbit' => Carbon::now(),
            'pembuat_pengguna_id' => Auth::id(),
        ]);

        LogAudit::log('tambah_pengumuman', "Kader memposting pengumuman baru: {$data['title']}");

        return back()->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    public function deleteAnnouncement($id)
    {
        $ann = Pengumuman::findOrFail($id);
        $title = $ann->judul;
        $ann->delete();

        LogAudit::log('hapus_pengumuman', "Kader menghapus pengumuman: {$title}");
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }

    /**
     * Send specific Notification to a Family.
     */
    public function notifications()
    {
        $families = Pengguna::whereHas('peran', function ($q) {
            $q->where('nama', 'warga');
        })->orderBy('kepala_keluarga', 'asc')->get();

        $notifications = Notifikasi::with('penerima')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('kader.notifications', compact('families', 'notifications'));
    }

    public function storeNotification(Request $request)
    {
        $data = $request->validate([
            'family_id' => 'nullable|exists:pengguna,id', // null means broadcast
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Notifikasi::create([
            'penerima_pengguna_id' => $data['family_id'],
            'judul' => $data['title'],
            'pesan' => $data['message'],
        ]);
        
        $target = $data['family_id'] ? "Keluarga ID: {$data['family_id']}" : "Semua Keluarga (Broadcast)";
        LogAudit::log('kirim_notifikasi', "Kader mengirim notifikasi ke {$target}: {$data['title']}");

        return back()->with('success', 'Notifikasi berhasil dikirim.');
    }

    /**
     * View System Audit Logs.
     */
    public function auditLogs()
    {
        $logs = LogAudit::with('pengguna')->orderBy('created_at', 'desc')->paginate(30);
        return view('kader.audit-logs', compact('logs'));
    }
}
