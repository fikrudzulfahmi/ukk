<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Tambah Pos Baru</h6>
            </div>
            <div class="card-body">
                <form action="<?= base_url('pos/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Pos <?= session()->get('active_event_id') ?></label>
                        <input type="text" name="nama_pos" class="form-control" placeholder="Contoh: Pos 1 - Engine" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Pos</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light small">
                        <tr>
                            <th class="ps-4">NAMA POS</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pos as $p): ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?= $p['nama_pos'] ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-light text-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditPos<?= $p['id_pos'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button onclick="confirmDelete('<?= base_url('pos/delete/' . $p['id_pos']) ?>')" class="btn btn-sm btn-light text-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php foreach ($pos as $p): ?>
    <div class="modal fade" id="modalEditPos<?= $p['id_pos'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="<?= base_url('pos/update/' . $p['id_pos']) ?>" method="post" class="modal-content">
                <?= csrf_field() ?>
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Edit Nama Pos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Pos</label>
                        <input type="text" name="nama_pos" class="form-control" value="<?= $p['nama_pos'] ?>" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Update Pos</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<?= $this->endSection() ?>