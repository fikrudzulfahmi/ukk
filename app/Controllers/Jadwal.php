<?php

namespace App\Controllers;

use App\Models\JadwalSiswaModel;
use App\Models\SiswaModel;
use App\Models\PosModel;
use Dompdf\Dompdf;

class Jadwal extends BaseController
{
    protected $jadwalModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalSiswaModel();
    }

    public function index()
    {
        $id_event = session()->get('active_event_id');
        if (!$id_event) return redirect()->to('/event')->with('error', 'Aktifkan Event!');

        $siswaM = new \App\Models\SiswaModel();
        $posM   = new \App\Models\PosModel();

        // 1. Ambil siswa yang BELUM dijadwalkan (untuk form input)
        $siswaTersedia = $siswaM->select('siswa.*')
            ->join('penjadwalan', "penjadwalan.id_siswa = siswa.id_siswa AND penjadwalan.id_event = $id_event", 'left')
            ->where('siswa.id_event', $id_event)
            ->where('penjadwalan.id_siswa', null)
            ->orderBy('siswa.kelas', 'ASC')
            ->orderBy('siswa.nama_siswa', 'ASC')
            ->findAll();

        // 2. Ambil semua data jadwal
        $rawJadwal = $this->jadwalModel->where('penjadwalan.id_event', $id_event)
            ->join('siswa', 'siswa.id_siswa = penjadwalan.id_siswa')
            ->join('pos_ukk', 'pos_ukk.id_pos = penjadwalan.id_pos')
            ->select('penjadwalan.*, siswa.nama_siswa, siswa.kelas, pos_ukk.nama_pos')
            ->orderBy('tanggal_ujian', 'ASC') // <--- Ubah DESC menjadi ASC di sini
            ->orderBy('id_siswa', 'ASC')
            ->orderBy('urutan', 'ASC')
            ->findAll();

        // 3. LOGIKA GROUPING
        $jadwalGrouped = [];
        foreach ($rawJadwal as $row) {
            $tgl = $row['tanggal_ujian'];
            $idSiswa = $row['id_siswa'];

            $jadwalGrouped[$tgl][$idSiswa]['nama'] = $row['nama_siswa'];
            $jadwalGrouped[$tgl][$idSiswa]['kelas'] = $row['kelas'];
            $jadwalGrouped[$tgl][$idSiswa]['detail_pos'][] = [
                'nama_pos' => $row['nama_pos'],
                'urutan'   => $row['urutan'],
                'id_jadwal' => $row['id_jadwal'],
                'jam_mulai' => $row['jam_mulai'],
                'jam_selesai' => $row['jam_selesai'],
                'id_pos'   => $row['id_pos']
            ];
        }

        $data = [
            'title'  => 'Penjadwalan Rolling UKK',
            'siswa'  => $siswaTersedia,
            'pos'    => $posM->findAll(),
            'jadwal' => $jadwalGrouped, // Kirim data yang sudah rapi ke View
            'rawJadwal' => $rawJadwal
        ];

        return view('admin/jadwal_index', $data);
    }
    public function store()
    {
        $id_event = session()->get('active_event_id');
        $id_siswa_list = $this->request->getPost('id_siswa'); // Ini berupa array dari checkbox
        $tanggal  = $this->request->getPost('tanggal_ujian');

        // PERBAIKAN 1: Samakan kondisi dengan pesan error (maksimal 10 siswa)
        if (!$id_siswa_list || count($id_siswa_list) > 10) {
            return redirect()->back()->with('error', 'Pilih maksimal 10 siswa untuk satu sesi rolling!');
        }

        $posM = new \App\Models\PosModel();
        // LOGIKA INI SUDAH BENAR: Hanya mengambil pos pada active event
        $daftar_pos = $posM->where('id_event', $id_event)->orderBy('id_pos', 'ASC')->findAll();
        $total_pos  = count($daftar_pos);

        // PERBAIKAN 2: Cegah error "Division by Zero" jika event belum punya pos
        if ($total_pos === 0) {
            return redirect()->back()->with('error', 'Gagal membuat jadwal! Event aktif ini belum memiliki pos. Tambahkan pos terlebih dahulu.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($id_siswa_list as $index => $id_siswa) {
            // Logika Rolling: Setiap 2 siswa akan mulai di pos yang berbeda
            // Siswa 1 & 2 (index 0,1) -> Start Pos 1
            // Siswa 3 & 4 (index 2,3) -> Start Pos 2, dst
            $start_offset = floor($index / 2);

            for ($i = 0; $i < $total_pos; $i++) {
                $pos_index = ($start_offset + $i) % $total_pos;

                $this->jadwalModel->insert([
                    'id_event'      => $id_event,
                    'id_siswa'      => $id_siswa,
                    'id_pos'        => $daftar_pos[$pos_index]['id_pos'],
                    'tanggal_ujian' => $tanggal,
                    'urutan'        => $i + 1 // Urutan 1 adalah Pos Awal mereka
                ]);
            }
        }

        $db->transComplete();

        // Cek jika ada kegagalan query pada database
        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menyimpan jadwal.');
        }

        return redirect()->to('/jadwal')->with('success', 'Jadwal rolling siswa berhasil dibuat!');
    }

    public function update($id)
    {
        $id_event = session()->get('active_event_id');
        $id_pos   = $this->request->getPost('id_pos');
        $tgl      = $this->request->getPost('tanggal_ujian');
        $jam      = $this->request->getPost('jam_mulai');
        $jam_sel  = $this->request->getPost('jam_selesai');

        // PERBAIKAN 3: Pastikan Pos tujuan yang dikirim pada saat UPDATE adalah milik Active Event
        $posM = new \App\Models\PosModel();
        $posValid = $posM->where(['id_pos' => $id_pos, 'id_event' => $id_event])->first();

        if (!$posValid) {
            return redirect()->back()->with('error', 'Gagal update! Pos tujuan tidak valid atau bukan dari event aktif.');
        }

        // Validasi Kapasitas: Cek slot kecuali untuk data yang sedang diedit ini sendiri
        $cek = $this->jadwalModel->where([
            'id_pos'        => $id_pos,
            'tanggal_ujian' => $tgl,
            'jam_mulai'     => $jam,
            'id_jadwal !='  => $id // Kecualikan ID yang sedang diedit
        ])->countAllResults();

        if ($cek >= 2) {
            return redirect()->back()->with('error', 'Gagal update! Slot tujuan sudah penuh (Maks. 2 siswa).');
        }

        $this->jadwalModel->update($id, [
            'id_pos'        => $id_pos,
            'tanggal_ujian' => $tgl,
            'jam_mulai'     => $jam,
            'jam_selesai'   => $jam_sel,
        ]);

        return redirect()->to('/jadwal')->with('success', 'Jadwal berhasil diperbarui.');
    }
    public function delete($id)
    {
        $this->jadwalModel->delete($id);
        return redirect()->to('/jadwal')->with('success', 'Jadwal dihapus.');
    }


    // 1. Cetak SEMUA Jadwal (Kolektif)
    public function cetak_kolektif()
    {
        // Bersihkan semua output buffer di awal
        while (ob_get_level()) {
            ob_end_clean();
        }

        $id_event = session()->get('active_event_id');

        $jadwal = $this->jadwalModel
            ->select('penjadwalan.tanggal_ujian, s.nama_siswa, s.nisn, s.kelas')
            ->join('siswa s', 's.id_siswa = penjadwalan.id_siswa')
            ->where('penjadwalan.id_event', $id_event)
            ->where('penjadwalan.urutan', 1)
            ->orderBy('penjadwalan.tanggal_ujian', 'ASC')
            ->orderBy('s.kelas', 'ASC')
            ->orderBy('s.nama_siswa', 'ASC')
            ->findAll();

        $counts = [];
        foreach ($jadwal as $j) {
            $counts[$j['tanggal_ujian']] =
                ($counts[$j['tanggal_ujian']] ?? 0) + 1;
        }

        $eventModel = new \App\Models\EventModel();
        $event = $eventModel->find($id_event);

        $data = [
            'title'     => 'Jadwal_Peserta_UKK_' . date('Ymd_His') . '.pdf',
            'jadwal'    => $jadwal,
            'counts'    => $counts,
            'event'     => $event,
            'logo_smki' => $this->base64_encode_image(FCPATH . 'assets/img/logosmki.png'),
            'logo_iso'  => $this->base64_encode_image(FCPATH . 'assets/img/logoiso.png'),
        ];

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);

        $html = view('admin/pdf_jadwal_kolektif', $data);
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
                'inline; filename="Jadwal_Peserta_UKK.pdf"'
            )
            ->setBody($dompdf->output());
    }

    // 2. Cetak Presensi (Per Hari/Tanggal)
    public function cetak_presensi()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        $id_event = session()->get('active_event_id');
        $tanggal  = $this->request->getGet('tanggal');

        if (!$tanggal) {
            return redirect()->back()->with('error', 'Pilih tanggal!');
        }

        $db = \Config\Database::connect();

        // 1️⃣ HITUNG OFFSET (Jumlah siswa di hari-hari sebelumnya)
        // Ini agar jika hari pertama berakhir di no 10, hari kedua mulai dari 11
        $siswaSebelumnya = $this->jadwalModel
            ->where('id_event', $id_event)
            ->where('urutan', 1)
            ->where('tanggal_ujian <', $tanggal) // Cari yang tanggalnya sebelum hari ini
            ->countAllResults();

        $startNumber = $siswaSebelumnya + 1;

        // 2️⃣ QUERY SISWA HARI INI
        $presensi = $this->jadwalModel
            ->select('s.nama_siswa, s.kelas, p.nama_pos as pos_awal')
            ->join('siswa s', 's.id_siswa = penjadwalan.id_siswa')
            ->join('pos_ukk p', 'p.id_pos = penjadwalan.id_pos')
            ->where('penjadwalan.tanggal_ujian', $tanggal)
            ->where('penjadwalan.urutan', 1)
            ->where('penjadwalan.id_event', $id_event)
            ->orderBy('s.kelas', 'ASC')
            ->orderBy('s.nama_siswa', 'ASC')
            ->findAll();

        // 3️⃣ TAMBAHKAN NOMOR PESERTA BERKELANJUTAN
        foreach ($presensi as $key => $row) {
            // Nomor dimulai dari hasil hitung hari sebelumnya + urutan hari ini
            $nomorUrut = $startNumber + $key;
            $presensi[$key]['no_peserta_otomatis'] = str_pad($nomorUrut, 2, '0', STR_PAD_LEFT);
        }

        $data['presensi'] = $presensi;

        // 4️⃣ Query daftar pos & penguji (Tetap seperti sebelumnya)
        $data['daftar_pos'] = $db->table('penjadwalan j')
            ->select('p.nama_pos, u.nama_lengkap')
            ->join('pos_ukk p', 'p.id_pos = j.id_pos')
            ->join('event_penguji ep', 'ep.id_pos = p.id_pos AND ep.tanggal = j.tanggal_ujian')
            ->join('users u', 'u.id_user = ep.id_user')
            ->where('j.tanggal_ujian', $tanggal)
            ->where('j.id_event', $id_event)
            ->groupBy('p.id_pos')
            ->orderBy('p.id_pos', 'ASC')
            ->get()
            ->getResultArray();

        $data['event']   = $db->table('events')->where('id_event', $id_event)->get()->getRowArray();
        $data['tanggal'] = $tanggal;
        $data['logo_smki'] = $this->base64_encode_image(FCPATH . 'assets/img/logosmki.png');
        $data['logo_iso']  = $this->base64_encode_image(FCPATH . 'assets/img/logoiso.png');

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $html = view('admin/pdf_presensi', $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="Presensi_' . $tanggal . '.pdf"')
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

    public function cetak_berita_acara()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        $id_event = session()->get('active_event_id');
        $tanggal  = $this->request->getGet('tanggal');

        if (!$tanggal) {
            return redirect()->back()->with('error', 'Pilih tanggal!');
        }

        $db = \Config\Database::connect();

        // 1️⃣ Hitung jumlah siswa unik
        $total_siswa = $this->jadwalModel
            ->where('tanggal_ujian', $tanggal)
            ->where('id_event', $id_event)
            ->groupBy('id_siswa')
            ->countAllResults();

        // 2️⃣ Query daftar penguji (join aman tanpa interpolasi langsung)
        $daftar_penguji = $db->table('penjadwalan j')
            ->select('p.nama_pos, u.nama_lengkap')
            ->join('pos_ukk p', 'p.id_pos = j.id_pos')
            ->join(
                'event_penguji ep',
                'ep.id_pos = p.id_pos 
             AND ep.id_event = j.id_event 
             AND ep.tanggal = j.tanggal_ujian'
            )
            ->join('users u', 'u.id_user = ep.id_user')
            ->where('j.tanggal_ujian', $tanggal)
            ->where('j.id_event', $id_event)
            ->groupBy('p.id_pos')
            ->orderBy('p.id_pos', 'ASC')
            ->get()
            ->getResultArray();

        $event = $db->table('events')->where('id_event', $id_event)->get()->getRowArray();

        $data = [
            'title'         => 'Cetak_Berita_Acara_' . date('Ymd_His') . '.pdf',
            'tanggal'      => $tanggal,
            'event'        => $event,
            'jml_siswa'    => $total_siswa,
            'penguji'      => $daftar_penguji,
            'logo_smki'    => $this->base64_encode_image(FCPATH . 'assets/img/logosmki.png'),
            'logo_iso'     => $this->base64_encode_image(FCPATH . 'assets/img/logoiso.png')
        ];

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);

        $html = view('admin/cetak_berita_acara_pdf', $data);
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
                'inline; filename="Berita_Acara_' . $tanggal . '.pdf"'
            )
            ->setBody($dompdf->output());
    }


    public function cetak_kartu_massal()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        ini_set('zlib.output_compression', 'Off');

        $id_event = session()->get('active_event_id');
        $db = \Config\Database::connect();

        $peserta = $db->table('penjadwalan p')
            ->select('s.id_siswa, s.nama_siswa, s.kelas, s.nisn')
            ->join('siswa s', 's.id_siswa = p.id_siswa')
            ->where('p.id_event', $id_event)
            ->groupBy('p.id_siswa')
            ->orderBy('s.kelas', 'ASC')
            ->orderBy('s.nama_siswa', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($peserta)) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }


        require_once APPPATH . 'ThirdParty/phpqrcode/qrlib.php';

        foreach ($peserta as &$p) {

            $link = base_url('ujian/mulai/' . $p['id_siswa'] . '/' . md5($p['nisn']));

            ob_start();
            \QRcode::png($link, null, QR_ECLEVEL_M, 4, 1);
            $imageString = ob_get_contents();
            ob_end_clean();

            $p['qr_base64'] = 'data:image/png;base64,' . base64_encode($imageString);
        }
        unset($p);

        $event = $db->table('events')->where('id_event', $id_event)->get()->getRowArray();

        $data = [
            'title'     => 'Kartu_Peserta_UKK_' . date('Ymd_His') . '.pdf',
            'peserta'   => $peserta,
            'event'     => $event,
        ];

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('isFontSubsettingEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);

        $html = view('admin/pdf_kartu_peserta', $data);
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
                'inline; filename="' . $data['title'] . '"'
            )
            ->setBody($dompdf->output());
    }
}
