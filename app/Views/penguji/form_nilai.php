<?= $this->extend('layout/penguji_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <a href="<?= base_url('dashboard-penguji') ?>" class="btn btn-sm btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Antrian
            </a>
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <h5 class="mb-0">Penilaian Siswa: <strong><?= $siswa['nama_siswa'] ?></strong></h5>
                    <small>NISN: <?= $siswa['nisn'] ?></small>
                </div>
            </div>
        </div>
    </div>

    <form action="<?= base_url('penilaian/simpan') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id_siswa" value="<?= $siswa['id_siswa'] ?>">

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Item Penilaian (Elemen)</th>
                            <th class="text-center" width="300px">Status Kompetensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($elemen as $e): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold"><?= $e['nama_elemen'] ?></div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <input type="radio" class="btn-check" name="is_kompeten[<?= $e['id_elemen'] ?>]"
                                            id="bk_<?= $e['id_elemen'] ?>" value="0" required>
                                        <label class="btn btn-outline-danger btn-sm px-3" for="bk_<?= $e['id_elemen'] ?>">
                                            <i class="bi bi-x-circle me-1"></i> Belum Kompeten
                                        </label>

                                        <input type="radio" class="btn-check" name="is_kompeten[<?= $e['id_elemen'] ?>]"
                                            id="k_<?= $e['id_elemen'] ?>" value="1" required>
                                        <label class="btn btn-outline-success btn-sm px-3" for="k_<?= $e['id_elemen'] ?>">
                                            <i class="bi bi-check-circle me-1"></i> Kompeten
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white p-4 text-end">
                <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm">
                    <i class="bi bi-check-circle me-2"></i> Simpan Semua Nilai
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>