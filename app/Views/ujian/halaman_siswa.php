<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Teori - UKK</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/ukk.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: var(--uk-canvas, #f8fafc);
            padding-bottom: 95px;
        }

        .exam-head {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            padding: 1.15rem 1.35rem;
            color: #fff;
            background:
                radial-gradient(120% 140% at 100% 0%, rgba(16, 185, 129, .4), transparent 55%),
                linear-gradient(135deg, #064e3b 0%, #065f46 55%, #059669 100%);
            box-shadow: 0 16px 36px -20px rgba(5, 150, 105, 0.7);
        }

        .exam-head__name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: -.02em;
            margin: 0 0 .15rem;
        }

        .exam-head__meta {
            font-size: .8rem;
            color: rgba(255, 255, 255, .82);
            display: flex;
            align-items: center;
            gap: .45rem;
        }

        .exam-head__badge {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .35rem .75rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .25);
            color: #fff;
        }

        .soal-card {
            border: 1px solid var(--uk-line) !important;
            border-radius: 18px !important;
            box-shadow: var(--uk-shadow-sm) !important;
            background: #ffffff;
        }

        .soal-card .badge {
            background: var(--uk-brand-soft) !important;
            color: var(--uk-brand) !important;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .04em;
            padding: .45rem .8rem;
            border-radius: 9px;
        }

        .soal-card p.fw-semibold {
            font-size: .98rem;
            line-height: 1.65;
            color: var(--uk-ink) !important;
        }

        .option-btn {
            border: 1px solid var(--uk-line);
            background: #ffffff;
            border-radius: 12px !important;
            font-size: .9rem;
            color: var(--uk-ink-2);
            transition: all .16s ease;
        }

        .option-btn:hover {
            border-color: var(--uk-brand);
            background: #f0fdf4;
            color: var(--uk-ink);
        }

        .btn-check:checked + .option-btn {
            border-color: var(--uk-brand) !important;
            background: #ecfdf5 !important;
            color: var(--uk-brand) !important;
            font-weight: 600;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, .15);
        }

        /* Panel Navigasi Floating (Desktop) */
        .floating-nav {
            position: fixed;
            top: 24px;
            right: 24px;
            width: 260px;
            background: #ffffff;
            padding: 1rem;
            border-radius: 18px;
            border: 1px solid var(--uk-line);
            box-shadow: var(--uk-shadow);
            max-height: 85vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .floating-nav h6 {
            font-size: .84rem;
            font-weight: 700;
            color: var(--uk-ink);
            border-color: var(--uk-line) !important;
        }

        .nav-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 6px;
        }

        .nav-box {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--uk-line);
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            font-size: .85rem;
            text-decoration: none;
            color: var(--uk-muted);
            background-color: #fff;
            transition: .18s ease;
        }

        .nav-box:hover {
            border-color: var(--uk-brand);
            color: var(--uk-brand);
            background: #f0fdf4;
        }

        .nav-box.answered {
            background: var(--uk-gradient);
            border-color: transparent;
            color: #ffffff;
            box-shadow: 0 4px 10px -2px rgba(5, 150, 105, 0.4);
        }

        .btn-finish {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            border-radius: 14px;
            font-weight: 700;
            letter-spacing: .02em;
            box-shadow: 0 14px 28px -14px rgba(239, 68, 68, .9);
        }

        .btn-finish:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: #fff;
        }

        /* Mobile Bottom Navigasi Soal */
        @media (max-width: 991.98px) {
            body {
                padding-bottom: 95px;
            }

            .floating-nav {
                position: fixed;
                bottom: 0;
                top: auto;
                left: 0;
                right: 0;
                width: 100%;
                background: rgba(255, 255, 255, 0.96);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                padding: .65rem .85rem env(safe-area-inset-bottom, .65rem);
                border-radius: 18px 18px 0 0;
                border: 1px solid var(--uk-line);
                border-bottom: none;
                box-shadow: 0 -8px 24px rgba(0, 0, 0, 0.08);
                z-index: 1050;
            }

            .nav-title {
                display: none;
            }

            .nav-container {
                flex-wrap: nowrap;
                overflow-x: auto;
                justify-content: flex-start;
                padding-bottom: 4px;
                gap: 8px;
            }

            .nav-container::-webkit-scrollbar {
                height: 4px;
            }

            .nav-container::-webkit-scrollbar-thumb {
                background-color: var(--uk-line-strong);
                border-radius: 10px;
            }

            .nav-box {
                flex: 0 0 auto;
                width: 38px;
                height: 38px;
                font-size: .8rem;
            }
        }
    </style>
</head>

