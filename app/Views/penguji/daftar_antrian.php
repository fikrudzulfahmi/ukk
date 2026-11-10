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

    .student-no {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #0f172a;
        font-weight: 700;
        font-size: .85rem;
        flex-shrink: 0;
    }

    /* Chip POS */
    .pos-scroll {
        display: flex;
        gap: .5rem;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding-bottom: 2px;
    }

    .pos-scroll::-webkit-scrollbar {
        display: none;
    }

    .pos-chip {
        white-space: nowrap;
        border-radius: 999px;
        padding: .45rem .95rem;
        font-size: .8rem;
        font-weight: 600;
        border: 1px solid #d1fae5;
        background: #fff;
        color: #047857;
        flex-shrink: 0;
    }

    .pos-chip.is-active {
        background: #059669;
        border-color: #059669;
        color: #fff;
        box-shadow: 0 6px 14px -8px rgba(5, 150, 105, .9);
    }

    /* Kartu sesi (mobile) */
    .sesi-card {
        background: #fff;
        border: 1px solid var(--uk-line, #e2e8f0);
        border-radius: 16px;
        padding: .85rem;
        margin-bottom: .75rem;
        box-shadow: 0 4px 14px -10px rgba(15, 23, 42, .3);
    }

    .sesi-card__head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .5rem;
        margin-bottom: .65rem;
    }

    .sesi-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
        padding: .28rem .7rem;
    }

    .sesi-status {
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .05em;
        text-transform: uppercase;
        border-radius: 999px;
        padding: .25rem .65rem;
    }

    .sesi-status.is-done { background: #d1fae5; color: #047857; }
    .sesi-status.is-progress { background: #fef3c7; color: #b45309; }
    .sesi-status.is-waiting { background: #f1f5f9; color: #64748b; }

    .sesi-student {
        display: flex;
        align-items: center;
        gap: .65rem;
        padding: .55rem 0;
        border-top: 1px dashed #eef2f7;
    }

    .sesi-student:first-child {
        border-top: none;
    }

    .sesi-student__name {
        font-weight: 700;
        color: #0f172a;
        font-size: .92rem;
        line-height: 1.25;
        word-break: break-word;
    }

    .sesi-student__nisn {
        font-size: .72rem;
        color: #94a3b8;
    }

    .sesi-student__score {
        text-align: right;
        flex-shrink: 0;
        min-width: 72px;
    }

    .sesi-card__btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        width: 100%;
        min-height: 46px;
        margin-top: .75rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: .9rem;
    }

    @media (max-width: 991.98px) {
        .page-title {
            font-size: 1.15rem;
        }

        .progress-wrap {
            max-width: 100% !important;
        }
    }
</style>

<div class="container-fluid py-3 py-md-4">

    <div class="row align-items-center mb-3 mb-md-4 g-2">
        <div class="col-12 col-md-6">
            <h3 class="fw-bold text-dark mb-1 page-title">Antrian Penilaian</h3>
            <p class="text-muted mb-2 small">
                <i class="bi bi-geo-alt-fill text-success me-1"></i> <?= esc($pos['nama_pos'] ?? '-') ?>
            </p>

            <div class="d-flex align-items-center progress-wrap" style="max-width: 400px;">
                <div class="progress flex-grow-1 shadow-sm" style="height: 10px; border-radius: 10px; background-color: #e9ecef;">
                    <div class="progress-bar bg-success"
                        role="progressbar"
                        style="width: <?= $persentase_global ?>%; transition: width 1s ease;"
                        aria-valuenow="<?= $persentase_global ?>"
                        aria-valuemin="0"
                        aria-valuemax="100">
                    </div>
                </div>
                <span class="ms-3 fw-bold text-success"><?= $persentase_global ?>%</span>
            </div>
            <div class="d-flex justify-content-between mt-1 progress-wrap" style="max-width: 400px;">
                <small class="text-muted">Progres penilaian hari ini</small>
                <small class="text-muted fw-bold"><?= $total_nilai_masuk ?> / <?= $total_nilai_harus_diisi ?></small>
            </div>
        </div>
    </div>

    <div class="card filter-card bg-white shadow-sm mb-3 mb-md-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-md-end">
                <div class="col-12 col-md-4">
                    <form method="get" action="<?= base_url('penilaian/input') ?>" id="form-tanggal">
                        <input type="hidden" name="id_pos" value="<?= $active_pos_id ?>">
                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">Tanggal Ujian</label>
                        <input type="date" name="date" class="form-control form-control-sm" value="<?= $tanggal_filter ?>" onchange="document.getElementById('form-tanggal').submit();">
                    </form>
                </div>

                <div class="col-12 col-md-8">
                    <label class="d-block text-muted small fw-bold text-uppercase mb-1">Pos Pengujian</label>
                    <?php if (!empty($list_pos)): ?>
                        <div class="pos-scroll">
                            <?php foreach ($list_pos as $lp): ?>
                                <a href="<?= base_url('penilaian/input?date=' . $tanggal_filter . '&id_pos=' . $lp['id_pos']) ?>"
                                    class="pos-chip <?= ($lp['id_pos'] == $active_pos_id) ? 'is-active' : '' ?>">
                                    <i class="bi bi-pc-display me-1"></i><?= esc($lp['nama_pos']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <span class="text-muted fst-italic small">Tidak ada pos aktif</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Siapkan data per sesi sekali, dipakai untuk tabel desktop & kartu mobile
    $sesiList = [];
    $groups   = array_chunk($siswa, max(1, (int) $kapasitas_sesi));

    foreach ($groups as $index => $pair) {
        $countSelesai = 0;
        $countAdaIsi  = 0;
        $students     = [];
        $totalSiswaDalamPair = count($pair);

        foreach ($pair as $s) {
            $dataNilai  = $nilaiMap[$s['id_siswa']] ?? ['terisi' => 0, 'rata_rata' => 0];
            $nilaiMasuk = (int) ($dataNilai['terisi'] ?? 0);
            $rataRata   = $dataNilai['rata_rata'] ?? 0;
            $persen     = ($total_elemen > 0) ? (int) round(($nilaiMasuk / $total_elemen) * 100) : 0;
            $isSelesai  = ($total_elemen > 0 && $nilaiMasuk >= $total_elemen);
            $isKompeten = ($rataRata >= 75);

            if ($isSelesai) {
                $countSelesai++;
            }
            if ($nilaiMasuk > 0) {
                $countAdaIsi++;
            }

            $students[] = [
                'no'       => $mapping_no[$s['id_siswa']] ?? '-',
                'nama'     => $s['nama_siswa'],
                'nisn'     => $s['nisn'],
                'terisi'   => $nilaiMasuk,
                'skor'     => $rataRata,
                'persen'   => $persen,
                'selesai'  => $isSelesai,
                'kompeten' => $isKompeten,
            ];
        }

        $sudahIsiSemua = ($total_elemen > 0 && $countSelesai === $totalSiswaDalamPair);
        $adaYangIsi    = ($countAdaIsi > 0);

        if ($sudahIsiSemua) {
            $statusKey   = 'done';
            $statusLabel = 'Selesai';
            $dotColor    = 'text-success';
            $btnStyle    = 'btn-success';
            $btnText     = 'Edit Nilai (Selesai)';
            $btnIcon     = 'bi-check2-all';
        } elseif ($adaYangIsi) {
            $statusKey   = 'progress';
            $statusLabel = 'Progres';
            $dotColor    = 'text-warning';
            $btnStyle    = 'btn-warning text-dark';
            $btnText     = 'Lanjutkan';
            $btnIcon     = 'bi-pencil-fill';
        } else {
            $statusKey   = 'waiting';
            $statusLabel = 'Menunggu';
            $dotColor    = 'text-secondary';
            $btnStyle    = 'btn-success text-white';
            $btnText     = 'Mulai Nilai';
            $btnIcon     = 'bi-pencil-fill';
        }

        $sesiList[] = [
            'sesi'         => $index + 1,
            'students'     => $students,
            'status_key'   => $statusKey,
            'status_label' => $statusLabel,
            'dot'          => $dotColor,
            'btn_style'    => $btnStyle,
            'btn_text'     => $btnText,
            'btn_icon'     => $btnIcon,
            'done'         => $countSelesai,
            'total'        => $totalSiswaDalamPair,
        ];
    }
    ?>

    <?php if (empty($siswa)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-calendar2-x text-light" style="font-size: 4rem;"></i>
                <h5 class="mt-3 fw-bold text-muted">Tidak Ada Jadwal</h5>
                <p class="text-secondary small mb-0">Silakan pilih tanggal lain atau pastikan jadwal sudah di-generate.</p>
            </div>
        </div>
    <?php else: ?>

        <!-- Mobile: kartu per sesi -->
        <div class="d-lg-none">
            <?php foreach ($sesiList as $s): ?>
                <div class="sesi-card">
                    <div class="sesi-card__head">
                        <span class="sesi-badge"><i class="bi bi-people-fill"></i> Sesi <?= $s['sesi'] ?></span>
                        <span class="sesi-status is-<?= $s['status_key'] ?>"><?= $s['status_label'] ?></span>
                    </div>

                    <?php foreach ($s['students'] as $st): ?>
                        <div class="sesi-student">
                            <span class="student-no"><?= $st['no'] ?></span>
                            <div class="flex-grow-1 min-w-0">
                                <div class="sesi-student__name"><?= esc($st['nama']) ?></div>
                                <div class="sesi-student__nisn">NISN: <?= esc($st['nisn']) ?></div>
                            </div>
                            <div class="sesi-student__score">
                                <?php if ($st['selesai']): ?>
                                    <span class="badge <?= $st['kompeten'] ? 'text-bg-success' : 'text-bg-danger' ?> rounded-pill" style="font-size:.62rem;">
                                        <?= $st['kompeten'] ? 'KOMPETEN' : 'TIDAK KOMPETEN' ?>
                                    </span>
                                <?php elseif ($st['terisi'] > 0): ?>
                                    <span class="badge text-bg-warning rounded-pill" style="font-size:.62rem;"><?= $st['persen'] ?>%</span>
                                <?php else: ?>
                                    <span class="badge text-bg-light border text-muted rounded-pill" style="font-size:.62rem;">BELUM</span>
                                <?php endif; ?>
                                <div class="small fw-bold mt-1 <?= $st['terisi'] > 0 ? ($st['kompeten'] ? 'text-success' : 'text-danger') : 'text-muted' ?>">
                                    <?= $st['terisi'] > 0 ? 'Skor ' . $st['skor'] : '—' ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <a href="<?= base_url('penilaian/input_dual/' . $s['sesi'] . '/' . $pos['id_pos'] . '?date=' . $tanggal_filter) ?>"
                        class="btn <?= $s['btn_style'] ?> sesi-card__btn">
                        <i class="bi <?= $s['btn_icon'] ?>"></i> <?= $s['btn_text'] ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Desktop: tabel -->
        <div class="card queue-card shadow-sm d-none d-lg-block">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="text-secondary">
                            <th class="ps-4 text-center" width="80">SESI</th>
                            <th>KANDIDAT SISWA (<?= $kapasitas_sesi ?> PESERTA/SESI)</th>
                            <th class="text-center" width="220">STATUS &amp; AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sesiList as $s): ?>
                            <tr class="bg-white">
                                <td class="ps-md-4 text-md-center">
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold shadow-sm mx-md-auto" style="width: 40px; height: 40px;">
                                        <?= $s['sesi'] ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="row g-3">
                                        <?php foreach ($s['students'] as $st): ?>
                                            <div class="col-12 col-lg-6">
                                                <div class="student-item d-flex align-items-center justify-content-between">
                                                    <span class="student-no"><?= $st['no'] ?></span>

                                                    <div class="flex-grow-1 ms-3">
                                                        <div class="fw-bold text-dark" style="font-size:0.95rem;">
                                                            <?= esc($st['nama']) ?>
                                                        </div>
                                                        <div class="text-muted small">
                                                            NISN: <?= esc($st['nisn']) ?>
                                                        </div>
                                                    </div>

                                                    <div class="text-end ms-2" style="min-width: 100px;">
                                                        <div class="mb-1">
                                                            <?php if ($st['selesai']): ?>
                                                                <span class="badge <?= $st['kompeten'] ? 'bg-success' : 'bg-danger' ?> rounded-pill" style="font-size: 0.65rem;">
                                                                    <i class="bi <?= $st['kompeten'] ? 'bi-check-circle' : 'bi-x-circle' ?> me-1"></i> <?= $st['kompeten'] ? 'KOMPETEN' : 'TIDAK KOMPETEN' ?>
                                                                </span>
                                                            <?php elseif ($st['terisi'] > 0): ?>
                                                                <span class="badge bg-warning text-dark rounded-pill" style="font-size: 0.65rem;">
                                                                    <i class="bi bi-hourglass-split me-1"></i> <?= $st['persen'] ?>%
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-light text-muted border rounded-pill" style="font-size: 0.65rem;">BELUM</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="small fw-bold <?= $st['terisi'] > 0 ? ($st['kompeten'] ? 'text-success' : 'text-danger') : 'text-muted' ?>" style="font-size: 0.8rem;">
                                                            Skor: <?= $st['skor'] ?>
                                                        </div>
                                                        <div class="progress mt-1" style="height: 5px; background: #eee;">
                                                            <div class="progress-bar <?= $st['selesai'] ? ($st['kompeten'] ? 'bg-success' : 'bg-danger') : 'bg-success' ?>"
                                                                role="progressbar" style="width: <?= $st['persen'] ?>%;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </td>

                                <td class="text-md-center px-4">
                                    <div class="mb-2">
                                        <i class="bi bi-circle-fill <?= $s['dot'] ?> me-1" style="font-size: 8px;"></i>
                                        <small class="fw-bold text-uppercase text-muted" style="font-size: 10px; letter-spacing: 0.5px;"><?= $s['status_label'] ?></small>
                                    </div>
                                    <a href="<?= base_url('penilaian/input_dual/' . $s['sesi'] . '/' . $pos['id_pos'] . '?date=' . $tanggal_filter) ?>"
                                        class="btn <?= $s['btn_style'] ?> btn-sm rounded-pill px-4 py-2 w-100 shadow-sm fw-bold transition-all">
                                        <i class="bi <?= $s['btn_icon'] ?> me-1"></i>
                                        <?= $s['btn_text'] ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
