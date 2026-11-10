<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penguji - UKK TBSM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-4">
        <h3 class="mb-4 text-center">Dashboard Penguji UKK</h3>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <label class="form-label fw-bold">1. Pilih Pos Penilaian</label>
                <select id="select-pos" class="form-select form-select-lg mb-3">
                    <?php foreach ($pos as $p): ?>
                        <option value="<?= $p['id_pos'] ?>"><?= $p['nama_pos'] ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="form-label fw-bold">2. Pilih 2 Siswa (Antrian)</label>
                <div class="list-group mb-3">
                    <?php foreach ($siswa as $s): ?>
                        <label class="list-group-item d-flex gap-3">
                            <input class="form-check-input flex-shrink-0" type="checkbox" name="siswa_pilihan" value="<?= $s['id_siswa'] ?>" style="width: 1.5em; height: 1.5em;">
                            <span>
                                <strong><?= $s['nama_siswa'] ?></strong>
                                <small class="d-block text-muted"><?= $s['kelas'] ?> - <?= $s['nisn'] ?></small>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <button onclick="mulaiUjian()" class="btn btn-primary btn-lg w-100">Mulai Penilaian Tandem</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function mulaiUjian() {
            const pos = document.getElementById('select-pos').value;
            const selectedSiswa = Array.from(document.querySelectorAll('input[name="siswa_pilihan"]:checked'))
                .map(cb => cb.value);

            if (selectedSiswa.length !== 2) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Tepat 2 Siswa',
                    text: 'Mode tandem membutuhkan tepat 2 siswa untuk diuji bersamaan.',
                    confirmButtonColor: '#059669',
                    confirmButtonText: 'Mengerti'
                });
                return;
            }

            // Redirect ke halaman matrix yang sudah kita buat sebelumnya
            window.location.href = `<?= base_url('penilaian/pos') ?>/${pos}/${selectedSiswa[0]}/${selectedSiswa[1]}`;
        }
    </script>

</body>

</html>