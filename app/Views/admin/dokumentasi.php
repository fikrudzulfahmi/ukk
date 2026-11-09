<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-middle">
        <h5 class="fw-bold mb-0">Dokumentasi Kegiatan UKK</h5>
        <div>
            <a href="<?= base_url('dokumentasi/cetak') ?>" class="btn btn-danger btn-sm rounded-pill me-2" target="_blank">
                <i class="bi bi-file-pdf"></i> Cetak PDF
            </a>
            <button class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-cloud-arrow-up"></i> Unggah Dokumentasi
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light small">
                    <tr>
                        <th class="ps-4" width="200">PREVIEW</th>
                        <th>JUDUL KEGIATAN</th>
                        <th>NAMA FILE</th>
                        <th>TANGGAL UNGGAH</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dokumentasi)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-image-alt d-block mb-2" style="font-size: 2rem;"></i>
                                Belum ada dokumentasi yang diunggah.
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($dokumentasi as $d): ?>
                        <tr>
                            <td class="ps-4">
                                <img src="<?= base_url('uploads/dokumentasi/' . $d['nama_file']) ?>"
                                    class="rounded shadow-sm shadow-sm"
                                    style="width: 150px; height: 90px; object-fit: cover;"
                                    alt="<?= $d['judul_foto'] ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDetail<?= $d['id_dokumentasi'] ?>"
                                    role="button">
                            </td>
                            <td class="fw-bold"><?= $d['judul_foto'] ?></td>
                            <td><small class="text-muted"><?= $d['nama_file'] ?></small></td>
                            <td><?= date('d M Y, H:i', strtotime($d['created_at'])) ?></td>
                            <td class="text-center">
                                <button onclick="confirmDelete('<?= base_url('dokumentasi/hapus/' . $d['id_dokumentasi']) ?>')" class="btn btn-sm btn-light text-danger">
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

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('dokumentasi/upload') ?>" method="post" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Unggah Dokumentasi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Judul Kegiatan / Foto</label>
                    <input type="text" name="judul" class="form-control" placeholder="Contoh: Pembukaan UKK Hari 1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Pilih File Gambar (JPG/PNG)</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" required>
                    <div class="form-text mt-1 small">Maksimal ukuran file 2MB</div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-primary w-100">Simpan Dokumentasi</button>
            </div>
        </form>
    </div>
</div>

<?php foreach ($dokumentasi as $d): ?>
    <div class="modal fade" id="modalDetail<?= $d['id_dokumentasi'] ?>" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0 p-0 mb-2">
                    <h5 class="modal-title text-white"><?= $d['judul_foto'] ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0 text-center">
                    <img src="<?= base_url('uploads/dokumentasi/' . $d['nama_file']) ?>" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?= $this->endSection() ?>