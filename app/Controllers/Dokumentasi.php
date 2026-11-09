<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dokumentasi extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $id_event = session()->get('active_event_id');

        // Mengambil data langsung dari tabel menggunakan Query Builder
        $data = [
            'title' => 'Dokumentasi Kegiatan',
            'dokumentasi' => $db->table('dokumentasi')
                ->where('id_event', $id_event)
                ->orderBy('created_at', 'DESC')
                ->get()->getResultArray()
        ];

        return view('admin/dokumentasi', $data);
    }

    public function upload()
    {
        $db = \Config\Database::connect();
        $img = $this->request->getFile('foto');
        $judul = $this->request->getPost('judul');

        // 1. Validasi Input
        if (!$img->isValid() || $img->hasMoved()) {
            return redirect()->back()->with('error', 'File gagal diunggah.');
        }

        // 2. Batasi jenis file & ukuran (contoh: max 2MB)
        if (!in_array($img->getMimeType(), ['image/jpg', 'image/jpeg', 'image/png'])) {
            return redirect()->back()->with('error', 'Format harus JPG atau PNG.');
        }

        if ($img->getSizeByUnit('mb') > 2) {
            return redirect()->back()->with('error', 'Ukuran file maksimal 2MB.');
        }

        // 3. Pindahkan file ke folder public/uploads/dokumentasi
        $newName = $img->getRandomName();
        $img->move(FCPATH . 'uploads/dokumentasi', $newName);

        // 4. Simpan ke Database (Query Builder)
        $db->table('dokumentasi')->insert([
            'id_event'   => session()->get('active_event_id'),
            'judul_foto' => $judul,
            'nama_file'  => $newName,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/dokumentasi')->with('success', 'Dokumentasi berhasil disimpan!');
    }

    public function hapus($id)
    {
        $db = \Config\Database::connect();

        // Cari data file di DB
        $foto = $db->table('dokumentasi')->where('id_dokumentasi', $id)->get()->getRowArray();

        if ($foto) {
            // Hapus file fisik dari folder agar tidak menumpuk
            $path = FCPATH . 'uploads/dokumentasi/' . $foto['nama_file'];
            if (file_exists($path)) {
                unlink($path);
            }

            // Hapus data dari database
            $db->table('dokumentasi')->where('id_dokumentasi', $id)->delete();
            return redirect()->to('/dokumentasi')->with('success', 'Dokumentasi telah dihapus.');
        }

        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    public function cetak()
    {
        // 1. Bersihkan buffer
        while (ob_get_level()) {
            ob_end_clean();
        }
        ini_set('zlib.output_compression', 'Off');

        $db = \Config\Database::connect();
        $id_event = session()->get('active_event_id');

        // 2. Ambil Data Dokumentasi & Event
        $dokumentasi = $db->table('dokumentasi')
            ->where('id_event', $id_event)
            ->orderBy('judul_foto', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->get()->getResultArray();

        $event = $db->table('events')
            ->where('id_event', $id_event)
            ->get()->getRowArray();

        if (empty($dokumentasi)) {
            return redirect()->back()->with('error', 'Tidak ada foto untuk dicetak!');
        }

        // 3. Siapkan Data ke View (Termasuk Logo & Image Base64)
        $data = [
            'dokumentasi' => $dokumentasi,
            'event'       => $event,
            'logo_smki'   => $this->base64_encode_image(FCPATH . 'assets/img/logosmki.png'),
            'logo_iso'    => $this->base64_encode_image(FCPATH . 'assets/img/logoiso.png'),
        ];

        // Convert foto dokumentasi ke base64 agar tampil di PDF
        foreach ($data['dokumentasi'] as &$d) {
            $d['foto_base64'] = $this->base64_encode_image(FCPATH . 'uploads/dokumentasi/' . $d['nama_file']);
        }

        $data['title'] = 'Dokumentasi_Kegiatan_' . date('Ymd_His') . '.pdf';

        // 4. Render Dompdf
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $html = view('admin/pdf_dokumentasi', $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="Dokumentasi_UKK.pdf"')
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
}
