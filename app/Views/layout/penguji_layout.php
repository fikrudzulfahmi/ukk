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
            background: #ffffff;
            border-bottom: 1px solid var(--uk-line);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        }

        .pg-bar__inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: .85rem 1.15rem;
            display: flex;
            align-items: center;
            gap: .9rem;
        }

        .pg-mark {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: var(--uk-gradient);
            color: #fff;
            font-size: 1.15rem;
            flex: 0 0 auto;
            box-shadow: 0 6px 16px -4px rgba(5, 150, 105, 0.45);
        }

        .pg-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1rem;
            font-weight: 800;
            color: var(--uk-ink);
            line-height: 1.15;
            display: block;
            letter-spacing: -.02em;
        }

        .pg-sub {
            font-size: .68rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--uk-muted);
            font-weight: 600;
        }

        .pg-user {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            padding: .38rem .85rem .38rem .45rem;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid var(--uk-line);
            color: var(--uk-ink);
            font-size: .82rem;
            font-weight: 600;
        }

        .pg-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: var(--uk-brand-soft);
            color: var(--uk-brand);
            font-size: .75rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .pg-out {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .42rem .85rem;
            border-radius: 10px;
            border: 1px solid var(--uk-line);
            background: #fff;
            color: var(--uk-ink-2);
            font-size: .82rem;
            font-weight: 600;
            transition: .18s ease;
        }

        .pg-out:hover { background: var(--uk-danger-soft); border-color: #fca5a5; color: var(--uk-danger); }

        .pg-main {
            padding: 1.6rem 0 5rem;
            max-width: 1200px;
        }

        .pg-nav {
            display: none;
            align-items: center;
            gap: .25rem;
            padding: .25rem;
            background: #f8fafc;
            border: 1px solid var(--uk-line);
            border-radius: 999px;
        }

        .pg-nav a {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .4rem .85rem;
            border-radius: 999px;
            font-size: .82rem;
            font-weight: 600;
            color: var(--uk-ink-2);
            text-decoration: none;
            transition: .18s ease;
        }

        .pg-nav a:hover { color: var(--uk-brand); }

        .pg-nav a.active {
            background: var(--uk-brand-soft);
            color: var(--uk-brand);
        }

        @media (min-width: 768px) {
            .pg-nav { display: inline-flex; }
        }
    </style>
</head>

<body>

    <header class="pg-bar">
        <div class="pg-bar__inner">
            <span class="pg-mark"><i class="bi bi-clipboard2-check-fill"></i></span>
            <div class="me-auto">
                <span class="pg-title">Penilaian Penguji</span>
                <span class="pg-sub">E-UKK TSM SMK Islam 1 Blitar</span>
            </div>

            <nav class="pg-nav">
                <a href="<?= base_url('penilaian') ?>" class="<?= url_is('penilaian') ? 'active' : '' ?>">
                    <i class="bi bi-house-door"></i> Beranda
                </a>
                <a href="<?= base_url('penilaian/input') ?>" class="<?= (url_is('penilaian/input*') || url_is('penilaian/input_dual*')) ? 'active' : '' ?>">
                    <i class="bi bi-pencil-square"></i> Input Nilai
                </a>
                <a href="<?= base_url('penilaian/riwayat') ?>" class="<?= url_is('penilaian/riwayat*') ? 'active' : '' ?>">
                    <i class="bi bi-clock-history"></i> Riwayat
                </a>
            </nav>

            <span class="pg-user d-none d-sm-inline-flex">
                <span class="pg-avatar"><?= esc(strtoupper(substr(session()->get('nama_lengkap') ?? 'P', 0, 1))) ?></span>
                <?= esc(session()->get('nama_lengkap') ?? 'Penguji') ?>
            </span>

            <a href="javascript:void(0)" onclick="logoutConfirm()" class="pg-out">
                <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Keluar</span>
            </a>
        </div>
    </header>

    <main class="container pg-main">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Mobile Bottom Navigation Bar Penguji -->
    <nav class="uk-bottom-bar">
        <ul class="uk-bottom-bar-nav">
            <li class="uk-bottom-bar-item">
                <a href="<?= base_url('penilaian') ?>" class="uk-bottom-link <?= url_is('penilaian') ? 'active' : '' ?>">
                    <i class="bi bi-house-door"></i>
                    <span>Beranda</span>
                </a>
            </li>
            <li class="uk-bottom-bar-item">
                <a href="<?= base_url('penilaian/input') ?>" class="uk-bottom-link <?= (url_is('penilaian/input*') || url_is('penilaian/input_dual*')) ? 'active' : '' ?>">
                    <i class="bi bi-pencil-square"></i>
                    <span>Input Nilai</span>
                </a>
            </li>
            <li class="uk-bottom-bar-item">
                <a href="<?= base_url('penilaian/riwayat') ?>" class="uk-bottom-link <?= (url_is('penilaian/riwayat*') ? 'active' : '') ?>">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat</span>
                </a>
            </li>
            <li class="uk-bottom-bar-item">
                <a href="javascript:void(0)" onclick="logoutConfirm()" class="uk-bottom-link text-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar</span>
                </a>
            </li>
        </ul>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function logoutConfirm() {
            Swal.fire({
                title: 'Ingin Keluar?',
                text: "Sesi penilaian Anda akan berakhir.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('logout') ?>";
                }
            })
        }
    </script>
</body>

</html>
