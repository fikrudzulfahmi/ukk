<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'UKK System' ?> | E-UKK TSM</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/ukk.css') ?>">

    <style>
        :root {
            --sidebar-width: 268px;
            --sidebar-collapsed-width: 88px;
            --sidebar-bg: #ffffff;
            --transition-speed: .28s;
        }

        /* ---------------- SIDEBAR (CLEAN WHITE AESTHETIC - OPSI A) ---------------- */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            z-index: 1060;
            display: flex;
            flex-direction: column;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--uk-line);
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.02);
            transition: width var(--transition-speed) cubic-bezier(.4, 0, .2, 1);
        }

        .sidebar-scroll-area {
            flex: 1 1 auto;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 1.5rem;
            scrollbar-width: thin;
            scrollbar-color: var(--uk-line-strong) transparent;
        }

        .sidebar-scroll-area::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll-area::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll-area::-webkit-scrollbar-thumb { background: var(--uk-line-strong); border-radius: 10px; }

        /* branding */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: .8rem;
            padding: 1.35rem 1.25rem 1.1rem;
            white-space: nowrap;
            overflow: hidden;
            border-bottom: 1px solid var(--uk-line);
        }

        .brand-mark {
            flex: 0 0 auto;
            width: 42px;
            height: 42px;
            border-radius: 13px;
            display: grid;
            place-items: center;
            background: var(--uk-gradient);
            color: #fff;
            font-size: 1.15rem;
            box-shadow: 0 8px 18px -6px rgba(5, 150, 105, 0.6);
        }

        .brand-copy { line-height: 1.15; overflow: hidden; }
        .brand-copy .brand-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .98rem;
            font-weight: 800;
            color: var(--uk-ink);
            letter-spacing: -.02em;
            display: block;
        }
        .brand-copy .brand-sub {
            font-size: .69rem;
            color: var(--uk-muted);
            letter-spacing: .08em;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* event card */
        .event-card-wrapper { padding: 1.1rem 1rem .85rem; }

        .event-card {
            position: relative;
            overflow: hidden;
            border-radius: 14px;
            padding: .85rem .95rem;
            background: #f8fafc;
            border: 1px solid var(--uk-line);
        }

        .event-card::before {
            content: '';
            position: absolute;
            inset: 0 auto 0 0;
            width: 3px;
            background: var(--uk-brand);
        }

        .event-card .ec-label {
            display: flex;
            align-items: center;
            gap: .35rem;
            font-size: .64rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--uk-brand);
            margin-bottom: .35rem;
        }

        .event-card .ec-label .uk-dot {
            width: 6px;
            height: 6px;
            background: var(--uk-brand);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.16);
        }

        .event-card .ec-name {
            font-size: .82rem;
            font-weight: 600;
            color: var(--uk-ink);
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin: 0 0 .25rem;
        }

        .event-card .ec-year {
            font-size: .72rem;
            font-weight: 600;
            color: var(--uk-muted);
        }

        /* nav */
        .sidebar .nav { padding: 0 .65rem; }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .62rem .8rem;
            margin: .15rem 0;
            border-radius: 11px;
            color: var(--uk-ink-2);
            font-size: .865rem;
            font-weight: 550;
            white-space: nowrap;
            position: relative;
            transition: background-color .18s ease, color .18s ease, padding .2s ease;
        }

        .sidebar .nav-link i {
            font-size: 1.05rem;
            min-width: 22px;
            text-align: center;
            color: var(--uk-muted);
            transition: color .18s ease, transform .18s ease;
        }

        .sidebar .nav-link:hover {
            background: #f1f5f9;
            color: var(--uk-ink);
        }

        .sidebar .nav-link:hover i {
            color: var(--uk-brand);
            transform: translateX(1px);
        }

        .sidebar .nav-link.active {
            background: var(--uk-brand-soft);
            color: var(--uk-brand) !important;
            font-weight: 700;
            border: 1px solid var(--uk-brand-soft-border);
        }

        .sidebar .nav-link.active i {
            color: var(--uk-brand) !important;
        }

        .section-label {
            padding: 1.1rem .95rem .35rem;
            font-size: .63rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--uk-faint);
            white-space: nowrap;
        }

        /* footer */
        .sidebar-footer {
            border-top: 1px solid var(--uk-line);
            padding: .75rem .65rem 1rem;
            background: #ffffff;
        }

        .sidebar-footer .nav-link {
            color: var(--uk-danger);
        }

        .sidebar-footer .nav-link:hover {
            background: var(--uk-danger-soft);
            color: var(--uk-danger);
        }

        /* collapse */
        .sidebar.collapsed { width: var(--sidebar-collapsed-width); }
        .sidebar.collapsed .brand-copy,
        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .section-label,
        .sidebar.collapsed .event-card-wrapper { display: none !important; }
        .sidebar.collapsed .sidebar-brand { padding: 1.35rem 0 1.1rem; justify-content: center; }
        .sidebar.collapsed .nav-link { justify-content: center; padding: .68rem 0; }
        .sidebar.collapsed .nav-link i { min-width: 0; }

        /* floating toggle */
        .btn-float-toggle {
            position: absolute;
            right: -14px;
            top: 26px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1px solid var(--uk-line);
            background: #fff;
            color: var(--uk-brand);
            display: grid;
            place-items: center;
            cursor: pointer;
            z-index: 1100;
            font-size: .7rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            transition: transform .2s ease, background-color .2s ease;
        }

        .btn-float-toggle:hover { background: #f0fdf4; transform: scale(1.06); }

        /* ---------------- CONTENT ---------------- */
        .content-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left var(--transition-speed) ease, width var(--transition-speed) ease;
        }

        .sidebar.collapsed + .content-wrapper {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* topbar */
        .topbar {
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.75rem;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: saturate(180%) blur(12px);
            -webkit-backdrop-filter: saturate(180%) blur(12px);
            border-bottom: 1px solid var(--uk-line);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-greet {
            display: flex;
            flex-direction: column;
            line-height: 1.25;
        }

        .topbar-greet .tb-label {
            font-size: .74rem;
            color: var(--uk-muted);
            font-weight: 500;
        }

        .topbar-greet .tb-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .95rem;
            font-weight: 700;
            color: var(--uk-ink);
        }

        .tb-chip {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .42rem .8rem;
            border-radius: 999px;
            background: #fff;
            border: 1px solid var(--uk-line);
            box-shadow: var(--uk-shadow-xs);
            font-size: .77rem;
            font-weight: 600;
            color: var(--uk-ink-2);
        }

        /* main */
        .content-main { padding: 1.9rem 1.75rem 2.75rem; flex: 1 1 auto; }

        @media (max-width: 991.98px) {
            .sidebar { left: calc(-1 * var(--sidebar-width) - 12px); }
            .sidebar.active { left: 0 !important; }
            .content-wrapper { margin-left: 0 !important; width: 100% !important; }
            .btn-float-toggle { display: none !important; }
            .topbar { padding: 0 1.1rem; height: 66px; }
            .content-main { padding: 1.35rem 1.1rem 2rem; }
        }
    </style>
</head>
    <!-- jQuery & DataTables moved up for early use -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<body>
    <div class="d-flex">
        <!-- Sidebar Desktop -->
        <nav class="sidebar" id="sidebar">
            <button id="toggle-sidebar" class="btn-float-toggle" aria-label="Ciutkan menu">
                <i class="bi bi-chevron-left" id="toggle-icon"></i>
            </button>

            <div class="sidebar-brand">
                <span class="brand-mark"><i class="bi bi-mortarboard-fill"></i></span>
                <div class="brand-copy">
                    <span class="brand-title">E-UKK TSM</span>
                    <span class="brand-sub">SMK Islam 1 Blitar</span>
                </div>
            </div>

            <div class="sidebar-scroll-area">
                <div class="event-card-wrapper">
                    <div class="event-card">
                        <div class="ec-label"><span class="uk-dot"></span> Event Aktif</div>
                        <p class="ec-name"><?= esc(session()->get('nama_event') ?? 'Uji Kompetensi Keahlian') ?></p>
                        <span class="ec-year"><?= esc(session()->get('tahun_pelajaran') ?? 'Tahun pelajaran belum diset') ?></span>
                    </div>
                </div>

                <div class="nav flex-column">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= (url_is('dashboard*') ? 'active' : '') ?>">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>

                    <?php if (session()->get('role') == 'admin'): ?>
                        <div class="section-label">Konfigurasi</div>
                        <a href="<?= base_url('event') ?>" class="nav-link <?= (url_is('event*') ? 'active' : '') ?>">
                            <i class="bi bi-calendar-check"></i>
                            <span class="nav-text">Session Event</span>
                        </a>

                        <div class="section-label">Data Master</div>
                        <a href="<?= base_url('pos') ?>" class="nav-link <?= (url_is('pos*') ? 'active' : '') ?>">
                            <i class="bi bi-door-open"></i>
                            <span class="nav-text">Daftar Pos</span>
                        </a>
                        <a href="<?= base_url('elemen') ?>" class="nav-link <?= (url_is('elemen*') ? 'active' : '') ?>">
                            <i class="bi bi-list-check"></i>
                            <span class="nav-text">Elemen Nilai</span>
                        </a>
                        <a href="<?= base_url('siswa') ?>" class="nav-link <?= (url_is('siswa*') ? 'active' : '') ?>">
                            <i class="bi bi-people"></i>
                            <span class="nav-text">Peserta Siswa</span>
                        </a>
                        <a href="<?= base_url('jadwal') ?>" class="nav-link <?= (url_is('jadwal*') ? 'active' : '') ?>">
                            <i class="bi bi-calendar2-week"></i>
                            <span class="nav-text">Jadwal Siswa</span>
                        </a>
                        <a href="<?= base_url('penguji') ?>" class="nav-link <?= (url_is('penguji*') ? 'active' : '') ?>">
                            <i class="bi bi-person-gear"></i>
                            <span class="nav-text">Manajemen User</span>
                        </a>
                        <a href="<?= base_url('plotting') ?>" class="nav-link <?= (url_is('plotting*') ? 'active' : '') ?>">
                            <i class="bi bi-person-workspace"></i>
                            <span class="nav-text">Plotting</span>
                        </a>
                        <a href="<?= base_url('soalteori') ?>" class="nav-link <?= (url_is('soalteori*') ? 'active' : '') ?>">
                            <i class="bi bi-file-earmark-text"></i>
                            <span class="nav-text">Soal Teori</span>
                        </a>
                    <?php endif; ?>

                    <div class="section-label">Pelaksanaan</div>
                    <a href="<?= base_url('monitoring/matriks') ?>" class="nav-link <?= (url_is('monitoring/matriks*') ? 'active' : '') ?>">
                        <i class="bi bi-speedometer2"></i>
                        <span class="nav-text">Monitoring Nilai</span>
                    </a>

                    <?php if (session()->get('role') == 'admin'): ?>
                        <a href="<?= base_url('nilai_teori') ?>" class="nav-link <?= (url_is('nilai_teori*') ? 'active' : '') ?>">
                            <i class="bi bi-journal-check"></i>
                            <span class="nav-text">Nilai Teori</span>
                        </a>
                        <a href="<?= base_url('laporan') ?>" class="nav-link <?= (url_is('laporan*') ? 'active' : '') ?>">
                            <i class="bi bi-printer"></i>
                            <span class="nav-text">Rekap Nilai</span>
                        </a>
                        <a href="<?= base_url('dokumentasi') ?>" class="nav-link <?= (url_is('dokumentasi*') ? 'active' : '') ?>">
                            <i class="bi bi-image"></i>
                            <span class="nav-text">Dokumentasi</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="sidebar-footer">
                <a href="javascript:void(0)" onclick="logoutConfirm()" class="nav-link">
                    <i class="bi bi-box-arrow-left"></i>
                    <span class="nav-text">Keluar</span>
                </a>
            </div>
        </nav>

        <!-- Main Wrapper -->
        <div class="content-wrapper">
            <header class="topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light btn-icon d-lg-none" id="btn-mobile-toggle" aria-label="Buka menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div class="topbar-greet d-none d-sm-flex">
                        <span class="tb-label">Selamat datang kembali,</span>
                        <span class="tb-name"><?= esc(session()->get('nama_lengkap') ?? 'Pengguna') ?></span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="tb-chip d-none d-md-inline-flex">
                        <i class="bi bi-calendar3 text-muted"></i>
                        <?= date('d M Y') ?>
                    </span>
                    <span class="tb-chip">
                        <span class="uk-dot"></span>
                        <span class="badge-role-text text-muted fw-normal">Role</span>
                        <span class="text-primary fw-bold"><?= strtoupper(session()->get('role') ?? '-') ?></span>
                    </span>
                </div>
            </header>

            <main class="content-main">
                <div class="uk-page-head">
                    <div>
                        <h1 class="uk-page-title"><?= $title ?? 'Dashboard' ?></h1>
                        <ol class="uk-breadcrumb">
                            <li><a href="<?= base_url('dashboard') ?>">Home</a></li>
                            <li class="sep"><i class="bi bi-chevron-right"></i></li>
                            <li class="current"><?= $title ?? 'Dashboard' ?></li>
                        </ol>
                    </div>
                </div>

                <div class="content-body">
                    <?= $this->renderSection('content') ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="uk-bottom-bar">
        <ul class="uk-bottom-bar-nav">
            <li class="uk-bottom-bar-item">
                <a href="<?= base_url('dashboard') ?>" class="uk-bottom-link <?= (url_is('dashboard*') ? 'active' : '') ?>">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Beranda</span>
                </a>
            </li>
            <?php if (session()->get('role') == 'admin'): ?>
                <li class="uk-bottom-bar-item">
                    <a href="<?= base_url('siswa') ?>" class="uk-bottom-link <?= (url_is('siswa*') ? 'active' : '') ?>">
                        <i class="bi bi-people-fill"></i>
                        <span>Siswa</span>
                    </a>
                </li>
                <li class="uk-bottom-bar-item">
                    <a href="<?= base_url('jadwal') ?>" class="uk-bottom-link <?= (url_is('jadwal*') ? 'active' : '') ?>">
                        <i class="bi bi-calendar-week-fill"></i>
                        <span>Jadwal</span>
                    </a>
                </li>
            <?php endif; ?>
            <li class="uk-bottom-bar-item">
                <a href="<?= base_url('monitoring/matriks') ?>" class="uk-bottom-link <?= (url_is('monitoring*') ? 'active' : '') ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Matriks</span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnMobile = document.getElementById('btn-mobile-toggle');
            const btnDesktop = document.getElementById('toggle-sidebar');
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('toggle-icon');
            const tabletBreakpoint = 992;

            if (btnMobile) {
                btnMobile.addEventListener('click', (e) => {
                    e.stopPropagation();
                    sidebar.classList.toggle('active');
                });
            }

            if (btnDesktop) {
                btnDesktop.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    const isCollapsed = sidebar.classList.contains('collapsed');

                    toggleIcon.classList.replace(
                        isCollapsed ? 'bi-chevron-left' : 'bi-chevron-right',
                        isCollapsed ? 'bi-chevron-right' : 'bi-chevron-left'
                    );

                    localStorage.setItem('sidebar-state', isCollapsed ? 'small' : 'large');
                });
            }

            document.addEventListener('click', (e) => {
                if (window.innerWidth < tabletBreakpoint && sidebar.classList.contains('active')) {
                    if (!sidebar.contains(e.target) && !btnMobile.contains(e.target)) {
                        sidebar.classList.remove('active');
                    }
                }
            });

            const savedState = localStorage.getItem('sidebar-state');
            if (window.innerWidth >= tabletBreakpoint && savedState === 'small') {
                sidebar.classList.add('collapsed');
                if (toggleIcon) toggleIcon.classList.replace('bi-chevron-left', 'bi-chevron-right');
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php
    $flashSuccess = session()->getFlashdata('success');
    $flashError   = session()->getFlashdata('error');
    $flashErrors  = session()->getFlashdata('errors');
    ?>
    <?php if ($flashSuccess || $flashError || $flashErrors): ?>
        <script>
            (function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: <?= $flashError ? 6000 : 4000 ?>,
                    timerProgressBar: true
                });

                <?php if ($flashSuccess): ?>
                    Toast.fire({
                        icon: 'success',
                        title: <?= json_encode((string) $flashSuccess, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>
                    });
                <?php endif; ?>

                <?php if ($flashError): ?>
                    Toast.fire({
                        icon: 'error',
                        title: <?= json_encode((string) $flashError, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>
                    });
                <?php endif; ?>

                <?php if ($flashErrors): ?>
                    const errList = <?= json_encode(array_values((array) $flashErrors), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;
                    if (errList.length) {
                        Toast.fire({
                            icon: 'warning',
                            title: errList.join(' ')
                        });
                    }
                <?php endif; ?>
            })();
        </script>
    <?php endif; ?>

    <script>
        function confirmDelete(url) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Semua data siswa dan nilai dalam session ini akan ikut terhapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }
    </script>

    <script>
        function logoutConfirm() {
            Swal.fire({
                title: 'Ingin Keluar?',
                text: "Anda harus login kembali untuk mengakses data.",
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            if ($('#tableSiswa').length) {
                $('#tableSiswa').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "<?= base_url('siswa/get_data') ?>",
                        "type": "POST",
                        "data": function(d) {
                            d.<?= csrf_token() ?> = "<?= csrf_hash() ?>";
                        },
                        "error": function(xhr) {
                            console.log("XHR Response: " + xhr.responseText);
                        }
                    },
                    "columnDefs": [{
                            "targets": [0, 4],
                            "orderable": false
                        },
                        {
                            "targets": [0, 1, 3, 4],
                            "className": "text-center"
                        }
                    ],
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
                    }
                });
            }

            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                const nisn = $(this).data('nisn');
                const nama = $(this).data('nama');
                const kelas = $(this).data('kelas');

                $('#edit_nisn').val(nisn);
                $('#edit_nama').val(nama);
                $('#edit_kelas').val(kelas);

                $('#formEditSiswa').attr('action', '<?= base_url('siswa/update') ?>/' + id);
                $('#modalEdit').modal('show');
            });
        });
    </script>
</body>

</html>
