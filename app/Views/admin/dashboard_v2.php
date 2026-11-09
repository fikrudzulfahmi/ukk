<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">

    <div class="mb-4">
        <h4 class="fw-bold text-dark">Selamat Datang, Admin! 👋</h4>
        <p class="text-muted small">Berikut adalah ringkasan data uji kompetensi hari ini.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 overflow-hidden" style="border-radius: 15px;">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-people-fill fs-3 text-primary mb-0"></i>
                        </div>
                        <span class="badge bg-soft-primary text-primary rounded-pill small">Aktif</span>
                    </div>
                    <h6 class="text-muted fw-normal">Total Peserta</h6>
                    <h2 class="fw-bold mb-0"><?= $total_siswa ?> <small class="fs-6 text-muted">Siswa</small></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-person-badge-fill fs-3 text-success"></i>
                        </div>
                    </div>
                    <h6 class="text-muted fw-normal">Total Penguji</h6>
                    <h2 class="fw-bold mb-0"><?= $total_penguji ?> <small class="fs-6 text-muted">Orang</small></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-diagram-3-fill fs-3 text-warning"></i>
                        </div>
                    </div>
                    <h6 class="text-muted fw-normal">Kriteria/Pos</h6>
                    <h2 class="fw-bold mb-0"><?= $total_pos ?> <small class="fs-6 text-muted">Item</small></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-calendar-event-fill fs-3 text-info"></i>
                        </div>
                    </div>
                    <h6 class="text-muted fw-normal">Riwayat Event</h6>
                    <h2 class="fw-bold mb-0"><?= $total_event ?> <small class="fs-6 text-muted">Data</small></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-dark">Status Event Saat Ini</h5>
                    <i class="bi bi-info-circle text-muted"></i>
                </div>

                <?php if ($event_aktif): ?>
                    <div class="p-4 rounded-4 bg-light border-0 position-relative overflow-hidden">
                        <div class="position-relative z-1">
                            <h4 class="fw-bold text-primary mb-1"><?= $event_aktif['nama_event'] ?></h4>
                            <p class="text-muted mb-4"><i class="bi bi-calendar3 me-2"></i>Mulai Tanggal: <?= date('d M Y', strtotime($event_aktif['tanggal_mulai'])) ?></p>

                            <?php
                            $persen_teori = ($total_siswa > 0) ? round(($sudah_teori / $total_siswa) * 100) : 0;
                            ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-bold">Progres Ujian Teori</span>
                                <span class="small fw-bold"><?= $persen_teori ?>%</span>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 10px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: <?= $persen_teori ?>%"></div>
                            </div>
                            <small class="text-muted d-block mt-2"><?= $sudah_teori ?> dari <?= $total_siswa ?> siswa sudah memiliki nilai teori.</small>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50">
                        <h6 class="text-muted">Tidak ada event aktif</h6>
                        <a href="<?= base_url('event') ?>" class="btn btn-primary btn-sm px-4 mt-2">Pilih Event Sekarang</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="text-white">
                    <h5 class="fw-bold mb-3">Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('penilaian') ?>" class="btn btn-white btn-sm text-start py-2 bg-white bg-opacity-10 border-0 text-white shadow-none">
                            <i class="bi bi-pencil-square me-2"></i> Input Nilai Baru
                        </a>
                        <a href="<?= base_url('siswa') ?>" class="btn btn-white btn-sm text-start py-2 bg-white bg-opacity-10 border-0 text-white shadow-none">
                            <i class="bi bi-person-plus me-2"></i> Kelola Peserta
                        </a>
                        <a href="<?= base_url('laporan') ?>" class="btn btn-white btn-sm text-start py-2 bg-white bg-opacity-10 border-0 text-white shadow-none">
                            <i class="bi bi-file-earmark-bar-graph me-2"></i> Lihat Rekapitulasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-primary {
        background-color: #e0e7ff;
    }

    .rounded-4 {
        border-radius: 1rem !important;
    }

    .btn-white:hover {
        background-color: rgba(255, 255, 255, 0.2) !important;
    }
</style>
<?= $this->endSection() ?>