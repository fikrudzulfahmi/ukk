<?php

namespace App\Controllers;

use App\Models\PosModel;
use App\Models\ElemenModel;

class Elemen extends BaseController
{
    protected $elemenModel;
    protected $posModel;

    public function __construct()
    {
        // Inisialisasi model agar bisa digunakan di semua method
        $this->elemenModel = new ElemenModel();
        $this->posModel = new PosModel();
    }

    public function index()
    {
        $id_event = session()->get('active_event_id');

        if (!$id_event) {
            return redirect()->to('/event')->with('error', 'Silakan aktifkan salah satu Event terlebih dahulu!');
        }

        $data = [
            'title'  => 'Elemen Penilaian - ' . session()->get('nama_event'),
            'pos'    => $this->posModel->where('pos_ukk.id_event', $id_event)->findAll(),
            'elemen' => $this->elemenModel->where('elemen_penilaian.id_event', $id_event)
                ->select('elemen_penilaian.*, pos_ukk.nama_pos')

                // 1. Buat alias 'urutan_bab' untuk angka sebelum titik (parameter false agar SQL tidak di-escape otomatis)
                ->select("CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(elemen_penilaian.nama_elemen, ' ', 1), '.', 1) AS UNSIGNED) AS urutan_bab", false)

                // 2. Buat alias 'urutan_subbab' untuk angka setelah titik
                ->select("CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(elemen_penilaian.nama_elemen, ' ', 1), '.', -1) AS UNSIGNED) AS urutan_subbab", false)

                ->join('pos_ukk', 'pos_ukk.id_pos = elemen_penilaian.id_pos')
                ->orderBy('pos_ukk.nama_pos', 'ASC')

                // 3. Tinggal panggil nama aliasnya di sini (dijamin aman dari error query builder)
                ->orderBy('urutan_bab', 'ASC')
                ->orderBy('urutan_subbab', 'ASC')
                ->findAll()
        ];
        return view('admin/elemen_index', $data);
    }

    public function store()
    {
        $id_event = session()->get('active_event_id');
        $id_pos = $this->request->getPost('id_pos');
        $nama_elemen = $this->request->getPost('nama_elemen');

        foreach ($nama_elemen as $nama) {
            if (!empty($nama)) {
                $this->elemenModel->insert([
                    'id_event' => $id_event,
                    'id_pos' => $id_pos,
                    'nama_elemen' => $nama
                ]);
            }
        }

        return redirect()->back()->with('success', 'Elemen berhasil ditambahkan');
    }


    public function update($id)
    {
        $this->elemenModel->update($id, [
            'id_pos'      => $this->request->getPost('id_pos'),
            'nama_elemen' => $this->request->getPost('nama_elemen'),
        ]);

        return redirect()->to('/elemen')->with('success', 'Butir penilaian berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->elemenModel->delete($id);
        return redirect()->to('/elemen')->with('success', 'Item penilaian berhasil dihapus.');
    }
}
