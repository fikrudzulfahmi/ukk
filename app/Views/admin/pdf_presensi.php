<?= $this->extend('layout/pdf_layout') ?>

<?= $this->section('pdf_content') ?>
<?php
// Array pemetaan bahasa untuk tanggal
$hari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
$bulan = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];

$time = strtotime($tanggal);
$hari_inggris  = date('l', $time);
$bulan_inggris = date('F', $time);
$tanggal_indo = $hari[$hari_inggris] . ', ' . date('d', $time) . ' ' . $bulan[$bulan_inggris] . ' ' . date('Y', $time);
?>

<div class="judul-dokumen" style="margin-top: -20px;">
    DAFTAR HADIR PESERTA <?= $event['nama_event'] ?><br>
    KONSENTRASI KEAHLIAN TEKNIK SEPEDA MOTOR<br>
    TAHUN PELAJARAN <?= $event['tahun_pelajaran'] ?>
</div>

<p style="font-weight: bold; font-size: 12px; margin-bottom: 10px;">
    Hari / Tanggal: <?= $tanggal_indo ?>
</p>

<table class="table-data">
    <thead>
        <tr>
            <th width="5%">NO</th>
            <th width="10%">NO PESERTA</th>
            <th width="40%">NAMA PESERTA</th>
            <th width="15%">KELAS</th>
            <th width="30%" colspan="2">TANDA TANGAN</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($presensi as $i => $p) : $nomor = $i + 1; ?>
            <tr>
                <td class="text-center"><?= $nomor ?></td>
                <td class="text-center"><?= $p['no_peserta_otomatis'] ?></td>
                <td><?= strtoupper($p['nama_siswa']) ?></td>
                <td class="text-center"><?= $p['kelas'] ?></td>

                <?php if ($nomor % 2 != 0) : ?>
                    <td style="border-right: none; height: 25px; vertical-align: bottom; font-size: 9px; padding-bottom: 5px;">
                        <?= $nomor ?>. ........................
                    </td>
                    <td style="border-left: none;"></td>
                <?php else : ?>
                    <td style="border-right: none;"></td>
                    <td style="border-left: none; height: 25px; vertical-align: bottom; font-size: 9px; padding-bottom: 5px;">
                        <?= $nomor ?>. ........................
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div style="margin-top: 30px; page-break-inside: avoid;">
    <div style="font-weight: bold; margin-bottom: 15px; font-size: 11pt; text-align: center;">
        Penguji Uji Kompetensi Keahlian:
    </div>

    <table style="width: 100%; border: none !important;">
        <tr>
            <?php
            $count = 0;
            foreach ($daftar_pos as $pos) :
                if ($count > 0 && $count % 3 == 0) echo "</tr><tr>";
            ?>
                <td style="text-align: center; width: 33%; border: none !important; padding-bottom: 30px; vertical-align: top;">
                    <span style="font-size: 10pt;">Penguji <?= $pos['nama_pos'] ?></span><br><br><br><br><br>
                    <span style="font-weight: bold; text-decoration: underline;">
                        ( <?= $pos['nama_lengkap'] ?? '....................................' ?> )
                    </span>
                </td>
            <?php
                $count++;
            endforeach;
            ?>
        </tr>
    </table>
</div>
<?= $this->endSection() ?>