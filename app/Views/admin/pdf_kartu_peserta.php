<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 0.5cm;
        }

        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 0;
        }

        .page-wrapper {
            page-break-after: always;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .card-cell {
            width: 50%;
            padding: 8px;
            vertical-align: top;
        }

        .card {
            border: 2px solid #000;
            padding: 15px 15px 20px 15px;
            height: 10cm;
            position: relative;
        }

        .header {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
        }

        .sub-header {
            text-align: center;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .divider {
            border-top: 1px solid #000;
            margin: 6px 0 10px 0;
        }

        .no-label {
            text-align: center;
            font-size: 11px;
            margin-bottom: 4px;
        }

        .no-urut {
            font-size: 65px;
            font-weight: bold;
            background-color: #f2f2f2;
            border: 2px solid #000;
            text-align: center;
            padding: 8px 0;
            margin-bottom: 10px;
        }

        .nama {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            min-height: 35px;
        }

        .kelas {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }



        .qr-section {
            text-align: center;
            margin-top: 18px;
        }

        .qr-section img {
            border: 2px solid #000;
            padding: 3px;
        }

        .qr-caption {
            font-size: 9px;
            margin-top: 4px;
        }

        .footer-note {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            font-style: italic;
        }
    </style>

</head>

<body>

    <?php
    // Membagi array peserta menjadi per 4 orang (untuk 1 halaman)
    $chunks = array_chunk($peserta, 4);

    foreach ($chunks as $pageIndex => $pageData) : ?>
        <div class="page-wrapper">
            <table>
                <?php
                // Membagi 4 orang tersebut menjadi 2 baris (tiap baris 2 orang)
                $rows = array_chunk($pageData, 2);
                foreach ($rows as $rowIndex => $rowData) : ?>
                    <tr>
                        <?php foreach ($rowData as $siswaIndex => $s) :
                            // Hitung nomor urut total
                            $indexTotal = ($pageIndex * 4) + ($rowIndex * 2) + $siswaIndex;
                            $no_urut = sprintf("%03d", $indexTotal + 1);
                            $akun = "ukktsm-" . $no_urut;
                        ?>
                            <td class="card-cell">
                                <div class="card">

                                    <div class="header">KARTU PESERTA</div>
                                    <div class="sub-header"><?= $event['nama_event'] ?></div>
                                    <div class="sub-header">Tahun Pelajaran <?= $event['tahun_pelajaran'] ?></div>
                                    <div class="divider"></div>

                                    <div class="no-label">NOMOR PESERTA</div>
                                    <div class="no-urut"><?= $no_urut ?></div>

                                    <div class="nama"><?= $s['nama_siswa'] ?></div>
                                    <div class="kelas"><?= $s['kelas'] ?></div>



                                    <div class="qr-section">
                                        <img src="<?= $s['qr_base64'] ?>" width="85">
                                        <div class="qr-caption">
                                            Scan untuk mulai ujian teori
                                        </div>
                                    </div>

                                    <div class="footer-note">
                                        Teknik Sepeda Motor SMK Islam 1 Blitar
                                    </div>

                                </div>

                            </td>
                        <?php endforeach; ?>

                        <?php
                        // Jika dalam satu baris cuma ada 1 orang (data ganjil), tambah kolom kosong
                        if (count($rowData) == 1) echo '<td class="card-cell"></td>';
                        ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endforeach; ?>

</body>

</html>