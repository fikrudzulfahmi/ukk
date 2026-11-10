<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h5 class="fw-bold mb-0 text-dark" style="letter-spacing:-.02em;">
            <i class="bi bi-geo-alt-fill me-2 text-primary"></i>Master Pos UKK
        </h5>
        <small class="text-muted">
            <i class="bi bi-calendar-event me-1"></i><?= esc(session()->get('nama_event') ?? 'Event Aktif') ?>
            &middot; <?= count($pos) ?> pos
        </small>
    </div>
    <button class="btn btn-sm fw-bold px-3 rounded-3" data-bs-toggle="modal" data-bs-target="#modalClonePos"
        style="background:#ecfdf5; color:#047857; border:1px solid #a7f3d0;">
        <i class="bi bi-files me-1"></i> Clone dari Event Lain
    </button>
</div>

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
                                    <button type="button" class="btn btn-sm btn-light text-danger js-hapus-pos"
                                        data-nama="<?= esc($p['nama_pos']) ?>"
                                        data-url="<?= base_url('pos/delete/' . $p['id_pos']) ?>">
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

<!-- ================================ -->
<!-- MODAL CLONE POS                  -->
<!-- ================================ -->
<div class="modal fade" id="modalClonePos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= base_url('pos/clone') ?>" method="POST" class="modal-content border-0 shadow" id="formClonePos">
            <?= csrf_field() ?>
            <div class="modal-header border-0" style="background:#ecfdf5;">
                <h6 class="modal-title fw-bold text-success">
                    <i class="bi bi-files me-2"></i>Clone POS dari Event Lain
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted">
                    Seluruh POS dari event yang dipilih akan disalin ke event aktif
                    (<strong><?= esc(session()->get('nama_event') ?? '-') ?></strong>).
                    POS dengan nama sama tidak akan diduplikasi.
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
                                    (<?= (int) ($pos_counts[$ev['id_event']] ?? 0) ?> pos)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="alert alert-light border small mb-0">
                        <i class="bi bi-info-circle me-1 text-success"></i>
                        Hanya daftar POS yang disalin. Kriteria penilaian &amp; plotting penguji tetap terpisah.
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
    $(function () {
        // Konfirmasi clone
        const formClonePos = document.getElementById('formClonePos');
        if (formClonePos) {
            formClonePos.addEventListener('submit', async function (e) {
                const select = this.querySelector('select[name="id_event_sumber"]');
                if (!select || !select.value) return;

                e.preventDefault();
                const evName = select.options[select.selectedIndex].text.trim();
                const ok = await ukConfirm({
                    title: 'Clone POS?',
                    html: `Daftar POS dari <strong>${evName}</strong> akan disalin ke event aktif.`,
                    confirmButtonText: 'Ya, Clone'
                });
                if (ok) this.submit();
            });
        }

        // Hapus POS — konfirmasi modal sendiri
        $(document).on('click', '.js-hapus-pos', async function () {
            const url  = $(this).data('url');
            const nama = $(this).data('nama') || 'POS ini';
            const ok = await ukConfirm({
                title: 'Hapus ' + nama + '?',
                text: 'Pos akan dihapus beserta jadwal, kriteria penilaian, dan plotting penguji yang terkait.',
                icon: 'warning',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus'
            });
            if (ok) window.location.href = url;
        });
    });
</script>

<?= $this->endSection() ?>