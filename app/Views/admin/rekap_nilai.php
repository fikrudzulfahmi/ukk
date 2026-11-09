<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-2">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark"><?= esc(session()->get('nama_event') ?? 'Rekapitulasi Nilai UKK') ?></h4>
            <p class="text-muted small mb-0">
                <i class="bi bi-info-circle text-success me-1"></i>
                <?= $is_teori ? 'Rekapitulasi Nilai Akhir (Teori 30% : Praktik 70%)' : 'Rekapitulasi Nilai Akhir (Murni Praktik 100%)' ?>
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= base_url('laporan/export_excel') ?>" class="btn btn-outline-success btn-sm d-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>
            <a href="<?= base_url('laporan/export_pdf') ?>" target="_blank" class="btn btn-danger btn-sm d-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 860px;">
                <thead>
                    <tr>
                        <th width="4%" class="text-center">No</th>
                        <th width="24%">Nama Siswa</th>
                        <th width="12%" class="text-center">Kelas</th>
                        <?php if ($is_teori): ?>
                            <th width="14%" class="text-center">Nilai Teori (30%)</th>
                            <th width="14%" class="text-center">Rata Praktik (70%)</th>
                        <?php else: ?>
                            <th width="20%" class="text-center">Rata Praktik (100%)</th>
                        <?php endif; ?>
                        <th width="12%" class="text-center">Nilai Akhir</th>
                        <th width="14%" class="text-center">Kesimpulan</th>
                        <th width="8%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php if (empty($rekap)): ?>
                        <tr>
                            <td colspan="<?= $is_teori ? '8' : '7' ?>" class="py-5 text-muted fst-italic">
                                <i class="bi bi-folder-x fs-2 d-block mb-2 text-secondary"></i>
                                Belum ada data rekapitulasi penilaian pada event ini.
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($rekap as $i => $r):
                        $id_siswa = $r['id_siswa'];
                        $teori    = (float)($r['nilai_teori'] ?? 0);
                        $praktek  = (float)($r['rata_praktek'] ?? 0);

                        // Logika Perhitungan Nilai Akhir
                        $nilai_akhir = $is_teori ? (($teori * 0.3) + ($praktek * 0.7)) : $praktek;
                        $is_kompeten = ($nilai_akhir >= 75);
                    ?>
                        <tr>
                            <td class="text-muted fw-semibold"><?= $i + 1 ?></td>
                            <td class="text-start">
                                <div class="fw-bold text-dark"><?= esc($r['nama_siswa']) ?></div>
                                <div class="small text-muted">NISN: <?= esc($r['nisn']) ?></div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><?= esc($r['kelas']) ?></span>
                            </td>

                            <?php if ($is_teori): ?>
                                <td>
                                    <div class="small text-muted">Skor: <?= number_format($teori, 0) ?></div>
                                    <div class="fw-bold text-dark"><?= number_format($teori * 0.3, 2) ?></div>
                                </td>
                                <td>
                                    <div class="small text-muted">Skor: <?= number_format($praktek, 2) ?></div>
                                    <div class="fw-bold text-dark"><?= number_format($praktek * 0.7, 2) ?></div>
                                </td>
                            <?php else: ?>
                                <td>
                                    <div class="small text-muted">Skor: <?= number_format($praktek, 2) ?></div>
                                    <div class="fw-bold text-dark"><?= number_format($praktek, 2) ?></div>
                                </td>
                            <?php endif; ?>

                            <td>
                                <span class="badge <?= $is_kompeten ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> fs-6 px-3 py-2 border">
                                    <?= number_format($nilai_akhir, 2) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($is_kompeten): ?>
                                    <span class="badge bg-success text-white px-3 py-2 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i> KOMPETEN
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger text-white px-3 py-2 rounded-pill">
                                        <i class="bi bi-x-circle-fill me-1"></i> BELUM KOMPETEN
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalPos<?= $id_siswa ?>">
                                    <i class="bi bi-eye"></i> Detail
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
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                <div class="modal-header border-bottom py-3">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark" id="label<?= $id_siswa ?>"><?= esc($r['nama_siswa']) ?></h5>
                        <div class="d-flex gap-2 text-muted small">
                            <span><i class="bi bi-person-badge me-1"></i>NISN: <?= esc($r['nisn']) ?></span>
                            <span>&bull;</span>
                            <span><i class="bi bi-building me-1"></i><?= esc($r['kelas']) ?></span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">
                        <i class="bi bi-journal-check text-success me-1"></i> Rincian Nilai &amp; Catatan per POS Praktik
                    </h6>

                    <div class="table-responsive border rounded">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 25%;" class="ps-3">Nama POS</th>
                                    <th style="width: 15%;" class="text-center">Rata Nilai</th>
                                    <th style="width: 15%;" class="text-center">Status POS</th>
                                    <th style="width: 45%;">Catatan Penguji</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($detail_pos[$id_siswa]) && !empty($detail_pos[$id_siswa])): ?>
                                    <?php foreach ($detail_pos[$id_siswa] as $pos):
                                        $is_remedi_pos = ($pos['min_nilai'] == 25 || $pos['min_nilai'] === null);
                                    ?>
                                        <tr>
                                            <td class="fw-bold text-dark ps-3"><?= esc($pos['nama_pos']) ?></td>
                                            <td class="text-center fw-bold">
                                                <?= $pos['rata_nilai'] !== null ? number_format((float)$pos['rata_nilai'], 2) : '<span class="text-muted small fst-italic">Belum dinilai</span>' ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($is_remedi_pos): ?>
                                                    <span class="badge bg-danger-subtle text-danger px-3 py-1 rounded-pill border">Remedial</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill border">Kompeten</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="small pe-3">
                                                <?php if (!empty($pos['catatan'])): ?>
                                                    <div class="p-2 bg-light rounded text-dark border-start border-3 border-success" style="white-space: pre-wrap; font-size: 0.85rem; line-height: 1.4;"><?= esc($pos['catatan']) ?></div>
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
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-secondary btn-sm px-4 rounded-pill" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?= $this->endSection() ?>
