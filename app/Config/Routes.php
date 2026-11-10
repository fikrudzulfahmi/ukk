<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Public (Login)
$routes->get('/', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Proteksi Auth (Harus Login)
$routes->group('', ['filter' => ['auth', 'admin']], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
});

$routes->group('event', ['filter' => ['auth', 'admin']], function ($routes) {
    $routes->get('/', 'Event::index');
    $routes->post('store', 'Event::store'); // Untuk Tambah
    $routes->post('update/(:num)', 'Event::update/$1'); // Untuk Edit
    $routes->get('activate/(:num)', 'Event::activate/$1');
    $routes->get('delete/(:num)', 'Event::delete/$1');
});

$routes->group('siswa', ['filter' => ['auth', 'admin']], function ($routes) {
    $routes->get('/', 'Siswa::index');
    $routes->post('store', 'Siswa::store');
    $routes->post('update/(:num)', 'Siswa::update/$1');
    $routes->get('delete/(:num)', 'Siswa::delete/$1');
    $routes->post('import', 'Siswa::import');
    $routes->post('get_data', 'Siswa::get_data');
});

$routes->group('elemen', ['filter' => ['auth', 'admin']], function ($routes) {
    $routes->get('/', 'Elemen::index');
    $routes->post('store', 'Elemen::store');
    $routes->post('import', 'Elemen::import');
    $routes->post('clone', 'Elemen::clone');
    $routes->get('template', 'Elemen::template');
    $routes->post('update/(:num)', 'Elemen::update/$1');
    $routes->get('delete/(:num)', 'Elemen::delete/$1');
});

$routes->group('pos', ['filter' => ['auth', 'admin']], function ($routes) {
    $routes->get('/', 'Pos::index');           // Menampilkan daftar pos
    $routes->post('store', 'Pos::store');       // Menyimpan pos baru
    $routes->post('update/(:num)', 'Pos::update/$1');
    $routes->get('delete/(:num)', 'Pos::delete/$1'); // Menghapus pos
});

$routes->group('penguji', ['filter' => ['auth', 'admin']], function ($routes) {
    $routes->get('/', 'Penguji::index');
    $routes->post('store', 'Penguji::store');
    $routes->post('update/(:num)', 'Penguji::update/$1');
    $routes->get('delete/(:num)', 'Penguji::delete/$1');
    // Route untuk cetak akun penguji
    $routes->get('cetak_akun', 'Penguji::cetak_akun_penguji');
});

$routes->group('plotting', ['filter' => ['auth', 'admin']], function ($routes) {
    $routes->get('/', 'EventPenguji::index');
    $routes->post('store', 'EventPenguji::store');
    $routes->post('update/(:num)', 'EventPenguji::update/$1');
    $routes->get('delete/(:num)', 'EventPenguji::delete/$1');
    $routes->get('cetak-presensi/(:any)', 'EventPenguji::cetakPresensi/$1');
    $routes->get('cetak-semua-jadwal-penguji', 'EventPenguji::cetakSemuaJadwal');
});

$routes->group('jadwal', ['filter' => ['auth', 'admin']], function ($routes) {
    $routes->get('/', 'Jadwal::index');
    $routes->post('store', 'Jadwal::store');
    $routes->post('update/(:num)', 'Jadwal::update/$1');
    $routes->get('delete/(:num)', 'Jadwal::delete/$1');
    $routes->get('cetak_presensi', 'Jadwal::cetak_presensi');
    $routes->get('cetak_jadwal_kolektif', 'Jadwal::cetak_kolektif');
    // Route untuk cetak Berita Acara UKK
    $routes->get('cetak_berita_acara', 'Jadwal::cetak_berita_acara');
    $routes->get('cetak_kartu_massal', 'Jadwal::cetak_kartu_massal');
});

$routes->group('soalteori', ['filter' => ['auth', 'admin']], function ($routes) {
    $routes->get('/', 'SoalTeori::index'); // Langsung ke Penilaian
    $routes->post('simpan', 'SoalTeori::simpan');
    $routes->get('hapus/(:num)', 'SoalTeori::hapus/$1');
    $routes->post('import', 'SoalTeori::import');
    $routes->post('clone', 'SoalTeori::clone');
    $routes->get('template_soal_ukk.xlsx', 'SoalTeori::downloadTemplate');
    $routes->get('buka_kunci/(:num)', 'SoalTeori::buka_kunci/$1');
    $routes->get('cetak', 'SoalTeori::cetak');
});

