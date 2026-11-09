<?= $this->extend('layout/pdf_layout') ?>

<?= $this->section('pdf_content') ?>
<div class="judul-dokumen" style="margin-top: -20px;">
    NASKAH SOAL UJIAN TEORI <?= $event['nama_event'] ?? '' ?><br>
    KONSENTRASI KEAHLIAN TEKNIK SEPEDA MOTOR<br>
    TAHUN PELAJARAN <?= $event['tahun_pelajaran'] ?? '' ?>
</div>

<table class="table-soal">
    <?php $no = 1;
    foreach ($soal as $s) : ?>
        <tr>
            <td width="25px" style="vertical-align: top; padding: 5px;"><?= $no++; ?>.</td>
            <td style="vertical-align: top; padding: 5px;">
                <div><?= esc($s['pertanyaan']) ?></div>
                <div style="margin-left: 20px; margin-top: 5px;">
                    A. <?= esc($s['opsi_a']) ?><br>
                    B. <?= esc($s['opsi_b']) ?><br>
                    C. <?= esc($s['opsi_c']) ?><br>
                    D. <?= esc($s['opsi_d']) ?>
                </div>
                <div style="font-style: italic; color: #666; margin-top: 5px;">
                    Kunci: <?= strtoupper($s['jawaban_benar']) ?>
                </div>
                <hr style="border: 0.1px solid #eee; margin: 10px 0;">
            </td>
        </tr>
    <?php endforeach; ?>
</table>


<?= $this->endSection() ?>