<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !='siswa'){
    header("location: ../login.php");
    exit();
}
require '../config.php';

$id_log = $_GET['id'];

$query = "SELECT * FROM log_ibadah WHERE id = '$id_log'";
$result = mysqli_query($koneksi, $query);

$log = mysqli_fetch_assoc($result);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Input Ibadah - Aplikasi Monitoring Ibadah</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Monitoring Ibadah</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="input-ibadah.php">Input Ibadah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h4>Perbarui Laporan Ibadah Harian</h4>
            </div>
            <div class="card-body">
                <form action="../proses-edit.php" method="POST">
                    <input type="hidden" name="id_log" value="<?php echo $log['id']; ?>">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value = "<?php echo $log['tanggal']; ?>"required>
                    </div>

                    <p class="fw-bold">Sholat Fardhu</p>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="subuh" name="sholat_subuh" <?php if ($log['sholat_subuh'] == 1) echo 'checked'; ?>>
                                <label class="form-check-label" for="subuh">Subuh</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="dzuhur" name="sholat_dzuhur" <?php if ($log['sholat_dzuhur'] == 1) echo 'checked'; ?>>
                                <label class="form-check-label" for="dzuhur">Dzuhur</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="ashar" name="sholat_ashar" <?php if ($log['sholat_ashar'] == 1) echo 'checked'; ?>>
                                <label class="form-check-label" for="ashar">Ashar</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="maghrib" name="sholat_maghrib" <?php if ($log['sholat_maghrib'] == 1) echo 'checked'; ?>>
                                <label class="form-check-label" for="maghrib">Maghrib</label>
                            </div>
                             <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="isya" name="sholat_isya" <?php if ($log['sholat_isya'] == 1) echo 'checked'; ?>>
                                <label class="form-check-label" for="isya">Isya</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>

                    <div class="mb-3">
                        <label for="tilawah" class="form-label">Tilawah Al-Qur'an (Jumlah Halaman)</label>
                        <input type="number" class="form-control" id="tilawah" name="tilawah_halaman" value="<?php echo $log['tilawah_halaman']; ?>" min="0">
                    </div>

                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan Tambahan (Opsional)</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3"><?php echo $log['catatan']; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">perbarui laporan</button>
                    <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>