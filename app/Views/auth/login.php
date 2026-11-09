<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Informasi UKK TSM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #0d6efd;
            --dark-blue: #0a2647;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #b9d4ff 100%);
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            margin: auto;
        }

        .card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .brand-logo {
            width: 80px;
            height: auto;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--dark-blue);
            box-shadow: 0 8px 15px rgba(13, 110, 253, 0.2);
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            background-color: #fcfcfc;
        }

        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
            border-color: var(--primary-color);
        }

        .input-group-text {
            border-radius: 12px;
            background: transparent;
            border-right: none;
        }

        .has-icon .form-control {
            border-left: none;
        }

        .login-header h4 {
            color: var(--dark-blue);
            letter-spacing: -0.5px;
        }

        /* Animasi masuk */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</head>

<body>

    <div class="login-card animate-fade">
        <div class="text-center mb-4">
            <img src="<?= $logo_smki; ?>" alt="Logo Sekolah" class="brand-logo">
            <div class="login-header">
                <h4 class="fw-800 mb-0">APLIKASI UKK TSM</h4>
                <p class="text-muted small">SMK Islam 1 Blitar</p>
            </div>
        </div>

        <div class="card shadow-lg">
            <div class="card-body p-4 p-md-5">

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div class="small"><?= session()->getFlashdata('error') ?></div>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-600 small text-secondary">Username</label>
                        <div class="input-group">
                            <span class="input-group-text text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600 small text-secondary">Password</label>
                        <div class="input-group">
                            <span class="input-group-text text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password" required style="border-right: none;">
                            <span class="input-group-text text-muted" id="togglePassword" style="cursor: pointer; background: #fcfcfc;">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        Login Sekarang <i class="bi bi-arrow-right-short ms-1"></i>
                    </button>
                </form>

            </div>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted small">
                &copy; 2026 <strong>Teknik Sepeda Motor</strong><br>
                SMK Islam 1 Blitar
            </p>
        </div>
    </div>

    <script>
        // 1. Logika Toggle Password (Melihat Isi Password)
        const toggleBtn = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        toggleBtn.addEventListener('click', function() {
            const isPassword = passwordInput.getAttribute('type') === 'password';

            // Ubah Tipe Input
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

            // Ubah Ikon
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');

            // Animasi klik
            this.style.transform = 'scale(1.2)';
            setTimeout(() => this.style.transform = 'scale(1)', 100);
        });

        // 2. Logika Loading Button (Mencegah Klik Ganda)
        const loginForm = document.querySelector('form');
        const loginBtn = loginForm.querySelector('button[type="submit"]');

        loginForm.addEventListener('submit', function() {
            // Simpan teks asli
            const originalText = loginBtn.innerHTML;

            // Nonaktifkan tombol agar tidak bisa diklik lagi
            loginBtn.disabled = true;

            // Ubah tampilan menjadi loading
            loginBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Mohon Tunggu...
        `;

            // Jika terjadi error (misal koneksi terputus), tombol akan kembali aktif setelah 10 detik
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