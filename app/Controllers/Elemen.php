<?php

namespace App\Controllers;

use App\Models\PosModel;
use App\Models\ElemenModel;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

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

        $db = \Config\Database::connect();

        // Event lain (selain yang aktif) untuk pilihan clone kriteria
        $events = $db->table('events')
            ->select('id_event, nama_event, tahun_pelajaran')
            ->where('id_event !=', $id_event)
            ->orderBy('id_event', 'DESC')
            ->get()->getResultArray();

        // Jumlah butir kriteria per event, untuk ditampilkan di modal clone
        $countRows = $db->table('elemen_penilaian')
            ->select('id_event, COUNT(*) AS total')
            ->groupBy('id_event')
            ->get()->getResultArray();

        $eventCounts = [];
        foreach ($countRows as $row) {
            $eventCounts[$row['id_event']] = (int) $row['total'];
        }

        $data = [
            'title'        => 'Kriteria Penilaian - ' . session()->get('nama_event'),
            'pos'          => $this->posModel->where('pos_ukk.id_event', $id_event)->findAll(),
            'elemen'       => $this->elemenModel->where('elemen_penilaian.id_event', $id_event)
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
                ->findAll(),
            'events'       => $events,
            'event_counts' => $eventCounts,
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

    /**
     * Import kriteria penilaian dari Excel.
     * Format: Kolom A = Nama POS, Kolom B = Nama Elemen/Kriteria.
     * POS yang belum ada akan dibuat otomatis.
     */
    public function import()
    {
        $id_event = session()->get('active_event_id');
        if (!$id_event) {
            return redirect()->to('/event')->with('error', 'Silakan aktifkan salah satu Event terlebih dahulu!');
        }

        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return redirect()->back()->with('error', 'File Excel tidak valid atau gagal diunggah.');
        }

        $ext = strtolower($file->getClientExtension() ?: $file->getExtension());
        if (!in_array($ext, ['xlsx', 'xls'], true)) {
            return redirect()->back()->with('error', 'Format file harus .xlsx atau .xls');
        }

        try {
            $reader = $ext === 'xlsx' ? new Xlsx() : new Xls();
            $reader->setReadDataOnly(true);
            // returnCellRef = false => baris menjadi array index 0,1,2 (bukan A,B,C)
            $rows = $reader->load($file->getTempName())->getActiveSheet()->toArray(null, true, true, false);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        $db = \Config\Database::connect();

        // Cache POS & elemen yang sudah ada pada event aktif
        $posCache = [];
        foreach ($db->table('pos_ukk')->where('id_event', $id_event)->get()->getResultArray() as $p) {
            $posCache[$this->normalize($p['nama_pos'])] = (int) $p['id_pos'];
        }

        $elemenCache = [];
        foreach ($db->table('elemen_penilaian')->where('id_event', $id_event)->get()->getResultArray() as $el) {
            $elemenCache[$el['id_pos'] . '|' . $this->normalize($el['nama_elemen'])] = true;
        }

        $posBaru = 0;
        $elemenBaru = 0;
        $duplikat = 0;

        $db->transBegin();
        try {
            foreach ($rows as $index => $row) {
                // Lewati baris header
                if ($index === 0) {
                    continue;
                }

                $namaPos    = $this->cleanCell($row[0] ?? '');
                $namaElemen = $this->cleanCell($row[1] ?? '');

                // Baris hanya dianggap valid bila nama elemen terisi
                if ($namaElemen === '') {
                    continue;
                }

                // POS kosong => pakai POS pertama milik event, atau buat POS umum
                if ($namaPos === '') {
                    $namaPos = 'Kriteria Umum';
                }

                $posKey = $this->normalize($namaPos);
                if (!isset($posCache[$posKey])) {
                    $db->table('pos_ukk')->insert([
                        'id_event' => $id_event,
                        'nama_pos' => $namaPos,
                    ]);
                    $posCache[$posKey] = (int) $db->insertID();
                    $posBaru++;
                }

                $id_pos = $posCache[$posKey];
                $elemenKey = $id_pos . '|' . $this->normalize($namaElemen);

                if (isset($elemenCache[$elemenKey])) {
                    $duplikat++;
                    continue;
                }

                $db->table('elemen_penilaian')->insert([
                    'id_event'    => $id_event,
                    'id_pos'      => $id_pos,
                    'nama_elemen' => $namaElemen,
                ]);
                $elemenCache[$elemenKey] = true;
                $elemenBaru++;
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Import gagal: ' . $e->getMessage());
        }

        $pesan = "{$elemenBaru} kriteria berhasil diimport";
        if ($posBaru > 0) {
            $pesan .= ", {$posBaru} POS baru dibuat";
        }
        if ($duplikat > 0) {
            $pesan .= ", {$duplikat} baris dilewati karena sudah ada";
        }

        return redirect()->to('/elemen')->with('success', $pesan . '.');
    }

    /**
     * Unduh template Excel import kriteria.
     */
    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Kriteria');

        $sheet->fromArray([
            ['POS', 'NAMA ELEMEN / KRITERIA'],
            ['Pengujian Mesin', '1.1 Memeriksa celah katup'],
            ['Pengujian Mesin', '1.2 Memeriksa sistem bahan bakar'],
            ['Pengujian Sasis', '2.1 Memeriksa sistem rem'],
            ['Pengujian Kelistrikan', '3.1 Memeriksa sistem pengapian'],
        ], null, 'A1');

        $sheet->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet->getStyle('A1:B1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD1FAE5');
        $sheet->getColumnDimension('A')->setWidth(28);
        $sheet->getColumnDimension('B')->setWidth(60);

        $dir = WRITEPATH . 'uploads';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $path = $dir . '/template_import_kriteria.xlsx';
        (new XlsxWriter($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        return $this->response->download($path, null)
            ->setFileName('template_import_kriteria.xlsx')
            ->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /**
     * Salin (clone) seluruh POS + kriteria dari event lain ke event aktif.
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

        // Cache POS & kriteria pada event tujuan
        $posTujuan = [];
        foreach ($db->table('pos_ukk')->where('id_event', $id_event)->get()->getResultArray() as $p) {
            $posTujuan[$this->normalize($p['nama_pos'])] = (int) $p['id_pos'];
        }

        $kriteriaTujuan = [];
        foreach ($db->table('elemen_penilaian')->where('id_event', $id_event)->get()->getResultArray() as $el) {
            $kriteriaTujuan[$el['id_pos'] . '|' . $this->normalize($el['nama_elemen'])] = true;
        }

        $posSumber = $db->table('pos_ukk')->where('id_event', $id_sumber)->get()->getResultArray();
        $kriteriaSumber = $db->table('elemen_penilaian')->where('id_event', $id_sumber)->get()->getResultArray();

        if (empty($posSumber) && empty($kriteriaSumber)) {
            return redirect()->back()->with('error', 'Event "' . esc($sumber['nama_event']) . '" belum memiliki kriteria untuk disalin.');
        }

        $posBaru = 0;
        $kriteriaBaru = 0;
        $duplikat = 0;

        $db->transBegin();
        try {
            // 1. Salin POS, mapping id_pos lama => id_pos baru
            $mapPos = [];
            foreach ($posSumber as $p) {
                $key = $this->normalize($p['nama_pos']);
                if (isset($posTujuan[$key])) {
                    $mapPos[$p['id_pos']] = $posTujuan[$key];
                    continue;
                }

                $db->table('pos_ukk')->insert([
                    'id_event' => $id_event,
                    'nama_pos' => $p['nama_pos'],
                ]);

                $newId = (int) $db->insertID();
                $posTujuan[$key] = $newId;
                $mapPos[$p['id_pos']] = $newId;
                $posBaru++;
            }

            // 2. Salin kriteria mengikuti POS hasil mapping
            foreach ($kriteriaSumber as $el) {
                $idPosBaru = $mapPos[$el['id_pos']] ?? $posTujuan[$this->normalize($el['nama_pos'] ?? '')] ?? null;
                if (!$idPosBaru) {
                    continue;
                }

                $key = $idPosBaru . '|' . $this->normalize($el['nama_elemen']);
                if (isset($kriteriaTujuan[$key])) {
                    $duplikat++;
                    continue;
                }

                $db->table('elemen_penilaian')->insert([
                    'id_event'    => $id_event,
                    'id_pos'      => $idPosBaru,
                    'nama_elemen' => $el['nama_elemen'],
                ]);

                $kriteriaTujuan[$key] = true;
                $kriteriaBaru++;
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Clone gagal: ' . $e->getMessage());
        }

        $pesan = "{$kriteriaBaru} kriteria disalin dari \"" . esc($sumber['nama_event']) . '"';
        if ($posBaru > 0) {
            $pesan .= ", {$posBaru} POS baru dibuat";
        }
        if ($duplikat > 0) {
            $pesan .= ", {$duplikat} kriteria dilewati karena sudah ada";
        }

        return redirect()->to('/elemen')->with('success', $pesan . '.');
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

    /**
     * Normalisasi teks untuk perbandingan duplikat.
     */
    private function normalize(string $teks): string
    {
        return mb_strtolower(trim(preg_replace('/\s+/u', ' ', $teks)));
    }

    /**
     * Bersihkan isi cell: hilangkan spasi berlebih & karakter non-breaking space.
     */
    private function cleanCell($value): string
    {
        $value = (string) $value;
        $value = str_replace(["\xc2\xa0", "\u{00A0}"], ' ', $value);

        return trim(preg_replace('/\s+/u', ' ', $value));
    }
}
