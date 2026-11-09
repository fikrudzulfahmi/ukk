<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Daftar Penguji UKK</h5>
        <button class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-person-plus"></i> Tambah Penguji
        </button>
        <a href="<?= base_url('penguji/cetak_akun'); ?>" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> Cetak Semua Akun
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light small">
                <tr>
                    <th class="ps-4">NAMA PENGUJI</th>
                    <th>INSTANSI</th>
                    <th>USERNAME</th>
                    <th class="text-center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($penguji as $p): ?>
                    <tr>
                        <td class="ps-4 fw-bold"><?= $p['nama_lengkap'] ?></td>
                        <td><?= $p['instansi'] ?></td>
                        <td><code class="text-primary"><?= $p['username'] ?></code></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $p['id_user'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button onclick="confirmDelete('<?= base_url('penguji/delete/' . $p['id_user']) ?>')" class="btn btn-sm btn-light text-danger">
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

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('penguji/store') ?>" method="post" class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Tambah Penguji Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Instansi</label>
                    <input type="text" name="instansi" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-primary w-100">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>

<?php foreach ($penguji as $p): ?>
    <div class="modal fade" id="modalEdit<?= $p['id_user'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <form action="<?= base_url('penguji/update/' . $p['id_user']) ?>" method="post" class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Edit Data Penguji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= $p['nama_lengkap'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Instansi</label>
                        <input type="text" name="instansi" class="form-control" value="<?= $p['instansi'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= $p['username'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Baru (Kosongkan jika tidak ganti)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100">Update Data</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>
<?= $this->endSection() ?>