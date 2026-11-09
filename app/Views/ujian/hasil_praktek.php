<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian Praktik - UKK</title>
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
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 15px;
            font-size: 2rem;
        }

        .icon-success {
            background: #d1e7dd;
            color: #198754;
        }

        .icon-danger {
            background: #f8d7da;
            color: #dc3545;
        }

        /* Tambahan style untuk kotak elemen nilai */
        .status-badge {
            display: inline-block;
            min-width: 35px;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 6px;
            padding: 4px 6px;
            text-align: center;
        }

        .skor-25 {
            background: #fee2e2;
            color: #b91c1c;
        }

        /* BK */
        .skor-70 {
            background: #fef9c3;
            color: #854d0e;
        }

        /* CK */
        .skor-85 {
            background: #dcfce7;
            color: #15803d;
        }

        /* K */
        .skor-100 {
            background: #dbeafe;
            color: #1e40af;
        }

        /* SK */
        .skor-null {
            background: #f1f5f9;
            color: #94a3b8;
        }

        /* Belum Dinilai */
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/ukk.css') ?>">

    <style>
        body {
            background:
                radial-gradient(760px 420px at 10% -10%, rgba(99, 102, 241, .14), transparent 62%),
                radial-gradient(700px 420px at 100% 108%, rgba(168, 85, 247, .14), transparent 60%),
                #f6f7fb;
        }

        .finish-card {
            border-radius: 24px;
            border: 1px solid var(--uk-line);
            box-shadow: 0 28px 70px -46px rgba(16, 24, 40, .55);
        }

        .icon-success { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #047857; }
        .icon-danger { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #b91c1c; }

        .status-badge {
            display: inline-block;
            min-width: 38px;
            font-size: .72rem;
            font-weight: 700;
            border-radius: 8px;
            padding: 4px 7px;
            text-align: center;
            letter-spacing: .02em;
        }
    </style>
</head>

<body>

    <div class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
        <div class="col-md-8 col-lg-6 w-100" style="max-width: 600px;">
            <div class="card finish-card p-3 p-md-4">
                <div class="card-body">

                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark mb-1">Hasil Ujian Praktik</h4>
                        <div class="p-2 mb-2 bg-light rounded-3 border">
                            <h6 class="text-primary fw-bold mb-0">
                                <i class="bi bi-person-badge me-2"></i><?= isset($nama_siswa) ? strtoupper(esc($nama_siswa)) : 'PESERTA UJIAN' ?>
                            </h6>
                        </div>
                        <p class="text-muted small">Status kelulusan UKK Praktik Anda</p>
                    </div>

                    <?php if (empty($rekap_pos)): ?>
                        <div class="alert alert-info text-center border-0 shadow-sm rounded-4">
                            Belum ada jadwal/data ujian praktik untuk Anda.
                        </div>
                    <?php else: ?>

                        <?php if ($butuh_remedi): ?>
                            <div class="text-center mb-4">
                                <div class="icon-box icon-danger">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                </div>
                                <h5 class="fw-bold text-danger mb-1">BUTUH REMEDIAL</h5>
                                <p class="small text-secondary mb-0">Ada POS yang belum kompeten. Silakan cek detail di bawah dan temui Asesor.</p>
                            </div>
                        <?php else: ?>
                            <div class="text-center mb-4">
                                <div class="icon-box icon-success">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <h5 class="fw-bold text-success mb-1">KOMPETEN (LULUS)</h5>
                                <p class="small text-secondary mb-0">Selamat! Anda telah menyelesaikan seluruh ujian praktik dengan baik.</p>
                            </div>
                        <?php endif; ?>

                        <div class="row g-3">
                            <?php foreach ($rekap_pos as $pos): ?>
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm" style="border-radius: 12px; border-left: 5px solid <?= $pos['status_remedi'] ? '#dc3545' : '#198754' ?> !important; background-color: #f8f9fa;">

                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <h6 class="fw-bold mb-0 text-dark"><?= $pos['nama_pos'] ?></h6>
                                                    <small class="text-muted" style="font-size: 0.75rem;"><?= date('d M Y', strtotime($pos['tanggal'])) ?></small>
                                                </div>
                                                <div>
                                                    <?php if ($pos['status_remedi']): ?>
                                                        <span class="badge bg-danger rounded-pill px-3">Remedial</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success rounded-pill px-3">Kompeten</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-column gap-2">
                                                <?php foreach ($pos['elemen'] as $e):
                                                    $val = $e['nilai'];
                                                    $label = '-';
                                                    $class = 'skor-null';

                                                    if ($val == 25) {
                                                        $label = 'BK';
                                                        $class = 'skor-25';
                                                    } elseif ($val == 70) {
                                                        $label = 'CK';
                                                        $class = 'skor-70';
                                                    } elseif ($val == 85) {
                                                        $label = 'K';
                                                        $class = 'skor-85';
                                                    } elseif ($val == 100) {
                                                        $label = 'SK';
                                                        $class = 'skor-100';
                                                    }
                                                ?>
                                                    <div class="d-flex align-items-start bg-white p-2 rounded border border-light shadow-sm">
                                                        <div class="status-badge <?= $class ?> me-2 flex-shrink-0 mt-1"><?= $label ?></div>
                                                        <div style="font-size: 0.85rem; line-height: 1.3;">
                                                            <strong class="text-dark"><?= $e['kode'] ?>:</strong>
                                                            <span class="text-secondary"><?= $e['nama_elemen'] ?></span>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    <?php endif; ?>

                    <hr class="my-4 opacity-25">

                    <div class="text-center">
                        <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                </div>
            </div>

            <p class="text-center mt-4 text-muted small">
                &copy; <?= date('Y') ?> UKK System - Digital Examination
            </p>
        </div>
    </div>

</body>

</html>