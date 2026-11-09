<?= $this->extend('layout/pdf_layout') ?>

<?= $this->section('pdf_content') ?>
<?php
$hari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
$bulan = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
$time = strtotime($tanggal);
$tanggal_indo = $hari[date('l', $time)] . ', ' . date('d', $time) . ' ' . $bulan[date('m', $time)] . ' ' . date('Y', $time);
?>

<div class="judul-dokumen" style="margin-top: -20px;">
    DAFTAR HADIR PENGUJI <?= $event['nama_event'] ?><br>
    KONSENTRASI KEAHLIAN TEKNIK SEPEDA MOTOR<br>
    TAHUN PELAJARAN <?= $event['tahun_pelajaran'] ?>
</div>

<p class="text-bold" style="margin-bottom: 10px;">Hari / Tanggal: <?= $tanggal_indo ?></p>

<table class="table-data">
    <thead>
        <tr>
            <th width="5%">NO</th>
            <th width="33%">NAMA PENGUJI</th>
            <th width="22%">INSTANSI</th>
            <th width="10%">POS TUGAS</th>
            <th width="30%" colspan="2">TANDA TANGAN</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($presensi as $i => $p) : $nomor = $i + 1; ?>
            <tr>
                <td class="text-center"><?= $nomor ?></td>
                <td><b style="font-size: 12px;"><?= $p['nama_lengkap'] ?></b></td>
                <td class="text-center" style="font-size: 12px;"><?= $p['instansi'] ?></td>
                <td class="text-center"><?= $p['nama_pos'] ?></td>

                <?php if ($nomor % 2 != 0) : ?>
                    <td style="border-right: none; height: 40px; vertical-align: bottom; font-size: 9px;"><?= $nomor ?>. .......................</td>
                    <td style="border-left: none;"></td>
                <?php else : ?>
                    <td style="border-right: none;"></td>
                    <td style="border-left: none; height: 40px; vertical-align: bottom; font-size: 9px;"><?= $nomor ?>. ........................</td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div style="margin-top: 30px; float: right; width: 250px; text-align: right; font-size: 12px;">
    Blitar, <?= date('d', $time) . ' ' . $bulan[date('m', $time)] . ' ' . date('Y', $time) ?><br>
    Ketua Konsentrasi Keahlian,<br><br><br><br><br>
    <b style="text-decoration: underline;"><?= $event['ketua'] ?></b>
</div>
<?= $this->endSection() ?>