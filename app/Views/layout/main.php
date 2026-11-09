<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'UKK System' ?> | TSM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #4361ee;
            --sidebar-bg: #1e1e2d;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 85px;
            --transition-speed: 0.3s;
            --text-muted: #a2a3b7;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fb;
            color: #3f4254;
            margin: 0;
            overflow-x: hidden;
        }

        /* --- SIDEBAR BASE --- */
        .sidebar {
            background: var(--sidebar-bg);
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1060;
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        /* --- CUSTOM SCROLLBAR --- */
        .sidebar-scroll-area {
            overflow-y: auto;
            overflow-x: hidden;
            flex-grow: 1;
            padding-bottom: 30px;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
        }

        .sidebar-scroll-area::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll-area::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll-area::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        /* --- BRANDING --- */
        .sidebar-brand {
            padding: 25px;
            white-space: nowrap;
            overflow: hidden;
        }

        /* --- NAV LINK STYLING --- */
        .nav-link {
            color: var(--text-muted);
            padding: 12px 18px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: 0.2s;
            border-radius: 8px;
            margin: 2px 15px;
            white-space: nowrap;
        }

        .nav-link i {
            min-width: 30px;
            /* Menjaga ikon tetap sejajar */
            font-size: 1.25rem;
            display: inline-flex;
            align-items: center;
        }

        .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-link.active {
            color: #fff !important;
            background: var(--primary-color) !important;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }

        /* --- SECTION LABEL --- */
        .section-label {
            color: #565674;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 20px 25px 8px;
            white-space: nowrap;
        }

        /* --- EVENT CARD --- */
        .event-card-wrapper {
            padding: 0 20px;
            margin-bottom: 10px;
        }

        .event-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 12px;
            text-align: center;
        }

        /* --- COLLAPSED STATE --- */
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar.collapsed .brand-text,
        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .section-label,
        .sidebar.collapsed .event-card-wrapper,
        .sidebar.collapsed .badge-role-text {
            display: none !important;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            margin: 4px 15px;
            padding: 12px 0;
        }

        .sidebar.collapsed .nav-link i {
            min-width: unset;
            margin: 0;
        }

        /* --- CONTENT WRAPPER --- */
        .content-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: all var(--transition-speed) ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed+.content-wrapper {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* --- TOPBAR --- */
        .topbar {
            height: 70px;
            background: #fff;
            border-bottom: 1px solid #ebedf3;
            padding: 0 25px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* --- FLOATING TOGGLE --- */
        .btn-float-toggle {
            position: absolute;
            right: -15px;
            top: 30px;
            width: 30px;
            height: 30px;
            background: #fff;
            border: 1px solid #ebedf3;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1100;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            color: var(--primary-color);
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 991.98px) {
            .sidebar {
                left: -280px;
            }

            .sidebar.active {
                left: 0 !important;
            }

            .content-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .btn-float-toggle {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <nav class="sidebar" id="sidebar">
            <button id="toggle-sidebar" class="btn-float-toggle">
                <i class="bi bi-chevron-left" id="toggle-icon"></i>
            </button>

            <div class="sidebar-brand d-flex align-items-center">
                <i class="bi bi-gear-wide-connected fs-3 text-warning me-3"></i>
                <span class="brand-text h5 mb-0 fw-bold text-white">E-UKK TSM</span>
            </div>

            <div class="sidebar-scroll-area">
                <div class="event-card-wrapper">
                    <div class="event-card">
                        <div class="text-uppercase mb-1" style="font-size: 9px; letter-spacing: 1px; color: #565674;">Event Aktif</div>
                        <p class="text-info" style="font-size: 12px;"><?= session()->get('nama_event') ?? 'UJI KOMPETENSI KEAHLIAN' ?></p>
                        <div class="fw-bold text-primary small text-truncate" style="margin-top: -15px;">
                            <?= session()->get('tahun_pelajaran') ?? 'Belum Ada Tahun Pelajaran' ?>
                        </div>
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
                            <i class="bi bi-calendar-check"></i> <span class="nav-text">Session Event</span>
                        </a>

                        <div class="section-label">Data Master</div>
                        <a href="<?= base_url('pos') ?>" class="nav-link <?= (url_is('pos*') ? 'active' : '') ?>">
                            <i class="bi bi-door-open"></i> <span class="nav-text">Daftar Pos</span>
                        </a>
                        <a href="<?= base_url('elemen') ?>" class="nav-link <?= (url_is('elemen*') ? 'active' : '') ?>">
                            <i class="bi bi-list-check"></i> <span class="nav-text">Elemen Nilai</span>
                        </a>
                        <a href="<?= base_url('siswa') ?>" class="nav-link <?= (url_is('siswa*') ? 'active' : '') ?>">
                            <i class="bi bi-people"></i> <span class="nav-text">Peserta Siswa</span>
                        </a>
                        <a href="<?= base_url('jadwal') ?>" class="nav-link <?= (url_is('jadwal*') ? 'active' : '') ?>">
                            <i class="bi bi-calendar2-week"></i> <span class="nav-text">Jadwal Siswa</span>
                        </a>
                        <a href="<?= base_url('penguji') ?>" class="nav-link <?= (url_is('penguji*') ? 'active' : '') ?>">
                            <i class="bi bi-person-badge"></i> <span class="nav-text">Data Penguji</span>
                        </a>
                        <a href="<?= base_url('plotting') ?>" class="nav-link <?= (url_is('plotting*') ? 'active' : '') ?>">
                            <i class="bi bi-person-workspace"></i> <span class="nav-text">Plotting</span>
                        </a>
                        <a href="<?= base_url('soalteori') ?>" class="nav-link <?= (url_is('soalteori*') ? 'active' : '') ?>">
                            <i class="bi bi-file-earmark-text"></i> <span class="nav-text">Soal Teori</span>
                        </a>
                    <?php endif; ?>

                    <div class="section-label">Pelaksanaan</div>
                    <a href="<?= base_url('monitoring/matriks') ?>" class="nav-link <?= (url_is('monitoring/matriks*') ? 'active' : '') ?>">
                        <i class="bi bi-speedometer2"></i> <span class="nav-text">Monitoring Nilai</span>
                    </a>

                    <?php if (session()->get('role') == 'admin'): ?>
                        <a href="<?= base_url('nilai_teori') ?>" class="nav-link <?= (url_is('nilai_teori*') ? 'active' : '') ?>">
                            <i class="bi bi-journal-check"></i> <span class="nav-text">Nilai Teori</span>
                        </a>
                        <a href="<?= base_url('laporan') ?>" class="nav-link <?= (url_is('laporan*') ? 'active' : '') ?>">
                            <i class="bi bi-printer"></i> <span class="nav-text">Rekap Nilai</span>
                        </a>
                        <a href="<?= base_url('dokumentasi') ?>" class="nav-link <?= (url_is('dokumentasi*') ? 'active' : '') ?>">
                            <i class="bi bi-image"></i> <span class="nav-text">Dokumentasi</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="sidebar-footer mt-auto pb-4">
                <hr class="mx-4 border-secondary opacity-25">
                <a href="javascript:void(0)" onclick="logoutConfirm()" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-left"></i> <span class="nav-text">Keluar</span>
                </a>
            </div>
        </nav>

        <div class="content-wrapper">
            <header class="topbar d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="btn btn-light d-lg-none me-3" id="btn-mobile-toggle">
                        <i class="bi bi-list fs-3"></i>
                    </button>
                    <div class="fw-semibold text-dark d-none d-sm-block">
                        <span class="text-muted fw-normal">Selamat Datang,</span>
                        <span class="text-primary fw-bold"><?= session()->get('nama_lengkap') ?></span>
                    </div>
                </div>

                <div class="dropdown">
                    <span class="badge bg-light text-dark border p-2 px-3 rounded-pill shadow-sm">
                        <i class="bi bi-record-fill text-success me-1"></i>
                        <span class="badge-role-text">Role:</span> <span class="text-primary"><?= strtoupper(session()->get('role')) ?></span>
                    </span>
                </div>
            </header>

            <main class="p-4 p-md-5">
                <div class="mb-4">
                    <h2 class="fw-bold mb-1 text-dark"><?= $title ?? 'Dashboard' ?></h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item small"><a href="#" class="text-decoration-none text-muted">Home</a></li>
                            <li class="breadcrumb-item small active fw-semibold text-primary"><?= $title ?? 'Dashboard' ?></li>
                        </ol>
                    </nav>
                </div>

                <div class="content-body">
                    <?= $this->renderSection('content') ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnMobile = document.getElementById('btn-mobile-toggle');
            const btnDesktop = document.getElementById('toggle-sidebar');
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('toggle-icon');
            const tabletBreakpoint = 992;

            // 1. Mobile Toggle
            if (btnMobile) {
                btnMobile.addEventListener('click', (e) => {
                    e.stopPropagation();
                    sidebar.classList.toggle('active');
                });
            }

            // 2. Desktop Toggle
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

            // 3. Click outside to close (Mobile)
            document.addEventListener('click', (e) => {
                if (window.innerWidth < tabletBreakpoint && sidebar.classList.contains('active')) {
                    if (!sidebar.contains(e.target) && !btnMobile.contains(e.target)) {
                        sidebar.classList.remove('active');
                    }
                }
            });

            // 4. Restore State
            const savedState = localStorage.getItem('sidebar-state');
            if (window.innerWidth >= tabletBreakpoint && savedState === 'small') {
                sidebar.classList.add('collapsed');
                if (toggleIcon) toggleIcon.classList.replace('bi-chevron-left', 'bi-chevron-right');
            }
        });
    </script>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(url) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Semua data siswa dan nilai dalam session ini akan ikut terhapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
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
                confirmButtonColor: '#4361ee', // Warna primary kita
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal',
                reverseButtons: true // Memposisikan tombol 'Batal' di kiri
            }).then((result) => {
                if (result.isConfirmed) {
                    // Arahkan ke route logout Anda
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
        $('#tableSiswa').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('siswa/get_data') ?>",
                "type": "POST",
                "data": function(d) {
                    // Ini untuk mengirimkan token CSRF terbaru setiap kali request
                    d.<?= csrf_token() ?> = "<?= csrf_hash() ?>";
                },
                "error": function(xhr, error, thrown) {
                    // Diagnosa Error: Lihat pesan asli dari server di konsol browser
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

        $(document).ready(function() {
            // Gunakan delegasi event $(document).on karena baris tabel dibuat dinamis
            $(document).on('click', '.btn-edit', function() {
                // Ambil data dari atribut tombol
                const id = $(this).data('id');
                const nisn = $(this).data('nisn');
                const nama = $(this).data('nama');
                const kelas = $(this).data('kelas');

                // Isi input di dalam modal
                $('#edit_nisn').val(nisn);
                $('#edit_nama').val(nama);
                $('#edit_kelas').val(kelas);

                // Ubah action form secara dinamis
                $('#formEditSiswa').attr('action', '<?= base_url('siswa/update') ?>/' + id);

                // Munculkan modal
                $('#modalEdit').modal('show');
            });
        });
    </script>

</body>

</html>