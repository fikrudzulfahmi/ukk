<?= $this->extend('layout/penguji_layout') ?>

<?= $this->section('content') ?>
<style>
    .assessment-card {
        border-radius: 15px;
        border: none;
    }

    .table thead th {
        background-color: #212529;
        color: #fff;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    /* Segmented Control 4 Opsi */
    .rubrik-group {
        display: flex;
        background: #eee;
        padding: 2px;
        border-radius: 8px;
        width: 100%;
        min-width: 240px;
    }

    .rubrik-group .btn-check+.btn {
        flex: 1;
        font-size: 11px;
        padding: 6px 2px;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        color: #666;
    }

    /* Warna khusus sesuai level */
    .btn-check:checked+.btn-bk {
        background: #dc3545 !important;
        color: white !important;
    }

    .btn-check:checked+.btn-ck {
        background: #fd7e14 !important;
        color: white !important;
    }

    .btn-check:checked+.btn-k {
        background: #198754 !important;
        color: white !important;
    }

    .btn-check:checked+.btn-sk {
        background: #0d6efd !important;
        color: white !important;
    }

    #save-status {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1050;
        display: none;
    }

    @media (max-width: 768px) {

        /* Mengubah grup tombol menjadi vertikal */
        .rubrik-group {
            display: flex;
            flex-direction: column;
            width: 100%;
            min-width: 80px;
            /* Ukuran lebar minimal tombol di HP */
        }

        /* Menghilangkan radius sudut di tengah agar tampak menyatu */
        .rubrik-group .btn {
            border-radius: 0 !important;
            margin-bottom: -1px;
            /* Menghilangkan double border */
            padding: 10px 5px;
            /* Lebih tinggi agar mudah ditekan */
        }

        /* Memberi radius hanya pada atas dan bawah saja */
        .rubrik-group .btn:first-child {
            border-radius: 8px 8px 0 0 !important;
        }

        .rubrik-group .btn:last-child {
            border-radius: 0 0 8px 8px !important;
        }

        /* Mengecilkan teks elemen agar tabel tidak terlalu lebar */
        td div.fw-bold {
            font-size: 11px !important;
            line-height: 1.2;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Input Nilai Praktek (Skala 4)</h3>
            <p class="text-muted mb-0"><?= $pos['nama_pos'] ?> | Mode Penilaian <?= count($siswa_list) ?> Peserta</p>
        </div>
        <a href="<?= base_url('penilaian?date=' . $current_date) ?>"
            class="btn btn-outline-dark rounded-pill px-4 fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div id="save-status" class="alert alert-dark shadow-lg py-2 px-4 rounded-pill border-0">
        <span class="spinner-border spinner-border-sm me-2 text-primary"></span>
        <small class="fw-bold">Menyimpan...</small>
    </div>

    <div class="card assessment-card shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" width="30%">Kriteria / Elemen</th>
                        <?php foreach ($siswa_list as $s): ?>
                            <th class="text-center">
                                <div class="fw-bold"><?= $s['nama_siswa'] ?></div>
                                <div class="btn-group mt-1">
                                    <button type="button" class="btn btn-xs btn-light border py-0 px-2 fill-all" data-siswa="<?= $s['id_siswa'] ?>" data-val="85" style="font-size: 10px;">Set All K (85)</button>
                                </div>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php foreach ($elemen as $e): ?>
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="fw-bold" style="font-size: 13px;"><?= $e['nama_elemen'] ?></div>
                            </td>
                            <?php foreach ($siswa_list as $siswa): ?>
                                <td class="px-2">
                                    <?php
                                    // Ambil nilai dari database
                                    $val = $existing[$siswa['id_siswa']][$e['id_elemen']] ?? null;
                                    ?>
                                    <div class="rubrik-group shadow-sm mx-auto">

                                        <input type="radio" class="btn-check btn-autosave"
                                            name="n_<?= $siswa['id_siswa'] ?>_<?= $e['id_elemen'] ?>"
                                            id="bk_<?= $siswa['id_siswa'] ?>_<?= $e['id_elemen'] ?>"
                                            value="25" data-siswa="<?= $siswa['id_siswa'] ?>" data-elemen="<?= $e['id_elemen'] ?>"
                                            <?= ($val == 25) ? 'checked' : '' ?>>
                                        <label class="btn <?= ($val == 25) ? 'btn-danger text-white' : 'btn-outline-light text-dark' ?> btn-bk"
                                            for="bk_<?= $siswa['id_siswa'] ?>_<?= $e['id_elemen'] ?>">BK<br>25</label>

                                        <input type="radio" class="btn-check btn-autosave"
                                            name="n_<?= $siswa['id_siswa'] ?>_<?= $e['id_elemen'] ?>"
                                            id="ck_<?= $siswa['id_siswa'] ?>_<?= $e['id_elemen'] ?>"
                                            value="70" data-siswa="<?= $siswa['id_siswa'] ?>" data-elemen="<?= $e['id_elemen'] ?>"
                                            <?= ($val == 70) ? 'checked' : '' ?>>
                                        <label class="btn <?= ($val == 70) ? 'btn-warning text-white' : 'btn-outline-light text-dark' ?> btn-ck"
                                            for="ck_<?= $siswa['id_siswa'] ?>_<?= $e['id_elemen'] ?>">CK<br>70</label>

                                        <input type="radio" class="btn-check btn-autosave"
                                            name="n_<?= $siswa['id_siswa'] ?>_<?= $e['id_elemen'] ?>"
                                            id="k_<?= $siswa['id_siswa'] ?>_<?= $e['id_elemen'] ?>"
                                            value="85" data-siswa="<?= $siswa['id_siswa'] ?>" data-elemen="<?= $e['id_elemen'] ?>"
                                            <?= ($val == 85) ? 'checked' : '' ?>>
                                        <label class="btn <?= ($val == 85) ? 'btn-success text-white' : 'btn-outline-light text-dark' ?> btn-k"
                                            for="k_<?= $siswa['id_siswa'] ?>_<?= $e['id_elemen'] ?>">K<br>85</label>

                                        <input type="radio" class="btn-check btn-autosave"
                                            name="n_<?= $siswa['id_siswa'] ?>_<?= $e['id_elemen'] ?>"
                                            id="sk_<?= $siswa['id_siswa'] ?>_<?= $e['id_elemen'] ?>"
                                            value="100" data-siswa="<?= $siswa['id_siswa'] ?>" data-elemen="<?= $e['id_elemen'] ?>"
                                            <?= ($val == 100) ? 'checked' : '' ?>>
                                        <label class="btn <?= ($val == 100) ? 'btn-primary text-white' : 'btn-outline-light text-dark' ?> btn-sk"
                                            for="sk_<?= $siswa['id_siswa'] ?>_<?= $e['id_elemen'] ?>">SK<br>100</label>

                                    </div>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>

                    <tr class="bg-light border-top border-2">
                        <td class="ps-4 py-4 align-top">
                            <div class="fw-bold text-primary" style="font-size: 14px;">
                                <i class="bi bi-journal-text me-1"></i> Catatan Penilaian
                            </div>
                            <small class="text-muted" style="font-size: 11px;">Tambahkan catatan umum untuk performa siswa pada modul/POS ini (Opsional).</small>
                        </td>
                        <?php foreach ($siswa_list as $siswa): ?>
                            <td class="px-2 py-3">
                                <?php $cat = $catatan_siswa[$siswa['id_siswa']] ?? ''; ?>
                                <textarea class="form-control form-control-sm input-catatan shadow-sm"
                                    rows="3"
                                    placeholder="Ketik catatan di sini..."
                                    data-siswa="<?= $siswa['id_siswa'] ?>"
                                    data-pos="<?= $id_pos ?>"
                                    style="font-size: 12px; resize: none;"><?= esc($cat) ?></textarea>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Fungsi pembantu untuk mengupdate tampilan visual radio button
        function updateVisual(inputElement) {
            let parent = $(inputElement).closest('.rubrik-group');

            parent.find('label').each(function() {
                $(this).removeClass('btn-danger btn-warning btn-success btn-primary text-white');
                $(this).addClass('btn-outline-light text-dark');
            });

            let checkedInput = parent.find('input:checked');
            let label = checkedInput.next('label');

            if (label.hasClass('btn-bk')) label.addClass('btn-danger text-white').removeClass('btn-outline-light text-dark');
            if (label.hasClass('btn-ck')) label.addClass('btn-warning text-white').removeClass('btn-outline-light text-dark');
            if (label.hasClass('btn-k')) label.addClass('btn-success text-white').removeClass('btn-outline-light text-dark');
            if (label.hasClass('btn-sk')) label.addClass('btn-primary text-white').removeClass('btn-outline-light text-dark');
        }

        // Event listener untuk menyimpan Nilai (Skor)
        $(document).on('change', '.btn-autosave', function() {
            updateVisual(this);

            const data = {
                id_siswa: $(this).data('siswa'),
                id_elemen: $(this).data('elemen'),
                is_kompeten: $(this).val(),
                "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
            };

            $('#save-status').show();
            $.post("<?= base_url('penilaian/simpan_single') ?>", data, function() {
                setTimeout(() => {
                    $('#save-status').fadeOut();
                }, 500);
            }).fail(function() {
                alert('Gagal menyimpan data nilai!');
                $('#save-status').hide();
            });
        });

        // SKRIP BARU: Event listener untuk auto-save Catatan Umum saat user selesai mengetik (blur)
        $(document).on('blur', '.input-catatan', function() {
            const data = {
                id_siswa: $(this).data('siswa'),
                id_pos: $(this).data('pos'),
                catatan: $(this).val(),
                "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
            };

            $('#save-status').show();
            $.post("<?= base_url('penilaian/simpan_catatan') ?>", data, function() {
                setTimeout(() => {
                    $('#save-status').fadeOut();
                }, 500);
            }).fail(function() {
                alert('Gagal menyimpan catatan!');
                $('#save-status').hide();
            });
        });

        // Perbaikan untuk 'Set All' agar tidak lag (trigger change secukupnya)
        $('.fill-all').on('click', function() {
            const idSiswa = $(this).data('siswa');
            const val = $(this).data('val');

            if (confirm('Set semua elemen siswa ini menjadi Kompeten (85)?')) {
                $(`input[name^="n_${idSiswa}_"][value="${val}"]`).each(function() {
                    if (!$(this).is(':checked')) {
                        $(this).prop('checked', true);
                        updateVisual(this);
                        $(this).trigger('change');
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>