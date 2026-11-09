<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
    <div class="d-flex align-items-center mb-4 px-2">
        <i class="bi bi-wrench-adjustable-circle fs-4 me-2 text-warning"></i>
        <span class="fs-5 fw-bold">UKK TBSM</span>
    </div>

    <ul class="nav flex-column gap-2">
        <li class="nav-item">
            <a class="nav-link rounded" href="<?= base_url('dashboard') ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <?php if (session()->get('role') == 'admin'): ?>
            <div class="text-uppercase text-muted small mt-3 mb-1 px-2" style="font-size: 0.7rem;">Master Data</div>
            <li class="nav-item">
                <a class="nav-link rounded" href="<?= base_url('event') ?>">
                    <i class="bi bi-calendar-event me-2"></i> Session Event
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded" href="#">
                    <i class="bi bi-people me-2"></i> Data Siswa
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded" href="#">
                    <i class="bi bi-person-badge me-2"></i> Data Penguji
                </a>
            </li>
        <?php endif; ?>

        <div class="text-uppercase text-muted small mt-3 mb-1 px-2" style="font-size: 0.7rem;">Pelaksanaan</div>
        <li class="nav-item">
            <a class="nav-link rounded" href="#">
                <i class="bi bi-clipboard-check me-2"></i> Penilaian
            </a>
        </li>

        <li class="nav-item mt-4">
            <a class="nav-link rounded text-danger" href="<?= base_url('logout') ?>">
                <i class="bi bi-box-arrow-left me-2"></i> Logout
            </a>
        </li>
    </ul>
</nav>