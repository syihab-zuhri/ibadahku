<?php
session_start();

if (!isset($_SESSION['id_user']) || ($_SESSION['role'] ?? '') !== 'guru') {
    header("Location: ../login.php");
    exit();
}

require '../config.php';

// --- Ambil ID siswa dari URL ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID siswa tidak valid.");
}

$id_user = (int) $_GET['id'];

// --- Ambil data siswa ---
$query = "SELECT * FROM users WHERE id = $id_user AND role = 'siswa'";
$hasil_log = mysqli_query($koneksi, $query);


$siswa = mysqli_fetch_assoc($hasil_log);

// --- Ambil log ibadah siswa ---
$sql_log = "
    SELECT log_ibadah.*, users.nama_lengkap
    FROM log_ibadah log_ibadah
    JOIN users users ON log_ibadah.id_user = users.id
    WHERE log_ibadah.id_user = $id_user
    ORDER BY log_ibadah.tanggal DESC
";
$hasil_log = mysqli_query($koneksi, $sql_log);

?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Ibadah Siswa</title>
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
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="data-siswa.php">Data Murid</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-warning" href="../logout.php">Logout</a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">

    <h3>Riwayat Ibadah: <span class="text-dark"><?php echo ($siswa['nama_lengkap']); ?></span></h3>
    
    <?php  include "../alert.php"?> 

    <div class="card mt-4">
        <div class="card-header">
            <h5>laporan ibadah </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Subuh</th>
                            <th>Dzuhur</th>
                            <th>Ashar</th>
                            <th>Maghrib</th>
                            <th>Isya</th>
                            <th>Tilawah</th>
                            <th>Status</th>
                            <th>Review</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php (mysqli_num_rows($hasil_log) > 0) ?>
                        <?php while ($log = mysqli_fetch_assoc($hasil_log)): ?>
                            <tr>
                                <td><?php echo date('d M Y', strtotime($log['tanggal'])); ?></td>
                                <td><?php echo ($log['sholat_subuh'] == 1) ? '✔' : '❌'; ?></td>
                                <td><?php echo ($log['sholat_dzuhur'] == 1) ? '✔' : '❌'; ?></td>
                                <td><?php echo ($log['sholat_ashar'] == 1) ? '✔' : '❌'; ?></td>
                                <td><?php echo ($log['sholat_maghrib'] == 1) ? '✔' : '❌'; ?></td>
                                <td><?php echo ($log['sholat_isya'] == 1) ? '✔' : '❌'; ?></td>
                                <td><?php echo $log['tilawah_halaman']; ?></td>

                                <td>
                                    <?php
                                    if ($log['status_validasi'] == 'menunggu') {
                                        echo '<span class="badge bg-secondary">Menunggu</span>';
                                    } elseif ($log['status_validasi'] == 'ditolak') {
                                        echo '<span class="badge bg-danger">Ditolak</span>';
                                    } else {
                                        echo '<span class="badge bg-success">Disetujui</span>';
                                    }
                                    ?>

                                </td>
                                <td>
                                    <a href="review-ibadah.php?id=<?php echo $log['id']; ?>" class="btn btn-sm btn-primary">
                                        Review
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
