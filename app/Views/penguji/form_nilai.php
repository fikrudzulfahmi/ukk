<?= $this->extend('layout/penguji_layout') ?>

<?= $this->section('content') ?>
<style>
    body { background: var(--uk-canvas, #f5f6fa); }

    .rate-head {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .6rem;
        border-radius: 18px; padding: 1.1rem 1.35rem; color: #fff;
        background:
            radial-gradient(120% 140% at 100% 0%, rgba(124, 58, 237, .55), transparent 55%),
            linear-gradient(135deg, #1e1b4b 0%, #312e81 55%, #4338ca 100%);
        box-shadow: 0 20px 44px -30px rgba(30, 27, 75, .9);
    }
    .rate-head__name { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.06rem; font-weight: 700; letter-spacing: -.02em; margin: 0 0 .15rem; }
    .rate-head__meta { font-size: .78rem; color: rgba(255,255,255,.7); display: flex; align-items: center; gap: .4rem; }

    .rate-card { border: 1px solid var(--uk-line); border-radius: 18px !important; box-shadow: var(--uk-shadow-sm) !important; }
    .rate-card thead th { text-transform: uppercase; font-size: .7rem; letter-spacing: .06em; color: var(--uk-muted); background: #f8f9fd; border-bottom: 1px solid var(--uk-line); }
    .rate-item { font-size: .9rem; color: var(--uk-ink); font-weight: 600; }
    .rate-foot { background: linear-gradient(180deg, #fafaff, #f4f5fb); border-top: 1px solid var(--uk-line); }

    .btn-grade { background: linear-gradient(135deg, #059669, #10b981); border: none; border-radius: 13px; font-weight: 700; letter-spacing: .02em; color: #fff; box-shadow: 0 14px 30px -16px rgba(5,150,105,.9); }
    .btn-grade:hover { background: linear-gradient(135deg, #047857, #059669); color: #fff; transform: translateY(-1px); }

    .btn-check:checked + .btn-outline-danger { background: linear-gradient(135deg, #ef4444, #dc2626) !important; border-color: transparent; color: #fff; }
    .btn-check:checked + .btn-outline-success { background: linear-gradient(135deg, #059669, #10b981) !important; border-color: transparent; color: #fff; }
</style>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <a href="<?= base_url('dashboard-penguji') ?>" class="btn btn-sm btn-light mb-3 px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Antrian
            </a>

            <div class="rate-head mb-4">
                <div>
                    <p class="rate-head__name mb-0"><i class="bi bi-clipboard-check me-1"></i> Penilaian: <?= esc($siswa['nama_siswa']) ?></p>
                    <div class="rate-head__meta">
                        <i class="bi bi-person-vcard"></i> NISN <?= esc($siswa['nisn']) ?>
                        <?php if (!empty($siswa['kelas'])): ?><span class="opacity-50">•</span><i class="bi bi-mortarboard"></i> <?= esc($siswa['kelas']) ?><?php endif; ?>
                    </div>
                </div>
                <span class="badge rounded-pill" style="background: rgba(255,255,255,.14); color:#fff; border:1px solid rgba(255,255,255,.2);">
                    <i class="bi bi-shield-check me-1"></i> Asesor
                </span>
            </div>
        </div>
    </div>

    <form action="<?= base_url('penilaian/simpan') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id_siswa" value="<?= $siswa['id_siswa'] ?>">

        <div class="card rate-card border-0">
            <div class="card-body p-0">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 44px;">#</th>
                            <th class="ps-0">Item Penilaian (Elemen)</th>
                            <th class="text-center pe-4" style="width: 300px;">Status Kompetensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $idx = 0; foreach ($elemen as $e): $idx++; ?>
                            <tr>
                                <td class="ps-4 text-muted fw-bold small"><?= $idx ?></td>
                                <td class="ps-2"><div class="rate-item"><?= esc($e['nama_elemen']) ?></div></td>
                                <td class="text-center pe-4">
                                    <div class="btn-group" role="group">
                                        <input type="radio" class="btn-check" name="is_kompeten[<?= $e['id_elemen'] ?>]"
                                            id="bk_<?= $e['id_elemen'] ?>" value="0" required>
                                        <label class="btn btn-outline-danger btn-sm px-3 rounded-3" for="bk_<?= $e['id_elemen'] ?>">
                                            <i class="bi bi-x-circle me-1"></i> Belum
                                        </label>

                                        <input type="radio" class="btn-check" name="is_kompeten[<?= $e['id_elemen'] ?>]"
                                            id="k_<?= $e['id_elemen'] ?>" value="1" required>
                                        <label class="btn btn-outline-success btn-sm px-3 rounded-3" for="k_<?= $e['id_elemen'] ?>">
                                            <i class="bi bi-check-circle me-1"></i> Kompeten
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer rate-foot p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        <?= count($elemen) ?> elemen — centang seluruhnya sebelum menyimpan.
                    </div>
                    <button type="submit" class="btn btn-grade px-4">
                        <i class="bi bi-check2-all me-2"></i> Simpan Nilai
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>