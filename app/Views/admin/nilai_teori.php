<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-0">Input Nilai Teori</h5>
            <small class="text-muted">Nilai akan tersimpan otomatis saat Anda selesai mengisi angka</small>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableTeori">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" width="60">No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th width="150" class="text-center">Nilai Angka</th>
                        <th width="100" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($siswa as $i => $s): ?>
                        <tr>
                            <td class="ps-4 text-muted"><?= $i + 1 ?></td>
                            <td>
                                <div class="fw-bold"><?= $s['nama_siswa'] ?></div>
                                <small class="text-muted"><?= $s['nisn'] ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= $s['kelas'] ?></span></td>

                            <td>
                                <input type="number"
                                    class="form-control text-center fw-bold input-nilai"
                                    data-id="<?= $s['id_siswa'] ?>"
                                    value="<?= $s['nilai_angka'] ?>"
                                    placeholder="0" min="0" max="100" disabled>
                            </td>
                            <td>
                                <?php if (isset($s['is_locked']) && $s['is_locked'] == 1): ?>
                                    <button type="button"
                                        class="btn btn-warning btn-sm shadow-sm btn-unlock"
                                        data-url="<?= base_url('soalteori/buka_kunci/' . $s['id_siswa']) ?>"
                                        data-nama="<?= $s['nama_siswa'] ?>">
                                        <i class="bi bi-key-fill"></i> Buka Kunci
                                    </button>
                                <?php else: ?>
                                    <span class="badge bg-success">Sedang Mengerjakan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.input-nilai').on('change', function() {
            const input = $(this);
            const id_siswa = input.data('id');
            const nilai = input.val();
            const statusIcon = $('#status-' + id_siswa);

            // Feedback awal
            statusIcon.html('<div class="spinner-border spinner-border-sm text-primary"></div>');

            $.ajax({
                url: "<?= base_url('nilai_teori/simpan_ajax') ?>",
                method: "POST",
                dataType: "json",
                data: {
                    // CSRF Token CodeIgniter 4
                    "<?= csrf_token() ?>": "<?= csrf_hash() ?>",
                    "id_siswa": id_siswa,
                    "nilai": nilai
                },
                success: function(response) {
                    if (response.status === 'success') {
                        statusIcon.html('<i class="bi bi-check-circle-fill text-success"></i>');
                        input.addClass('is-valid').removeClass('is-invalid');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                    console.error("Status: " + status);
                    console.dir(xhr);

                    statusIcon.html('<i class="bi bi-exclamation-circle-fill text-danger" title="Gagal Simpan"></i>');
                    input.addClass('is-invalid');

                    // Jika error 403, biasanya masalah CSRF. Refresh halaman.
                    if (xhr.status == 403) {
                        alert("Sesi habis atau CSRF Error. Silakan refresh halaman.");
                    }
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.btn-unlock').on('click', function() {
            const url = $(this).data('url');
            const namaSiswa = $(this).data('nama');

            Swal.fire({
                title: 'Buka Kunci Ujian?',
                html: `Apakah Anda yakin ingin membuka kembali ujian untuk <b>${namaSiswa}</b>?<br><small class="text-danger">Siswa dapat mengubah kembali jawabannya.</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ffc107', // Warna kuning warning
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Buka Kunci!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading saat proses redirect
                    Swal.fire({
                        title: 'Memproses...',
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        allowOutsideClick: false,
                        showConfirmButton: false
                    });

                    // Arahkan ke URL buka kunci
                    window.location.href = url;
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>