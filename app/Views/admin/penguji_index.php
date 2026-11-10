<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex flex-wrap gap-2 justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-0">Manajemen User</h5>
            <small class="text-muted">Kelola nama, username, password, dan role akun.</small>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('penguji/cetak_akun'); ?>" class="btn btn-light border btn-sm rounded-pill" target="_blank">
                <i class="bi bi-printer"></i> Cetak Akun Penguji
            </a>
            <button class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-person-plus"></i> Tambah User
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light small">
                    <tr>
                        <th class="ps-4">NAMA</th>
                        <th>USERNAME</th>
                        <th>INSTANSI</th>
                        <th class="text-center">ROLE</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $p): ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?= esc($p['nama_lengkap']) ?></td>
                            <td><code class="text-primary"><?= esc($p['username']) ?></code></td>
                            <td><?= esc($p['instansi'] ?: '-') ?></td>
                            <td class="text-center">
                                <span class="badge rounded-pill <?= $p['role'] === 'admin' ? 'text-bg-primary' : 'text-bg-success' ?>">
                                    <?= esc(ucfirst($p['role'])) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-light text-primary" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $p['id_user'] ?>" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <?php if ((int) $p['id_user'] !== (int) session()->get('id_user')): ?>
                                        <button onclick="confirmDelete('<?= base_url('penguji/delete/' . $p['id_user']) ?>')" class="btn btn-sm btn-light text-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('penguji/store') ?>" method="post" class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Role</label>
                    <select name="role" class="form-select">
                        <option value="penguji">Penguji</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Instansi <span class="text-muted fw-normal">(opsional)</span></label>
                    <input type="text" name="instansi" class="form-control">
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

<?php foreach ($users as $p): ?>
    <div class="modal fade" id="modalEdit<?= $p['id_user'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <form action="<?= base_url('penguji/update/' . $p['id_user']) ?>" method="post" class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Edit Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= esc($p['nama_lengkap']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= esc($p['username']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Role</label>
                        <select name="role" class="form-select">
                            <option value="penguji" <?= $p['role'] === 'penguji' ? 'selected' : '' ?>>Penguji</option>
                            <option value="admin" <?= $p['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Instansi <span class="text-muted fw-normal">(opsional)</span></label>
                        <input type="text" name="instansi" class="form-control" value="<?= esc($p['instansi']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Baru <span class="text-muted fw-normal">(kosongkan jika tidak ganti)</span></label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100">Update Akun</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>
<?= $this->endSection() ?>
