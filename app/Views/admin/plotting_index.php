<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Tugaskan Penguji</h6>
            </div>
            <div class="card-body">
                <form action="<?= base_url('plotting/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Penguji</label>
                        <select name="id_user" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach ($penguji as $p): ?>
                                <option value="<?= $p['id_user'] ?>"><?= $p['nama_lengkap'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Pos Ujian</label>
                        <select name="id_pos" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <?php foreach ($pos as $ps): ?>
                                <option value="<?= $ps['id_pos'] ?>"><?= $ps['nama_pos'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Penugasan</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Penugasan</button>
                </form>
            </div>
        </div>
        <div class="card border-0 shadow-sm mt-3" style="border-radius: 16px; background: #ffffff;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="bg-success-subtle p-2 rounded-3 text-success">
                            <i class="bi bi-printer-fill fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-bold text-dark mb-0">Jadwal Penguji</h6>
                        <small class="text-muted">Cetak jadwal tugas untuk semua penguji</small>
                    </div>
                </div>

                <div class="alert alert-success border-0 pb-0" style="background: #ecfdf5; border-radius: 10px;">
                    <p class="small text-success">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Sistem akan membuat file PDF dengan <strong>Auto Page Break</strong> (satu penguji satu halaman).
                    </p>
                </div>

                <div class="d-grid gap-2">
                    <a href="<?= base_url('plotting/cetak-semua-jadwal-penguji') ?>" target="_blank" class="btn btn-primary fw-bold py-2">
                        <i class="bi bi-file-earmark-pdf-fill me-2"></i> Cetak Kolektif Semua Penguji
                    </a>

                    <button type="button" class="btn btn-outline-secondary btn-sm border-0 mt-1" disabled>
                        <i class="bi bi-check2-circle me-1"></i>
                        Total Penguji Terplotting: <?= $total_penguji ?> Orang
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
    $hari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
    $bulan = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
    ?>

    <div class="col-md-8">
        <div class="accordion shadow-sm" id="accordionPlotting">
            <?php if (empty($plotting)): ?>
                <div class="card border-0 p-5 text-center">
                    <i class="bi bi-person-badge fs-1 text-muted"></i>
                    <p class="text-muted mt-2">Belum ada plotting penguji.</p>
                </div>
            <?php else: ?>
                <?php
                $idx = 0;
                foreach ($plotting as $tgl => $listPenguji):
                    $idx++;
                    $time = strtotime($tgl);
                    $tgl_id = date('Ymd', $time);
                    $tgl_indo = $hari[date('l', $time)] . ', ' . date('d', $time) . ' ' . $bulan[date('m', $time)] . ' ' . date('Y', $time);
                ?>
                    <div class="accordion-item border-0 mb-3 shadow-xs" style="border-radius: 12px; overflow: hidden;">
                        <h2 class="accordion-header d-flex align-items-center bg-white border-bottom">
                            <button class="accordion-button <?= ($idx > 1) ? 'collapsed' : '' ?> fw-bold flex-grow-1"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $tgl_id ?>"
                                style="box-shadow: none;">
                                <span><i class="bi bi-calendar-check-fill text-success me-2"></i> <?= $tgl_indo ?></span>
                            </button>

                            <div class="pe-3 d-flex align-items-center bg-white">
                                <a href="<?= base_url('plotting/cetak-presensi/' . $tgl) ?>"
                                    target="_blank"
                                    class="badge bg-soft-danger text-danger border border-danger px-2 me-2"
                                    style="font-size: 11px;">
                                    <i class="bi bi-printer me-1"></i> Daftar Hadir
                                </a>
                                <span class="badge bg-soft-success text-success border border-success px-3" style="font-size: 11px;">
                                    <?= count($listPenguji) ?> Penguji
                                </span>
                            </div>
                        </h2>
                        <div id="collapse<?= $tgl_id ?>" class="accordion-collapse collapse <?= ($idx == 1) ? 'show' : '' ?>" data-bs-parent="#accordionPlotting">
                            <div class="accordion-body p-0">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light small text-muted">
                                        <tr>
                                            <th class="ps-4">NAMA PENGUJI</th>
                                            <th>POS TUGAS</th>
                                            <th class="text-center" width="100px">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($listPenguji as $pt): ?>
                                            <tr>
                                                <td class="ps-4 fw-bold"><?= $pt['nama_lengkap'] ?></td>
                                                <td><span class="badge bg-info-light text-info border border-info px-2"><?= $pt['nama_pos'] ?></span></td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-sm btn-link text-primary p-0 me-2 btn-edit-plot"
                                                        data-id="<?= $pt['id_event_penguji'] ?>"
                                                        data-user="<?= $pt['id_user'] ?>"
                                                        data-pos="<?= $pt['id_pos'] ?>"
                                                        data-tanggal="<?= $pt['tanggal'] ?>">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <button onclick="confirmDelete('<?= base_url('plotting/delete/' . $pt['id_event_penguji']) ?>')"
                                                        class="btn btn-sm btn-link text-danger p-0">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>



    <style>
        .bg-soft-success {
            background-color: #f0fff4;
        }

        .bg-info-light {
            background-color: #e0f7fa;
        }

        .shadow-xs {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .accordion-button:not(.collapsed) {
            background-color: #f8f9fa;
            color: #198754;
            box-shadow: none;
        }

        .accordion-button::after {
            margin-left: 10px;
            /* Memberi jarak antara badge dan panah */
        }

        .accordion-header {
            background-color: white;
        }

        /* Menghilangkan background biru saat tombol diklik */
        .accordion-button:not(.collapsed) {
            background-color: white !important;
            color: #198754 !important;
        }
    </style>
</div>

<div class="modal fade" id="modalEditPlot" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEditPlot" method="post" class="modal-content">
            <?= csrf_field() ?>

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Edit Penugasan Penguji</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label small fw-bold">Penguji</label>
                    <select name="id_user" id="edit_user" class="form-select" required>
                        <?php foreach ($penguji as $p): ?>
                            <option value="<?= $p['id_user'] ?>">
                                <?= $p['nama_lengkap'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Pos Ujian</label>
                    <select name="id_pos" id="edit_pos" class="form-select" required>
                        <?php foreach ($pos as $ps): ?>
                            <option value="<?= $ps['id_pos'] ?>">
                                <?= $ps['nama_pos'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Tanggal Penugasan</label>
                    <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Update Plotting</button>
                </div>

            </div>
        </form>
    </div>
</div>
<script>
    document.querySelectorAll('.btn-edit-plot').forEach(btn => {
        btn.addEventListener('click', function() {

            let id = this.dataset.id;
            let user = this.dataset.user;
            let pos = this.dataset.pos;
            let tanggal = this.dataset.tanggal;

            document.getElementById('edit_user').value = user;
            document.getElementById('edit_pos').value = pos;
            document.getElementById('edit_tanggal').value = tanggal;

            document.getElementById('formEditPlot').action =
                "<?= base_url('plotting/update/') ?>" + id;

            new bootstrap.Modal(document.getElementById('modalEditPlot')).show();
        });
    });
</script>

<?= $this->endSection() ?>