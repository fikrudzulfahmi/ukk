<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
// Array Helper untuk konversi hari dan bulan ke Indonesia
$hari = [
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
];
$bulan = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
];
?>

<style>
    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: #0d6efd;
        box-shadow: none;
    }

    .accordion-button::after {
        background-size: 1rem;
    }

    .shadow-xs {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
    }

    /* Membuat tabel di dalam accordion terlihat seamless */
    .accordion-item {
        border: 1px solid #eee !important;
    }
</style>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-magic me-2"></i>Generate Jadwal Rolling</h6>
            </div>
            <div class="card-body">
                <form action="<?= base_url('jadwal/store') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted d-flex justify-content-between">
                            PILIH SISWA (MAKS 10)
                            <span class="badge rounded-pill bg-light text-primary border border-primary" id="counter-info">
                                Terpilih: <span id="jumlah-terpilih">0</span>/10
                            </span>
                        </label>

                        <input type="text" id="searchSiswa" class="form-control form-control-sm mb-2" placeholder="Cari nama atau kelas...">

                        <div class="border rounded bg-light p-2" style="max-height: 300px; overflow-y: auto;" id="listSiswa">
                            <?php if (empty($siswa)): ?>
                                <div class="text-center py-3 text-muted small italic">Semua siswa sudah dijadwalkan.</div>
                            <?php endif; ?>
                            <?php foreach ($siswa as $s): ?>
                                <div class="form-check list-item-siswa p-2 rounded mb-1 bg-white border-bottom shadow-xs">
                                    <input class="form-check-input siswa-checkbox ms-1" type="checkbox" name="id_siswa[]" value="<?= $s['id_siswa'] ?>" id="s<?= $s['id_siswa'] ?>">
                                    <label class="form-check-label ms-2 d-block" for="s<?= $s['id_siswa'] ?>">
                                        <div class="fw-bold text-dark small mb-0"><?= esc(strtoupper($s['nama_siswa'])) ?></div>
                                        <div class="text-muted" style="font-size: 10px;"><?= $s['kelas'] ?></div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">TANGGAL PELAKSANAAN</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-event"></i></span>
                            <input type="date" name="tanggal_ujian" class="form-control bg-light border-start-0" required>
                        </div>
                    </div>

                    <button type="submit" id="btn-submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm" disabled>
                        <i class="bi bi-arrow-repeat me-1"></i> Generate Jadwal Rolling
                    </button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 15px; background: #f8f9fa;">
            <div class="card-body p-4 text-center">
                <h6 class="fw-bold text-dark mb-3">Cetak Jadwal & Presensi</h6>
                <div class="d-grid gap-2">

                    <a href="<?= base_url('jadwal/cetak_jadwal_kolektif') ?>" target="_blank" class="btn btn-outline-primary border-2 fw-bold">
                        <i class="bi bi-printer-fill me-2"></i> Cetak Jadwal Kolektif
                    </a>

                    <hr class="my-2">

                    <form action="<?= base_url('jadwal/cetak_presensi') ?>" method="get" target="_blank">
                        <label class="small text-muted mb-1 d-block">Pilih Tanggal Presensi:</label>
                        <div class="input-group mb-2">
                            <input type="date" name="tanggal" class="form-control form-control-sm" required>
                            <button type="submit" class="btn btn-danger btn-sm px-3">
                                <i class="bi bi-file-pdf"></i> Cetak
                            </button>
                        </div>
                    </form>

                    <hr class="my-2">

                    <form action="<?= base_url('jadwal/cetak_berita_acara') ?>" method="get" target="_blank">
                        <label class="small text-muted mb-1 d-block">Pilih Tanggal Berita Acara:</label>
                        <div class="input-group mb-2">
                            <input type="date" name="tanggal" class="form-control form-control-sm" required>
                            <button type="submit" class="btn btn-dark btn-sm px-3">
                                <i class="bi bi-file-earmark-pdf"></i> Cetak BA
                            </button>
                        </div>
                    </form>

                    <hr class="my-2">

                    <div class="text-start">
                        <label class="small text-muted mb-1 d-block text-center">Kartu Peserta (Massal):</label>
                        <a href="<?= base_url('jadwal/cetak_kartu_massal') ?>" target="_blank" class="btn btn-success btn-sm d-block fw-bold">
                            <i class="bi bi-person-badge-fill me-2"></i> Cetak Kartu Peserta
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-table me-2"></i>Data Jadwal Rolling</h6>
                <span class="badge bg-light text-dark border">Total: <?= count($jadwal) ?> Baris</span>
            </div>
            <div class="card-body p-0">
                <div class="accordion shadow-sm" id="accordionJadwal">
                    <?php if (empty($jadwal)): ?>
                        <div class="card border-0 shadow-sm p-5 text-center">
                            <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                            <h6 class="text-muted">Belum ada jadwal yang di-generate untuk event ini.</h6>
                        </div>
                    <?php else: ?>
                        <?php
                        $idx = 0;
                        foreach ($jadwal as $tgl => $grupSiswa):
                            $idx++;
                            $time = strtotime($tgl);
                            // Mengambil nama hari dan bulan berdasarkan array helper di atas
                            $nama_hari = $hari[date('l', $time)];
                            $nama_bulan = $bulan[date('m', $time)];
                            $tgl_indo = $nama_hari . ', ' . date('d', $time) . ' ' . $nama_bulan . ' ' . date('Y', $time);
                            $tgl_id = date('Ymd', $time);
                        ?>
                            <div class="accordion-item border-0 mb-3 shadow-xs" style="border-radius: 12px; overflow: hidden;">
                                <h2 class="accordion-header">
                                    <button class="accordion-button <?= ($idx > 1) ? 'collapsed' : '' ?> bg-white fw-bold"
                                        type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $tgl_id ?>">
                                        <div class="d-flex justify-content-between w-100 align-items-center">
                                            <span>
                                                <i class="bi bi-calendar2-check-fill text-primary me-2"></i>
                                                <?= $tgl_indo ?>
                                            </span>
                                            <span class="badge bg-soft-primary text-primary border-primary border px-3 me-3" style="font-size: 11px;">
                                                <?= count($grupSiswa) ?> Peserta
                                            </span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse<?= $tgl_id ?>" class="accordion-collapse collapse <?= ($idx == 1) ? 'show' : '' ?>" data-bs-parent="#accordionJadwal">
                                    <div class="accordion-body p-0 bg-white">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="bg-light small text-muted">
                                                    <tr>
                                                        <th class="ps-4">PESERTA</th>
                                                        <th class="text-center">ALUR ROLLING (URUTAN POS)</th>
                                                        <th class="text-center" width="100px">AKSI</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($grupSiswa as $idSiswa => $dataSiswa): ?>
                                                        <tr>
                                                            <td class="ps-4">
                                                                <div class="fw-bold text-dark"><?= esc(strtoupper($dataSiswa['nama'])) ?></div>
                                                                <div class="text-muted small" style="font-size: 11px;"><?= $dataSiswa['kelas'] ?></div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                                                    <?php foreach ($dataSiswa['detail_pos'] as $p): ?>
                                                                        <div class="position-relative text-center border rounded bg-light p-1 shadow-sm" style="min-width: 90px;">
                                                                            <a href="javascript:void(0)"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#modalEditJadwal<?= $p['id_jadwal'] ?>"
                                                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary border border-light"
                                                                                style="font-size: 7px; padding: 2px 4px;">
                                                                                <i class="bi bi-pencil"></i>
                                                                            </a>
                                                                            <div class="fw-bold text-primary" style="font-size: 9px;">URUTAN <?= $p['urutan'] ?></div>
                                                                            <div class="text-dark fw-semibold" style="font-size: 10px;"><?= $p['nama_pos'] ?></div>

                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <button onclick="confirmDelete('<?= base_url('jadwal/delete_group/' . $idSiswa . '/' . $tgl) ?>')"
                                                                    class="btn btn-sm btn-outline-danger border-0">
                                                                    <i class="bi bi-person-x-fill"></i>
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
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <style>
                    .bg-soft-primary {
                        background-color: #eef2ff;
                    }

                    .shadow-xs {
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                    }

                    .accordion-button:focus {
                        border-color: transparent;
                        box-shadow: none;
                    }

                    .accordion-button:not(.collapsed) {
                        color: #0d6efd;
                        background-color: #f8f9fa;
                    }
                </style>
            </div>
        </div>
    </div>
