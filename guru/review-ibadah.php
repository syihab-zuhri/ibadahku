<?php
session_start();

if(!isset($_SESSION['id_user']) || $_SESSION['role'] != 'guru'){
    header("Location:../login.php");
    exit();
}

require '../config.php';

$id_log = $_GET['id'];

$query = "SELECT log_ibadah.*,users.nama_lengkap
        FROM log_ibadah
        JOIN users ON log_ibadah.id_user = users.id
        WHERE log_ibadah.id='$id_log' AND users.role = 'siswa'";

$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data){
    header("location: dashboard.php");
    exit();
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Review Laporan - Ibadahku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Validasi Laporan Siswa</h5>
            </div>
            <div class="card-body">
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="text-primary">Data Siswa</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td width="100">Nama</td>
                                <td>: <strong><?php echo htmlspecialchars($data['nama_lengkap']); ?></strong></td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>: <?php echo date('d M Y', strtotime($data['tanggal'])); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-primary">Rincian Ibadah</h5>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Sholat Subuh 
                                    <?php if ($data['sholat_subuh'] == 1): ?>
                                <span class="badge bg-success">✔</span>
                                    <?php else: ?>
                                <span class="badge bg-danger">❌</span>
                                    <?php endif; ?>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Sholat Dzuhur 
                                    <?php if ($data['sholat_dzuhur'] == 1): ?>
                                <span class="badge bg-success">✔</span>
                                    <?php else: ?>
                                <span class="badge bg-danger">❌</span>
                                    <?php endif; ?>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Sholat Ashar 
                                    <?php if ($data['sholat_ashar'] == 1): ?>
                                <span class="badge bg-success">✔</span>
                                    <?php else: ?>
                                <span class="badge bg-danger">❌</span>
                                    <?php endif; ?>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Sholat Maghrib 
                                    <?php if ($data['sholat_maghrib'] == 1): ?>
                                <span class="badge bg-success">✔</span>
                                    <?php else: ?>
                                <span class="badge bg-danger">❌</span>
                                    <?php endif; ?>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Sholat Isya 
                                    <?php if ($data['sholat_isya'] == 1): ?>
                                <span class="badge bg-success">✔</span>
                                    <?php else: ?>
                                <span class="badge bg-danger">❌</span>
                                    <?php endif; ?>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Tilawah 
                                <span class="badge bg-secondary">
                                    <?php echo $data['tilawah_halaman']; ?>
                                    Halaman
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <hr>

                <form action="proses-review.php" method="POST">
                    <input type="hidden" name="id_log" value="<?php echo $data['id']; ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status Validasi</label>
                        <select class="form-select" name="status_validasi">
                            <option value="menunggu" <?php if ($data['status_validasi'] == 'menunggu') echo 'selected'; ?>>Menunggu</option>
                            <option value="disetujui" <?php if ($data['status_validasi'] == 'disetujui') echo 'selected'; ?>>Disetujui ✅</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan Guru (Feedback)</label>
                        <textarea class="form-control" name="catatan_guru" rows="3">
                            <?php if(isset($row['catatan_guru'])): echo htmlspecialchars($row['catatan_guru']); endif; ?>
                        </textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Simpan Validasi</button>
                        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>