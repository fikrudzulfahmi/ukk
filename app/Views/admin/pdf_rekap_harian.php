<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pengumuman Hasil Harian UKK</title>
    <style>
        @page {
            margin: 30px 40px;
            /* Mengecilkan margin kertas atas-bawah-kiri-kanan */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            /* Huruf sedikit dikecilkan */
            color: #333;
        }

        .page-break {
            page-break-after: always;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
        }

        .student-info {
            width: 100%;
            margin-bottom: 10px;
            /* Spasi dikurangi */
        }

        .student-info td {
            padding: 2px 0;
            font-size: 12px;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .table-data th,
        .table-data td {
            border: 1px solid #999;
            padding: 4px 6px;
            /* Padding sel tabel dipadatkan */
            vertical-align: top;
        }

        /* Mencegah baris tabel terpotong di tengah saat pindah halaman */
        .table-data tr {
            page-break-inside: avoid;
        }

        .table-data th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .status-remedi {
            color: #c53030;
            font-weight: bold;
        }

        .status-lulus {
            color: #276749;
            font-weight: bold;
        }

        .elemen-item {
            margin-bottom: 3px;
            font-size: 10px;
            /* Teks elemen dikecilkan */
            line-height: 1.2;
        }

        .badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #ccc;
        }

        .skor-25 {
            background: #fee2e2;
            color: #b91c1c;
        }

        .skor-70 {
            background: #fef9c3;
            color: #854d0e;
        }

        .skor-85 {
            background: #dcfce7;
            color: #15803d;
        }

        .skor-100 {
            background: #dbeafe;
            color: #1e40af;
        }

        .skor-null {
            background: #f1f5f9;
            color: #94a3b8;
        }

        .conclusion {
            border: 1px dashed #666;
            padding: 8px;
            /* Padding dikurangi */
            text-align: center;
            background-color: #fdfdfd;
            font-size: 12px;
            page-break-inside: avoid;
            /* Pastikan kotak kesimpulan tidak terbelah */
        }
    </style>
</head>

<body>

    <?php $count = count($rekap_siswa);
    $i = 0; ?>
    <?php foreach ($rekap_siswa as $siswa): $i++; ?>

        <div class="header">
            <h2>LEMBAR PENGUMUMAN HASIL UJIAN PRAKTEK (UKK)</h2>
            <p style="margin: 5px 0 0 0;">Tanggal Ujian: <?= date('d-m-Y', strtotime($current_date)) ?></p>
        </div>

        <table class="student-info">
            <tr>
                <td width="15%"><strong>Nama Siswa</strong></td>
                <td width="2%">:</td>
                <td width="83%" style="text-transform: uppercase;"><strong><?= $siswa['nama_siswa'] ?></strong></td>
            </tr>
            <tr>
                <td><strong>NISN</strong></td>
                <td>:</td>
                <td><?= $siswa['nisn'] ?></td>
            </tr>
            <tr>
                <td><strong>Kelas</strong></td>
                <td>:</td>
                <td><?= $siswa['kelas'] ?></td>
            </tr>
        </table>

        <table class="table-data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Pos Ujian</th>
                    <th width="50%">Detail Penilaian Elemen</th>
                    <th width="15%">Status Pos</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($siswa['pos'] as $pos): ?>
                    <tr>
                        <td style="text-align: center;"><?= $no++ ?></td>
                        <td><strong><?= $pos['nama_pos'] ?></strong></td>
                        <td>
                            <?php foreach ($pos['elemen'] as $e):
                                $val = $e['nilai'];
                                $label = '-';
                                $class = 'skor-null';

                                if ($val == 25) {
                                    $label = 'BK';
                                    $class = 'skor-25';
                                } elseif ($val == 70) {
                                    $label = 'CK';
                                    $class = 'skor-70';
                                } elseif ($val == 85) {
                                    $label = 'K';
                                    $class = 'skor-85';
                                } elseif ($val == 100) {
                                    $label = 'SK';
                                    $class = 'skor-100';
                                }
                            ?>
                                <div class="elemen-item">
                                    <span class="badge <?= $class ?>"><?= $label ?></span>
                                    <strong><?= $e['kode'] ?>:</strong> <?= $e['nama_elemen'] ?>
                                </div>
                            <?php endforeach; ?>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <?php if ($pos['status_remedi']): ?>
                                <span class="status-remedi">REMEDIAL</span>
                            <?php else: ?>
                                <span class="status-lulus">KOMPETEN</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="conclusion">
            Berdasarkan hasil penilaian di atas, peserta ujian dinyatakan:<br>
            <?php if ($siswa['butuh_remedi']): ?>
                <h3 style="color: #c53030; margin: 10px 0;">MEMBUTUHKAN REMEDIAL</h3>
                <p style="margin: 0; font-size: 11px;">Harap segera melapor kepada Asesor/Penguji pada POS yang berstatus REMEDIAL.</p>
            <?php else: ?>
                <h3 style="color: #276749; margin: 10px 0;">KOMPETEN (LULUS)</h3>
                <p style="margin: 0; font-size: 11px;">Selamat! Anda telah menyelesaikan ujian pada hari ini dengan baik.</p>
            <?php endif; ?>
        </div>

        <?php if ($i < $count): ?>
            <div class="page-break"></div>
        <?php endif; ?>

    <?php endforeach; ?>

</body>

</html>