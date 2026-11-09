<?php

namespace App\Controllers;

use App\Models\SoalModel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SoalTeori extends BaseController
{
    protected $soalModel;

    public function __construct()
    {
        $this->soalModel = new SoalModel();
    }

    public function index()
    {
        $id_event = session()->get('active_event_id');

        $db = \Config\Database::connect();

        // Event lain untuk pilihan clone soal
        $events = $db->table('events')
            ->select('id_event, nama_event, tahun_pelajaran')
            ->where('id_event !=', $id_event ?? 0)
            ->orderBy('id_event', 'DESC')
            ->get()->getResultArray();

        // Jumlah soal per event, untuk info di modal clone
        $countRows = $db->table('soal_teori')
            ->select('id_event, COUNT(*) AS total')
            ->groupBy('id_event')
            ->get()->getResultArray();

        $eventCounts = [];
        foreach ($countRows as $row) {
            $eventCounts[$row['id_event']] = (int) $row['total'];
        }

        $data = [
            'title'        => 'Manajemen Soal Teori',
            // Pastikan kolom id_event ada di tabel soal_teori
            'soal'         => $this->soalModel->where('id_event', $id_event)->findAll(),
            'events'       => $events,
            'event_counts' => $eventCounts,
        ];

        // Path view disesuaikan ke folder admin
        return view('admin/soal_teori_index', $data);
    }

    /**
     * Salin (clone) seluruh bank soal dari event/tahun sebelumnya.
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

        $soalSumber = $db->table('soal_teori')->where('id_event', $id_sumber)->orderBy('id_soal', 'ASC')->get()->getResultArray();
        if (empty($soalSumber)) {
            return redirect()->back()->with('error', 'Event "' . esc($sumber['nama_event']) . '" belum memiliki soal untuk disalin.');
        }

        // Kunci duplikat berdasarkan teks pertanyaan (dinormalisasi)
        $existing = [];
        foreach ($db->table('soal_teori')->select('pertanyaan')->where('id_event', $id_event)->get()->getResultArray() as $s) {
            $existing[$this->normalize($s['pertanyaan'])] = true;
        }

        $baru = 0;
        $duplikat = 0;

        $db->transBegin();
        try {
            foreach ($soalSumber as $s) {
                $key = $this->normalize($s['pertanyaan']);
                if (isset($existing[$key])) {
                    $duplikat++;
                    continue;
                }

                $db->table('soal_teori')->insert([
                    'id_event'      => $id_event,
                    'pertanyaan'    => $s['pertanyaan'],
                    'opsi_a'        => $s['opsi_a'],
                    'opsi_b'        => $s['opsi_b'],
                    'opsi_c'        => $s['opsi_c'],
                    'opsi_d'        => $s['opsi_d'],
                    'jawaban_benar' => $s['jawaban_benar'],
                ]);

                $existing[$key] = true;
                $baru++;
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Clone gagal: ' . $e->getMessage());
        }

        $pesan = "{$baru} soal disalin dari \"" . esc($sumber['nama_event']) . '"';
        if ($duplikat > 0) {
            $pesan .= ", {$duplikat} soal dilewati karena sudah ada";
        }

        return redirect()->to(base_url('soalteori'))->with('success', $pesan . '.');
    }

    private function normalize(string $teks): string
    {
        return mb_strtolower(trim(preg_replace('/\s+/u', ' ', strip_tags($teks))));
    }


    public function simpan()
    {
        $this->soalModel->save([
            'id_event'      => session()->get('active_event_id'),
            'pertanyaan'    => $this->request->getPost('pertanyaan'),
            'opsi_a'        => $this->request->getPost('opsi_a'),
            'opsi_b'        => $this->request->getPost('opsi_b'),
            'opsi_c'        => $this->request->getPost('opsi_c'),
            'opsi_d'        => $this->request->getPost('opsi_d'),
            'jawaban_benar' => $this->request->getPost('jawaban_benar'),
        ]);

        return redirect()->to(base_url('soalteori'))->with('success', 'Soal berhasil disimpan.');
    }

    public function hapus($id)
    {
        $this->soalModel->delete($id);
        return redirect()->to(base_url('soalteori'))->with('success', 'Soal berhasil dihapus.');
    }

    public function import()
    {
        $file = $this->request->getFile('file_excel');

        // Validasi ekstensi file
        if ($file->getExtension() !== 'xlsx') {
            return redirect()->back()->with('error', 'Format file harus .xlsx');
        }

        $reader = new Xlsx();
        $spreadsheet = $reader->load($file->getTempName());
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $id_event = session()->get('active_event_id');
        $successCount = 0;

        foreach ($sheetData as $index => $row) {
            // Lewati baris pertama (header)
            if ($index == 0) continue;

            // Pastikan baris tidak kosong (cek pertanyaan)
            if (empty($row[0])) continue;

            $data = [
                'id_event'      => $id_event,
                'pertanyaan'    => $row[0], // Kolom A
                'opsi_a'        => $row[1], // Kolom B
                'opsi_b'        => $row[2], // Kolom C
                'opsi_c'        => $row[3], // Kolom D
                'opsi_d'        => $row[4], // Kolom E
                'jawaban_benar' => strtoupper(trim($row[5])), // Kolom F (A/B/C/D)
            ];

            $this->soalModel->insert($data);
            $successCount++;
        }

        return redirect()->to(base_url('soalteori'))->with('success', "$successCount soal berhasil diimport.");
    }
    public function downloadTemplate()
    {
        $path = ROOTPATH . 'template_soal_ukk.xlsx'; // Pastikan file ada di folder utama hosting

        if (file_exists($path)) {
            return $this->response->download($path, null);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("File tidak ditemukan di: " . $path);
        }
    }

    public function buka_kunci($id_siswa)
    {
        $db = \Config\Database::connect();
        $id_event = session()->get('active_event_id');

        $db->table('nilai_teori')
            ->where(['id_siswa' => $id_siswa, 'id_event' => $id_event])
            ->update(['is_locked' => 0]);

        return redirect()->back()->with('success', 'Ujian siswa berhasil dibuka kembali.');
    }

    public function cetak()
    {
        // 1. Bersihkan buffer agar PDF tidak corrupt
        while (ob_get_level()) {
            ob_end_clean();
        }
        ini_set('zlib.output_compression', 'Off');

        $id_event = session()->get('active_event_id');
        $db = \Config\Database::connect();

        // 2. Ambil data soal dan data event
        $soal = $this->soalModel->where('id_event', $id_event)->findAll();
        $event = $db->table('events')->where('id_event', $id_event)->get()->getRowArray();

        if (empty($soal)) {
            return redirect()->back()->with('error', 'Tidak ada soal untuk dicetak!');
        }

        // 3. Siapkan data untuk View (termasuk logo base64)
        $data = [
            'title'     => 'Soal Teori - ' . $event['nama_event'],
            'soal'      => $soal,
            'event'     => $event,
            'logo_smki' => $this->base64_encode_image(FCPATH . 'assets/img/logosmki.png'),
            'logo_iso'  => $this->base64_encode_image(FCPATH . 'assets/img/logoiso.png'),
        ];

        // 4. Render ke Dompdf
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $html = view('admin/pdf_soal_teori', $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="Soal_Teori_UKK.pdf"')
            ->setBody($dompdf->output());
    }

    // Fungsi helper (pastikan sudah ada di controller ini)
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
