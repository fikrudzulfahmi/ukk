<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Penilaian UKK') ?> | E-UKK TSM</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/ukk.css') ?>">

    <style>
        .pg-bar {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: linear-gradient(100deg, #101829 0%, #171f36 60%, #1e2745 100%);
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            box-shadow: 0 12px 30px -24px rgba(0, 0, 0, .9);
        }

        .pg-bar__inner {
            max-width: 1180px;
            margin: 0 auto;
            padding: .8rem 1.15rem;
            display: flex;
            align-items: center;
            gap: .9rem;
        }

        .pg-mark {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            color: #fff;
            font-size: 1.05rem;
            flex: 0 0 auto;
        }

        .pg-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .95rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            display: block;
            letter-spacing: -.02em;
        }

        .pg-sub {
            font-size: .68rem;
            letter-spacing: .09em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .45);
            font-weight: 600;
        }

        .pg-user {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            padding: .38rem .8rem .38rem .4rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .1);
            color: #fff;
            font-size: .8rem;
            font-weight: 600;
        }

        .pg-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, .16);
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .pg-out {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .42rem .78rem;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, .16);
            color: rgba(255, 255, 255, .82);
            font-size: .8rem;
            font-weight: 600;
            transition: .18s ease;
        }

        .pg-out:hover { background: rgba(239, 68, 68, .16); border-color: rgba(248, 113, 113, .4); color: #fecaca; }

        .pg-main { padding: 1.6rem 0 3rem; }
    </style>
</head>

<body>

    <nav class="pg-bar">
        <div class="pg-bar__inner">
            <span class="pg-mark"><i class="bi bi-clipboard2-check"></i></span>
            <div class="me-auto">
                <span class="pg-title">Penilaian UKK</span>
                <span class="pg-sub">SMK Islam 1 Blitar</span>
            </div>

            <span class="pg-user d-none d-sm-inline-flex">
                <span class="pg-avatar"><?= esc(strtoupper(substr(session()->get('nama_lengkap') ?? 'P', 0, 1))) ?></span>
                <?= esc(session()->get('nama_lengkap') ?? 'Penguji') ?>
            </span>

            <a href="<?= base_url('logout') ?>" class="pg-out">
                <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Keluar</span>
            </a>
        </div>
    </nav>

    <div class="container pg-main">
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
