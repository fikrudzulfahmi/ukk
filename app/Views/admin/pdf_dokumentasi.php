<?= $this->extend('layout/pdf_layout') ?>

<?= $this->section('pdf_content') ?>
<div class="judul-dokumen" style="margin-top: -20px;">
    DOKUMENTASI KEGIATAN <?= $event['nama_event'] ?><br>
    KONSENTRASI KEAHLIAN TEKNIK SEPEDA MOTOR<br>
    TAHUN PELAJARAN <?= $event['tahun_pelajaran'] ?>
</div>

<div style="width: 100%; text-align: center;">
    <?php foreach ($dokumentasi as $d) : ?>
        <div style="width: 85%; display: inline-block; margin-bottom: 30px; border: 1px solid #ddd; padding: 10px; page-break-inside: avoid; background-color: #fff;">
            <img src="<?= $d['foto_base64'] ?>" style="width: 100%; height: auto; max-height: 400px; object-fit: contain; margin-bottom: 10px;">

            <div style="font-weight: bold; font-size: 11px; color: #333; text-transform: uppercase;">
                <?= $d['judul_foto'] ?>
            </div>
            <div style="font-size: 9px; color: #666; margin-top: 5px;">
                Waktu Pengambilan: <?= date('d/m/Y H:i', strtotime($d['created_at'])) ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<div style="clear: both;"></div>
<?= $this->endSection() ?>