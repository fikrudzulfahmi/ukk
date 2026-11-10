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

    .dual-table thead th {
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

    .rubrik-group .btn-check + .btn {
        flex: 1;
        font-size: 11px;
        padding: 6px 3px;
        border: none;
        border-radius: 7px;
        font-weight: 700;
        color: #64748b;
        background: transparent;
        transition: all .15s ease;
        line-height: 1.15;
    }

    .rubrik-group .btn-check + .btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* Warna khusus sesuai level */
    .btn-check:checked + .btn-bk {
        background: #ef4444 !important;
        color: white !important;
        box-shadow: 0 4px 10px -2px rgba(239, 68, 68, 0.4);
    }

    .btn-check:checked + .btn-ck {
        background: #f59e0b !important;
        color: white !important;
        box-shadow: 0 4px 10px -2px rgba(245, 158, 11, 0.4);
    }

    .btn-check:checked + .btn-k {
        background: #059669 !important;
        color: white !important;
        box-shadow: 0 4px 10px -2px rgba(5, 150, 105, 0.4);
    }

    .btn-check:checked + .btn-sk {
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

    /* ===== Mode tab siswa (mobile) ===== */
    .mob-tabs {
        display: flex;
        gap: .5rem;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding-bottom: 2px;
    }

    .mob-tabs::-webkit-scrollbar { display: none; }

    .mob-tab {
        flex: 1 0 auto;
        min-width: 42%;
        text-align: left;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: .5rem .7rem;
        line-height: 1.2;
    }

    .mob-tab__tag {
        display: block;
        font-size: .62rem;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: #94a3b8;
    }

    .mob-tab__name {
        display: block;
        font-size: .8rem;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .mob-tab.is-active {
        border-color: #059669;
        background: #ecfdf5;
        box-shadow: 0 6px 16px -12px rgba(5, 150, 105, .9);
    }

    .mob-tab.is-active .mob-tab__tag { color: #047857; }

    .mob-panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .6rem;
        margin-top: .6rem;
        padding: .6rem .75rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px 12px 0 0;
        border-bottom: none;
    }

    .mob-panel-head__name {
        font-weight: 800;
        font-size: .9rem;
        color: #0f172a;
        line-height: 1.2;
    }

    .mob-panel-head__prog {
        font-size: .7rem;
        color: #64748b;
        font-weight: 600;
    }

    .mob-progress {
        height: 5px;
        background: #e2e8f0;
        border-radius: 0 0 0 0;
        margin-bottom: .75rem;
        overflow: hidden;
    }

    .mob-progress__bar {
        height: 100%;
        width: 0%;
        background: #059669;
        transition: width .3s ease;
    }

    @media (max-width: 991.98px) {
        .dual-head { padding: .9rem 1rem; margin-bottom: .9rem; border-radius: 14px; }
        .dual-head__title { font-size: 1rem; }

        .assessment-card { border-radius: 0 0 14px 14px !important; }

        .dual-table,
        .dual-table tbody,
        .dual-table tr,
        .dual-table td {
            display: block;
            width: 100%;
        }

        .dual-table thead { display: none; }

        .dual-table tbody tr {
            padding: .7rem .8rem;
            border-bottom: 1px solid #eef2f7;
        }

        .dual-table tbody tr:last-child { border-bottom: none; }

        .dual-table td {
            border: none !important;
            padding: 0 !important;
        }

        .dual-table .siswa-col { display: none; }
        .dual-table .siswa-col.is-active { display: block; }

        .dual-table td.criteria { padding-bottom: .55rem !important; }
        .dual-table td.criteria .fw-bold { font-size: 13px !important; }

        .dual-table .rubrik-group {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: .35rem;
            min-width: 0;
            width: 100%;
            padding: 0;
            background: transparent;
            border: none;
            box-shadow: none !important;
        }

        .dual-table .rubrik-group .btn-check + .btn {
            padding: .65rem .2rem;
            font-size: 12px;
            border-radius: 10px !important;
            margin: 0 !important;
            background: #f8fafc;
            border: 1px solid #e2e8f0 !important;
        }

        .dual-table .rubrik-group .btn-check:checked + .btn {
            border-color: transparent !important;
        }

        .dual-table textarea.input-catatan { font-size: 13px !important; }
    }
</style>

<div class="container-fluid py-3 px-2 px-lg-3">
    <div class="dual-head">
        <div>
            <h3 class="dual-head__title"><i class="bi bi-ui-checks-grid me-2"></i> Input Nilai Praktik (Skala 4)</h3>
            <small class="opacity-80">
                <i class="bi bi-geo-alt"></i> <?= esc($pos['nama_pos']) ?>
                <span class="mx-1">•</span>
                <i class="bi bi-people"></i> <?= count($siswa_list) ?> Peserta
            </small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="dual-head__badge"><i class="bi bi-lightning-charge me-1"></i> Auto-Save</span>
            <a href="<?= base_url('penilaian/input?date=' . $current_date) ?>" class="btn btn-sm btn-light rounded-pill px-3 fw-bold text-success">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div id="save-status" class="alert alert-dark shadow-lg py-2 px-4 rounded-pill border-0">
        <span class="spinner-border spinner-border-sm me-2 text-success"></span>
        <small class="fw-bold">Menyimpan nilai...</small>
    </div>

    <?php if (!empty($siswa_list)): $firstSiswa = $siswa_list[0]; ?>
        <!-- Mobile: tab per siswa -->
        <div class="d-lg-none">
            <div class="mob-tabs">
                <?php foreach ($siswa_list as $i => $s): ?>
                    <button type="button"
                        class="mob-tab <?= $i === 0 ? 'is-active' : '' ?>"
                        data-col="<?= $i + 1 ?>"
                        data-siswa="<?= $s['id_siswa'] ?>"
                        data-nama="<?= esc($s['nama_siswa'], 'attr') ?>">
                        <span class="mob-tab__tag">Siswa <?= chr(65 + $i) ?></span>
                        <span class="mob-tab__name"><?= esc($s['nama_siswa']) ?></span>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="mob-panel-head">
                <div>
                    <div class="mob-panel-head__name" id="mobSiswaNama"><?= esc($firstSiswa['nama_siswa']) ?></div>
                    <div class="mob-panel-head__prog"><span id="mobProgText">0/<?= count($elemen) ?></span> elemen dinilai</div>
                </div>
                <button type="button" class="btn btn-sm btn-light text-success border fw-bold fill-all"
                    data-siswa="<?= $firstSiswa['id_siswa'] ?>" data-val="85">
                    <i class="bi bi-magic me-1"></i>Set All K
                </button>
            </div>
            <div class="mob-progress"><div class="mob-progress__bar" id="mobProgBar"></div></div>
        </div>

        <div class="card assessment-card shadow-sm overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0 dual-table">
                    <thead>
                        <tr>
                            <th class="ps-4" width="30%">Kriteria / Elemen Penilaian</th>
                            <?php foreach ($siswa_list as $i => $s): ?>
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
                                <td class="ps-4 py-3 criteria">
                                    <div class="fw-bold text-dark" style="font-size: 13px;"><?= esc($e['nama_elemen']) ?></div>
                                </td>
                                <?php foreach ($siswa_list as $i => $siswa): ?>
                                    <td class="px-2 siswa-col <?= $i === 0 ? 'is-active' : '' ?>" data-col="<?= $i + 1 ?>">
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
                            <td class="ps-4 py-4 align-top criteria">
                                <div class="fw-bold text-success" style="font-size: 14px;">
                                    <i class="bi bi-journal-text me-1"></i> Catatan Penilaian
                                </div>
                                <small class="text-muted" style="font-size: 11px;">Tambahkan catatan performa siswa pada POS ini (Opsional).</small>
                            </td>
                            <?php foreach ($siswa_list as $i => $siswa): ?>
                                <td class="px-2 py-3 siswa-col <?= $i === 0 ? 'is-active' : '' ?>" data-col="<?= $i + 1 ?>">
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
    <?php else: ?>
        <div class="alert alert-warning border-0 shadow-sm">Tidak ada peserta pada sesi ini.</div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const totalElemen = <?= (int) count($elemen) ?>;

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

        function updateProgress(idSiswa) {
            if (!idSiswa) return;
            const dinilai = new Set();
            $('.btn-autosave:checked').each(function() {
                if (String($(this).data('siswa')) === String(idSiswa)) {
                    dinilai.add(String($(this).data('elemen')));
                }
            });
            const jumlah = dinilai.size;
            const persen = totalElemen > 0 ? Math.round((jumlah / totalElemen) * 100) : 0;
            $('#mobProgText').text(jumlah + '/' + totalElemen);
            $('#mobProgBar').css('width', persen + '%');
        }

        let activeSiswa = $('.mob-tab.is-active').data('siswa') || <?= !empty($siswa_list) ? (int) $siswa_list[0]['id_siswa'] : 0 ?>;

        function setActiveSiswa(col, idSiswa, nama) {
            $('.dual-table .siswa-col').removeClass('is-active');
            $('.dual-table .siswa-col[data-col="' + col + '"]').addClass('is-active');

            $('.mob-tab').removeClass('is-active');
            $('.mob-tab[data-col="' + col + '"]').addClass('is-active');

            $('#mobSiswaNama').text(nama);
            $('.mob-panel-head .fill-all').attr('data-siswa', idSiswa).data('siswa', idSiswa);

            activeSiswa = idSiswa;
            updateProgress(idSiswa);
        }

        $(document).on('click', '.mob-tab', function() {
            setActiveSiswa($(this).data('col'), $(this).data('siswa'), $(this).data('nama'));
        });

        $(document).on('change', '.btn-autosave', function() {
            updateVisual(this);
            updateProgress($(this).data('siswa'));

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
                ukToast('error', 'Gagal menyimpan data nilai!');
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
                ukToast('error', 'Gagal menyimpan catatan!');
                $('#save-status').hide();
            });
        });

        $(document).on('click', '.fill-all', async function() {
            const idSiswa = $(this).data('siswa');
            const val = $(this).data('val');

            const ok = await ukConfirm({
                title: 'Set Semua Kompeten?',
                text: 'Semua elemen penilaian siswa ini akan diisi Kompeten (85).',
                confirmButtonText: 'Ya, Set Semua'
            });
            if (!ok) return;

            $(`input[name^="n_${idSiswa}_"][value="${val}"]`).each(function() {
                if (!$(this).is(':checked')) {
                    $(this).prop('checked', true);
                    updateVisual(this);
                    $(this).trigger('change');
                }
            });
        });

        updateProgress(activeSiswa);
    });
</script>
<?= $this->endSection() ?>
