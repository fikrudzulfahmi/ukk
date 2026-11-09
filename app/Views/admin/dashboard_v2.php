<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-0">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="uk-eyebrow">Ringkasan</div>
            <h4 class="fw-bold mb-1">Selamat datang, <?= esc(session()->get('nama_lengkap') ?? 'Admin') ?> 👋</h4>
            <p class="text-muted small mb-0">Berikut ringkasan pelaksanaan uji kompetensi saat ini.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('penilaian') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil-square me-1"></i> Input Nilai
            </a>
            <a href="<?= base_url('laporan') ?>" class="btn btn-light btn-sm">
                <i class="bi bi-file-earmark-bar-graph me-1"></i> Rekap
            </a>
        </div>
    </div>

    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="uk-stat uk-stat--indigo">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="uk-stat__icon"><i class="bi bi-people-fill"></i></span>
                    <span class="badge bg-soft-primary rounded-pill">Aktif</span>
                </div>
                <div class="uk-stat__label">Total Peserta</div>
                <div class="uk-stat__value"><?= (int) $total_siswa ?> <span class="fs-6 fw-semibold text-muted">siswa</span></div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="uk-stat uk-stat--green">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="uk-stat__icon"><i class="bi bi-person-badge-fill"></i></span>
                </div>
                <div class="uk-stat__label">Total Penguji</div>
                <div class="uk-stat__value"><?= (int) $total_penguji ?> <span class="fs-6 fw-semibold text-muted">orang</span></div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="uk-stat uk-stat--amber">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="uk-stat__icon"><i class="bi bi-diagram-3-fill"></i></span>
                </div>
                <div class="uk-stat__label">Kriteria / Pos</div>
                <div class="uk-stat__value"><?= (int) $total_pos ?> <span class="fs-6 fw-semibold text-muted">item</span></div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="uk-stat uk-stat--sky">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="uk-stat__icon"><i class="bi bi-calendar-event-fill"></i></span>
                </div>
                <div class="uk-stat__label">Riwayat Event</div>
                <div class="uk-stat__value"><?= (int) $total_event ?> <span class="fs-6 fw-semibold text-muted">data</span></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 fw-bold">Status Event Saat Ini</h6>
                        <small class="text-muted">Progres pelaksanaan ujian</small>
                    </div>
                    <span class="badge bg-soft-primary rounded-pill">
                        <i class="bi bi-broadcast me-1"></i> Live
                    </span>
                </div>

                <div class="card-body">
                    <?php if ($event_aktif): ?>
                        <?php $persen_teori = ($total_siswa > 0) ? (int) round(($sudah_teori / $total_siswa) * 100) : 0; ?>

                        <div class="p-4 rounded-4 border" style="background: linear-gradient(135deg, #f7f8ff 0%, #fdfbff 100%); border-color: #ecebff !important;">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-1">
                                <h5 class="fw-bold mb-0 text-dark"><?= esc($event_aktif['nama_event']) ?></h5>
                                <span class="badge bg-soft-success rounded-pill"><span class="uk-dot me-1"></span>Berjalan</span>
                            </div>
                            <p class="text-muted small mb-4">
                                <i class="bi bi-calendar3 me-2"></i>Mulai <?= date('d M Y', strtotime($event_aktif['tanggal_mulai'])) ?>
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-semibold text-secondary">Progres Ujian Teori</span>
                                <span class="small fw-bold text-primary"><?= $persen_teori ?>%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: <?= $persen_teori ?>%"></div>
                            </div>
                            <small class="text-muted d-block mt-2"><?= (int) $sudah_teori ?> dari <?= (int) $total_siswa ?> siswa sudah memiliki nilai teori.</small>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-sm-6">
                                <a href="<?= base_url('monitoring/matriks') ?>" class="d-flex align-items-center gap-3 p-3 rounded-4 border text-decoration-none h-100 uk-quick">
                                    <span class="uk-stat__icon bg-soft-primary" style="width:40px;height:40px;font-size:1rem;"><i class="bi bi-speedometer2"></i></span>
                                    <span>
                                        <span class="d-block fw-semibold small text-dark">Monitoring Nilai</span>
                                        <span class="d-block text-muted" style="font-size:.76rem;">Pantau input penilaian pos</span>
                                    </span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="<?= base_url('jadwal') ?>" class="d-flex align-items-center gap-3 p-3 rounded-4 border text-decoration-none h-100 uk-quick">
                                    <span class="uk-stat__icon bg-soft-success" style="width:40px;height:40px;font-size:1rem;"><i class="bi bi-calendar2-week"></i></span>
                                    <span>
                                        <span class="d-block fw-semibold small text-dark">Jadwal Siswa</span>
                                        <span class="d-block text-muted" style="font-size:.76rem;">Atur sesi &amp; penguji</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="uk-empty">
                            <div class="uk-empty__icon"><i class="bi bi-calendar-x"></i></div>
                            <h6>Belum ada event aktif</h6>
                            <p>Aktifkan salah satu session event agar peserta dan penguji bisa mulai dinilai.</p>
                            <a href="<?= base_url('event') ?>" class="btn btn-primary btn-sm px-4">Pilih Event Sekarang</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100 text-white border-0" style="background: linear-gradient(150deg, #4338ca 0%, #6d28d9 60%, #7e22ce 100%); box-shadow: 0 24px 50px -30px rgba(79, 70, 229, .85);">
                <div class="card-body d-flex flex-column">
                    <h6 class="fw-bold mb-1 text-white">Akses Cepat</h6>
                    <p class="small mb-4" style="color: rgba(255,255,255,.72);">Pintasan pekerjaan panitia UKK.</p>

                    <div class="d-grid gap-2 mt-auto">
                        <a href="<?= base_url('penilaian') ?>" class="uk-action">
                            <i class="bi bi-pencil-square"></i> Input Nilai Baru
                        </a>
                        <a href="<?= base_url('siswa') ?>" class="uk-action">
                            <i class="bi bi-person-plus"></i> Kelola Peserta
                        </a>
                        <a href="<?= base_url('soalteori') ?>" class="uk-action">
                            <i class="bi bi-journal-text"></i> Bank Soal Teori
                        </a>
                        <a href="<?= base_url('laporan') ?>" class="uk-action">
                            <i class="bi bi-file-earmark-bar-graph"></i> Lihat Rekapitulasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .uk-quick { transition: box-shadow .18s ease, border-color .18s ease; }
    .uk-quick:hover { border-color: #d9dcf0 !important; box-shadow: var(--uk-shadow-sm); }

    .uk-action {
        display: flex;
        align-items: center;
        gap: .65rem;
        padding: .62rem .85rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, .12);
        color: #fff;
        font-size: .855rem;
        font-weight: 600;
        transition: background-color .18s ease, transform .18s ease;
    }

    .uk-action:hover { background: rgba(255, 255, 255, .22); color: #fff; transform: translateX(2px); }
</style>
<?= $this->endSection() ?>