// Rute Nilai Teori (Sudah disesuaikan)
$routes->group('nilai_teori', ['filter' => ['auth', 'admin']], function ($routes) {
    $routes->get('/', 'Penilaian::nilai_teori'); // Langsung ke Penilaian
    $routes->get('(:num)', 'Penilaian::nilai_teori/$1');
    $routes->post('simpan_ajax', 'Penilaian::simpan_teori_ajax');
});

$routes->group('monitoring', ['filter' => ['auth', 'admin']], function ($routes) {
    // URL: base_url('monitoring/matriks')
    $routes->get('matriks', 'Penilaian::monitoring_matriks');

    // URL: base_url('monitoring/cetak_rekap_harian')
    $routes->get('cetak_rekap_harian', 'Penilaian::cetak_rekap_harian');
});

$routes->group('laporan', ['filter' => ['auth', 'admin']], function ($routes) {
    // URL: base_url('laporan/rekap_nilai')
    $routes->get('/', 'Penilaian::rekap_nilai');
    $routes->get('export_pdf', 'Penilaian::export_pdf');
    $routes->get('export_excel', 'Penilaian::export_excel');

    // Anda bisa menambah rute laporan lain di sini nanti, misal:
    // $routes->get('cetak_sertifikat', 'Penilaian::cetak_sertifikat');
});

$routes->group('penilaian', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Penilaian::index');
    $routes->get('input', 'Penilaian::input');
    $routes->get('riwayat', 'Penilaian::riwayat');
    // Menggunakan (:any) karena jam mengandung karakter ':' (contoh: 08:30:00)
    $routes->get('dual/(:any)/(:num)', 'Penilaian::input_dual/$1/$2');
    // Pastikan parameter pertama (:num) untuk sesi_ke dan kedua (:num) untuk id_pos
    $routes->get('input_dual/(:num)/(:num)', 'Penilaian::input_dual/$1/$2');
    $routes->post('simpan_dual', 'Penilaian::simpan_dual');
    $routes->post('simpan_single', 'Penilaian::simpan_single');
    // Sesuaikan 'Penilaian' dengan nama Controller asli Anda
    $routes->post('simpan_catatan', 'Penilaian::simpan_catatan');
});

$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    // Group penilaian di dalam group admin
    $routes->group('penilaian', function ($routes) {
        $routes->get('nilai_teori', 'Admin\Penilaian::nilai_teori');
        $routes->get('nilai_teori/(:num)', 'Admin\Penilaian::nilai_teori/$1');
        $routes->post('simpan_teori_ajax', 'Admin\Penilaian::simpan_teori_ajax');
    });
});


$routes->group('dokumentasi', ['filter' => ['auth', 'admin']], function ($routes) {
    // URL: base_url('dokumentasi')
    $routes->get('/', 'Dokumentasi::index');

    // URL: base_url('dokumentasi/upload')
    $routes->post('upload', 'Dokumentasi::upload');

    // URL: base_url('dokumentasi/hapus/1')
    $routes->get('hapus/(:num)', 'Dokumentasi::hapus/$1');

    $routes->get('cetak', 'Dokumentasi::cetak');
});
// Route untuk Ujian Siswa (Akses via QR Code)
// Format: /ujian/mulai/ID_SISWA/TOKEN_NISN
$routes->get('ujian/mulai/(:num)/(:any)', 'Ujian::mulai/$1/$2');
$routes->post('ujian/simpan_ajax', 'Ujian::simpan_ajax');
$routes->get('ujian/selesai/(:num)', 'Ujian::selesai/$1');
// URL: base_url('ujian/hasil_praktek')
// URL: base_url('ujian/hasil_praktek/123/a1b2c3d4e5f6g7h8')
$routes->get('ujian/hasil_praktek/(:segment)/(:segment)', 'Ujian::hasil_praktek/$1/$2');
