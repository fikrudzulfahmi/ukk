<?php

namespace App\Controllers;

use App\Models\PosModel;

class Pos extends BaseController
{
    protected $posModel;

    public function __construct()
    {
        $this->posModel = new PosModel();
    }

    public function index()
    {
        $id_event = session()->get('active_event_id');
        $pos = $this->posModel->where('id_event', $id_event)->findAll();
        $data = [
            'title' => 'Master Pos UKK',
            'pos'   => $pos,
            'id_event' => $id_event
        ];
        return view('admin/pos_index', $data);
    }

    public function store()
    {
        $this->posModel->insert([
            'id_event' => session()->get('active_event_id'),
            'nama_pos' => $this->request->getPost('nama_pos')
        ]);
        return redirect()->to('/pos')->with('success', 'Pos baru berhasil ditambahkan.');
    }

    public function update($id)
    {
        $this->posModel->update($id, [
            'nama_pos' => $this->request->getPost('nama_pos')
        ]);

        return redirect()->to('/pos')->with('success', 'Nama Pos berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->posModel->delete($id);
        return redirect()->to('/pos')->with('success', 'Pos berhasil dihapus.');
    }
}
