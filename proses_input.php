<?php
session_start();
require 'config.php';


$id_user = $_SESSION['id_user'];


$tanggal = $_POST['tanggal'];
$tilawah = $_POST['tilawah_halaman'];
$catatan = $_POST['catatan'];


$subuh = isset($_POST['sholat_subuh']) ? 1 : 0;
$dzuhur = isset($_POST['sholat_dzuhur']) ? 1 : 0;
$ashar = isset($_POST['sholat_ashar']) ? 1 : 0;
$maghrib = isset($_POST['sholat_maghrib']) ? 1 : 0;
$isya = isset($_POST['sholat_isya']) ? 1 : 0;


$query = "INSERT INTO log_ibadah 
          (id_user, tanggal, sholat_subuh, sholat_dzuhur, sholat_ashar, sholat_maghrib, sholat_isya, tilawah_halaman, catatan) 
          VALUES 
          ('$id_user', '$tanggal', '$subuh', '$dzuhur', '$ashar', '$maghrib', '$isya', '$tilawah', '$catatan')";


$result = mysqli_query($koneksi, $query);

if ($result) {
    $_SESSION['pesan'] = "data ibadah berhasil disimpan";
    header("Location: siswa/dashboard.php");
    exit();
} else {
   
    echo "Error: Gagal menyimpan data. " . mysqli_error($koneksi);
}
?>