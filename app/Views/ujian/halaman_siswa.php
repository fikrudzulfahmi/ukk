<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Teori - UKK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .option-btn {
            border: 2px solid #dee2e6;
            transition: 0.2s;
            cursor: pointer;
        }

        .btn-check:checked+.option-btn {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }

        .soal-card {
            border-radius: 15px;
        }

        /* Panel Navigasi Floating */
        .floating-nav {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 250px;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-height: 85vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .nav-box {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 4px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            color: #495057;
            background-color: #fff;
            transition: 0.2s;
        }

        /* Warna jika soal sudah dijawab */
        .nav-box.answered {
            background-color: #198754;
            /* Hijau Bootstrap */
            color: white;
            border-color: #198754;
        }

        .nav-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* Responsif untuk HP: Floating di bawah & Horizontal Scroll */
        @media (max-width: 992px) {
            body {
                /* Tambahkan ruang di bagian bawah agar tombol Selesai tidak tertutup panel */
                padding-bottom: 90px;
            }

            .floating-nav {
                position: fixed;
                bottom: 0;
                /* Menempel di bawah layar */
                top: auto;
                /* Membatalkan top: 20px dari desktop */
                left: 0;
                right: 0;
                width: 100%;
                background: white;
                padding: 10px 15px;
                border-radius: 15px 15px 0 0;
                /* Melengkung di atas */
                box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
                max-height: auto;
                z-index: 1050;
            }

            .nav-title {
                display: none;
                /* Sembunyikan teks "Navigasi Soal" di HP agar ringkas */
            }

            .nav-container {
                flex-wrap: nowrap;
                /* Paksa berjajar ke samping */
                overflow-x: auto;
                /* Aktifkan scroll horizontal */
                justify-content: flex-start;
                /* Mulai dari kiri */
                padding-bottom: 8px;
                /* Ruang kecil untuk scrollbar */
            }

            /* Mempercantik scrollbar di HP */
            .nav-container::-webkit-scrollbar {
                height: 5px;
            }

            .nav-container::-webkit-scrollbar-thumb {
                background-color: #adb5bd;
                border-radius: 10px;
            }

            .nav-box {
                flex: 0 0 auto;
                /* Mencegah kotak mengecil saat dipaksa sebaris */
                margin: 0 5px;
                /* Jarak antar kotak */
            }
        }
    </style>
</head>

<body>

    <div class="container py-4">
        <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
            <div class="card-body">
                <h6 class="mb-1"><?= $siswa['nama_siswa'] ?></h6>
                <div class="small opacity-75"><?= $siswa['kelas'] ?> | Ujian Teori</div>
            </div>
        </div>

        <div class="floating-nav">
            <h6 class="text-center mb-3 border-bottom pb-2">Navigasi Soal</h6>
            <div class="nav-container">
                <?php foreach ($soal as $index => $s): ?>
                    <?php
                    // Cek apakah sudah dijawab untuk memberi warna hijau
                    $is_answered = isset($list_jawaban[$s['id_soal']]) ? 'answered' : '';
                    ?>
                    <a href="#soal_<?= $index + 1 ?>" class="nav-box <?= $is_answered ?>" id="nav_<?= $s['id_soal'] ?>">
                        <?= $index + 1 ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php foreach ($soal as $index => $s): ?>
            <div class="card border-0 shadow-sm mb-4 soal-card" id="soal_<?= $index + 1 ?>">
                <div class="card-body">
                    <div class="badge bg-light text-primary mb-3">Soal No. <?= $index + 1 ?></div>
                    <p class="fw-semibold text-dark"><?= $s['pertanyaan'] ?></p>

                    <div class="d-grid gap-2 mt-3">
                        <?php foreach (['A', 'B', 'C', 'D'] as $opt): ?>
                            <?php
                            $sudah_dijawab = (isset($list_jawaban[$s['id_soal']]) && $list_jawaban[$s['id_soal']] == $opt) ? 'checked' : '';
                            ?>
                            <input type="radio" class="btn-check ajax-save"
                                name="soal_<?= $s['id_soal'] ?>"
                                id="q<?= $s['id_soal'] . $opt ?>"
                                value="<?= $opt ?>"
                                data-soal="<?= $s['id_soal'] ?>"
                                data-siswa="<?= $siswa['id_siswa'] ?>"
                                <?= $sudah_dijawab ?>>
                            <label class="btn option-btn text-start p-3 rounded-3" for="q<?= $s['id_soal'] . $opt ?>">
                                <span class="fw-bold me-2"><?= $opt ?>.</span> <?= $s['opsi_' . strtolower($opt)] ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="p-3">
            <button onclick="finalisasi()" class="btn btn-danger w-100 py-3 fw-bold shadow">
                SELESAI & KUNCI JAWABAN
            </button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('.ajax-save').on('change', function() {
            const input = $(this);
            const data = {
                id_siswa: input.data('siswa'),
                id_soal: input.data('soal'),
                jawaban: input.val(),
                "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
            };

            $('#nav_' + data.id_soal).addClass('answered');

            $.post("<?= base_url('ujian/simpan_ajax') ?>", data, function(res) {
                // Toast Notification (Muncul kecil di pojok tanpa mengganggu siswa)
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true
                });

                if (res.status === 'success') {
                    Toast.fire({
                        icon: 'success',
                        title: 'Jawaban tersimpan'
                    });
                }
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Koneksi Terputus',
                    text: 'Jawaban gagal disimpan, silakan periksa internet Anda.',
                });
            });
        });

        function finalisasi() {
            // 1. Hitung total soal (berdasarkan jumlah elemen dengan class .soal-card)
            const totalSoal = $('.soal-card').length;

            // 2. Hitung soal yang sudah dijawab (berdasarkan radio button yang :checked)
            const terjawab = $('.btn-check:checked').length;

            // 3. Cek apakah ada soal yang belum dijawab
            if (terjawab < totalSoal) {
                // Jika belum lengkap, munculkan peringatan dan hentikan proses
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops! Belum Selesai',
                    text: `Anda baru menjawab ${terjawab} dari ${totalSoal} soal. Silakan lengkapi semua jawaban sebelum menyelesaikan ujian!`,
                    confirmButtonText: 'Kembali Mengerjakan',
                    confirmButtonColor: '#3085d6'
                });
                return; // Menghentikan fungsi agar tidak lanjut ke konfirmasi selesai
            }

            // 4. Jika sudah lengkap, tampilkan konfirmasi selesai
            Swal.fire({
                title: 'Selesai Ujian?',
                text: "Jawaban akan dikunci dan tidak bisa diubah lagi!",
                icon: 'question', // Sedikit ubah icon agar lebih pas
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Selesai!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const btnSelesai = document.querySelector('button[onclick="finalisasi()"]');

                    btnSelesai.disabled = true;
                    btnSelesai.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Memproses Jawaban...
            `;

                    Swal.fire({
                        title: 'Harap Tunggu',
                        html: 'Sedang mengunci jawaban Anda...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // ---> TAMBAHKAN BARIS INI <---
                    // Bersihkan memori pelanggaran saat siswa selesai secara normal
                    const violationKey = 'pelanggaran_ujian_<?= $siswa['id_siswa'] ?>';
                    localStorage.removeItem(violationKey);

                    window.location.href = "<?= base_url('ujian/selesai/' . $siswa['id_siswa']) ?>";
                }
            });
        }

        // PENGAMANAN UJIAN (ANTI-CHEAT) DENGAN LOCAL STORAGE
        // Buat kunci (key) unik berdasarkan ID Siswa agar tidak tertukar
        const violationKey = 'pelanggaran_ujian_<?= $siswa['id_siswa'] ?>';

        // Cek apakah sebelumnya sudah ada pelanggaran di Local Storage? Jika belum, set 0
        let jumlahPelanggaran = localStorage.getItem(violationKey) ? parseInt(localStorage.getItem(violationKey)) : 0;
        const batasMaksimal = 3; // Ubah angka ini untuk mengatur batas toleransi

        document.addEventListener("visibilitychange", function() {
            // Jika halaman disembunyikan (buka tab baru, minimize, dll)
            if (document.hidden) {
                jumlahPelanggaran++;

                // SIMPAN jumlah terbaru ke Local Storage
                localStorage.setItem(violationKey, jumlahPelanggaran);

                if (jumlahPelanggaran >= batasMaksimal) {
                    // Paksa Selesai jika melanggar batas
                    Swal.fire({
                        icon: 'error',
                        title: 'Pelanggaran Maksimal!',
                        text: 'Anda terdeteksi keluar dari ujian berkali-kali. Ujian dihentikan paksa!',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: true,
                        confirmButtonText: 'Tutup Ujian'
                    }).then((result) => {
                        // Bersihkan Local Storage karena ujian sudah selesai (agar ujian berikutnya mulai dari 0 lagi)
                        localStorage.removeItem(violationKey);

                        // Tampilkan loading dan redirect ke fungsi selesai
                        Swal.fire({
                            title: 'Mengunci Jawaban...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        window.location.href = "<?= base_url('ujian/selesai/' . $siswa['id_siswa']) ?>";
                    });

                } else {
                    // Beri Peringatan jika masih dalam batas toleransi
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan Pelanggaran!',
                        text: `Sistem mendeteksi Anda meninggalkan halaman ujian. Ini adalah peringatan ke-${jumlahPelanggaran} dari ${batasMaksimal}. Jika melebihi batas, ujian akan otomatis diakhiri!`,
                        confirmButtonText: 'Saya Mengerti',
                        confirmButtonColor: '#d33',
                        allowOutsideClick: false
                    });
                }
            }
        });

        // FUNGSI UNTUK MASUK KE MODE LAYAR PENUH
        function openFullscreen() {
            const elem = document.documentElement;
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                /* Safari */
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                /* IE11 */
                elem.msRequestFullscreen();
            }
        }

        // JALANKAN POP-UP SAAT HALAMAN DIMUAT
        $(document).ready(function() {
            // Tampilkan pop-up wajib klik sebelum bisa melihat soal
            Swal.fire({
                title: 'Siap Mengerjakan?',
                text: 'Ujian ini menggunakan mode Layar Penuh. Pastikan koneksi internet Anda stabil.',
                icon: 'info',
                confirmButtonText: 'Mulai Ujian',
                confirmButtonColor: '#0d6efd',
                allowOutsideClick: false, // Tidak bisa ditutup dengan klik di luar
                allowEscapeKey: false // Tidak bisa ditutup dengan tombol ESC
            }).then((result) => {
                if (result.isConfirmed) {
                    openFullscreen(); // Panggil fungsi fullscreen saat tombol diklik
                }
            });
        });
    </script>
</body>

</html>