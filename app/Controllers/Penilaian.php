<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Penilaian extends BaseController
{
    // Halaman utama bagi penguji: Daftar Siswa yang harus dinilai hari ini
    // Halaman utama bagi penguji: Daftar Siswa yang harus dinilai hari ini
    public function index()
    {
        $id_user    = session()->get('id_user');
        $id_event   = session()->get('active_event_id');
        $filterDate = $this->request->getGet('date') ?? date('Y-m-d');

        // [BARU]: Tangkap input filter POS dari tab/dropdown yang dipilih penguji
        $filterPos  = $this->request->getGet('id_pos');

        $epModel     = new \App\Models\EventPengujiModel();
        $jadwalModel = new \App\Models\JadwalSiswaModel();
        $nilaiM      = new \App\Models\NilaiPraktekModel();
        $elemenM     = new \App\Models\ElemenModel();
        $siswaModel  = new \App\Models\SiswaModel();

        // ===============================
        // Mapping nomor urut siswa global event
        // ===============================
        $semuaSiswaEvent = $siswaModel
            ->where('siswa.id_event', $id_event)
            ->orderBy('kelas', 'ASC')
            ->orderBy('nama_siswa', 'ASC')
            ->findAll();

        $mappingNoUrut = [];
        foreach ($semuaSiswaEvent as $index => $s) {
            $mappingNoUrut[$s['id_siswa']] = $index + 1;
        }

        // ===============================
        // [PERBAIKAN]: Ambil SEMUA plotting penguji (Bukan cuma satu/first)
        // ===============================
        $allPlotting = $epModel->where([
            'id_user'  => $id_user,
            'event_penguji.id_event' => $id_event,
            'tanggal'  => $filterDate
        ])
            ->join('pos_ukk', 'pos_ukk.id_pos = event_penguji.id_pos')
            ->findAll(); // <-- Diubah menjadi findAll() untuk mendukung multi-pos

        // Kapasitas Sesi
        $kapasitas_sesi = (int) (session()->get('active_event_capacity') ?? 2);
        if ($kapasitas_sesi < 1) {
            $kapasitas_sesi = 2;
        }

        // Jika tidak ada plotting sama sekali pada tanggal tersebut
        if (empty($allPlotting)) {
            return view('penguji/daftar_antrian', [
                'title'                   => 'Antrian Penilaian',
                'kapasitas_sesi'          => $kapasitas_sesi,
                'siswa'                   => [],
                'pos'                     => ['nama_pos' => 'Tidak ada jadwal'],
                'list_pos'                => [], // Array kosong agar view tidak error saat looping tab
                'active_pos_id'           => null,
                'tanggal_filter'          => $filterDate,
                'status_jam'              => [],
                'total_elemen'            => 0,
                'persentase_global'       => 0,
                'total_siswa'             => 0,
                'is_empty'                => true,
                'total_nilai_masuk'       => 0,
                'total_nilai_harus_diisi' => 0,
                'nilaiMap'                => []
            ]);
        }

        // ===============================
        // [BARU]: Tentukan POS mana yang sedang aktif dibuka
        // ===============================
        $activePlotting = null;
        if ($filterPos) {
            foreach ($allPlotting as $p) {
                if ($p['id_pos'] == $filterPos) {
                    $activePlotting = $p;
                    break;
                }
            }
        }

        // Jika id_pos di URL kosong atau tidak valid (tidak dimiliki penguji), default ke POS pertama
        if (!$activePlotting) {
            $activePlotting = $allPlotting[0];
        }

        $activePosId = $activePlotting['id_pos'];


        // ===============================
        // Ambil siswa sesuai POS AKTIF & tanggal
        // ===============================
        $siswa = $jadwalModel
            ->where([
                'penjadwalan.id_pos'   => $activePosId, // Gunakan ID Pos Aktif
                'tanggal_ujian'        => $filterDate,
                'penjadwalan.id_event' => $id_event
            ])
            ->join('siswa', 'siswa.id_siswa = penjadwalan.id_siswa')
            ->select('penjadwalan.*, siswa.nama_siswa, siswa.nisn, siswa.kelas')
            ->orderBy('siswa.kelas', 'ASC')
            ->orderBy('siswa.nama_siswa', 'ASC')
            ->findAll();

        // ===============================
        // Hitung total elemen di POS AKTIF ini
        // ===============================
        $totalElemen = $elemenM
            ->where('id_pos', $activePosId) // Gunakan ID Pos Aktif
            ->countAllResults();

        // ===============================
        // OPTIMASI: Ambil jumlah nilai siswa sesuai POS AKTIF
        // ===============================
        $nilaiGrouped = $nilaiM
            ->select('nilai_praktek.id_siswa')
            ->select('COUNT(nilai_praktek.id_elemen) as total_terisi')
            ->select('SUM(CASE WHEN is_kompeten REGEXP "^[0-9]+$" THEN CAST(is_kompeten AS UNSIGNED) ELSE 0 END) as total_skor')
            ->join('elemen_penilaian', 'elemen_penilaian.id_elemen = nilai_praktek.id_elemen')
            ->where('nilai_praktek.id_event', $id_event)
            ->where('elemen_penilaian.id_pos', $activePosId) // Gunakan ID Pos Aktif
            ->groupBy('nilai_praktek.id_siswa')
            ->findAll();

        $nilaiMap = [];
        foreach ($nilaiGrouped as $n) {
            $skor = (float) $n['total_skor'];
            $rataRata = ($totalElemen > 0) ? ($skor / $totalElemen) : 0;

            $nilaiMap[$n['id_siswa']] = [
                'terisi'     => (int) $n['total_terisi'],
                'total_skor' => $skor,
                'rata_rata'  => round($rataRata, 2),
                'status'     => ($rataRata >= 75) ? 'KOMPETEN' : 'BELUM KOMPETEN'
            ];
        }

        // Susun status per jam
        $statusJam = [];
        foreach ($siswa as $s) {
            $jumlahNilai = $nilaiMap[$s['id_siswa']] ?? 0;
            $statusJam[$s['jam_mulai']][$s['id_siswa']] = $jumlahNilai;
        }

        // Hitung progress global
        $totalSiswa = count($siswa);
        $totalNilaiYangHarusDiisi = $totalSiswa * $totalElemen;
        $totalNilaiMasuk = 0;

        foreach ($siswa as $s) {
            $terisi = $nilaiMap[$s['id_siswa']]['terisi'] ?? 0;
            $totalNilaiMasuk += min($terisi, $totalElemen);
        }

        $persentaseGlobal = ($totalNilaiYangHarusDiisi > 0)
            ? round(($totalNilaiMasuk / $totalNilaiYangHarusDiisi) * 100)
            : 0;

        // Return ke view dengan data tambahan list_pos dan active_pos_id
        return view('penguji/daftar_antrian', [
            'title'                   => 'Antrian Penilaian',
            'kapasitas_sesi'          => $kapasitas_sesi,
            'siswa'                   => $siswa,
            'mapping_no'              => $mappingNoUrut,
            'pos'                     => $activePlotting,   // Data POS yang sedang aktif beralih
            'list_pos'                => $allPlotting,      // [BARU]: Semua POS yang diuji hari ini untuk tombol TAB
            'active_pos_id'           => $activePosId,     // [BARU]: ID POS yang aktif untuk kelas CSS 'active'
            'tanggal_filter'          => $filterDate,
            'status_jam'              => $statusJam,
            'total_elemen'            => $totalElemen,
            'persentase_global'       => $persentaseGlobal,
            'total_siswa'             => $totalSiswa,
            'nilaiMap'                => $nilaiMap,
            'total_nilai_masuk'       => $totalNilaiMasuk,
            'total_nilai_harus_diisi' => $totalNilaiYangHarusDiisi
        ]);
    }

    public function input_dual($sesi_ke, $id_pos)
    {
        $db      = \Config\Database::connect();
        $elemenM = new \App\Models\ElemenModel();
        $nilaiM  = new \App\Models\NilaiPraktekModel();
        $catatanM = new \App\Models\CatatanPenilaianModel(); // Model Catatan Baru

        $id_event = session()->get('active_event_id');
        $filterDate = $this->request->getGet('date') ?? date('Y-m-d');
        $kapasitas_sesi = session()->get('active_event_capacity') ?? 2;

        $limit  = $kapasitas_sesi;
        $offset = ($sesi_ke - 1) * $limit;

        $siswa_list = $db->table('penjadwalan j')
            ->select('s.id_siswa, s.nama_siswa, s.nisn, s.kelas')
            ->join('siswa s', 's.id_siswa = j.id_siswa')
            ->where([
                'j.id_pos'        => $id_pos,
                'j.id_event'      => $id_event,
                'j.tanggal_ujian' => $filterDate
            ])
            ->orderBy('s.kelas', 'ASC')
            ->orderBy('s.nama_siswa', 'ASC')
            ->limit($limit, $offset)
            ->get()->getResultArray();

        $existing_nilai = [];
        $catatan_siswa = []; // Array untuk menampung catatan per siswa

        if (!empty($siswa_list)) {
            $ids = array_column($siswa_list, 'id_siswa');

            // Ambil Nilai
            $res = $nilaiM->whereIn('id_siswa', $ids)->where('id_event', $id_event)->findAll();
            foreach ($res as $row) {
                $existing_nilai[$row['id_siswa']][$row['id_elemen']] = $row['is_kompeten'];
            }

            // Ambil Catatan Umum
            $res_catatan = $catatanM->whereIn('id_siswa', $ids)
                ->where('id_event', $id_event)
                ->where('id_pos', $id_pos)
                ->findAll();
            foreach ($res_catatan as $row) {
                $catatan_siswa[$row['id_siswa']] = $row['catatan'];
            }
        }

        $data = [
            'title'          => 'Penilaian Pasangan - Sesi ' . $sesi_ke,
            'kapasitas_sesi' => $kapasitas_sesi,
            'siswa_list'     => $siswa_list,
            'id_pos'         => $id_pos,
            'sesi_ke'        => $sesi_ke,
            'current_date'   => $filterDate,
            'pos'            => $db->table('pos_ukk')->where('id_pos', $id_pos)->get()->getRowArray(),
            'elemen'         => $elemenM->where('id_pos', $id_pos)->orderBy('CAST(nama_elemen AS UNSIGNED)', 'ASC')->findAll(),
            'existing'       => $existing_nilai,
            'catatan_siswa'  => $catatan_siswa // Lempar ke view
        ];

        return view('penguji/form_nilai_dual', $data);
    }

    // Tambahkan method baru khusus untuk menyimpan catatan
    public function simpan_catatan()
    {
        if ($this->request->isAJAX()) {
            $catatanM = new \App\Models\CatatanPenilaianModel();
            $id_event = session()->get('active_event_id');

            $id_siswa = $this->request->getPost('id_siswa');
            $id_pos   = $this->request->getPost('id_pos');
            $catatan  = $this->request->getPost('catatan');

            $existing = $catatanM->where([
                'id_siswa' => $id_siswa,
                'id_pos'   => $id_pos,
                'id_event' => $id_event
            ])->first();

            $data = [
                'id_event' => $id_event,
                'id_pos'   => $id_pos,
                'id_siswa' => $id_siswa,
                'catatan'  => $catatan
            ];

            if ($existing) {
                $catatanM->update($existing['id_catatan'], $data);
            } else {
                $catatanM->insert($data);
            }

            return $this->response->setJSON(['status' => 'success']);
        }
    }

    public function simpan_dual()
    {
        $nilaiM = new \App\Models\NilaiPraktekModel();
        $id_event = session()->get('active_event_id');

        // Mengambil data array 'siswa' dari form
        $data_post = $this->request->getPost('siswa'); // contains id_siswa
        $nilai_post = $this->request->getPost('nilai'); // contains [index_siswa][id_elemen] = value

        foreach ($data_post as $index => $s) {
            $id_siswa = $s['id_siswa'];

            if (isset($nilai_post[$index])) {
                foreach ($nilai_post[$index] as $id_elemen => $value) {
                    // Cari data lama jika ingin update
                    $existing = $nilaiM->where([
                        'id_siswa' => $id_siswa,
                        'id_elemen' => $id_elemen,
                        'id_event' => $id_event
                    ])->first();

                    $payload = [
                        'id_event'    => $id_event,
                        'id_siswa'    => $id_siswa,
                        'id_elemen'   => $id_elemen,
                        'is_kompeten' => $value
                    ];

                    if ($existing) {
                        $nilaiM->update($existing['id_nilai'], $payload);
                    } else {
                        $nilaiM->insert($payload);
                    }
                }
            }
        }

        return redirect()->to('/penilaian')->with('success', 'Nilai dua siswa berhasil disimpan sekaligus!');
    }

    public function simpan_single()
    {
        if ($this->request->isAJAX()) {
            $nilaiM = new \App\Models\NilaiPraktekModel();
            $id_event = session()->get('active_event_id');

            $id_siswa    = $this->request->getPost('id_siswa');
            $id_elemen   = $this->request->getPost('id_elemen');
            $is_kompeten = $this->request->getPost('is_kompeten'); // Akan berisi 25, 70, 85, atau 100

            $existing = $nilaiM->where([
                'id_siswa'  => $id_siswa,
                'id_elemen' => $id_elemen,
                'id_event'  => $id_event
            ])->first();

            $data = [
                'id_event'    => $id_event,
                'id_siswa'    => $id_siswa,
                'id_elemen'   => $id_elemen,
                'is_kompeten' => $is_kompeten
            ];

            if ($existing) {
                $nilaiM->update($existing['id_nilai'], $data);
            } else {
                $nilaiM->insert($data);
            }

            return $this->response->setJSON(['status' => 'success', 'message' => 'Nilai ' . $is_kompeten . ' tersimpan']);
        }
    }


    public function nilai_teori()
    {
        $db = \Config\Database::connect();
        $id_event = session()->get('active_event_id');

        $siswa = $db->table('penjadwalan j')
            ->select('
            s.id_siswa, 
            s.no_peserta, 
            s.nama_siswa, 
            s.nisn, 
            s.kelas, 
            nt.nilai_angka,
            nt.is_locked
        ')
            ->join('siswa s', 's.id_siswa = j.id_siswa')
            ->join('nilai_teori nt', 'nt.id_siswa = s.id_siswa AND nt.id_event = j.id_event', 'left')
            ->where('j.id_event', $id_event)

            // --- TAMBAHKAN BARIS INI ---
            ->groupBy('s.id_siswa')
            // ---------------------------

            ->orderBy('s.kelas', 'ASC')
            ->orderBy('s.nama_siswa', 'ASC')
            ->get()->getResultArray();

        return view('admin/nilai_teori', [
            'title' => 'Input Nilai Teori',
            'siswa' => $siswa
        ]);
    }

    public function simpan_teori_ajax()
    {
        $db = \Config\Database::connect();
        $id_event = session()->get('active_event_id');
        $id_siswa = $this->request->getPost('id_siswa');
        $nilai    = $this->request->getPost('nilai');

        // Cek apakah data sudah ada untuk siswa tersebut di event ini
        $exist = $db->table('nilai_teori')
            ->where(['id_event' => $id_event, 'id_siswa' => $id_siswa])
            ->get()->getRow();

        $data = [
            'id_event'    => $id_event,
            'id_siswa'    => $id_siswa,
            'nilai_angka' => $nilai
        ];

        if ($exist) {
            $db->table('nilai_teori')->where('id_teori', $exist->id_teori)->update($data);
        } else {
            $db->table('nilai_teori')->insert($data);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function monitoring_matriks()
    {
        $db = \Config\Database::connect();
        $id_event = session()->get('active_event_id');
        $current_date = $this->request->getVar('date') ?: date('Y-m-d');

        // 1. Ambil jadwal siswa di hari tersebut
        $jadwalSiswa = $db->table('penjadwalan j')
            ->select('s.id_siswa, s.nama_siswa, s.nisn, s.kelas, p.id_pos, p.nama_pos')
            ->join('siswa s', 's.id_siswa = j.id_siswa')
            ->join('pos_ukk p', 'p.id_pos = j.id_pos')
            ->where([
                'j.tanggal_ujian' => $current_date,
                'j.id_event'      => $id_event
            ])
            ->orderBy('s.kelas', 'ASC')
            ->orderBy('s.nama_siswa', 'ASC')
            ->orderBy('j.urutan', 'ASC')
            ->get()->getResultArray();

        $pos_ids = array_unique(array_column($jadwalSiswa, 'id_pos'));
        $siswa_ids = array_unique(array_column($jadwalSiswa, 'id_siswa'));

        $elemen_per_pos = [];
        $matriks_nilai = [];
        $catatan_data = []; // Array baru untuk menyimpan catatan

        // 2. Ambil Elemen Penilaian
        if (!empty($pos_ids)) {
            $elemen_data = $db->table('elemen_penilaian')
                ->whereIn('id_pos', $pos_ids)
                ->orderBy('CAST(nama_elemen AS UNSIGNED)', 'ASC')
                ->get()->getResultArray();
            foreach ($elemen_data as $e) {
                $elemen_per_pos[$e['id_pos']][] = $e;
            }
        }

        // 3. Ambil Nilai Ujian Siswa
        if (!empty($siswa_ids)) {
            $nilai_data = $db->table('nilai_praktek')
                ->where('id_event', $id_event)
                ->whereIn('id_siswa', $siswa_ids)
                ->get()->getResultArray();
            foreach ($nilai_data as $n) {
                $matriks_nilai[$n['id_siswa']][$n['id_elemen']] = $n['is_kompeten'];
            }

            // 3.5 Ambil Catatan Penguji per POS
            $notes = $db->table('catatan_penilaian')
                ->where('id_event', $id_event)
                ->whereIn('id_siswa', $siswa_ids)
                ->get()->getResultArray();
            foreach ($notes as $note) {
                // Simpan dengan key id_siswa dan id_pos agar mudah dipanggil
                $catatan_data[$note['id_siswa']][$note['id_pos']] = $note['catatan'];
            }
        }

        // 4. GROUPING DATA (Siswa -> POS -> Elemen) dan Deteksi Remedial
        $rekap_siswa = [];
        foreach ($jadwalSiswa as $row) {
            $id_siswa = $row['id_siswa'];
            $id_pos = $row['id_pos'];

            // Buat header profil siswa
            if (!isset($rekap_siswa[$id_siswa])) {
                $rekap_siswa[$id_siswa] = [
                    'nama_siswa'   => $row['nama_siswa'],
                    'nisn'         => $row['nisn'] ?? '-',
                    'kelas'        => $row['kelas'],
                    'butuh_remedi' => false, // Default aman
                    'pos'          => []
                ];
            }

            $elemen_pos_ini = [];
            $pos_remedi = false;

            // Masukkan elemen dan cek nilainya
            if (isset($elemen_per_pos[$id_pos])) {
                foreach ($elemen_per_pos[$id_pos] as $index => $el) {
                    $nilai = $matriks_nilai[$id_siswa][$el['id_elemen']] ?? null;

                    if ($nilai == 25 || $nilai === null) {
                        $pos_remedi = true;
                        $rekap_siswa[$id_siswa]['butuh_remedi'] = true;
                    }

                    $elemen_pos_ini[] = [
                        'kode'        => 'E-' . ($index + 1),
                        'nama_elemen' => $el['nama_elemen'],
                        'nilai'       => $nilai
                    ];
                }
            }

            // Ambil catatan spesifik untuk siswa dan POS ini
            $catatan_penguji = $catatan_data[$id_siswa][$id_pos] ?? '';

            // Simpan data POS ke dalam array siswa tersebut
            $rekap_siswa[$id_siswa]['pos'][] = [
                'nama_pos'      => $row['nama_pos'],
                'elemen'        => $elemen_pos_ini,
                'status_remedi' => $pos_remedi,
                'catatan'       => $catatan_penguji // <-- Masukkan catatan ke sini
            ];
        }

        return view('admin/monitoring_matriks', [
            'rekap_siswa'  => $rekap_siswa,
            'current_date' => $current_date,
            'title'        => 'Rekap Harian UKK'
        ]);
    }
    public function rekap_nilai()
    {
        $db = \Config\Database::connect();

        // 1. Ambil event yang sedang aktif langsung dari database
        $activeEvent = $db->table('events')->where('is_active', 1)->get()->getRowArray();

        // Jika tidak ada event aktif, kembalikan ke halaman sebelumnya atau beri nilai default
        if (!$activeEvent) {
            return redirect()->back()->with('error', 'Tidak ada event yang sedang aktif.');
        }

        $id_event = $activeEvent['id_event'];
        $is_teori = (int)$activeEvent['is_teori'];

    // 2. Mulai query rekap data utama (Nilai Akhir)
    // OPTIMASI: Ambil rekap langsung tanpa subquery lambat
    $builder = $db->table('penjadwalan j');

    $select = 's.id_siswa, s.no_peserta, s.nama_siswa, s.nisn, s.kelas, AVG(np.is_kompeten) as rata_praktek';
    if ($is_teori) {
        $select .= ', nt.nilai_angka as nilai_teori';
    } else {
        $select .= ', 0 as nilai_teori';
    }

    $builder->select($select);
    $builder->join('siswa s', 's.id_siswa = j.id_siswa');
    if ($is_teori) {
        $builder->join('nilai_teori nt', 'nt.id_siswa = s.id_siswa AND nt.id_event = j.id_event', 'left');
    }
    $builder->join('nilai_praktek np', 'np.id_siswa = s.id_siswa AND np.id_event = j.id_event', 'left');
    $builder->where('j.id_event', $id_event);
    $builder->groupBy('s.id_siswa');
    $builder->orderBy('s.kelas', 'ASC');
    $builder->orderBy('s.nama_siswa', 'ASC');

    $rekap = $builder->get()->getResultArray();

    // 3. OPTIMASI: Ambil agregasi nilai per POS langsung dengan JOIN ringkas dan efisien
    $posSiswaRaw = $db->table('penjadwalan j')
        ->select('j.id_siswa, p.id_pos, p.nama_pos, AVG(np.is_kompeten) as rata_pos, MIN(np.is_kompeten) as min_pos, cp.catatan')
        ->join('pos_ukk p', 'p.id_pos = j.id_pos')
        ->join('nilai_praktek np', 'np.id_siswa = j.id_siswa AND np.id_pos = j.id_pos AND np.id_event = j.id_event', 'left')
        ->join('catatan_penilaian cp', 'cp.id_siswa = j.id_siswa AND cp.id_pos = j.id_pos AND cp.id_event = j.id_event', 'left')
        ->where('j.id_event', $id_event)
        ->groupBy('j.id_siswa, p.id_pos, p.nama_pos, cp.catatan')
        ->get()->getResultArray();

        // Grouping detail POS berdasarkan id_siswa agar mudah dipanggil di View
        $detail_pos = [];
        foreach ($posSiswaRaw as $ps) {
            $detail_pos[$ps['id_siswa']][] = [
                'nama_pos'   => $ps['nama_pos'],
                'rata_nilai' => $ps['rata_pos'],
                'min_nilai'  => $ps['min_pos'], // Digunakan untuk mendeteksi remedi jika ada elemen bernilai 25/NULL
                'catatan'    => $ps['catatan']
            ];
        }

        return view('admin/rekap_nilai', [
            'title'      => 'Rekapitulasi Nilai UKK',
            'rekap'      => $rekap,
            'is_teori'   => $is_teori,
            'detail_pos' => $detail_pos // Kirim data detail POS ke view
        ]);
    }
    private function get_data_rekap()
    {
        $db = \Config\Database::connect();

        // 1. Ambil event yang sedang aktif dari database
        $activeEvent = $db->table('events')->where('is_active', 1)->get()->getRowArray();

        if (!$activeEvent) {
            return []; // Kembalikan array kosong jika tidak ada event aktif
        }

        $id_event = $activeEvent['id_event'];
        $is_teori = (int)$activeEvent['is_teori'];

        // 2. Mulai query rekap data
        $builder = $db->table('penjadwalan j');

        // Setup Select secara dinamis
        $select = 's.id_siswa, s.no_peserta, s.nama_siswa, s.nisn, s.kelas, AVG(np.is_kompeten) as rata_praktek';
        if ($is_teori) {
            $select .= ', nt.nilai_angka as nilai_teori';
        } else {
            $select .= ', 0 as nilai_teori';
        }

        $builder->select($select);
        $builder->join('siswa s', 's.id_siswa = j.id_siswa');

        // Join tabel nilai_teori HANYA jika is_teori sedang aktif
        if ($is_teori) {
            $builder->join('nilai_teori nt', 'nt.id_siswa = s.id_siswa AND nt.id_event = j.id_event', 'left');
        }

        $builder->join('nilai_praktek np', 'np.id_siswa = s.id_siswa AND np.id_event = j.id_event', 'left');

        $builder->where('j.id_event', $id_event);
        $builder->groupBy('s.id_siswa');
        $builder->orderBy('s.kelas', 'ASC');
        $builder->orderBy('s.nama_siswa', 'ASC');

        return $builder->get()->getResultArray();
    }
    public function export_pdf()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        ini_set('memory_limit', '512M');
        set_time_limit(300);
        ini_set('zlib.output_compression', 'Off');
        $id_event = session()->get('active_event_id');
        $db = \Config\Database::connect();
        $data['rekap']      = $this->get_data_rekap();
        $data['event'] = $db->table('events')->where('id_event', $id_event)->get()->getRowArray();

        $path_logo_smki = FCPATH . 'assets/img/logosmki.png';
        $path_logo_iso  = FCPATH . 'assets/img/logoiso.png';

        $data['logo_smki'] = $this->base64_encode_image($path_logo_smki);
        $data['logo_iso']  = $this->base64_encode_image($path_logo_iso);

        $data['title'] = 'Rekap_Nilai_UKK_' . date('Ymd_His') . '.pdf';

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('isFontSubsettingEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);

        $html = view('admin/export_pdf_rekap', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        while (ob_get_level()) {
            ob_end_clean();
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader(
                'Content-Disposition',
                'inline; filename="Rekap_Nilai_UKK.pdf"'
            )
            ->setBody($dompdf->output());
    }


    private function base64_encode_image($path)
    {
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        return '';
    }

    public function export_excel()
    {
        $rekap = $this->get_data_rekap();
        if (empty($rekap)) return redirect()->back()->with('error', 'Data kosong.');

        // Ambil status ujian teori dari session (1 = Aktif, 0 = Non-Aktif)
        $is_teori = (int)(session()->get('is_teori') ?? 1);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- 1. JUDUL EXCEL (Baris 1 & 2) ---
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'REKAPITULASI NILAI UJI KOMPETENSI KEAHLIAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'KOMPETENSI KEAHLIAN TEKNIK SEPEDA MOTOR SMK ISLAM 1 BLITAR');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        // --- 2. HEADER TABEL DINAMIS (Baris 4) ---
        $sheet->setCellValue('A4', 'NO');
        $sheet->setCellValue('B4', 'NAMA SISWA');
        $sheet->setCellValue('C4', 'NISN');
        $sheet->setCellValue('D4', 'KELAS');

        // Header berubah otomatis mengikuti status ujian teori
        $sheet->setCellValue('E4', $is_teori ? 'TEORI (30%)' : 'TEORI (0% - NON AKTIF)');
        $sheet->setCellValue('F4', $is_teori ? 'RATA PRAKTEK (70%)' : 'RATA PRAKTEK (100%)');

        $sheet->setCellValue('G4', 'TOTAL NILAI');
        $sheet->setCellValue('H4', 'KESIMPULAN');

        // Styling Khusus Header Tabel (Background Biru, Teks Putih, Bold, Center)
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'], // Teks Putih
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4472C4'], // Background Biru
            ],
        ];
        $sheet->getStyle('A4:H4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(25); // Agak tinggikan baris header


        // --- 3. ISI DATA (Mulai Baris 5) ---
        $row = 5;
        foreach ($rekap as $i => $r) {
            $nilai_teori = floatval($r['nilai_teori'] ?? 0);
            $rata_praktek = floatval($r['rata_praktek'] ?? 0);

            // Perhitungan total nilai disesuaikan dengan status teori
            $na = $is_teori ? (($nilai_teori * 0.3) + ($rata_praktek * 0.7)) : $rata_praktek;

            // Penentuan predikat kompetensi
            if ($na < 75) {
                $status = 'TIDAK KOMPETEN';
                $color = 'FF0000';
            } elseif ($na >= 75 && $na < 80) {
                $status = 'CUKUP KOMPETEN';
                $color = 'FF8C00';
            } elseif ($na >= 80 && $na < 95) {
                $status = 'KOMPETEN';
                $color = '228B22';
            } else {
                $status = 'SANGAT KOMPETEN';
                $color = '0000FF';
            }

            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $r['nama_siswa']);
            $sheet->setCellValueExplicit('C' . $row, $r['nisn'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); // Agar NISN 0 di depan tidak hilang
            $sheet->setCellValue('D' . $row, $r['kelas']);

            // Memasukkan angka nilai siswa secara tepat pada sel barisnya masing-masing
            $sheet->setCellValue('E' . $row, $is_teori ? number_format($nilai_teori, 2) : '0.00');
            $sheet->setCellValue('F' . $row, number_format($rata_praktek, 2));
            $sheet->setCellValue('G' . $row, number_format($na, 2));

            $sheet->setCellValue('H' . $row, $status);
            $sheet->getStyle('H' . $row)->getFont()->setBold(true)->getColor()->setARGB($color);

            // Alignment center untuk kolom NO, NISN, KELAS, dan NILAI
            $sheet->getStyle("A$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C$row:H$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $row++;
        }


        // --- 4. BORDER & AUTOSIZE KOLOM ---
        $sheet->getStyle("A4:H" . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // --- 5. OUTPUT FILE ---
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Rekap_Nilai_UKK_TSM.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function cetak_rekap_harian()
    {
        $db = \Config\Database::connect();
        $id_event = session()->get('active_event_id');
        $current_date = $this->request->getVar('date') ?: date('Y-m-d');

        // 1. Ambil jadwal siswa
        $jadwalSiswa = $db->table('penjadwalan j')
            ->select('s.id_siswa, s.nama_siswa, s.nisn, s.kelas, p.id_pos, p.nama_pos')
            ->join('siswa s', 's.id_siswa = j.id_siswa')
            ->join('pos_ukk p', 'p.id_pos = j.id_pos')
            ->where([
                'j.tanggal_ujian' => $current_date,
                'j.id_event'      => $id_event
            ])
            ->orderBy('s.kelas', 'ASC')
            ->orderBy('s.nama_siswa', 'ASC')
            ->orderBy('j.urutan', 'ASC')
            ->get()->getResultArray();

        if (empty($jadwalSiswa)) {
            return redirect()->back()->with('error', 'Tidak ada data ujian untuk dicetak pada tanggal tersebut.');
        }

        $pos_ids = array_unique(array_column($jadwalSiswa, 'id_pos'));
        $siswa_ids = array_unique(array_column($jadwalSiswa, 'id_siswa'));

        $elemen_per_pos = [];
        $matriks_nilai = [];

        // 2. Ambil Elemen Penilaian
        if (!empty($pos_ids)) {
            $elemen_data = $db->table('elemen_penilaian')
                ->whereIn('id_pos', $pos_ids)
                ->orderBy('CAST(nama_elemen AS UNSIGNED)', 'ASC')
                ->get()->getResultArray();
            foreach ($elemen_data as $e) {
                $elemen_per_pos[$e['id_pos']][] = $e;
            }
        }

        // 3. Ambil Nilai Ujian Siswa
        if (!empty($siswa_ids)) {
            $nilai_data = $db->table('nilai_praktek')
                ->where('id_event', $id_event)
                ->whereIn('id_siswa', $siswa_ids)
                ->get()->getResultArray();
            foreach ($nilai_data as $n) {
                $matriks_nilai[$n['id_siswa']][$n['id_elemen']] = $n['is_kompeten'];
            }
        }

        // 4. GROUPING DATA (Siswa -> POS -> Elemen)
        $rekap_siswa = [];
        foreach ($jadwalSiswa as $row) {
            $id_siswa = $row['id_siswa'];
            $id_pos = $row['id_pos'];

            if (!isset($rekap_siswa[$id_siswa])) {
                $rekap_siswa[$id_siswa] = [
                    'nama_siswa'   => $row['nama_siswa'],
                    'nisn'         => $row['nisn'] ?? '-',
                    'kelas'        => $row['kelas'],
                    'butuh_remedi' => false,
                    'pos'          => []
                ];
            }

            $elemen_pos_ini = [];
            $pos_remedi = false;

            if (isset($elemen_per_pos[$id_pos])) {
                foreach ($elemen_per_pos[$id_pos] as $index => $el) {
                    $nilai = $matriks_nilai[$id_siswa][$el['id_elemen']] ?? null;

                    // Logika Remedial: BK (25) atau Kosong
                    if ($nilai == 25 || $nilai === null) {
                        $pos_remedi = true;
                        $rekap_siswa[$id_siswa]['butuh_remedi'] = true;
                    }

                    $elemen_pos_ini[] = [
                        'kode'        => 'E-' . ($index + 1),
                        'nama_elemen' => $el['nama_elemen'],
                        'nilai'       => $nilai
                    ];
                }
            }

            $rekap_siswa[$id_siswa]['pos'][] = [
                'nama_pos'      => $row['nama_pos'],
                'elemen'        => $elemen_pos_ini,
                'status_remedi' => $pos_remedi
            ];
        }

        $data = [
            'rekap_siswa'  => $rekap_siswa,
            'current_date' => $current_date
        ];

        // 5. Render ke DomPDF
        $html = view('admin/pdf_rekap_harian', $data);

        // Instansiasi DomPDF (sesuaikan dengan cara pemanggilan library di project Anda)
        $dompdf = new \Dompdf\Dompdf();

        // Opsional: Agar tag HTML5 dan CSS lebih rapi terbaca
        $options = $dompdf->getOptions();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Keluarkan output sebagai Response resmi CI4 agar browser membacanya sebagai PDF
        return $this->response->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="Pengumuman_Harian_UKK_' . $current_date . '.pdf"')
            ->setBody($dompdf->output());
    }
}
