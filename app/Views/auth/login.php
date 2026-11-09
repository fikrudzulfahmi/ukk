<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | E-UKK TSM — SMK Islam 1 Blitar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/ukk.css') ?>">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background:
                radial-gradient(900px 500px at 8% -10%, rgba(16, 185, 129, .15), transparent 60%),
                radial-gradient(700px 480px at 100% 110%, rgba(5, 150, 105, .12), transparent 62%),
                #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .auth-shell {
            width: 100%;
            max-width: 940px;
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            background: #ffffff;
            border: 1px solid var(--uk-line);
            border-radius: 26px;
            overflow: hidden;
            box-shadow: 0 30px 80px -50px rgba(6, 78, 59, .35), 0 2px 6px rgba(0, 0, 0, .04);
            animation: rise .55s cubic-bezier(.22, .61, .36, 1) both;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: none; }
        }

        /* ---------- brand panel ---------- */
        .auth-brand {
            position: relative;
            padding: 2.8rem 2.4rem;
            color: #fff;
            background:
                radial-gradient(120% 80% at 85% 0%, rgba(16, 185, 129, .4), transparent 55%),
                linear-gradient(155deg, #064e3b 0%, #065f46 55%, #047857 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .auth-brand::after {
            content: '';
            position: absolute;
            width: 340px;
            height: 340px;
            right: -140px;
            bottom: -150px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .12);
            box-shadow: 0 0 0 40px rgba(255, 255, 255, .03), 0 0 0 90px rgba(255, 255, 255, .02);
        }

        .auth-logo {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .25);
            display: grid;
            place-items: center;
            padding: 8px;
            backdrop-filter: blur(8px);
        }

        .auth-logo img { max-width: 100%; max-height: 100%; object-fit: contain; }

        .auth-brand h1 {
            font-size: 1.85rem;
            font-weight: 800;
            letter-spacing: -.035em;
            color: #fff;
            margin: 1.6rem 0 .6rem;
            line-height: 1.15;
        }

        .auth-brand p.lead-sm {
            font-size: .9rem;
            color: rgba(255, 255, 255, .78);
            max-width: 32ch;
            margin-bottom: 1.85rem;
            line-height: 1.55;
        }

        .auth-points { list-style: none; padding: 0; margin: 0 0 1rem; display: grid; gap: .7rem; }

        .auth-points li {
            display: flex;
            align-items: center;
            gap: .65rem;
            font-size: .845rem;
            color: rgba(255, 255, 255, .88);
            font-weight: 550;
        }

        .auth-points i {
            width: 24px;
            height: 24px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            font-size: .75rem;
            background: rgba(255, 255, 255, .2);
            color: #fff;
        }

        .auth-brand footer {
            font-size: .72rem;
            color: rgba(255, 255, 255, .55);
            letter-spacing: .04em;
        }

        /* ---------- form panel ---------- */
        .auth-form {
            padding: 2.85rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-eyebrow {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--uk-brand);
            margin-bottom: .45rem;
        }

        .auth-form h2 {
            font-size: 1.55rem;
            font-weight: 800;
            letter-spacing: -.03em;
            color: var(--uk-ink);
            margin-bottom: .35rem;
        }

        .auth-form .sub {
            font-size: .865rem;
            color: var(--uk-muted);
            margin-bottom: 1.75rem;
        }

        .auth-form .form-label { font-size: .78rem; font-weight: 600; color: var(--uk-ink-2); }

        .auth-form .input-group-text {
            background: #f8fafc;
            border: 1px solid var(--uk-line);
            color: var(--uk-muted);
            border-radius: 12px 0 0 12px;
            padding-inline: .85rem;
        }

        .auth-form .form-control { border-radius: 0 12px 12px 0; border-left: none; padding-block: .68rem; border-color: var(--uk-line); }
        .auth-form .form-control:focus { border-color: var(--uk-brand); }
        .auth-form .pw-toggle { border-radius: 0 12px 12px 0; cursor: pointer; background: #f8fafc; }
        .auth-form .pw-toggle:hover { color: var(--uk-brand); }

        .auth-form .btn-primary { padding-block: .78rem; font-size: .92rem; }

        .auth-meta {
            margin-top: 1.5rem;
            font-size: .74rem;
            color: var(--uk-faint);
            text-align: center;
            line-height: 1.6;
        }

        @media (max-width: 860px) {
            .auth-shell { grid-template-columns: 1fr; max-width: 460px; }
            .auth-brand { padding: 2rem 1.75rem; }
            .auth-brand h1 { font-size: 1.45rem; margin-top: 1.1rem; }
            .auth-brand p.lead-sm, .auth-points, .auth-brand footer { display: none; }
            .auth-form { padding: 2.1rem 1.6rem 2.3rem; }
        }
    </style>
</head>

<body>

    <div class="auth-shell">
        <!-- brand side -->
        <aside class="auth-brand">
            <div>
                <div class="auth-logo">
                    <img src="<?= $logo_smki; ?>" alt="Logo SMK Islam 1 Blitar">
                </div>
                <h1>Aplikasi UKK<br>Teknik Sepeda Motor</h1>
                <p class="lead-sm">Sistem informasi digital pelaksanaan Uji Kompetensi Keahlian — penjadwalan, penilaian, dan rekapitulasi nilai real-time.</p>

                <ul class="auth-points">
                    <li><i class="bi bi-check-lg"></i> Penilaian praktik tandem real-time (Skala 4)</li>
                    <li><i class="bi bi-check-lg"></i> Ujian teori online anti-curang &amp; kunci otomatis</li>
                    <li><i class="bi bi-check-lg"></i> Cetak kartu peserta, presensi, &amp; berita acara</li>
                </ul>
            </div>
            <footer>&copy; <?= date('Y') ?> SMK Islam 1 Blitar &middot; Jurusan TSM</footer>
        </aside>

        <!-- form side -->
        <section class="auth-form">
            <div class="auth-eyebrow">Portal Masuk</div>
            <h2>Selamat datang</h2>
            <p class="sub">Masuk dengan akun yang telah diberikan admin UKK.</p>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div class="small"><?= session()->getFlashdata('error') ?></div>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('auth/login') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus autocomplete="username">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required autocomplete="current-password">
                        <span class="input-group-text pw-toggle" id="togglePassword" aria-label="Tampilkan password">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Masuk Sekarang <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </form>

            <div class="auth-meta">
                Lupa akses akun? Hubungi panitia UKK atau pengawas ruangan.<br>
                &copy; <?= date('Y') ?> Teknik Sepeda Motor &mdash; SMK Islam 1 Blitar
            </div>
        </section>
    </div>

    <script>
        const toggleBtn = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        toggleBtn.addEventListener('click', function() {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });

        const loginForm = document.querySelector('form');
        const loginBtn = loginForm.querySelector('button[type="submit"]');

        loginForm.addEventListener('submit', function() {
            const originalText = loginBtn.innerHTML;
            loginBtn.disabled = true;
            loginBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mohon tunggu...`;

            setTimeout(() => {
                if (loginBtn.disabled) {
                    loginBtn.disabled = false;
                    loginBtn.innerHTML = originalText;
                }
            }, 10000);
        });
    </script>
</body>

</html>
