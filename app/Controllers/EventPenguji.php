<?php

namespace App\Controllers;

use App\Models\EventPengujiModel;
use App\Models\UserModel;
use App\Models\PosModel;
use App\Models\EventModel;
use Dompdf\Options;


class EventPenguji extends BaseController
{
    protected $epModel;

    public function __construct()
    {
        $this->epModel = new EventPengujiModel();
    }

    public function index()
    {
        $id_event = session()->get('active_event_id');

        if (!$id_event) {
            return redirect()->to('/event')->with('error', 'Aktifkan Event terlebih dahulu!');
        }

        $userM = new UserModel();
        $posM  = new PosModel();

        $rawPlotting = $this->epModel->where('event_penguji.id_event', $id_event)
            ->join('users', 'users.id_user = event_penguji.id_user')
            ->join('pos_ukk', 'pos_ukk.id_pos = event_penguji.id_pos')
            ->select('event_penguji.*, users.nama_lengkap, pos_ukk.nama_pos')
            ->orderBy('event_penguji.tanggal', 'ASC')
            ->orderBy('pos_ukk.nama_pos', 'ASC')
            ->findAll();

        $plottingGrouped = [];
        foreach ($rawPlotting as $row) {
            $plottingGrouped[$row['tanggal']][] = $row;
        }

        // --- LOGIKA TAMBAHAN UNTUK COUNTER ---
        // Mengambil semua id_user dari plotting, lalu hilangkan duplikatnya
        $idPengujiUnik = array_unique(array_column($rawPlotting, 'id_user'));
        // -------------------------------------

        $data = [
            'title'           => 'Plotting Penguji',
            'penguji'         => $userM->where('role', 'penguji')->findAll(),
            'pos'             => $posM->where('id_event', $id_event)->findAll(),
            'rawPlotting'     => $rawPlotting,
            'plotting'        => $plottingGrouped,
            'total_penguji'   => count($idPengujiUnik) // Kirim hasil hitungan ke view
        ];

        return view('admin/plotting_index', $data);
    }

    public function store()
    {
        $this->epModel->insert([
            'id_event' => session()->get('active_event_id'),
            'id_user'  => $this->request->getPost('id_user'),
            'id_pos'   => $this->request->getPost('id_pos'),
            'tanggal'  => $this->request->getPost('tanggal'),
        ]);
        return redirect()->to('/plotting')->with('success', 'Data plotting berhasil disimpan.');
    }

    public function update($id)
    {
        $this->epModel->update($id, [
            'id_user'  => $this->request->getPost('id_user'),
            'id_pos'   => $this->request->getPost('id_pos'),
            'tanggal'  => $this->request->getPost('tanggal'),
        ]);
        return redirect()->to('/plotting')->with('success', 'Data plotting berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->epModel->delete($id);
        return redirect()->to('/plotting')->with('success', 'Data plotting berhasil dihapus.');
    }

    public function cetakPresensi($tanggal)
    {
        // Bersihkan semua output buffer di awal
        while (ob_get_level()) {
            ob_end_clean();
        }

        $id_event = session()->get('active_event_id');

        $presensi = $this->epModel
            ->where('event_penguji.id_event', $id_event)
            ->where('event_penguji.tanggal', $tanggal)
            ->join('users', 'users.id_user = event_penguji.id_user')
            ->join('pos_ukk', 'pos_ukk.id_pos = event_penguji.id_pos')
            ->select('event_penguji.*, users.nama_lengkap, users.instansi, pos_ukk.nama_pos')
            ->findAll();

        if (!$presensi) {
            return redirect()->back()
                ->with('error', 'Tidak ada data penguji pada tanggal tersebut.');
        }

        $eventM = new EventModel();
        $event = $eventM->find($id_event);

        $data = [
            'title'     => 'Daftar_Hadir_Penguji_' . date('Ymd') . '.pdf',
            'presensi'  => $presensi,
            'event'     => $event,
            'tanggal'   => $tanggal,
            'logo_smki' => $this->base64_encode_image(FCPATH . 'assets/img/logosmki.png'),
            'logo_iso'  => $this->base64_encode_image(FCPATH . 'assets/img/logoiso.png'),
        ];

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);

        $html = view('admin/cetak_presensi_penguji', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Bersihkan lagi sebelum kirim response
        while (ob_get_level()) {
            ob_end_clean();
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader(
                'Content-Disposition',
                'inline; filename="Daftar_Hadir_Penguji_' . $tanggal . '.pdf"'
            )
            ->setBody($dompdf->output());
    }


    public function cetakSemuaJadwal()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        $id_event = session()->get('active_event_id');
        $db = \Config\Database::connect();

        $jadwal = $db->table('event_penguji ep')
            ->select('ep.tanggal, u.nama_lengkap, u.instansi, p.nama_pos')
            ->join('users u', 'u.id_user = ep.id_user')
            ->join('pos_ukk p', 'p.id_pos = ep.id_pos')
            ->where('ep.id_event', $id_event)
            ->orderBy('ep.tanggal', 'ASC')
            ->orderBy('p.nama_pos', 'ASC')
            ->get()->getResultArray();

        $event = $db->table('events')->where('id_event', $id_event)->get()->getRowArray();

        $data = [
            'title'     => 'Jadwal_Penguji_UKK_' . date('Ymd_His') . '.pdf',
            'jadwal'    => $jadwal,
            'event'     => $event,
            'logo_smki' => $this->base64_encode_image(FCPATH . 'assets/img/logosmki.png'),
            'logo_iso'  => $this->base64_encode_image(FCPATH . 'assets/img/logoiso.png'),
        ];

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);

        $html = view('admin/pdf_jadwal_rekap_penguji', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        while (ob_get_level()) {
            ob_end_clean();
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="Jadwal_Penguji_UKK.pdf"')
            ->setBody($dompdf->output());
    }


    // Fungsi pembantu jika belum ada
    private function base64_encode_image($path)
    {
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        return '';
    }
}
