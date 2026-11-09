<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
$totalElemen = count($elemen);
$totalPos    = count($pos);
?>

<div class="container-fluid py-2">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Kriteria Penilaian</h4>
            <p class="text-muted small mb-0">
                <i class="bi bi-list-check text-success me-1"></i>
                <?= $totalElemen ?> butir kriteria dalam <?= $totalPos ?> POS &mdash; <?= esc(session()->get('nama_event') ?? '-') ?>
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= base_url('elemen/template') ?>" class="btn btn-light btn-sm border d-flex align-items-center gap-1">
                <i class="bi bi-download"></i> Template
            </a>
            <button class="btn btn-outline-success btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalImport">
                <i class="bi bi-file-earmark-excel"></i> Import Excel
            </button>
            <button class="btn btn-outline-success btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalClone">
                <i class="bi bi-files"></i> Clone Event
            </button>
            <button class="btn btn-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalTambahElemen">
                <i class="bi bi-plus-lg"></i> Tambah Kriteria
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 18%;">POS</th>
                            <th style="width: 68%;">NAMA ELEMEN / BUTIR PENILAIAN</th>
                            <th class="text-center" style="width: 14%;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($elemen)): ?>
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="bi bi-clipboard-x fs-1 d-block mb-2 text-secondary"></i>
                                    Belum ada kriteria. Tambah manual, import Excel, atau clone dari event sebelumnya.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php
                        $currentPos = null;
                        foreach ($elemen as $el):
                        ?>

                            <?php if ($currentPos != $el['nama_pos']): ?>
                                <tr class="table-light">
                                    <td colspan="3" class="fw-bold text-success ps-4">
                                        <i class="bi bi-folder2-open me-1"></i> <?= esc($el['nama_pos']) ?>
                                    </td>
                                </tr>
                                <?php $currentPos = $el['nama_pos']; ?>
                            <?php endif; ?>

                            <tr>
                                <td></td>
                                <td class="fw-semibold"><?= esc($el['nama_elemen']) ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light text-success btn-edit"
                                            data-id="<?= $el['id_elemen'] ?>"
                                            data-nama="<?= esc($el['nama_elemen'], 'attr') ?>"
                                            data-pos="<?= $el['id_pos'] ?>"
                                            title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button onclick="confirmDelete('<?= base_url('elemen/delete/' . $el['id_elemen']) ?>')"
                                            class="btn btn-sm btn-light text-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahElemen" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('elemen/store') ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Tambah Butir Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Pilih Pos</label>
                    <select name="id_pos" class="form-select" required>
                        <option value="">-- Pilih Pos --</option>
                        <?php foreach ($pos as $p): ?>
                            <option value="<?= $p['id_pos'] ?>"><?= esc($p['nama_pos']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="wrapper-elemen">
                    <div class="mb-2">
                        <input type="text" name="nama_elemen[]"
                            class="form-control"
                            placeholder="Contoh: Menyetel rantai roda" required>
                    </div>
                </div>

                <button type="button" class="btn btn-sm btn-outline-success" onclick="addRow()">
                    + Tambah Baris
                </button>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-primary w-100">Simpan Elemen</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="modalImport" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('elemen/import') ?>" method="post" enctype="multipart/form-data" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-excel text-success me-2"></i>Import Kriteria dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success border-0 small mb-3" style="background:#ecfdf5;">
                    <div class="fw-bold mb-1"><i class="bi bi-info-circle me-1"></i> Ketentuan format:</div>
                    <ul class="mb-0 ps-3">
                        <li>Baris pertama adalah header (otomatis dilewati).</li>
                        <li><strong>Kolom A</strong>: Nama POS (misal: Pengujian Mesin).</li>
                        <li><strong>Kolom B</strong>: Nama elemen / butir kriteria.</li>
                        <li>POS yang belum ada di event aktif akan dibuat otomatis.</li>
                        <li>Kriteria yang teksnya sama akan dilewati (tidak dobel).</li>
                    </ul>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-bold small">Pilih File (.xlsx / .xls)</label>
                    <input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls" required>
                </div>

                <div class="text-center">
                    <a href="<?= base_url('elemen/template') ?>" class="btn btn-link btn-sm text-decoration-none">
                        <i class="bi bi-download me-1"></i>Unduh Template Excel
                    </a>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success btn-sm px-4">
                    <i class="bi bi-cloud-arrow-up me-1"></i>Mulai Import
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Clone dari Event Sebelumnya -->
<div class="modal fade" id="modalClone" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('elemen/clone') ?>" method="post" class="modal-content" id="formClone">
            <?= csrf_field() ?>
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-files text-success me-2"></i>Clone Kriteria dari Event Lain</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted">
                    Seluruh POS dan butir kriteria dari event yang dipilih akan disalin ke event aktif
                    (<strong><?= esc(session()->get('nama_event') ?? '-') ?></strong>).
                    Data yang sudah ada tidak akan diduplikasi.
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
                                    (<?= (int) ($event_counts[$ev['id_event']] ?? 0) ?> kriteria)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="alert alert-light border small mb-0">
                        <i class="bi bi-info-circle me-1 text-success"></i>
                        POS dengan nama sama akan digabung, kriteria yang sudah ada dilewati.
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

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEdit" method="post" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Elemen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Pos</label>
                    <select name="id_pos" id="edit_pos" class="form-select">
                        <?php foreach ($pos as $p): ?>
                            <option value="<?= $p['id_pos'] ?>">
                                <?= esc($p['nama_pos']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Elemen</label>
                    <textarea name="nama_elemen"
                        id="edit_nama"
                        class="form-control"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    function addRow() {
        let html = `
        <div class="mb-2">
            <input type="text" name="nama_elemen[]" 
                   class="form-control" 
                   placeholder="Elemen tambahan">
        </div>`;
        document.getElementById('wrapper-elemen')
            .insertAdjacentHTML('beforeend', html);
    }
</script>

<script>
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {

            let id = this.dataset.id;
            let nama = this.dataset.nama;
            let pos = this.dataset.pos;

            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_pos').value = pos;

            document.getElementById('formEdit').action =
                "<?= base_url('elemen/update/') ?>" + id;

            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        });
    });

    // Konfirmasi clone agar tidak salah pilih event
    const formClone = document.getElementById('formClone');
    if (formClone) {
        formClone.addEventListener('submit', function(e) {
            const select = this.querySelector('select[name="id_event_sumber"]');
            if (!select || !select.value) return;

            e.preventDefault();
            const eventName = select.options[select.selectedIndex].text.trim();

            Swal.fire({
                title: 'Clone kriteria?',
                html: `POS &amp; kriteria dari <strong>${eventName}</strong> akan disalin ke event aktif.`,
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
    }
</script>


<?= $this->endSection() ?>
