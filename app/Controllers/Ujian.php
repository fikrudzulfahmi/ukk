<?php

namespace App\Controllers;

use App\Models\SoalModel;
use App\Models\SiswaModel;

class Ujian extends BaseController
{
    public function mulai($id_siswa, $token)
    {
        $db = \Config\Database::connect();

        // 1. Ambil data siswa
        $siswa = $db->table('siswa')->where('id_siswa', $id_siswa)->get()->getRowArray();

        // 2. Validasi Token
        if (!$siswa || md5($siswa['nisn']) !== $token) {
            return "Akses Tidak Sah!";
        }

        // 3. Ambil ID Event
        $penjadwalan = $db->table('penjadwalan')->where('id_siswa', $id_siswa)->get()->getRowArray();
        $id_event = $penjadwalan ? $penjadwalan['id_event'] : null;

        // 4. Ambil jawaban yang sudah disimpan (AGAR TIDAK HILANG SAAT REFRESH)
        $jawabanSiswa = $db->table('jawaban_siswa')
            ->where('id_siswa', $id_siswa)
            ->get()->getResultArray();

        // Ubah format jawaban agar mudah dicek di view (id_soal => jawaban_pilihan)
        $list_jawaban = [];
        foreach ($jawabanSiswa as $j) {
            $list_jawaban[$j['id_soal']] = $j['jawaban_pilihan'];
        }

        // 5. Cek status kunci
        // Ambil data nilai_teori sekalian untuk dikirim ke view jika sudah selesai
        $cekLock = $db->table('nilai_teori')
            ->where(['id_siswa' => $id_siswa, 'id_event' => $id_event, 'is_locked' => 1])
            ->get()->getRowArray();

        // Jika sudah terkunci, langsung lempar ke view selesai beserta nilainya
        if ($cekLock) {
            return view('ujian/status_selesai', [
                'nilai' => $cekLock['nilai_angka'] ?? 0,
                'id_siswa' => $siswa['id_siswa'], // Tambahkan ini
                'token'    => md5($siswa['nisn']),
                'nama_siswa' => $siswa['nama_siswa'] // <--- TAMBAHKAN INI
            ]);
        }

        $soalModel = new \App\Models\SoalModel();
        $data = [
            'siswa'        => $siswa,
            'soal'         => $soalModel->where('id_event', $id_event)->orderBy('RAND()')->findAll(),
            'list_jawaban' => $list_jawaban // Kirim data jawaban ke view
        ];

        return view('ujian/halaman_siswa', $data);
    }

    public function simpan_ajax()
    {
        $db = \Config\Database::connect();

        $id_siswa = $this->request->getPost('id_siswa');
        $id_soal  = $this->request->getPost('id_soal');
        $jawaban  = $this->request->getPost('jawaban');

        // Ambil info soal untuk cek jawaban benar
        $soal = $db->table('soal_teori')->where('id_soal', $id_soal)->get()->getRowArray();
        $is_benar = ($jawaban == $soal['jawaban_benar']) ? 1 : 0;

        // Simpan/Update ke tabel jawaban_siswa
        $db->table('jawaban_siswa')->replace([
            'id_siswa'        => $id_siswa,
            'id_soal'         => $id_soal,
            'jawaban_pilihan' => $jawaban,
            'is_benar'        => $is_benar
        ]);

        // Hitung skor total
        $id_event = $soal['id_event'];
        $totalSoal = $db->table('soal_teori')->where('id_event', $id_event)->countAllResults();
        $jumlahBenar = $db->table('jawaban_siswa')
            ->join('soal_teori', 'soal_teori.id_soal = jawaban_siswa.id_soal')
            ->where(['id_siswa' => $id_siswa, 'soal_teori.id_event' => $id_event, 'is_benar' => 1])
            ->countAllResults();

        // Hitung skor akhir
        $nilai_akhir = ($totalSoal > 0) ? ($jumlahBenar / $totalSoal) * 100 : 0;

        // Cek apakah data nilai sudah ada untuk siswa & event ini
        $existingNilai = $db->table('nilai_teori')
            ->where(['id_siswa' => $id_siswa, 'id_event' => $id_event])
            ->get()->getRow();

        if ($existingNilai) {
            // Jika sudah ada, cukup UPDATE nilai_angka saja
            $db->table('nilai_teori')
                ->where('id_teori', $existingNilai->id_teori)
                ->update(['nilai_angka' => $nilai_akhir]);
        } else {
            // Jika benar-benar belum ada, baru INSERT
            $db->table('nilai_teori')->insert([
                'id_siswa'    => $id_siswa,
                'id_event'    => $id_event,
                'nilai_angka' => $nilai_akhir,
                'is_locked'   => 0
            ]);
        }

        return $this->response->setJSON(['status' => 'success', 'skor' => $nilai_akhir]);
    }

