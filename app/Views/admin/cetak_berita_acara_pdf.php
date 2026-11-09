<?= $this->extend('layout/pdf_layout') ?>

<?= $this->section('pdf_content') ?>
<?php
$hari_map = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
$bulan_map = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];

$timestamp = strtotime($tanggal);
$nama_hari = $hari_map[date('l', $timestamp)];
$nama_bulan = $bulan_map[date('F', $timestamp)];
$tgl_formatted = date('d', $timestamp) . ' ' . $nama_bulan . ' ' . date('Y', $timestamp);
?>

<div class="judul-dokumen" style="margin-top: -20px;">
    <span style="font-size: 12pt; text-decoration: underline;">BERITA ACARA <?= $event['nama_event'] ?></span><br>
    <span style="font-size: 12pt;">KONSENTRASI KEAHLIAN TEKNIK SEPEDA MOTOR</span><br>
    <span style="font-size: 12pt;">TAHUN PELAJARAN <?= $event['tahun_pelajaran'] ?></span>
</div>

<div style="text-align: justify; font-size: 12px; line-height: 1.6; margin-top: 20px;">
    <p><i>Bismillahirrahmanirrohim.</i></p>
    <p>Pada hari ini <b><?= $nama_hari ?></b> tanggal <b><?= date('d', $timestamp) ?></b> bulan <b><?= $nama_bulan ?></b> tahun <b><?= date('Y', $timestamp) ?></b>, bertempat di Bengkel SMK Islam 1 Blitar, telah diselenggarakan Uji Kompetensi Keahlian, Konsentrasi Keahlian Teknik Sepeda Motor, dimulai pukul 07.30 WIB sampai dengan pukul 14.00 WIB.</p>
</div>

<table style="width: 100%; border: none; font-size: 12px; margin-top: 15px; border-collapse: collapse;">
    <tr>
        <td width="30%" style="padding: 4px 0;">Pada Sekolah</td>
        <td style="padding: 4px 0;">: SMK Islam 1 Blitar</td>
    </tr>
    <tr>
        <td style="padding: 4px 0;">Alamat</td>
        <td style="padding: 4px 0;">: Jl. Musi No. 6 Blitar</td>
    </tr>
    <tr>
        <td style="padding: 4px 0;">Jumlah Peserta seharusnya</td>
        <td style="padding: 4px 0;">: <?= $jml_siswa ?> Siswa</td>
    </tr>
    <tr>
        <td style="padding: 4px 0;">Jumlah yang hadir</td>
        <td style="padding: 4px 0;">: .......... Siswa</td>
    </tr>
    <tr>
        <td style="padding: 4px 0;">Jumlah yang tidak hadir</td>
        <td style="padding: 4px 0;">: .......... Siswa</td>
    </tr>
    <tr>
        <td style="padding: 4px 0;">Yaitu Nomor</td>
        <td style="padding: 4px 0;">: ....., ....., ....., ....., ....., ....., ....., .....</td>
    </tr>
</table>

<p style="margin-top: 20px; margin-bottom: 5px; font-size: 12px;">Catatan selama Uji Kompetensi Kejuruan :</p>
<div style="width: 95%; border: 1px solid black; min-height: 120px; padding: 10px; font-size: 12px; line-height: 1.8;">
    ........................................................................................................................................................................................ <br>
    ........................................................................................................................................................................................ <br>
    ........................................................................................................................................................................................ <br>
    ........................................................................................................................................................................................ <br>
    ........................................................................................................................................................................................
</div>

<p style="font-size: 12px; margin-top: 15px;">Demikian berita acara ini dibuat dengan sesungguhnya.</p>

<div style="margin-top: 20px; page-break-inside: avoid;">
    <div style="font-size: 11pt; text-align: right; margin-right: 50px; margin-bottom: 15px;">
        Blitar, <?= $tgl_formatted ?>
    </div>

    <table style="width: 100%; border: none !important; border-collapse: collapse;">
        <tr>
            <?php
            $count = 0;
            foreach ($penguji as $p) :
                if ($count > 0 && $count % 3 == 0) echo "</tr><tr>";
            ?>
                <td style="width: 33%; text-align: center; border: none !important; padding-bottom: 35px; vertical-align: top;">
                    <span style="font-size: 10pt;">Penguji <?= $p['nama_pos'] ?></span><br><br><br><br><br>
                    <span style="font-size: 10pt; font-weight: bold; text-decoration: underline;">
                        ( <?= $p['nama_lengkap'] ?> )
                    </span>
                </td>
            <?php
                $count++;
            endforeach;

            // Filler untuk sisa kolom agar layout tetap rapi
            $sisa = $count % 3;
            if ($sisa !== 0) {
                for ($i = 0; $i < (3 - $sisa); $i++) {
                    echo '<td style="width: 33%; border: none !important;"></td>';
                }
            }
            ?>
        </tr>
    </table>
</div>
<?= $this->endSection() ?>