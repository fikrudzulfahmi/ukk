<?= $this->extend('layout/pdf_layout') ?>

<?= $this->section('pdf_content') ?>
<?php
$hari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
$bulan = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
$time = time();
$tanggal_indo = $hari[date('l', $time)] . ', ' . date('d', $time) . ' ' . $bulan[date('m', $time)] . ' ' . date('Y', $time);
?>

<div class="judul-dokumen" style="margin-top: -20px;">
    DAFTAR AKUN PENGUJI <?= $event['nama_event'] ?><br>
    KONSENTRASI KEAHLIAN TEKNIK SEPEDA MOTOR<br>
    TAHUN PELAJARAN <?= $event['tahun_pelajaran'] ?>
</div>

<table class="table-data">
    <thead>
        <tr>
            <th width="5%">NO</th>
            <th width="35%">NAMA LENGKAP</th>
            <th width="20%">INSTANSI</th>
            <th width="20%">USERNAME</th>
            <th width="20%">PASSWORD</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($penguji as $p) : ?>
            <tr>
                <td class="text-center"><?= $no++; ?></td>
                <td style="text-transform: uppercase; font-weight: bold;"><?= $p['nama_lengkap']; ?></td>
                <td><?= $p['instansi']; ?></td>
                <td class="text-center" style="font-family: monospace; font-size: 12px;"><?= $p['username']; ?></td>
                <td class="text-center" style="font-family: monospace; font-size: 12px;"><?= $p['username']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div style="margin-top: 40px; width: 100%; page-break-inside: avoid;">

    <div style="float: left; width: 200px; text-align: center;">
        <?php if (isset($qrcode)): ?>
            <img src="<?= $qrcode ?>" style="width: 90px; height: 90px; border: 1px solid #ccc; padding: 5px;">
        <?php endif; ?>
        <div style="font-size: 10px; margin-top: 8px; color: #333; line-height: 1.4;">
            Scan untuk Login:<br>
            <strong>https://ukk.ingintau.my.id</strong>
        </div>
    </div>

    <div style="float: right; width: 250px; text-align: right;">
        <div style="font-size: 12px; margin-bottom: 5px;">
            Blitar, <?= date('d', $time) . ' ' . $bulan[date('m', $time)] . ' ' . date('Y', $time) ?>
        </div>
        <div style="font-size: 12px;">
            Ketua Konsentrasi Keahlian,<br><br><br><br><br>
            <b style="text-decoration: underline;"><?= $event['ketua'] ?></b>
        </div>
    </div>

    <div style="clear: both;"></div>
</div>
<?= $this->endSection() ?>