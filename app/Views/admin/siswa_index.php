<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    /* Merapikan Search Box & Pagination DataTable */
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 10px;
        border: 1px solid var(--uk-line, #e2e8f0);
        padding: 6px 14px;
        margin-left: 10px;
        background: #fff;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 10px;
        border: 1px solid var(--uk-line, #e2e8f0);
        padding: 5px 10px;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #4338ca, #6366f1);
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(99, 102, 241, .35);
    }

    #tableSiswa tbody tr td {
        padding: 13px 12px;
    }

    #tableSiswa tbody tr:hover {
        background-color: #fbfbfe;
    }
</style>

<div class="card border-0 shadow-sm" style="border-radius: 16px; border: 1px solid var(--uk-line, #e5e7eb) !important;">
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center" style="border-radius: 16px 16px 0 0; border-bottom: 1px solid var(--uk-line, #e5e7eb);">
        <div>
            <h5 class="fw-bold mb-0 text-dark" style="letter-spacing: -.02em;">
                <i class="bi bi-people-fill me-2 text-primary"></i>Data Peserta UKK
            </h5>
            <small class="text-muted"><i class="bi bi-calendar-event me-1"></i> <?= esc(session()->get('nama_event') ?? 'Event Aktif') ?></small>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm fw-bold px-3 rounded-3" data-bs-toggle="modal" data-bs-target="#modalImport" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Import Excel
            </button>
            <button class="btn btn-primary btn-sm fw-bold px-3 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah" style="background: linear-gradient(135deg, #4338ca, #6366f1); border: none;">
                <i class="bi bi-plus-lg me-1"></i> Tambah Siswa
            </button>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="tableSiswa" class="table table-hover align-middle" style="width:100%; border-top: 1px solid #f8f9fa;">
                <thead class="bg-light">
                    <tr>
                        <th class="text-secondary small fw-bold text-uppercase ps-3">No</th>
                        <th class="text-secondary small fw-bold text-uppercase">NISN</th>
                        <th class="text-secondary small fw-bold text-uppercase">Nama Lengkap</th>
                        <th class="text-secondary small fw-bold text-uppercase">Kelas</th>
                        <th class="text-secondary small fw-bold text-uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="small">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditSiswa" action="" method="post" class="modal-content border-0 shadow">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">NISN</label>
                    <input type="text" name="nisn" id="edit_nisn" class="form-control bg-light" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                    <input type="text" name="nama_siswa" id="edit_nama" class="form-control bg-light" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Kelas</label>
                    <input type="text" name="kelas" id="edit_kelas" class="form-control bg-light" required>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('siswa/store') ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="modalTambahLabel">Tambah Peserta Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">NISN</label>
                    <input type="text" name="nisn" class="form-control" placeholder="Contoh: 0081234567" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama_siswa" class="form-control" placeholder="Nama Lengkap Siswa" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Kelas</label>
                    <input type="text" name="kelas" class="form-control" placeholder="Contoh: XII TBSM 1" required>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary px-4">Simpan Siswa</button>
            </div>
        </form>
    </div>
</div>

<?php foreach ($siswa as $s): ?>
    <div class="modal fade" id="modalEdit<?= $s['id_siswa'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= base_url('siswa/update/' . $s['id_siswa']) ?>" method="post" class="modal-content">
                <?= csrf_field() ?>
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Edit Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">NISN</label>
                        <input type="text" name="nisn" class="form-control" value="<?= $s['nisn'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_siswa" class="form-control" value="<?= $s['nama_siswa'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kelas</label>
                        <input type="text" name="kelas" class="form-control" value="<?= $s['kelas'] ?>" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary px-4">Update Data</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<div class="modal fade" id="modalImport" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('siswa/import') ?>" method="post" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Import dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info py-2 small">Format: A(NISN), B(Nama), C(Kelas).</div>
                <input type="file" name="file_excel" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success w-100">Proses Import</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditSiswa" action="" method="post" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Edit Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">NISN</label>
                    <input type="text" name="nisn" id="edit_nisn" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama_siswa" id="edit_nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Kelas</label>
                    <input type="text" name="kelas" id="edit_kelas" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-primary px-4">Update Data</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>