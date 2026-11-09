<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Siswa extends BaseController
{
    protected $siswaModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
    }

    public function index()
    {
        $id_event = session()->get('active_event_id');
        if (!$id_event) {
            return redirect()->to('/event')->with('error', 'Silakan aktifkan salah satu Event terlebih dahulu!');
        }

        $data = [
            'title' => 'Data Peserta - ' . session()->get('nama_event'),
            // Kita tetap ambil data siswa untuk merender Modal Edit di bawah halaman
            'siswa' => $this->siswaModel->where('id_event', $id_event)->findAll()
        ];
        return view('admin/siswa_index', $data);
    }

    public function get_data()
    {
        // Pastikan hanya melayani request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $id_event = session()->get('active_event_id');
        $postData = $this->request->getPost();

        $builder = $this->siswaModel->builder();
        $builder->where('id_event', $id_event);

        // Total data sebelum difilter
        $totalAllData = $builder->countAllResults(false);

        // Pencarian
        $search = $postData['search']['value'] ?? null;
        if ($search) {
            $builder->groupStart()
                ->like('nama_siswa', $search)
                ->orLike('nisn', $search)
                ->orLike('kelas', $search)
                ->groupEnd();
        }

        // Total data setelah difilter
        $totalFilteredData = $builder->countAllResults(false);

        // Ambil hasil akhir
        $siswa = $builder->limit($postData['length'], $postData['start'])->get()->getResultArray();

        $data = [];
        $no = $postData['start'] + 1;

        // Di dalam foreach pada method get_data() di Controller Siswa.php
        foreach ($siswa as $s) {
            $data[] = [
                $no++,
                $s['nisn'],
                '<strong>' . esc($s['nama_siswa']) . '</strong>',
                $s['kelas'],
                '<div class="text-center">
            <button class="btn btn-sm btn-light text-primary btn-edit" 
                data-id="' . $s['id_siswa'] . '" 
                data-nisn="' . $s['nisn'] . '" 
                data-nama="' . $s['nama_siswa'] . '" 
                data-kelas="' . $s['kelas'] . '">
                <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-light text-danger" onclick="confirmDelete(\'' . base_url('siswa/delete/' . $s['id_siswa']) . '\')">
                <i class="bi bi-trash"></i>
            </button>
        </div>'
            ];
        }

        return $this->response->setJSON([
            "draw"            => intval($postData['draw']),
            "recordsTotal"    => $totalAllData,
            "recordsFiltered" => $totalFilteredData,
            "data"            => $data,
        ]);
    }

    public function store()
    {
        $this->siswaModel->insert([
            'id_event'   => session()->get('active_event_id'),
            'nisn'       => $this->request->getPost('nisn'),
            'nama_siswa' => $this->request->getPost('nama_siswa'),
            'kelas'      => $this->request->getPost('kelas'),
        ]);
        return redirect()->to('/siswa')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function update($id)
    {
        $this->siswaModel->update($id, [
            'nisn'       => $this->request->getPost('nisn'),
            'nama_siswa' => $this->request->getPost('nama_siswa'),
            'kelas'      => $this->request->getPost('kelas'),
        ]);
        return redirect()->to('/siswa')->with('success', 'Data siswa diperbarui.');
    }

    public function delete($id)
    {
        $this->siswaModel->delete($id);
        return redirect()->to('/siswa')->with('success', 'Siswa berhasil dihapus.');
    }

    public function import()
    {
        $file = $this->request->getFile('file_excel');
        $id_event = session()->get('active_event_id');

        if ($file->isValid() && !$file->hasMoved()) {
            $reader = new Xlsx();
            $spreadsheet = $reader->load($file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $inserted = 0;
            foreach ($sheetData as $key => $row) {
                if ($key == 0) continue; // Lewati header kolom di Excel

                $this->siswaModel->insert([
                    'id_event'   => $id_event,
                    'nisn'       => $row[0], // Kolom A
                    'nama_siswa' => $row[1], // Kolom B
                    'kelas'      => $row[2], // Kolom C
                ]);
                $inserted++;
            }
            return redirect()->to('/siswa')->with('success', "$inserted data siswa berhasil diimport.");
        }
        return redirect()->back()->with('error', 'Gagal mengupload file.');
    }

    public function hasil_praktek()
    {
        $db = \Config\Database::connect();

        // Sesuaikan dengan nama session ID Siswa di sistem Anda
        $id_siswa = session()->get('id_siswa');

        // Ambil ID Event yang sedang aktif
        $event = $db->table('event')->where('status', 'aktif')->get()->getRowArray();
        $id_event = $event['id_event'] ?? 0;

        if (!$id_siswa) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

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

            // Ambil elemen di POS ini
            $elemen = $db->table('elemen_penilaian')
                ->where('id_pos', $id_pos)
                ->orderBy('CAST(nama_elemen AS UNSIGNED)', 'ASC')
                ->get()->getResultArray();

            $elemen_data = [];
            $pos_remedi = false;

            foreach ($elemen as $index => $el) {
                // Ambil nilai per elemen
                $nilai_row = $db->table('nilai_praktek')
                    ->where([
                        'id_siswa'  => $id_siswa,
                        'id_elemen' => $el['id_elemen'],
                        'id_event'  => $id_event
                    ])
                    ->get()->getRowArray();

                $nilai = $nilai_row ? $nilai_row['is_kompeten'] : null;

                // Logika Remedial: BK(25) atau Kosong(null)
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
            'title'        => 'Hasil Ujian Praktek',
            'rekap_pos'    => $rekap_pos,
            'butuh_remedi' => $butuh_remedi_global
        ];

        return view('siswa/hasil_praktek', $data);
    }
}
