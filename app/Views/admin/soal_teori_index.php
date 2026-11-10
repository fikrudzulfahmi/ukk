<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0">Manajemen Soal Teori</h5>
                <small class="text-muted">Kelola bank soal untuk sistem ujian scan QR</small>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= base_url('soalteori/cetak') ?>" class="btn btn-danger btn-sm shadow-sm" target="_blank">
                    <i class="bi bi-file-pdf"></i> Cetak Soal
                </a>
                <button class="btn btn-outline-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalClone">
                    <i class="bi bi-files"></i> Clone Tahun Lalu
                </button>
                <button class="btn btn-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="bi bi-file-earmark-excel"></i> Import
                </button>
                <button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-lg"></i> Tambah Soal
                </button>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-3">
        <div class="accordion accordion-flush" id="accordionSoal">
            <?php if (empty($soal)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-journal-x display-1 text-light"></i>
                    <p class="text-muted mt-3">Belum ada soal. Silakan tambah manual, import excel, atau clone dari tahun sebelumnya.</p>
                </div>
            <?php else: ?>
                <?php foreach ($soal as $i => $s): ?>
                    <div class="accordion-item border mb-2 rounded overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-3 text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#soal<?= $s['id_soal'] ?>">
                                <span class="badge bg-success me-3">#<?= $i + 1 ?></span>
                                <div class="text-truncate" style="max-width: 85%;">
                                    <?= strip_tags($s['pertanyaan']) ?>
                                </div>
                            </button>
                        </h2>
                        <div id="soal<?= $s['id_soal'] ?>" class="accordion-collapse collapse" data-bs-parent="#accordionSoal">
                            <div class="accordion-body bg-light">
                                <div class="row">
                                    <div class="col-md-9">
                                        <p class="mb-3 text-dark"><?= $s['pertanyaan'] ?></p>
                                        <div class="row g-2">
                                            <?php foreach (['a', 'b', 'c', 'd'] as $opsi): ?>
                                                <div class="col-md-6">
                                                    <div class="p-2 border rounded bg-white <?= (strtoupper($opsi) == $s['jawaban_benar']) ? 'border-success bg-success bg-opacity-10' : '' ?>">
                                                        <span class="fw-bold"><?= strtoupper($opsi) ?>.</span> <?= $s['opsi_' . $opsi] ?>
                                                        <?php if (strtoupper($opsi) == $s['jawaban_benar']): ?>
                                                            <i class="bi bi-check-circle-fill text-success float-end"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-md-end mt-3 mt-md-0 border-start">
                                        <a href="<?= base_url('soalteori/hapus/' . $s['id_soal']) ?>"
                                            class="btn btn-outline-danger btn-sm js-hapus-soal"
                                            data-url="<?= base_url('soalteori/hapus/' . $s['id_soal']) ?>">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg border-0">
        <form action="<?= base_url('soalteori/simpan') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="modal-content border-0">
                <div class="modal-header bg-primary text-white border-0">
                    <h6 class="modal-title fw-bold">Tambah Soal Baru</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pertanyaan</label>
                        <textarea name="pertanyaan" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Opsi A</label>
                            <input type="text" name="opsi_a" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Opsi B</label>
                            <input type="text" name="opsi_b" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Opsi C</label>
                            <input type="text" name="opsi_c" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Opsi D</label>
                            <input type="text" name="opsi_d" class="form-control" required>
                        </div>
                    </div>
                    <label class="form-label fw-bold text-primary">Kunci Jawaban Benar</label>
                    <div class="d-flex justify-content-around p-3 bg-light rounded border">
                        <?php foreach (['A', 'B', 'C', 'D'] as $k): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jawaban_benar" id="k<?= $k ?>" value="<?= $k ?>" required>
                                <label class="form-check-label fw-bold" for="k<?= $k ?>">Opsi <?= $k ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light shadow-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog border-0">
        <form action="<?= base_url('soalteori/import') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white border-0">
                    <h6 class="modal-title fw-bold" id="modalImportLabel">
                        <i class="bi bi-file-earmark-excel me-2"></i>Import Soal dari Excel
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <small class="fw-bold"><i class="bi bi-info-circle me-1"></i> Ketentuan Format Excel:</small>
                        <ul class="small mb-0 mt-1">
                            <li>Baris pertama adalah header (akan dilewati sistem).</li>
                            <li>Kolom A: Pertanyaan</li>
                            <li>Kolom B-E: Opsi A, B, C, D</li>
                            <li>Kolom F: Jawaban Benar (Hanya huruf A/B/C/D)</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <label for="file_excel" class="form-label fw-bold small">Pilih File (.xlsx)</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx" required>
                    </div>

                    <div class="text-center">
                        <a href="<?= base_url('soalteori/template_soal_ukk.xlsx') ?>" class="btn btn-link btn-sm text-decoration-none">
                            <i class="bi bi-download me-1"></i>Unduh Template Excel
                        </a>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 shadow-sm">
                        <i class="bi bi-cloud-arrow-up me-1"></i>Mulai Import
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalClone" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog border-0">
        <form action="<?= base_url('soalteori/clone') ?>" method="POST" class="modal-content border-0 shadow" id="formCloneSoal">
            <?= csrf_field() ?>
            <div class="modal-header border-0" style="background:#ecfdf5;">
                <h6 class="modal-title fw-bold text-success">
                    <i class="bi bi-files me-2"></i>Clone Soal dari Tahun Sebelumnya
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted">
                    Seluruh soal dari event yang dipilih akan disalin ke event aktif
                    (<strong><?= esc(session()->get('nama_event') ?? '-') ?></strong>).
                    Soal dengan pertanyaan yang sama tidak akan diduplikasi.
                </p>

                <?php if (empty($events)): ?>
                    <div class="alert alert-warning border-0 small mb-0">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Belum ada event lain. Buat event baru terlebih dahulu di menu Event.
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Pilih Event Sumber</label>
                        <select name="id_event_sumber" class="form-select" required>
                            <option value="">-- Pilih Event --</option>
                            <?php foreach ($events as $ev): ?>
                                <option value="<?= $ev['id_event'] ?>">
                                    <?= esc($ev['nama_event']) ?><?= !empty($ev['tahun_pelajaran']) ? ' — ' . esc($ev['tahun_pelajaran']) : '' ?>
                                    (<?= (int) ($event_counts[$ev['id_event']] ?? 0) ?> soal)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="alert alert-light border small mb-0">
                        <i class="bi bi-info-circle me-1 text-success"></i>
                        Kunci jawaban ikut tersalin. Soal lama di event sumber tetap utuh.
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success btn-sm px-4" <?= empty($events) ? 'disabled' : '' ?>>
                    <i class="bi bi-files me-1"></i>Clone Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const formCloneSoal = document.getElementById('formCloneSoal');
    if (formCloneSoal) {
        formCloneSoal.addEventListener('submit', function(e) {
            const select = this.querySelector('select[name="id_event_sumber"]');
            if (!select || !select.value) return;

            e.preventDefault();
            const eventName = select.options[select.selectedIndex].text.trim();

            Swal.fire({
                title: 'Clone bank soal?',
                html: `Seluruh soal dari <strong>${eventName}</strong> akan disalin ke event aktif.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Clone',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        // Hapus soal — konfirmasi modal (bukan confirm bawaan browser)
        $(document).on('click', '.js-hapus-soal', async function (e) {
            e.preventDefault();
            const url = $(this).data('url');
            const ok = await ukConfirm({
                title: 'Hapus soal ini?',
                text: 'Soal akan dihapus permanen dari bank soal event aktif.',
                icon: 'warning',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus'
            });
            if (ok) window.location.href = url;
        });
    }
</script>

<?= $this->endSection() ?>