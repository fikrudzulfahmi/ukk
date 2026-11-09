<?= $this->extend('layout/pdf_layout') ?>

<?= $this->section('pdf_content') ?>
<?php
// Ambil status dari session
$is_teori = (int)(session()->get('is_teori') ?? 1);

$daftar_bulan = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
];
$tgl_indo = date('d') . ' ' . $daftar_bulan[date('m')] . ' ' . date('Y');
?>

<div class="judul-dokumen" style="margin-top: -20px;">
    REKAP NILAI <?= $event['nama_event'] ?><br>
    KONSENTRASI KEAHLIAN TEKNIK SEPEDA MOTOR<br>
    TAHUN PELAJARAN <?= $event['tahun_pelajaran'] ?>
</div>

<table class="table-data">
    <thead>
        <tr>
            <th width="5%">NO</th>
            <th>NAMA SISWA</th>
            <th width="10%">KELAS</th>
            <?php if ($is_teori) : ?>
                <th width="12%">TEORI (30%)</th>
                <th width="12%">PRAKTEK (70%)</th>
            <?php else : ?>
                <th width="24%">PRAKTEK (100%)</th>
            <?php endif; ?>
            <th width="12%">NILAI AKHIR</th>
            <th width="24%">KESIMPULAN</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rekap as $i => $r) :
            $teori = (float)($r['nilai_teori'] ?? 0);
            $praktek = (float)($r['rata_praktek'] ?? 0);

            // LOGIKA DINAMIS PDF
            $na = $is_teori ? (($teori * 0.3) + ($praktek * 0.7)) : $praktek;

            $is_kompeten = ($na >= 75);
        ?>
            <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td style="text-transform: uppercase; font-weight: bold;"><?= $r['nama_siswa'] ?></td>
                <td class="text-center"><?= $r['kelas'] ?></td>
                <?php if ($is_teori) : ?>
                    <td class="text-center"><?= number_format($teori, 0) ?></td>
                    <td class="text-center"><?= number_format($praktek, 2) ?></td>
                <?php else : ?>
                    <td class="text-center"><?= number_format($praktek, 2) ?></td>
                <?php endif; ?>
                <td class="text-center" style="font-weight: bold;"><?= number_format($na, 2) ?></td>
                <td class="text-center" style="font-size: 11px;">
                    <?php if ($is_kompeten) : ?>
                        <span style="color: #08421d; font-weight: bold;">KOMPETEN</span>
                    <?php else : ?>
                        <span style="color: #770a0a; font-weight: bold;">BELUM KOMPETEN</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>