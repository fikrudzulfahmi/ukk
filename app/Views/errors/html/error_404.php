<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - UKK System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f0f2f5;
        }

        .error-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-card {
            max-width: 450px;
            width: 100%;
            padding: 40px;
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .error-icon {
            font-size: 5rem;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .btn-home {
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="container error-container">
        <div class="card error-card">
            <div class="error-icon">
                <i class="bi bi-exclamation-diamond"></i>
            </div>
            <h1 class="fw-bold text-dark">404</h1>
            <h4 class="text-secondary mb-3">Halaman Tidak Ditemukan</h4>
            <p class="text-muted mb-4">
                Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan.
            </p>
            <a href="<?= base_url() ?>" class="btn btn-primary btn-home">
                <i class="bi bi-house-door me-2"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

</body>

</html>