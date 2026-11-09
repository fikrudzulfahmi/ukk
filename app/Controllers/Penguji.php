<?php

namespace App\Controllers;

use App\Models\UserModel;

class Penguji extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title'   => 'Manajemen Data Penguji',
            'penguji' => $this->userModel->where('role', 'penguji')->findAll()
        ];
        return view('admin/penguji_index', $data);
    }

    public function store()
    {
        $this->userModel->insert([
            'username'  => $this->request->getPost('username'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'instansi' => $this->request->getPost('instansi'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'      => 'penguji'
        ]);

        return redirect()->to('/penguji')->with('success', 'Data penguji berhasil ditambahkan.');
    }

    public function update($id)
    {
        $data = [
            'username'  => $this->request->getPost('username'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'instansi' => $this->request->getPost('instansi'),
        ];

        // Update password hanya jika diisi
        $pass = $this->request->getPost('password');
        if ($pass) {
            $data['password'] = password_hash($pass, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);
        return redirect()->to('/penguji')->with('success', 'Data penguji diperbarui.');
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/penguji')->with('success', 'Data penguji berhasil dihapus.');
    }

    public function cetak_akun_penguji()
    {
        // Bersihkan semua output buffer
        while (ob_get_level()) {
            ob_end_clean();
        }

        ini_set('zlib.output_compression', 'Off');

        /*
    |--------------------------------------------------------------------------
    | 1. Generate QR Code (Tanpa Composer)
    |--------------------------------------------------------------------------
    */
        $qrcode_base64 = null;

        $qrPath = APPPATH . 'ThirdParty/phpqrcode/qrlib.php';

        if (file_exists($qrPath)) {

            require_once $qrPath;

            $url_tujuan = "https://ukk.ingintau.my.id";

            // Tangkap output QR ke buffer (tanpa simpan file)
            ob_start();
            \QRcode::png($url_tujuan, null, 'L', 4, 2);
            $imageString = ob_get_clean();

            $qrcode_base64 = 'data:image/png;base64,' . base64_encode($imageString);
        }

        /*
    |--------------------------------------------------------------------------
    | 2. Ambil Data Penguji
    |--------------------------------------------------------------------------
    */
        $db = \Config\Database::connect();

        $penguji = $db->table('users')
            ->select('nama_lengkap, username, instansi')
            ->where('role', 'penguji')
            ->orderBy('instansi', 'ASC')
            ->orderBy('nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($penguji)) {
            return redirect()->back()
                ->with('error', 'Data penguji tidak ditemukan!');
        }

        $event = $db->table('events')
            ->where('id_event', session()->get('active_event_id'))
            ->get()
            ->getRowArray();

        /*
    |--------------------------------------------------------------------------
    | 3. Siapkan Data ke View
    |--------------------------------------------------------------------------
    */
        $data = [
            'title'     => 'Daftar Akun Penguji',
            'penguji'   => $penguji,
            'event'     => $event,
            'logo_smki' => $this->base64_encode_image(
                FCPATH . 'assets/img/logosmki.png'
            ),
            'logo_iso'  => $this->base64_encode_image(
                FCPATH . 'assets/img/logoiso.png'
            ),
            'qrcode'    => $qrcode_base64
        ];

        /*
    |--------------------------------------------------------------------------
    | 4. Render PDF
    |--------------------------------------------------------------------------
    */
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('isFontSubsettingEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);

        $html = view('admin/pdf_daftar_akun_penguji', $data);

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
                'inline; filename="Daftar_Akun_Penguji.pdf"'
            )
            ->setBody($dompdf->output());
    }



    // Jangan lupa sertakan fungsi helper base64 jika belum ada di controller ini
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
