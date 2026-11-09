<?= $this->extend('layout/pdf_layout') ?>

<?= $this->section('pdf_content') ?>
<?php
$getIndo = function ($tgl) {
    $hari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
    $bulan = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
    $time = strtotime($tgl);
    return $hari[date('l', $time)] . ', ' . date('d', $time) . ' ' . $bulan[date('m', $time)] . ' ' . date('Y', $time);
};
?>

<div class="judul-dokumen" style="margin-top: -20px;">
    JADWAL PENGUJI <?= $event['nama_event'] ?><br>
    KONSENTRASI KEAHLIAN TEKNIK SEPEDA MOTOR<br>
    TAHUN PELAJARAN <?= $event['tahun_pelajaran'] ?>
</div>

<table class="table-data">
    <thead>
        <tr>
            <th width="8%">NO</th>
            <th width="32%">NAMA PENGUJI</th>
            <th width="30%">INSTANSI</th>
            <th width="30%">POS UJI</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $current_date = null;
        $no = 1;

        foreach ($jadwal as $row) :
            // Jika ganti tanggal, buat baris pemisah baru
            if ($current_date !== $row['tanggal']) :
                $current_date = $row['tanggal'];
                $no = 1; // Reset nomor urut setiap ganti tanggal
        ?>
                <tr style="background-color: #e9ecef; font-weight: bold;">
                    <td colspan="4" style="padding: 8px 10px; border: 1px solid black;">
                        HARI / TANGGAL: <?= $getIndo($row['tanggal']) ?>
                    </td>
                </tr>
            <?php endif; ?>

            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td style="text-transform: uppercase; font-weight: bold;"><?= $row['nama_lengkap'] ?></td>
                <td><?= $row['instansi'] ?></td>
                <td class="text-center">
                    <span style="font-size: 10pt;"><?= $row['nama_pos'] ?></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div style="margin-top: 20px; font-size: 10px; color: #666; font-style: italic;">
    * Jadwal dapat berubah sewaktu-waktu sesuai dengan kondisi di lapangan.
</div>
<?= $this->endSection() ?>