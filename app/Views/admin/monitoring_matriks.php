<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    .status-badge {
        display: inline-block;
        min-width: 35px;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 6px;
        padding: 4px 6px;
        text-align: center;
    }

    /* Warna Nilai Elemen */
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

    /* Warna Status POS & Siswa */
    .bg-soft-danger {
        background-color: #fff5f5;
        border: 1px solid #fed7d7;
        color: #c53030;
    }

    .bg-soft-success {
        background-color: #f0fff4;
        border: 1px solid #c6f6d5;
        color: #276749;
    }
</style>

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <form action="" method="GET" class="d-flex align-items-end gap-3">
                <div>
                    <label class="form-label small fw-bold text-secondary">TANGGAL UJIAN</label>
                    <input type="date" name="date" class="form-control border-2" value="<?= $current_date ?>" onchange="this.form.submit()">
                </div>
            </form>
            <form action="" method="GET" class="d-flex align-items-end gap-3 flex-wrap">
                <div>
                    <label class="form-label small fw-bold text-secondary">TANGGAL UJIAN</label>
                    <input type="date" name="date" class="form-control border-2" value="<?= $current_date ?>" onchange="this.form.submit()">
                </div>
                <div>
                    <a href="<?= base_url('monitoring/cetak_rekap_harian?date=' . $current_date) ?>" target="_blank" class="btn btn-danger">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak Pengumuman
                    </a>
                </div>
            </form>
            <div class="small text-muted d-flex gap-1 align-items-center">
                <span class="status-badge skor-25">BK: 25</span>
                <span class="status-badge skor-70">CK: 70</span>
                <span class="status-badge skor-85">K: 85</span>
                <span class="status-badge skor-100">SK: 100</span>
            </div>
        </div>
    </div>

    <?php if (!empty($rekap_siswa)): ?>
        <div class="row">
            <?php foreach ($rekap_siswa as $siswa): ?>
                <div class="col-12 mb-4">
                    <div class="card shadow-sm border-0" style="border-radius: 16px; border-left: 6px solid <?= $siswa['butuh_remedi'] ? '#ef4444' : '#059669' ?> !important; overflow: hidden;">

                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <div>
                                <h5 class="mb-0 fw-bold text-dark text-uppercase"><?= esc($siswa['nama_siswa']) ?></h5>
                                <span class="badge bg-light text-dark border mt-1"><?= esc($siswa['kelas']) ?></span>
                            </div>
                            <div>
                                <?php if ($siswa['butuh_remedi']): ?>
                                    <span class="badge bg-danger text-white px-3 py-2 rounded-pill"><i class="bi bi-exclamation-triangle me-1"></i> Ada Remedial</span>
                                <?php else: ?>
                                    <span class="badge bg-success text-white px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i> Aman / Kompeten</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="px-4" style="width: 20%;">Pos Ujian</th>
                                        <th class="px-3" style="width: 45%;">Detail Nilai Elemen</th>
                                        <th class="px-3" style="width: 20%;">Catatan Penguji</th>
                                        <th class="px-4 text-center" style="width: 15%;">Status POS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($siswa['pos'] as $pos): ?>
                                        <tr>
                                            <td class="px-4 fw-bold text-dark" style="font-size: 0.9rem;">
                                                <?= esc($pos['nama_pos']) ?>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="d-flex flex-wrap gap-2">
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
                                                        <div class="border rounded px-2 py-1 bg-white shadow-xs" style="font-size: 0.8rem;">
                                                            <span class="status-badge <?= $class ?> me-1"><?= $label ?></span>
                                                            <span class="text-secondary" title="<?= htmlspecialchars($e['nama_elemen']) ?>">
                                                                <strong><?= esc($e['kode']) ?>:</strong> <?= (strlen($e['nama_elemen']) > 30) ? esc(substr($e['nama_elemen'], 0, 30)) . '...' : esc($e['nama_elemen']) ?>
                                                            </span>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </td>
                                            <td class="px-3">
                                                <?php if (!empty($pos['catatan'])): ?>
                                                    <div class="small text-muted p-2 bg-light rounded border border-start-3 border-start-success" style="font-size: 0.8rem; white-space: pre-wrap;"><?= esc($pos['catatan']) ?></div>
                                                <?php else: ?>
                                                    <span class="text-muted small fst-italic">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 text-center">
                                                <?php if ($pos['status_remedi']): ?>
                                                    <span class="badge bg-danger-subtle text-danger border w-100 py-2 rounded-pill">Remedial</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success-subtle text-success border w-100 py-2 rounded-pill">Kompeten</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center border-0 shadow-sm" style="border-radius: 14px;">
            <i class="bi bi-calendar-x me-2"></i> Tidak ada data siswa yang ujian pada tanggal <strong><?= date('d-m-Y', strtotime($current_date)) ?></strong>.
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>