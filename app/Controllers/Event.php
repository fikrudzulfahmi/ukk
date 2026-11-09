<?php

namespace App\Controllers;

use App\Models\EventModel;

class Event extends BaseController
{
    protected $eventModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
    }

    public function index()
    {
        $data = [
            'title'  => 'Manajemen Session Event',
            'events' => $this->eventModel->orderBy('id_event', 'DESC')->findAll()
        ];
        return view('admin/event_index', $data);
    }

    // Prosedur Simpan Baru
    // Prosedur Simpan Baru
    public function store()
    {
        $this->eventModel->insert([
            'nama_event'      => $this->request->getPost('nama_event'),
            'ketua'           => $this->request->getPost('ketua'),
            'tahun_pelajaran' => $this->request->getPost('tahun_pelajaran'),
            'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
            'kapasitas_sesi'  => $this->request->getPost('kapasitas_sesi'),
            'is_teori'        => $this->request->getPost('is_teori'), // <-- Tambahan baru
            'is_active'       => 0
        ]);
        return redirect()->to('/event')->with('success', 'Event baru berhasil ditambahkan.');
    }

    // Prosedur Update Data
    public function update($id)
    {
        $this->eventModel->update($id, [
            'nama_event'      => $this->request->getPost('nama_event'),
            'ketua'           => $this->request->getPost('ketua'),
            'tahun_pelajaran' => $this->request->getPost('tahun_pelajaran'),
            'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
            'kapasitas_sesi'  => $this->request->getPost('kapasitas_sesi'),
            'is_teori'        => $this->request->getPost('is_teori'), // <-- Tambahan baru
        ]);
        return redirect()->to('/event')->with('success', 'Data event berhasil diperbarui.');
    }

    public function activate($id)
    {
        $this->eventModel->where('id_event >', 0)->set(['is_active' => 0])->update();
        $this->eventModel->update($id, ['is_active' => 1]);

        $event = $this->eventModel->find($id);

        // --- SIMPAN KAPASITAS & STATUS TEORI KE SESSION ---
        session()->set([
            'active_event_id'         => $event['id_event'],
            'nama_event'              => $event['nama_event'], // disesuaikan dengan view rekap kamu
            'active_event_capacity'   => $event['kapasitas_sesi'],
            'is_teori'                => $event['is_teori'] // <-- Tambahan baru (0 atau 1)
        ]);

        return redirect()->to('/event')->with('success', 'Event "' . $event['nama_event'] . '" sekarang aktif.');
    }

    public function delete($id)
    {
        $this->eventModel->delete($id);
        return redirect()->to('/event')->with('success', 'Event dihapus.');
    }
}