</div>

<?php foreach ($rawJadwal as $j): ?>
    <div class="modal fade" id="modalEditJadwal<?= $j['id_jadwal'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <form action="<?= base_url('jadwal/update/' . $j['id_jadwal']) ?>" method="post" class="modal-content">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Edit Detail Jadwal</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="small fw-bold">Siswa: <?= $j['nama_siswa'] ?></label>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control" value="<?= $j['jam_mulai'] ?>" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control" value="<?= $j['jam_selesai'] ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Ubah Pos</label>
                        <select name="id_pos" class="form-select">
                            <?php foreach ($pos as $ps): ?>
                                <option value="<?= $ps['id_pos'] ?>" <?= ($ps['id_pos'] == $j['id_pos'] ? 'selected' : '') ?>>
                                    <?= $ps['nama_pos'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.siswa-checkbox');
        const displayCount = document.getElementById('jumlah-terpilih');
        const btnSubmit = document.getElementById('btn-submit');
        const searchInput = document.getElementById('searchSiswa');
        const limit = 10;

        // Fitur Search di List Siswa
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            const items = document.querySelectorAll('.list-item-siswa');
            items.forEach(item => {
                const text = item.innerText.toLowerCase();
                item.style.display = text.includes(filter) ? "" : "none";
            });
        });

        // Validasi Checkbox
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const count = document.querySelectorAll('.siswa-checkbox:checked').length;
                displayCount.innerText = count;

                if (count > 0 && count <= limit) {
                    btnSubmit.disabled = false;
                } else {
                    btnSubmit.disabled = true;
                }

                if (count > limit) {
                    alert('Maksimal 10 siswa!');
                    this.checked = false;
                    displayCount.innerText = limit;
                }

                // Efek visual jika terpilih
                if (this.checked) {
                    this.parentElement.classList.add('bg-primary', 'bg-opacity-10');
                } else {
                    this.parentElement.classList.remove('bg-primary', 'bg-opacity-10');
                }
            });
        });
    });
</script>

<style>
    #listSiswa::-webkit-scrollbar {
        width: 5px;
    }

    #listSiswa::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 10px;
    }

    .shadow-xs {
        shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
    }

    .btn-white {
        background: #fff;
        border: 1px solid #dee2e6;
    }

    .btn-white:hover {
        background: #f8f9fa;
    }
</style>
<?= $this->endSection() ?>