<body>

    <div class="container py-4" style="max-width: 1080px;">
        <div class="exam-head mb-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <p class="exam-head__name mb-0"><?= esc($siswa['nama_siswa']) ?></p>
                <div class="exam-head__meta">
                    <i class="bi bi-person-vcard"></i> <?= esc($siswa['kelas']) ?>
                    <span class="mx-1 opacity-50">•</span>
                    <i class="bi bi-journal-text"></i> Ujian Teori UKK TSM
                </div>
            </div>
            <span class="exam-head__badge"><i class="bi bi-shield-lock me-1"></i> Mode Ujian Aktif</span>
        </div>

        <!-- Floating Nav / Mobile Bottom Bar Soal -->
        <div class="floating-nav">
            <h6 class="text-center mb-3 border-bottom pb-2 nav-title">Navigasi Soal</h6>
            <div class="nav-container">
                <?php foreach ($soal as $index => $s): ?>
                    <?php
                    $is_answered = isset($list_jawaban[$s['id_soal']]) ? 'answered' : '';
                    ?>
                    <a href="#soal_<?= $index + 1 ?>" class="nav-box <?= $is_answered ?>" id="nav_<?= $s['id_soal'] ?>">
                        <?= $index + 1 ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php foreach ($soal as $index => $s): ?>
            <div class="card border-0 mb-4 soal-card" id="soal_<?= $index + 1 ?>">
                <div class="card-body p-4">
                    <div class="badge mb-3">Soal No. <?= $index + 1 ?></div>
                    <p class="fw-semibold text-dark mb-3"><?= esc($s['pertanyaan']) ?></p>

                    <div class="d-grid gap-2 mt-2">
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
                            <label class="btn option-btn text-start p-3" for="q<?= $s['id_soal'] . $opt ?>">
                                <span class="fw-bold me-2 text-success"><?= $opt ?>.</span> <?= esc($s['opsi_' . strtolower($opt)]) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="p-3">
            <button onclick="finalisasi()" class="btn btn-finish text-white w-100 py-3 shadow">
                <i class="bi bi-lock-fill me-2"></i> SELESAI &amp; KUNCI JAWABAN
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
            const totalSoal = $('.soal-card').length;
            const terjawab = $('.btn-check:checked').length;

            if (terjawab < totalSoal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops! Belum Selesai',
                    text: `Anda baru menjawab ${terjawab} dari ${totalSoal} soal. Silakan lengkapi semua jawaban sebelum menyelesaikan ujian!`,
                    confirmButtonText: 'Kembali Mengerjakan',
                    confirmButtonColor: '#059669'
                });
                return;
            }

            Swal.fire({
                title: 'Selesai Ujian?',
                text: "Jawaban akan dikunci dan tidak bisa diubah lagi!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Selesai!',
                cancelButtonText: 'Batal',
                reverseButtons: true
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

                    const violationKey = 'pelanggaran_ujian_<?= $siswa['id_siswa'] ?>';
                    localStorage.removeItem(violationKey);

                    window.location.href = "<?= base_url('ujian/selesai/' . $siswa['id_siswa']) ?>";
                }
            });
        }

        const violationKey = 'pelanggaran_ujian_<?= $siswa['id_siswa'] ?>';
        let jumlahPelanggaran = localStorage.getItem(violationKey) ? parseInt(localStorage.getItem(violationKey)) : 0;
        const batasMaksimal = 3;

        document.addEventListener("visibilitychange", function() {
            if (document.hidden) {
                jumlahPelanggaran++;
                localStorage.setItem(violationKey, jumlahPelanggaran);

                if (jumlahPelanggaran >= batasMaksimal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pelanggaran Maksimal!',
                        text: 'Anda terdeteksi keluar dari ujian berkali-kali. Ujian dihentikan paksa!',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: true,
                        confirmButtonText: 'Tutup Ujian'
                    }).then((result) => {
                        localStorage.removeItem(violationKey);
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
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan Pelanggaran!',
                        text: `Sistem mendeteksi Anda meninggalkan halaman ujian. Ini adalah peringatan ke-${jumlahPelanggaran} dari ${batasMaksimal}. Jika melebihi batas, ujian akan otomatis diakhiri!`,
                        confirmButtonText: 'Saya Mengerti',
                        confirmButtonColor: '#ef4444',
                        allowOutsideClick: false
                    });
                }
            }
        });

        function openFullscreen() {
            const elem = document.documentElement;
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
        }

        $(document).ready(function() {
            Swal.fire({
                title: 'Siap Mengerjakan?',
                text: 'Ujian ini menggunakan mode Layar Penuh. Pastikan koneksi internet Anda stabil.',
                icon: 'info',
                confirmButtonText: 'Mulai Ujian',
                confirmButtonColor: '#059669',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    openFullscreen();
                }
            });
        });
    </script>
</body>

</html>
