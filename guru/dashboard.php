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
            <a class="navbar-brand" href="#">Dashboard Guru</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="../logout.php">Logout</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="data-siswa.php">Data Murid</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h3>Selamat Datang, <?php echo $_SESSION['username']; ?>!</h3>
        <p class="text-muted">Berikut adalah rekap laporan ibadah dari semua siswa.</p>

        <!-- GANTI BLOK INI: hanya 1 tombol Cetak PDF yang memicu modal -->
        <div class="mb-3">
        <!-- Tombol pemicu modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCetak">
            Cetak PDF
        </button>
        </div>

        <!-- Modal Cetak PDF -->
        <div class="modal fade" id="modalCetak" tabindex="-1" aria-labelledby="modalCetakLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formCetak" method="get" action="cetak-pdf.php" target="_blank" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCetakLabel">Pengaturan Cetak PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Pilihan mode -->
                <div class="mb-3">
                <label class="form-label">Pilih rentang cetak</label>
                <div>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="mode" id="modeCustom" value="custom" checked>
                    <label class="form-check-label" for="modeCustom">Custom (pilih tanggal awal & akhir)</label>
                    </div>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="mode" id="modeMonth" value="month">
                    <label class="form-check-label" for="modeMonth">Per Bulan</label>
                    </div>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="mode" id="modeWeek" value="week">
                    <label class="form-check-label" for="modeWeek">Per Minggu</label>
                    </div>
                </div>
                </div>

                //custom input sesuai permintaan
                <div id="box-custom" class="row g-2 mb-2">
                <div class="col">
                    <label class="form-label">Dari</label>
                    <input type="date" id="customStart" class="form-control" />
                </div>
                <div class="col">
                    <label class="form-label">Sampai</label>
                    <input type="date" id="customEnd" class="form-control" />
                </div>
                </div>

                //input per bulan
                <div id="box-month" class="mb-2" style="display:none;">
                <label class="form-label">Pilih Bulan</label>
                <input type="month" id="monthInput" class="form-control" />
                </div>

                <!-- Week input
                <div id="box-week" class="mb-2" style="display:none;">
                <label class="form-label">Pilih tanggal dalam minggu</label>
                <input type="date" id="weekInput" class="form-control" />
                <div class="form-text">(sistem akan menghitung Senin - Minggu berisi tanggal ini)</div>
                </div> -->

                <!-- Hidden fields yang dikirim ke cetak-pdf.php -->
                <input type="hidden" name="start" id="hiddenStart" />
                <input type="hidden" name="end" id="hiddenEnd" />
                <input type="hidden" name="mode_sent" id="hiddenMode" />
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" id="btnSubmitCetak" class="btn btn-primary">Cetak</button>
            </div>
            </form>
        </div>
        </div>

            <?php
            // Cek apakah ada pesan notifikasi di session
            if (isset($_SESSION['pesan'])) {
                // Tampilkan pesan dalam bentuk alert Bootstrap
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                echo $_SESSION['pesan'];
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
                
                // Hapus pesan dari session agar tidak muncul lagi
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
                            
                            <?php // Mulai perulangan untuk setiap baris data yang ditemukan
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
                            <?php endwhile; // Akhir dari perulangan ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/cetak.js"></script>
</body>
</html>