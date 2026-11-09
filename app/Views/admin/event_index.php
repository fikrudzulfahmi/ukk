<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between">
        <h5 class="fw-bold mb-0">Daftar Session UKK</h5>
        <button class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg"></i> Tambah Event
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light small">
                <tr>
                    <th class="ps-4">NAMA EVENT</th>
                    <th>KETUA</th>
                    <th>TAHUN PELAJARAN</th>
                    <th>TANGGAL MULAI</th>
                    <th>KAPASITAS SESI</th>
                    <th>STATUS</th>
                    <th class="text-center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $e): ?>
                    <tr>
                        <td class="ps-4 fw-bold"><?= $e['nama_event'] ?></td>
                        <td><?= $e['ketua'] ?></td>
                        <td><?= $e['tahun_pelajaran'] ?></td>
                        <td><?= date('d M Y', strtotime($e['tanggal_mulai'])) ?></td>
                        <td>
                            <span class="badge bg-info text-dark"><?= $e['kapasitas_sesi'] ?> Peserta/Sesi</span>
                        </td>
                        <td>
                            <?= $e['is_active'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Non-Aktif</span>' ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light text-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit<?= $e['id_event'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="<?= base_url('event/activate/' . $e['id_event']) ?>" class="btn btn-sm btn-light text-success"><i class="bi bi-play-fill"></i></a>
                                <button type="button"
                                    onclick="confirmDelete('<?= base_url('event/delete/' . $e['id_event']) ?>')"
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

<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('event/store') ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTambahLabel">Tambah Event Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Event</label>
                    <input type="text" name="nama_event" class="form-control" placeholder="Contoh: UKK Utama 2026" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ketua Event</label>
                    <input type="text" name="ketua" class="form-control" placeholder="Contoh: Bapak/Ibu X" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tahun Pelajaran</label>
                    <input type="text" name="tahun_pelajaran" class="form-control" placeholder="Contoh: 2025/2026" required>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kapasitas Sesi</label>
                        <input type="number" name="kapasitas_sesi" class="form-control" placeholder="Contoh: 2" value="2" min="1" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Metode Ujian Teori</label>
                    <select name="is_teori" class="form-select" required>
                        <option value="1">Aktif (Teori 30% : Praktek 70%)</option>
                        <option value="0">Non-Aktif (Murni Praktek 100%)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-primary">Simpan Event</button>
            </div>
        </form>
    </div>
</div>

<?php foreach ($events as $e): ?>
    <div class="modal fade" id="modalEdit<?= $e['id_event'] ?>" tabindex="-1" aria-labelledby="modalEditLabel<?= $e['id_event'] ?>" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= base_url('event/update/' . $e['id_event']) ?>" method="post" class="modal-content">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalEditLabel<?= $e['id_event'] ?>">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Event</label>
                        <input type="text" name="nama_event" class="form-control" value="<?= $e['nama_event'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ketua Event</label>
                        <input type="text" name="ketua" class="form-control" value="<?= $e['ketua'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun Pelajaran</label>
                        <input type="text" name="tahun_pelajaran" class="form-control" value="<?= $e['tahun_pelajaran'] ?>" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" value="<?= $e['tanggal_mulai'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kapasitas Sesi</label>
                            <input type="number" name="kapasitas_sesi" class="form-control" value="<?= $e['kapasitas_sesi'] ?>" min="1" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Metode Ujian Teori</label>
                        <select name="is_teori" class="form-select" required>
                            <option value="1" <?= $e['is_teori'] == 1 ? 'selected' : '' ?>>Aktif (Teori 30% : Praktek 70%)</option>
                            <option value="0" <?= $e['is_teori'] == 0 ? 'selected' : '' ?>>Non-Aktif (Murni Praktek 100%)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<?= $this->endSection() ?>