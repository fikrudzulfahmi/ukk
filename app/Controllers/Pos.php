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

        $db = \Config\Database::connect();

        // Event lain sebagai sumber clone
        $events = $db->table('events')
            ->select('id_event, nama_event, tahun_pelajaran')
            ->where('id_event !=', $id_event ?? 0)
            ->orderBy('id_event', 'DESC')
            ->get()->getResultArray();

        // Jumlah POS per event (info di dropdown)
        $countRows = $db->table('pos_ukk')
            ->select('id_event, COUNT(*) AS total')
            ->groupBy('id_event')
            ->get()->getResultArray();

        $posCounts = [];
        foreach ($countRows as $row) {
            $posCounts[$row['id_event']] = (int) $row['total'];
        }

        $data = [
            'title'      => 'Master Pos UKK',
            'pos'        => $pos,
            'id_event'   => $id_event,
            'events'     => $events,
            'pos_counts' => $posCounts,
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

    /**
     * Clone (salin) daftar POS dari event lain ke event aktif.
     * POS dengan nama sama (case-insensitive) dilewati.
     */
    public function clone()
    {
        $id_event = (int) session()->get('active_event_id');
        if (!$id_event) {
            return redirect()->to('/event')->with('error', 'Silakan aktifkan salah satu Event terlebih dahulu!');
        }

        $id_sumber = (int) $this->request->getPost('id_event_sumber');
        if (!$id_sumber) {
            return redirect()->back()->with('error', 'Pilih event sumber terlebih dahulu.');
        }
        if ($id_sumber === $id_event) {
            return redirect()->back()->with('error', 'Event sumber tidak boleh sama dengan event aktif.');
        }

        $db = \Config\Database::connect();

        $sumber = $db->table('events')->where('id_event', $id_sumber)->get()->getRowArray();
        if (!$sumber) {
            return redirect()->back()->with('error', 'Event sumber tidak ditemukan.');
        }

        $posSumber = $db->table('pos_ukk')
            ->where('id_event', $id_sumber)
            ->orderBy('id_pos', 'ASC')
            ->get()->getResultArray();

        if (empty($posSumber)) {
            return redirect()->back()->with('error', 'Event "' . esc($sumber['nama_event']) . '" belum memiliki POS untuk disalin.');
        }

        // Kunci duplikat berdasarkan nama POS (dinormalisasi)
        $existing = [];
        foreach ($db->table('pos_ukk')->select('nama_pos')->where('id_event', $id_event)->get()->getResultArray() as $p) {
            $existing[$this->normalize($p['nama_pos'])] = true;
        }

        $baru     = 0;
        $duplikat = 0;

        $db->transBegin();
        try {
            foreach ($posSumber as $p) {
                $key = $this->normalize($p['nama_pos']);
                if (isset($existing[$key])) {
                    $duplikat++;
                    continue;
                }

                $db->table('pos_ukk')->insert([
                    'id_event' => $id_event,
                    'nama_pos' => $p['nama_pos'],
                ]);

                $existing[$key] = true;
                $baru++;
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Clone gagal: ' . $e->getMessage());
        }

        if ($baru === 0) {
            $pesan = 'Tidak ada POS baru. ' . $duplikat . ' POS dilewati karena sudah ada di event aktif.';
        } else {
            $pesan = "{$baru} POS disalin dari \"" . esc($sumber['nama_event']) . '"';
            if ($duplikat > 0) {
                $pesan .= ", {$duplikat} POS dilewati karena sudah ada";
            }
            $pesan .= '.';
        }

        return redirect()->to('/pos')->with('success', $pesan);
    }

    private function normalize(string $teks): string
    {
        return mb_strtolower(trim(preg_replace('/\s+/u', ' ', $teks)));
    }
}
