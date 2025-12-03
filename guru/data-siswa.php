<?php
session_start();
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'guru') {
    header("location: ../login.php");
    exit();
}

require "../config.php";
$data = mysqli_query($koneksi, "SELECT * FROM users WHERE role='siswa'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"> -->
</head>

<body class="bg-light">
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
                        <a class="nav-link text-warning" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Data Siswa</h3>
        <!-- <a href="tambah-siswa.php" class="btn btn-primary">+ Tambah Siswa</a> -->
    </div>

    <!-- <?php  include "../alert.php"?> -->

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="table-primary">
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <!-- <th>Aksi</th> -->
                    </tr>
                </thead>

                <tbody>
                    <?php 
                    $no = 1;
                    while($row = mysqli_fetch_assoc($data)) {
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nama_lengkap'] ?></td>
                        <td><?= $row['username'] ?></td>

                        <!-- <td class="text-end">
                            <a href="hapus-siswa.php?id=<?= $row['id']; ?>"
                            class="btn btn-sm btn-danger"
                            style="padding: 4px 6px;"
                            onclick="return confirm('Hapus siswa ini beserta seluruh catatan ibadahnya?')">
                            <i class="bi bi-trash"></i>
                            </a>
                        </td> -->

                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
