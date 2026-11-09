<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian Matrix - UKK TBSM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-check:checked+.btn-kompeten {
            background-color: #198754;
            color: white;
            border-color: #198754;
        }

        .btn-check:checked+.btn-tidak {
            background-color: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        .sticky-top-custom {
            position: sticky;
            top: 0;
            z-index: 1020;
            background: white;
            border-bottom: 2px solid #dee2e6;
        }

        .table-matrix td {
            vertical-align: middle;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container-fluid py-3">
        <div class="row sticky-top-custom pb-2 mb-3">
            <div class="col-4">
                <h5>Elemen Penilaian</h5>
            </div>
            <div class="col-4 text-center border-start">
                <small class="text-muted">Siswa 1</small>
                <div class="fw-bold"><?= $siswa1['nama_siswa'] ?></div>
            </div>
            <div class="col-4 text-center border-start">
                <small class="text-muted">Siswa 2</small>
                <div class="fw-bold"><?= $siswa2['nama_siswa'] ?></div>
            </div>
        </div>

        <?php foreach ($elemen as $e): ?>
            <div class="card mb-2 shadow-sm">
                <div class="card-body p-2">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <span class="small fw-bold"><?= $e['nama_elemen'] ?></span>
                        </div>

                        <div class="col-4 text-center">
                            <div class="btn-group btn-group-sm w-100" role="group">
                                <input type="radio" class="btn-check" name="s1_<?= $e['id_elemen'] ?>" id="s1_k_<?= $e['id_elemen'] ?>" autocomplete="off" onchange="saveNilai(<?= $siswa1['id_siswa'] ?>, <?= $e['id_elemen'] ?>, '1')">
                                <label class="btn btn-outline-success btn-kompeten" for="s1_k_<?= $e['id_elemen'] ?>">K</label>

                                <input type="radio" class="btn-check" name="s1_<?= $e['id_elemen'] ?>" id="s1_tk_<?= $e['id_elemen'] ?>" autocomplete="off" onchange="saveNilai(<?= $siswa1['id_siswa'] ?>, <?= $e['id_elemen'] ?>, '0')">
                                <label class="btn btn-outline-danger btn-tidak" for="s1_tk_<?= $e['id_elemen'] ?>">TK</label>
                            </div>
                        </div>

                        <div class="col-4 text-center">
                            <div class="btn-group btn-group-sm w-100" role="group">
                                <input type="radio" class="btn-check" name="s2_<?= $e['id_elemen'] ?>" id="s2_k_<?= $e['id_elemen'] ?>" autocomplete="off" onchange="saveNilai(<?= $siswa2['id_siswa'] ?>, <?= $e['id_elemen'] ?>, '1')">
                                <label class="btn btn-outline-success btn-kompeten" for="s2_k_<?= $e['id_elemen'] ?>">K</label>

                                <input type="radio" class="btn-check" name="s2_<?= $e['id_elemen'] ?>" id="s2_tk_<?= $e['id_elemen'] ?>" autocomplete="off" onchange="saveNilai(<?= $siswa2['id_siswa'] ?>, <?= $e['id_elemen'] ?>, '0')">
                                <label class="btn btn-outline-danger btn-tidak" for="s2_tk_<?= $e['id_elemen'] ?>">TK</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div id="save-status" class="position-fixed bottom-0 end-0 m-3 badge bg-dark p-2" style="display:none;">
            Menyimpan...
        </div>
    </div>

    <script>
        function saveNilai(idSiswa, idElemen, status) {
            const statusEl = document.getElementById('save-status');
            statusEl.style.display = 'block';
            statusEl.innerText = 'Menyimpan...';

            const formData = new FormData();
            formData.append('id_siswa', idSiswa);
            formData.append('id_elemen', idElemen);
            formData.append('status', status);

            fetch('/penilaian/save', {
                    method: 'POST',
                    body: formData,
                    // CI4 CSRF (jika aktif) bisa ditambahkan di sini
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        statusEl.innerText = 'Tersimpan';
                        setTimeout(() => {
                            statusEl.style.display = 'none';
                        }, 1000);
                    }
                })
                .catch(error => {
                    statusEl.classList.replace('bg-dark', 'bg-danger');
                    statusEl.innerText = 'Gagal Menyimpan!';
                });
        }
    </script>

</body>

</html>