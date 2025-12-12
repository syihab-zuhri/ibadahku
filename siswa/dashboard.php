<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !='siswa'){
    header("location: ../login.php");
    exit();
}
require '../config.php';

$userId = $_SESSION['id_user'];

$query = "SELECT * FROM log_ibadah WHERE id_user = $userId
        ORDER BY tanggal DESC";

$hasil_log = mysqli_query($koneksi, $query);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Aplikasi Monitoring Ibadah</title>
  
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
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="input-ibadah.php">Input Ibadah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h3>Selamat Datang, <?php echo $_SESSION['username']; ?>!</h3>
                <p class="text-muted">Berikut adalah riwayat laporan ibadah Anda.</p>
                <a href="input-ibadah.php" class="btn btn-success mb-3">
                    + Tambah Laporan Harian
                </a>
                <a href="cetak-pdf.php" target="_blank" class="btn btn-danger mb-3">
                    Cetak PDF
                </a>
            </div>
        </div>

           <?php  include "../alert.php"?>

        <div class="card">
            <div class="card-header">
                <h5>Riwayat Ibadah</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <!-- th table header -->
                            <tr>
                                <th>Tanggal</th>
                                <th>Subuh</th>
                                <th>Dzuhur</th>
                                <th>Ashar</th>
                                <th>Maghrib</th>
                                <th>Isya</th>
                                <th>Tilawah (Hal)</th>
                                <th>Status</th>
                                <th>catatan Guru</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($hasil_log)) : ?>
                            <!-- tr = table row -->
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($row['tanggal'])); ?></td>
                                    <td><?php echo ($row['sholat_subuh'] == 1) ? '✔' : '❌'; ?></td>
                                    <td><?php echo ($row['sholat_dzuhur'] == 1) ? '✔' : '❌'; ?></td>
                                    <td><?php echo ($row['sholat_ashar'] == 1) ? '✔' : '❌'; ?></td>
                                    <td><?php echo ($row['sholat_maghrib'] == 1) ? '✔' : '❌'; ?></td>
                                    <td><?php echo ($row['sholat_isya'] == 1) ? '✔' : '❌'; ?></td>
            
                                    <td><?php echo $row['tilawah_halaman']; ?></td>
            
                                    <td>
                                        <?php if ($row['status_validasi'] == 'menunggu') : ?>
                                            <span class="badge bg-secondary">menunggu</span>
                                        <?php elseif ($row['status_validasi'] == 'ditolak') : ?>
                                            <span class="badge bg-danger">ditolak</span>
                                        <?php elseif ($row['status_validasi'] == 'disetujui') : ?>
                                            <span class="badge bg-success">disetujui</span>    
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if(isset($row['catatan_guru'])): echo htmlspecialchars($row['catatan_guru']); endif; ?>
                                    </td>

                                    <td>
                                        <a href="edit-ibadah.php?id=<?php echo $row['id']; ?>"
                                            class="btn btn-sm btn-primary">Edit</a> 

                                        <a href="../proses-hapus.php?id=<?php echo $row['id']; ?>"
                                            class="btn btn-sm btn-danger">hapus</a>
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