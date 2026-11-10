<?= $this->extend('layout/penguji_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-3 py-md-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Riwayat Penilaian</h3>
            <p class="text-muted mb-0">Daftar siswa yang pernah Anda uji beserta progres penilaiannya.</p>
        </div>
        <a href="<?= base_url('penilaian/input') ?>" class="btn btn-success btn-sm rounded-pill">
            <i class="bi bi-pencil-square me-1"></i> Input Nilai
        </a>
    </div>

    <?php if (empty($riwayat)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-clock-history d-block mb-2" style="font-size:2rem;"></i>
                Belum ada riwayat penilaian pada event ini.
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light small text-muted">
                            <tr>
                                <th class="ps-4 py-3">SISWA</th>
                                <th class="py-3">POS</th>
                                <th class="py-3">TANGGAL</th>
                                <th class="py-3 text-center">PROGRES</th>
                                <th class="py-3 text-center">STATUS</th>
                                <th class="py-3 text-end pe-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($riwayat as $r): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark"><?= esc($r['nama_siswa']) ?></div>
                                        <div class="small text-muted"><?= esc($r['kelas'] ?: '-') ?></div>
                                    </td>
                                    <td class="small"><?= esc($r['nama_pos']) ?></td>
                                    <td class="small text-muted"><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
                                    <td class="text-center" style="min-width:120px;">
                                        <div class="small text-muted mb-1"><?= (int) $r['terisi'] ?>/<?= (int) $r['total'] ?> elemen</div>
                                        <div class="progress" style="height:6px; border-radius:6px;">
                                            <div class="progress-bar <?= $r['status'] === 'selesai' ? 'bg-success' : 'bg-warning' ?>"
                                                style="width: <?= (int) $r['persen'] ?>%;"></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($r['status'] === 'selesai'): ?>
                                            <span class="badge rounded-pill text-bg-success">Selesai</span>
                                        <?php elseif ($r['status'] === 'proses'): ?>
                                            <span class="badge rounded-pill text-bg-warning">Proses</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill text-bg-light border text-muted">Belum</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="<?= base_url('penilaian/input?date=' . $r['tanggal'] . '&id_pos=' . $r['id_pos']) ?>"
                                            class="btn btn-sm btn-outline-success rounded-pill">
                                            <i class="bi bi-pencil-square"></i> Nilai
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
