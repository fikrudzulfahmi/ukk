<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Daftar Item Penilaian per Pos</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahElemen">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Item
                </button>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle">
                    <thead class="bg-light small text-muted">
                        <tr>
                            <th class="ps-4">POS</th>
                            <th>NAMA ELEMEN / BUTIR PENILAIAN</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $currentPos = null;
                        foreach ($elemen as $el):
                        ?>

                            <?php if ($currentPos != $el['nama_pos']): ?>
                                <tr class="table-light">
                                    <td colspan="3" class="fw-bold text-primary ps-4">
                                        <?= $el['nama_pos'] ?>
                                    </td>
                                </tr>
                                <?php $currentPos = $el['nama_pos']; ?>
                            <?php endif; ?>

                            <tr>
                                <td></td>
                                <td class="fw-semibold"><?= $el['nama_elemen'] ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light text-primary btn-edit"
                                            data-id="<?= $el['id_elemen'] ?>"
                                            data-nama="<?= $el['nama_elemen'] ?>"
                                            data-pos="<?= $el['id_pos'] ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button onclick="confirmDelete('<?= base_url('elemen/delete/' . $el['id_elemen']) ?>')"
                                            class="btn btn-sm btn-light text-danger">
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
                            <option value="<?= $p['id_pos'] ?>"><?= $p['nama_pos'] ?></option>
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

                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow()">
                    + Tambah Baris
                </button>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-primary w-100">Simpan Elemen</button>
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
                                <?= $p['nama_pos'] ?>
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
</script>


<?= $this->endSection() ?>