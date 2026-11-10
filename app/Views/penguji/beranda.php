<?= $this->extend('layout/penguji_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-3 py-md-4">

    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">Halo, <?= esc(session()->get('nama_lengkap') ?? 'Penguji') ?></h3>
        <p class="text-muted mb-0">Ringkasan penilaian yang menjadi tanggung jawab Anda.</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 pg-stat pg-stat--brand">
                <div class="card-body">
                    <div class="pg-stat__label">Diuji Hari Ini</div>
                    <div class="pg-stat__value"><?= (int) $hari_ini ?> <span>siswa</span></div>
                    <div class="pg-stat__hint"><?= date('d M Y') ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 pg-stat pg-stat--ink">
                <div class="card-body">
                    <div class="pg-stat__label">Total Siswa Diuji</div>
                    <div class="pg-stat__value"><?= (int) $total_siswa ?> <span>siswa</span></div>
                    <div class="pg-stat__hint"><?= (int) $jumlah_pos ?> pos</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 pg-stat pg-stat--green">
                <div class="card-body">
                    <div class="pg-stat__label">Sudah Selesai Dinilai</div>
                    <div class="pg-stat__value"><?= (int) $selesai ?> <span>siswa</span></div>
                    <div class="pg-stat__hint">Semua elemen terisi</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-6">
            <div class="card border-0 shadow-sm h-100 pg-stat pg-stat--amber">
                <div class="card-body">
                    <div class="pg-stat__label">Masih Proses Dinilai</div>
                    <div class="pg-stat__value"><?= (int) $proses ?> <span>siswa</span></div>
                    <div class="pg-stat__hint">Sebagian elemen sudah terisi</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-6">
            <div class="card border-0 shadow-sm h-100 pg-stat pg-stat--red">
                <div class="card-body">
                    <div class="pg-stat__label">Belum Dinilai</div>
                    <div class="pg-stat__value"><?= (int) $belum ?> <span>siswa</span></div>
                    <div class="pg-stat__hint">Belum ada nilai masuk</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-0">POS Anda Hari Ini</h6>
                <small class="text-muted">Langsung masuk ke daftar antrian penilaian</small>
            </div>
            <a href="<?= base_url('penilaian/input') ?>" class="btn btn-sm btn-outline-success rounded-pill">
                Semua Tanggal
            </a>
        </div>
        <div class="card-body">
            <?php if (empty($pos_hari_ini)): ?>
                <div class="text-center text-muted py-4">
                    <i class="bi bi-calendar-x d-block mb-2" style="font-size:1.6rem;"></i>
                    Tidak ada jadwal penilaian untuk Anda hari ini.
                </div>
            <?php else: ?>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($pos_hari_ini as $p): ?>
                        <a href="<?= base_url('penilaian/input?date=' . date('Y-m-d') . '&id_pos=' . $p['id_pos']) ?>"
                            class="btn btn-success rounded-pill px-3">
                            <i class="bi bi-pencil-square me-1"></i> <?= esc($p['nama_pos']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="d-grid d-sm-flex gap-2">
        <a href="<?= base_url('penilaian/input') ?>" class="btn btn-success">
            <i class="bi bi-pencil-square me-1"></i> Input Nilai
        </a>
        <a href="<?= base_url('penilaian/riwayat') ?>" class="btn btn-light border">
            <i class="bi bi-clock-history me-1"></i> Lihat Riwayat
        </a>
    </div>
</div>

<style>
    .pg-stat {
        border-radius: 16px;
        border-left: 4px solid var(--uk-line);
    }

    .pg-stat .card-body { padding: 1.1rem 1.15rem; }

    .pg-stat__label {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: .35rem;
    }

    .pg-stat__value {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.85rem;
        font-weight: 800;
        line-height: 1.1;
        color: #0f172a;
    }

    .pg-stat__value span {
        font-size: .78rem;
        font-weight: 600;
        color: #94a3b8;
    }

    .pg-stat__hint { font-size: .74rem; color: #94a3b8; margin-top: .3rem; }

    .pg-stat--brand { border-left-color: #059669; }
    .pg-stat--green { border-left-color: #10b981; }
    .pg-stat--amber { border-left-color: #f59e0b; }
    .pg-stat--red { border-left-color: #ef4444; }
    .pg-stat--ink { border-left-color: #334155; }
</style>
<?= $this->endSection() ?>
