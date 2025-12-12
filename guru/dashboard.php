<?php

session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !='guru'){
    header("location: ../login.php");
    exit();
}
require '../config.php';

$query = "SELECT log_ibadah.*, users.nama_lengkap
          FROM log_ibadah
          JOIN users ON log_ibadah.id_user = users.id
          WHERE users.role = 'siswa'
          ORDER BY log_ibadah.tanggal DESC";

$hasil_log = mysqli_query($koneksi, $query);

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
            <a class="navbar-brand" href="#">Monitoring Ibadah</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data-siswa.php">Data siswa</a>
                    </li>
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
        
            <?php
            if (isset($_SESSION['pesan'])) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                echo $_SESSION['pesan'];
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
                unset($_SESSION['pesan']);
            }
            ?>

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
                                <th>aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php
                                while ($log = mysqli_fetch_assoc($hasil_log)) : ?>
                                    <tr>
                                        <td><?php echo date('d M Y', strtotime($log['tanggal'])); ?></td>
                                        <td><?php echo htmlspecialchars($log['nama_lengkap']); ?></td>
                                        <td><?php echo ($log['sholat_subuh'] == 1) ? '✔' : '❌'; ?></td>
                                        <td><?php echo ($log['sholat_dzuhur'] == 1) ? '✔' : '❌'; ?></td>
                                        <td><?php echo ($log['sholat_ashar'] == 1) ? '✔' : '❌'; ?></td>
                                        <td><?php echo ($log['sholat_maghrib'] == 1) ? '✔' : '❌'; ?></td>
                                        <td><?php echo ($log['sholat_isya'] == 1) ? '✔' : '❌'; ?></td>
                                        <td><?php echo $log['tilawah_halaman']; ?></td>
                                        
                                        <td>
                                            <?php if ($log['status_validasi'] == 'disetujui') : ?>
                                                <span class="badge bg-success">Disetujui</span>
                                            <?php else : ?>
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="review-ibadah.php?id=<?php echo $log['id']; ?>"
                                            class="btn btn-primary">
                                                review
                                            </a>
                                        </td>
                                    </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>