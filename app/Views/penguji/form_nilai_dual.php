<?= $this->extend('layout/penguji_layout') ?>

<?= $this->section('content') ?>
<style>
    body { background: var(--uk-canvas, #f8fafc); }

    .dual-head {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .85rem;
        border-radius: 18px; padding: 1.15rem 1.4rem; color: #fff;
        background:
            radial-gradient(120% 140% at 100% 0%, rgba(16, 185, 129, .4), transparent 55%),
            linear-gradient(135deg, #064e3b 0%, #065f46 55%, #059669 100%);
        box-shadow: 0 16px 36px -20px rgba(5, 150, 105, 0.7);
        margin-bottom: 1.25rem;
    }

    .dual-head__title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.15rem;
        font-weight: 800;
        letter-spacing: -.02em;
        margin: 0;
    }

    .dual-head__badge {
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .25);
        color: #fff;
        font-weight: 600;
        padding: .4rem .9rem;
        border-radius: 999px;
        font-size: .8rem;
    }

    .assessment-card {
        border-radius: 18px !important;
        border: 1px solid var(--uk-line) !important;
        box-shadow: var(--uk-shadow-sm) !important;
        overflow: hidden;
        background: #ffffff;
    }

    .table thead th {
        background: #064e3b !important;
        color: #fff;
        position: sticky;
        top: 0;
        z-index: 100;
        border: none;
        font-size: .82rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        padding-top: .9rem;
        padding-bottom: .9rem;
    }

    /* Segmented Control 4 Opsi */
    .rubrik-group {
        display: flex;
        background: #f1f5f9;
        padding: 3px;
        border-radius: 10px;
        width: 100%;
        min-width: 250px;
        gap: 3px;
        border: 1px solid var(--uk-line);
    }

    .rubrik-group .btn-check+.btn {
        flex: 1;
        font-size: 11px;
        padding: 6px 3px;
        border: none;
        border-radius: 7px;
        font-weight: 700;
        color: #64748b;
        background: transparent;
        transition: all .15s ease;
    }

    .rubrik-group .btn-check+.btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* Warna khusus sesuai level */
    .btn-check:checked+.btn-bk {
        background: #ef4444 !important;
        color: white !important;
        box-shadow: 0 4px 10px -2px rgba(239, 68, 68, 0.4);
    }

    .btn-check:checked+.btn-ck {
        background: #f59e0b !important;
        color: white !important;
        box-shadow: 0 4px 10px -2px rgba(245, 158, 11, 0.4);
    }

    .btn-check:checked+.btn-k {
        background: #059669 !important;
        color: white !important;
        box-shadow: 0 4px 10px -2px rgba(5, 150, 105, 0.4);
    }

    .btn-check:checked+.btn-sk {
        background: #0284c7 !important;
        color: white !important;
        box-shadow: 0 4px 10px -2px rgba(2, 132, 199, 0.4);
    }

    #save-status {
        position: fixed;
        bottom: 75px;
        right: 20px;
        z-index: 1050;
        display: none;
    }

    @media (max-width: 768px) {
        .rubrik-group {
            display: flex;
            flex-direction: column;
            width: 100%;
            min-width: 80px;
        }

        .rubrik-group .btn {
            border-radius: 0 !important;
            margin-bottom: -1px;
            padding: 10px 5px;
        }

        .rubrik-group .btn:first-child {
            border-radius: 7px 7px 0 0 !important;
        }

        .rubrik-group .btn:last-child {
            border-radius: 0 0 7px 7px !important;
        }

        td div.fw-bold {
            font-size: 11px !important;
            line-height: 1.25;
        }
    }
</style>

<div class="container-fluid py-3">
    <div class="dual-head">
        <div>
            <h3 class="dual-head__title"><i class="bi bi-ui-checks-grid me-2"></i> Input Nilai Praktik (Skala 4)</h3>
            <small class="opacity-80">
                <i class="bi bi-geo-alt"></i> <?= esc($pos['nama_pos']) ?>
                <span class="mx-1">•</span>
                <i class="bi bi-people"></i> Mode <?= count($siswa_list) ?> Peserta Serentak
            </small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="dual-head__badge"><i class="bi bi-lightning-charge me-1"></i> Auto-Save Aktif</span>
            <a href="<?= base_url('penilaian?date=' . $current_date) ?>" class="btn btn-sm btn-light rounded-pill px-3 fw-bold text-success">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div id="save-status" class="alert alert-dark shadow-lg py-2 px-4 rounded-pill border-0">
        <span class="spinner-border spinner-border-sm me-2 text-success"></span>
        <small class="fw-bold">Menyimpan nilai...</small>
    </div>

    <div class="card assessment-card shadow-sm overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" width="30%">Kriteria / Elemen Penilaian</th>
                        <?php foreach ($siswa_list as $s): ?>
                            <th class="text-center">
                                <div class="fw-bold"><?= esc($s['nama_siswa']) ?></div>
                                <div class="btn-group mt-1">
                                    <button type="button" class="btn btn-xs btn-light text-success border py-0 px-2 fill-all" data-siswa="<?= $s['id_siswa'] ?>" data-val="85" style="font-size: 10px; font-weight: 600;">Set All K (85)</button>
                                </div>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php foreach ($elemen as $e): ?>
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark" style="font-size: 13px;"><?= esc($e['nama_elemen']) ?></div>
                            </td>
                            <?php foreach ($siswa_list as $siswa): ?>
                                <td class="px-2">
                                    <?php
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
                            <div class="fw-bold text-success" style="font-size: 14px;">
                                <i class="bi bi-journal-text me-1"></i> Catatan Penilaian
                            </div>
                            <small class="text-muted" style="font-size: 11px;">Tambahkan catatan performa siswa pada POS ini (Opsional).</small>
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
                }, 400);
            }).fail(function() {
                alert('Gagal menyimpan data nilai!');
                $('#save-status').hide();
            });
        });

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
                }, 400);
            }).fail(function() {
                alert('Gagal menyimpan catatan!');
                $('#save-status').hide();
            });
        });

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