    public function selesai($id_siswa)
    {
        $db = \Config\Database::connect();
        $nilaiAkhirSiswa = 0; // Set default nilai 0

        // 1. Ambil data event terakhir dari penjadwalan siswa
        $penjadwalan = $db->table('penjadwalan')
            ->where('id_siswa', $id_siswa)
            ->orderBy('id_jadwal', 'DESC')
            ->get()->getRowArray();

        $id_event = $penjadwalan ? $penjadwalan['id_event'] : null;

        if ($id_event) {
            // 2. Kunci nilai di tabel nilai_teori
            $db->table('nilai_teori')
                ->where(['id_siswa' => $id_siswa, 'id_event' => $id_event])
                ->update(['is_locked' => 1]);

            // 3. Ambil nilai akhirnya dari database untuk ditampilkan
            $dataNilai = $db->table('nilai_teori')
                ->where(['id_siswa' => $id_siswa, 'id_event' => $id_event])
                ->get()->getRowArray();

            if ($dataNilai) {
                $nilaiAkhirSiswa = $dataNilai['nilai_angka'];
            }
        }

        // --- TAMBAHAN KUNCI DI SINI ---
        // 4. Ambil data siswa dari database untuk mendapatkan NISN
        // 1. Tambahkan baris ini TEPAT SEBELUM return view untuk mengambil data siswa
        $siswa = $db->table('siswa')->where('id_siswa', $id_siswa)->get()->getRowArray();

        // 2. Baru tampilkan view-nya
        return view('ujian/status_selesai', [
            'nilai'    => $nilaiAkhirSiswa,
            'id_siswa' => $id_siswa,           // Pakai $id_siswa langsung dari parameter
            'token'    => md5($siswa['nisn'])  // NISN sekarang sudah bisa diambil dari $siswa
        ]);
    }
    public function hasil_praktek($id_siswa = null, $token = null)
    {
        $db = \Config\Database::connect();

        // Cek apakah URL tidak lengkap
        if (!$id_siswa || !$token) {
            return "Akses ditolak. Parameter tidak valid.";
        }

        // Opsional: Validasi Token Keamanan (Cek apakah MD5 NISN cocok)
        $siswa = $db->table('siswa')->where('id_siswa', $id_siswa)->get()->getRowArray();
        if (!$siswa || md5($siswa['nisn']) !== $token) {
            return "Akses ditolak. Token keamanan tidak valid atau kadaluarsa.";
        }

        // Ambil ID Event yang sedang aktif
        $event = $db->table('events')->where('is_active', 1)->get()->getRowArray();
        $id_event = $event['id_event'] ?? 0;

        // 1. Ambil jadwal POS untuk siswa ini
        $jadwal_pos = $db->table('penjadwalan j')
            ->select('p.id_pos, p.nama_pos, j.tanggal_ujian')
            ->join('pos_ukk p', 'p.id_pos = j.id_pos')
            ->where(['j.id_siswa' => $id_siswa, 'j.id_event' => $id_event])
            ->orderBy('j.tanggal_ujian', 'ASC')
            ->get()->getResultArray();

        $rekap_pos = [];
        $butuh_remedi_global = false;

        // 2. Looping per POS dan cek nilainya
        foreach ($jadwal_pos as $jp) {
            $id_pos = $jp['id_pos'];

            $elemen = $db->table('elemen_penilaian')
                ->where('id_pos', $id_pos)
                ->orderBy('CAST(nama_elemen AS UNSIGNED)', 'ASC')
                ->get()->getResultArray();

            $elemen_data = [];
            $pos_remedi = false;

            foreach ($elemen as $index => $el) {
                $nilai_row = $db->table('nilai_praktek')
                    ->where([
                        'id_siswa'  => $id_siswa,
                        'id_elemen' => $el['id_elemen'],
                        'id_event'  => $id_event
                    ])
                    ->get()->getRowArray();

                $nilai = $nilai_row ? $nilai_row['is_kompeten'] : null;

                if ($nilai == 25 || $nilai === null) {
                    $pos_remedi = true;
                    $butuh_remedi_global = true;
                }

                $elemen_data[] = [
                    'kode'        => 'E-' . ($index + 1),
                    'nama_elemen' => $el['nama_elemen'],
                    'nilai'       => $nilai
                ];
            }

            $rekap_pos[] = [
                'nama_pos'      => $jp['nama_pos'],
                'tanggal'       => $jp['tanggal_ujian'],
                'elemen'        => $elemen_data,
                'status_remedi' => $pos_remedi
            ];
        }

        $data = [
            'rekap_pos'    => $rekap_pos,
            'butuh_remedi' => $butuh_remedi_global,
            'nama_siswa'   => $siswa['nama_siswa']
        ];

        return view('ujian/hasil_praktek', $data);
    }
}
