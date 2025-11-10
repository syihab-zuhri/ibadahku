<?php

session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !='guru'){
    header("location: ../login.php");
    exit();
}
require '../config.php';

//mengambil data laporan ibadah dari data base
$query = "SELECT log_ibadah.*, users.nama_;lengkap
        FROM log_ibadah
        JOIN users ON log_ibadah.id_user = users.id
        WHERE users.role = 'siswa'
        ORDER BY log_ibadah.tanggal DESC";
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Guru - Ibadahku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Dashboard Guru</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h3>Selamat Datang, <?php echo $_SESSION['username']; ?>!</h3>
        <p class="text-muted">Berikut adalah rekap laporan ibadah dari semua siswa.</p>

        <div class="card mt-4">
            <div class="card-header">
                <h5>Riwayat Ibadah Siswa</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Siswa</th>
                                <th>Subuh</th>
                                <th>Dzuhur</th>
                                <th>Ashar</th>
                                <th>Maghrib</th>
                                <th>Isya</th>
                                <th>Tilawah (Hal)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>12 Okt 2025</td>
                                <td>Ahmad Abdullah</td>
                                <td>✔</td>
                                <td>✔</td>
                                <td>❌</td>
                                <td>✔</td>
                                <td>✔</td>
                                <td>2</td>
                                <td><span class="badge bg-success">Disetujui</span></td>
                            </tr>
                            <tr>
                                <td>11 Okt 2025</td>
                                <td>Siti Aisyah</td>
                                <td>✔</td>
                                <td>✔</td>
                                <td>✔</td>
                                <td>✔</td>
                                <td>✔</td>
                                <td>3</td>
                                <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                            </tr>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>