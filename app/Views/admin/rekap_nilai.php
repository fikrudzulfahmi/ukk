<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-0 text-dark"><?= session()->get('nama_event') ?></h4>
            <p class="text-muted small mb-0">
                <?= $is_teori ? 'Rekapitulasi Nilai Akhir (Teori 30% : Praktek 70%)' : 'Rekapitulasi Nilai Akhir (Murni Praktek 100%)' ?>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('laporan/export_excel') ?>" class="btn btn-success btn-sm shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>
            <a href="<?= base_url('laporan/export_pdf') ?>" target="_blank" class="btn btn-danger btn-sm shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 900px;">
                <thead class="bg-light text-center text-uppercase small text-secondary">
                    <tr>
                        <th width="5%" class="py-3">No</th>
                        <th width="20%" class="text-start py-3">Nama Siswa</th>
                        <th width="10%" class="py-3">Kelas</th>
                        <?php if ($is_teori): ?>
                            <th width="15%" class="py-3">Nilai Teori (30%)</th>
                            <th width="15%" class="py-3">Rata Praktek (70%)</th>
                        <?php else: ?>
                            <th width="20%" class="py-3">Rata Praktek (100%)</th>
                        <?php endif; ?>
                        <th width="10%" class="py-3">Nilai Akhir</th>
                        <th width="15%" class="py-3">Kesimpulan</th>
                        <th width="10%" class="py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php foreach ($rekap as $i => $r):
                        $id_siswa = $r['id_siswa'];
                        $teori    = (float)($r['nilai_teori'] ?? 0);
                        $praktek  = (float)($r['rata_praktek'] ?? 0);

                        // Logika Perhitungan Nilai Akhir
                        $nilai_akhir = $is_teori ? (($teori * 0.3) + ($praktek * 0.7)) : $praktek;
                        $is_kompeten = ($nilai_akhir >= 75);
                    ?>
                        <tr>
                            <td class="text-muted"><?= $i + 1 ?></td>
                            <td class="text-start">
                                <div class="fw-bold text-dark"><?= esc($r['nama_siswa']) ?></div>
                                <small class="text-muted">NISN: <?= esc($r['nisn']) ?></small>
                            </td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25"><?= esc($r['kelas']) ?></span></td>

                            <?php if ($is_teori): ?>
                                <td>
                                    <div class="small text-muted mb-1">Skor: <?= number_format($teori, 0) ?></div>
                                    <div class="fw-bold text-dark"><?= number_format($teori * 0.3, 2) ?></div>
                                </td>
                                <td>
                                    <div class="small text-muted mb-1">Skor: <?= number_format($praktek, 2) ?></div>
                                    <div class="fw-bold text-dark"><?= number_format($praktek * 0.7, 2) ?></div>
                                </td>
                            <?php else: ?>
                                <td>
                                    <div class="small text-muted mb-1">Skor: <?= number_format($praktek, 2) ?></div>
                                    <div class="fw-bold text-dark"><?= number_format($praktek, 2) ?></div>
                                </td>
                            <?php endif; ?>

                            <td>
                                <div class="p-2 <?= $is_kompeten ? 'bg-primary bg-opacity-10 text-primary' : 'bg-danger bg-opacity-10 text-danger' ?> fw-bold rounded shadow-sm d-inline-block w-100">
                                    <?= number_format($nilai_akhir, 2) ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($is_kompeten): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 w-100 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i> KOMPETEN
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 w-100 rounded-pill">
                                        <i class="bi bi-x-circle-fill me-1"></i> BELUM KOMPETEN
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-primary btn-sm shadow-sm rounded-pill w-100" data-bs-toggle="modal" data-bs-target="#modalPos<?= $id_siswa ?>">
                                    <i class="bi bi-eye-fill"></i> Detail
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php foreach ($rekap as $r):
    $id_siswa = $r['id_siswa'];
?>
    <div class="modal fade" id="modalPos<?= $id_siswa ?>" tabindex="-1" aria-labelledby="label<?= $id_siswa ?>" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow" style="border-radius: 12px;">
                <div class="modal-header bg-light border-bottom-0 pb-3">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark" id="label<?= $id_siswa ?>"><?= esc($r['nama_siswa']) ?></h5>
                        <div class="d-flex gap-2 text-muted small">
                            <span><i class="bi bi-person-badge me-1"></i>NISN: <?= esc($r['nisn']) ?></span>
                            <span>|</span>
                            <span><i class="bi bi-building me-1"></i><?= esc($r['kelas']) ?></span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <h6 class="fw-bold mb-3 text-secondary border-bottom pb-2"><i class="bi bi-info-circle-fill me-1"></i> Rincian Nilai & Catatan per POS Ujian</h6>

                    <div class="table-responsive border rounded">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-center small text-uppercase fw-bold text-secondary">
                                <tr>
                                    <th style="width: 25%;" class="py-3">Nama POS</th>
                                    <th style="width: 15%;" class="py-3">Rata Nilai</th>
                                    <th style="width: 15%;" class="py-3">Status POS</th>
                                    <th style="width: 45%;" class="py-3 text-start">Catatan Penguji</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($detail_pos[$id_siswa]) && !empty($detail_pos[$id_siswa])): ?>
                                    <?php foreach ($detail_pos[$id_siswa] as $pos):
                                        $is_remedi_pos = ($pos['min_nilai'] == 25 || $pos['min_nilai'] === null);
                                    ?>
                                        <tr>
                                            <td class="fw-bold text-dark px-3"><?= esc($pos['nama_pos']) ?></td>
                                            <td class="text-center fw-bold text-secondary">
                                                <?= $pos['rata_nilai'] !== null ? number_format((float)$pos['rata_nilai'], 2) : '<span class="text-muted small fst-italic">Belum dinilai</span>' ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($is_remedi_pos): ?>
                                                    <span class="badge bg-danger px-3 py-1 rounded w-100">Remedial</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success px-3 py-1 rounded w-100">Kompeten</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="small px-3 py-2 text-start">
                                                <?php if (!empty($pos['catatan'])): ?>
                                                    <div class="p-2 bg-light rounded text-dark" style="white-space: pre-wrap; font-size: 0.85rem; line-height: 1.4; border-left: 3px solid #6c757d;"><?= esc($pos['catatan']) ?></div>
                                                <?php else: ?>
                                                    <span class="text-muted fst-italic">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4 fst-italic">Belum ada data POS yang diujikan untuk siswa ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0 py-3">
                    <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?= $this->endSection() ?>