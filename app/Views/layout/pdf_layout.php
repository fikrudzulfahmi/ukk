<!DOCTYPE html>
<html>
<title><?= $title ?? 'Dokumen PDF' ?></title>

<head>
    <style>
        @page {
            margin: 150px 50px 50px 2.5cm;
            /* Konsisten 1.5cm untuk jilid */
        }

        header {
            position: fixed;
            top: -130px;
            left: 0;
            right: 0;
            height: 120px;
        }

        body {
            font-family: sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .kop-container {
            position: relative;
            width: 100%;
            text-align: center;
            min-height: 80px;
        }

        .logo-kiri {
            position: absolute;
            left: 0;
            top: 0;
            height: 70px;
        }

        .logo-kanan {
            position: absolute;
            right: 0;
            top: 10px;
            height: 60px;
        }

        .line {
            border-bottom: 2px solid black;
            margin-top: 5px;
            margin-bottom: 20px;
        }

        .judul-dokumen {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* Standarisasi Tabel untuk seluruh laporan */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .table-data th,
        .table-data td {
            border: 1px solid black;
            padding: 8px 6px;
        }

        .table-data th {
            background-color: #f0f0f0;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header>
        <div class="kop-container">
            <?php if ($logo_smki): ?>
                <img src="<?= $logo_smki; ?>" class="logo-kiri">
            <?php endif; ?>
            <?php if (isset($logo_iso) && $logo_iso): ?>
                <img src="<?= $logo_iso; ?>" class="logo-kanan">
            <?php endif; ?>
            <div class="kop-text">
                <div style="font-size: 14px;">LEMBAGA PENDIDIKAN MA'ARIF NU</div>
                <div style="font-weight: bold; font-size: 16px;">SEKOLAH MENENGAH KEJURUAN ISLAM 1 BLITAR</div>
                <div style="font-size: 11px;">NSS: 322056501004 NPSN:20535055 STATUS: TERAKREDITASI A</div>
                <div style="font-size: 11px;">Jl. Musi No.6 Blitar 66117 telp. (0342) 802137</div>
                <div style="font-size: 10px;">email: smkislam@gmail.com web: https://smkislam1blitar.sch.id</div>
            </div>
        </div>
        <div class="line"></div>
    </header>

    <main>
        <?= $this->renderSection('pdf_content') ?>
    </main>
</body>

</html>