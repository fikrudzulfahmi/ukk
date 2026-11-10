<?= $this->extend('layout/penguji_layout') ?>

<?= $this->section('content') ?>
<style>
    body {
        background: var(--uk-canvas, #f5f6fa);
    }

    .queue-card {
        border-radius: 18px;
        border: 1px solid var(--uk-line, #e8ecf3);
        overflow: hidden;
    }

    .filter-card {
        border-radius: 14px;
        border: 1px solid var(--uk-line, #e8ecf3);
    }

    .table thead th {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.06em;
        font-weight: 700;
        padding: 1rem;
        background-color: #f8f9fd;
        border-bottom: 1px solid var(--uk-line, #e8ecf3);
    }

    .initial-circle {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, var(--uk-brand-soft, #eeefff), #e9ecff);
        color: var(--uk-brand, #4f46e5);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: .8rem;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(16, 24, 40, .1);
    }

    .student-item {
        padding: 12px;
        border-radius: 12px;
        background: #fff;
        border: 1px solid #f0f0f0;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .student-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    /* Responsif untuk Mobile */
    @media (max-width: 768px) {

        /* Sembunyikan Header Tabel agar lebih ringkas */
        .table thead {
            display: none;
        }

        /* Buat setiap baris menjadi satu card utuh */
        .table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            background: #fff !important;
            padding: 0.5rem;
        }

        .table td {
            display: block;
            width: 100% !important;
            text-align: left !important;
            padding: 0.75rem !important;
            border: none !important;
        }

        /* Border pemisah antar bagian di dalam mode mobile */
        .table td:not(:last-child) {
            border-bottom: 1px dashed #e9ecef !important;
        }

        /* Buat tombol aksi menjadi lebar penuh (Full Width) */
        .btn-sm.rounded-pill {
            width: 100% !important;
            padding: 12px !important;
        }

        .student-item {
            margin-bottom: 10px;
        }
    }
</style>

<div class="container-fluid py-4">

    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-6 mb-3 mb-md-0">
            <h3 class="fw-bold text-dark mb-0">Antrian Penilaian</h3>
            <p class="text-muted mb-3">
                <i class="bi bi-geo-alt-fill text-primary me-1"></i> <?= $pos['nama_pos'] ?>
            </p>

            <div class="d-flex align-items-center" style="max-width: 400px;">
                <div class="progress flex-grow-1 shadow-sm" style="height: 12px; border-radius: 10px; background-color: #e9ecef;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                        role="progressbar"
                        style="width: <?= $persentase_global ?>%; transition: width 1s ease;"
                        aria-valuenow="<?= $persentase_global ?>"
                        aria-valuemin="0"
                        aria-valuemax="100">
                    </div>
                </div>
                <span class="ms-3 fw-bold text-success"><?= $persentase_global ?>%</span>
            </div>
            <div class="d-flex justify-content-between mt-1" style="max-width: 400px;">
                <small class="text-muted">Total Progres Penilaian Hari Ini</small>
                <small class="text-muted fw-bold"><?= $total_nilai_masuk ?> / <?= $total_nilai_harus_diisi ?></small>
            </div>
        </div>
    </div>

    <div class="card filter-card bg-white shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-12 col-md-4 mb-3 mb-md-0">
                    <form method="get" action="<?= base_url('penilaian/input') ?>" id="form-tanggal">
                        <div class="form-group mb-0">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Pilih Tanggal Ujian</label>
                            <input type="date" name="date" class="form-control form-control-sm" value="<?= $tanggal_filter ?>" onchange="document.getElementById('form-tanggal').submit();">
                        </div>
                    </form>
                </div>

                <div class="col-12 col-md-8 text-md-end">
                    <label class="d-block text-start text-md-end text-muted small fw-bold text-uppercase mb-1">Pilih Pos Pengujian Anda</label>
                    <?php if (!empty($list_pos)): ?>
                        <div class="d-flex flex-wrap gap-2 justify-content-start justify-content-md-end">
                            <?php foreach ($list_pos as $lp): ?>
                                <?php
                                // Cek apakah pos ini yang sedang aktif dibuka
                                $isActive = ($lp['id_pos'] == $active_pos_id) ? 'btn-success shadow-sm' : 'btn-outline-success';
                                ?>
                                <a href="<?= base_url('penilaian/input?date=' . $tanggal_filter . '&id_pos=' . $lp['id_pos']) ?>" class="btn btn-sm <?= $isActive ?>">
                                    <i class="fas fa-desktop me-1"></i> <?= $lp['nama_pos'] ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <span class="text-muted fst-italic">Tidak ada pos aktif</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card queue-card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="text-secondary">
                        <th class="ps-4 text-center" width="80">SESI</th>
                        <th>KANDIDAT SISWA (<?= $kapasitas_sesi ?> PESERTA/SESI)</th>
                        <th class="text-center" width="220">STATUS & AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $groups = array_chunk($siswa, $kapasitas_sesi);

                    // 1. CEK JIKA DATA KOSONG
                    if (empty($siswa)): ?>
                        <tr>
                            <td colspan="3" class="text-center py-5 border-0">
                                <i class="bi bi-calendar2-x text-light" style="font-size: 4rem;"></i>
                                <h5 class="mt-3 fw-bold text-muted">Tidak Ada Jadwal Hari Ini</h5>
                                <p class="text-secondary small">Silakan pilih tanggal lain atau pastikan jadwal sudah di-generate.</p>
                            </td>
                        </tr>
                        <?php
                    // 2. JIKA ADA DATA, JALANKAN LOOP
                    else:
                        foreach ($groups as $index => $pair):
                            $sesiKe = $index + 1;

                            // Logika Status untuk Tombol (Grup Pasangan)
                            $countSelesai = 0;
                            $countAdaIsi  = 0;
                            $totalSiswaDalamPair = count($pair);

                            foreach ($pair as $s) {
                                $infoNilai = $nilaiMap[$s['id_siswa']] ?? ['terisi' => 0];
                                $n = $infoNilai['terisi'];

                                // Syarat disebut selesai: jumlah terisi sama dengan total elemen
                                if ($total_elemen > 0 && $n >= $total_elemen) {
                                    $countSelesai++;
                                }
                                if ($n > 0) {
                                    $countAdaIsi++;
                                }
                            }

                            // Variabel Penentu Status Tombol
                            $sudahIsiSemua = ($total_elemen > 0 && $countSelesai === $totalSiswaDalamPair);
                            $adaYangIsi    = ($countAdaIsi > 0);

                            // Definisi UI Tombol
                            if ($sudahIsiSemua) {
                                $rowClass    = 'bg-light';
                                $statusLabel = 'Selesai';
                                $dotColor    = 'text-success';
                                $btnStyle    = 'btn-success';
                                $btnText     = 'Edit Nilai (Selesai)';
                            } elseif ($adaYangIsi) {
                                $rowClass    = 'bg-white';
                                $statusLabel = 'Progres';
                                $dotColor    = 'text-warning';
                                $btnStyle    = 'btn-warning text-dark';
                                $btnText     = 'Lanjutkan';
                            } else {
                                $rowClass    = 'bg-white';
                                $statusLabel = 'Menunggu';
                                $dotColor    = 'text-secondary';
                                $btnStyle    = 'btn-success text-white';
                                $btnText     = 'Mulai Nilai';
                            }
                        ?>
                            <tr class="<?= $rowClass ?>">
                                <td class="ps-md-4 text-md-center">
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold shadow-sm mx-md-auto" style="width: 40px; height: 40px;">
                                        <?= $sesiKe ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="row g-3">
                                        <?php foreach ($pair as $s) : ?>
                                            <div class="col-12 col-lg-6">
                                                <div class="student-item d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center justify-content-center fw-bold text-dark rounded-circle border"
                                                        style="width:38px; height:38px; background:#f1f5f9; border-color:#cbd5e1 !important; font-size:0.88rem; flex-shrink: 0;">
                                                        <?= $mapping_no[$s['id_siswa']] ?? '-' ?>
                                                    </div>

                                                    <div class="flex-grow-1 ms-3">
                                                        <div class="fw-bold text-dark text-truncate" style="font-size:0.95rem; max-width: 180px;">
                                                            <?= esc($s['nama_siswa']) ?>
                                                        </div>
                                                        <div class="text-muted small">
                                                            NISN: <?= esc($s['nisn']) ?>
                                                        </div>
                                                    </div>

                                                    <?php
                                                    $sID = $s['id_siswa'];
                                                    $dataNilai = $nilaiMap[$sID] ?? ['terisi' => 0, 'rata_rata' => 0];
                                                    $nilaiMasuk = $dataNilai['terisi'];
                                                    $rataRata   = $dataNilai['rata_rata'];
                                                    $persen     = ($total_elemen > 0) ? round(($nilaiMasuk / $total_elemen) * 100) : 0;
                                                    $isSelesai  = ($total_elemen > 0 && $nilaiMasuk >= $total_elemen);
                                                    $isKompeten = ($rataRata >= 75);
                                                    ?>

                                                    <div class="text-end ms-2" style="min-width: 100px;">
                                                        <div class="mb-1">
                                                            <?php if ($isSelesai): ?>
                                                                <span class="badge <?= $isKompeten ? 'bg-success' : 'bg-danger' ?> rounded-pill" style="font-size: 0.65rem;">
                                                                    <i class="bi <?= $isKompeten ? 'bi-check-circle' : 'bi-x-circle' ?> me-1"></i> <?= $isKompeten ? 'KOMPETEN' : 'TIDAK KOMPETEN' ?>
                                                                </span>
                                                            <?php elseif ($nilaiMasuk > 0): ?>
                                                                <span class="badge bg-warning text-dark rounded-pill" style="font-size: 0.65rem;">
                                                                    <i class="bi bi-hourglass-split me-1"></i> <?= $persen ?>%
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-light text-muted border rounded-pill" style="font-size: 0.65rem;">BELUM</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="small fw-bold <?= $nilaiMasuk > 0 ? ($isKompeten ? 'text-success' : 'text-danger') : 'text-muted' ?>" style="font-size: 0.8rem;">
                                                            Skor: <?= $rataRata ?>
                                                        </div>
                                                        <div class="progress mt-1" style="height: 5px; background: #eee;">
                                                            <div class="progress-bar <?= $isSelesai ? ($isKompeten ? 'bg-success' : 'bg-danger') : 'bg-success' ?>"
                                                                role="progressbar" style="width: <?= $persen ?>%;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </td>

                                <td class="text-md-center px-4">
                                    <div class="mb-2 d-none d-md-block">
                                        <i class="bi bi-circle-fill <?= $dotColor ?> me-1" style="font-size: 8px;"></i>
                                        <small class="fw-bold text-uppercase text-muted" style="font-size: 10px; letter-spacing: 0.5px;"><?= $statusLabel ?></small>
                                    </div>
                                    <a href="<?= base_url('penilaian/input_dual/' . $sesiKe . '/' . $pos['id_pos'] . '?date=' . $tanggal_filter) ?>"
                                        class="btn <?= $btnStyle ?> btn-sm rounded-pill px-4 py-2 w-100 shadow-sm fw-bold transition-all">
                                        <i class="bi <?= $sudahIsiSemua ? 'bi-check2-all' : 'bi-pencil-fill' ?> me-1"></i>
                                        <?= $btnText ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>