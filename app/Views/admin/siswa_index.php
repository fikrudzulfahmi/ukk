<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    /* Merapikan Search Box & Pagination DataTable */
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 5px 12px;
        margin-left: 10px;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 8px;
        padding: 5px;
    }

    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    /* Mempercantik baris */
    table.dataTable.no-footer {
        border-bottom: 1px solid #f0f0f0 !important;
    }

    #tableSiswa tbody tr td {
        padding: 15px 10px;
        /* Membuat baris lebih lega */
    }
</style>

<div class="card border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
        <div>
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-people-fill me-2 text-primary"></i>Data Peserta
            </h5>
            <small class="text-muted">Event: <?= session()->get('nama_event') ?></small>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-light-success btn-sm fw-bold px-3" data-bs-toggle="modal" data-bs-target="#modalImport" style="background-color: #e8f5e9; color: #2e7d32; border: none;">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Import
            </button>
            <button class="btn btn-primary btn-sm fw-bold px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
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