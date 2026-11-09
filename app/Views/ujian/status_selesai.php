<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Selesai - UKK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f0f2f5;
        }

        .finish-card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background: #d1e7dd;
            color: #198754;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 20px;
            font-size: 2.5rem;
        }

        /* Tambahan style untuk kotak nilai */
        .score-box {
            background-color: #e0f2fe;
            /* Warna biru muda yang lembut */
            border: 1px solid #bae6fd;
            border-radius: 15px;
        }
    </style>
</head>

<body>

    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="col-md-5 col-lg-4 w-100" style="max-width: 400px;">
            <div class="card finish-card p-4 text-center">
                <div class="card-body">
                    <div class="icon-box">
                        <i class="bi bi-check-all"></i>
                    </div>

                    <h3 class="fw-bold text-dark mb-2">Terima Kasih!</h3>
                    <h5 class="text-primary fw-bold mb-3">
                        <?= isset($nama_siswa) ? strtoupper(esc($nama_siswa)) : 'Peserta Ujian' ?>
                    </h5>
                    <p class="text-muted mb-4">
                        Ujian Teori Anda telah berhasil diselesaikan dan jawaban telah <strong>dikunci</strong>.
                    </p>

                    <div class="score-box p-4 mb-4">
                        <span class="d-block text-primary fw-semibold mb-1">Nilai Akhir Anda</span>
                        <h1 class="display-2 fw-bold text-primary mb-0">
                            <?= isset($nilai) ? round($nilai) : '0' ?>
                        </h1>
                    </div>
                    <hr class="my-4 opacity-25">
                    <div class="text-center mt-4">
                        <a href="<?= base_url('ujian/hasil_praktek/' . $id_siswa . '/' . $token) ?>" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                            <i class="bi bi-clipboard-check me-2"></i> Cek Hasil Ujian Praktik
                        </a>
                    </div>
                    <hr class="my-4 opacity-25">

                    <p class="small text-secondary mb-0">
                        Silakan letakkan perangkat Anda atau kembali ke pengawas ruangan.
                    </p>
                </div>
            </div>

            <p class="text-center mt-4 text-muted small">
                &copy; <?= date('Y') ?> UKK System - Digital Examination
            </p>
        </div>
    </div>

</body>

</html>