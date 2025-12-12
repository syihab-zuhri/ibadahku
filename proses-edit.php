<?php
session_start();
require 'config.php';


$id_user = $_SESSION['id_user'];
$id_log = $_POST['id_log'];

$tanggal = $_POST['tanggal'];
$tilawah = $_POST['tilawah_halaman'];
$catatan = $_POST['catatan'];

$subuh = isset($_POST['sholat_subuh']) ? 1 : 0;
$dzuhur = isset($_POST['sholat_dzuhur']) ? 1 : 0;
$ashar = isset($_POST['sholat_ashar']) ? 1 : 0;
$maghrib = isset($_POST['sholat_maghrib']) ? 1 : 0;
$isya = isset($_POST['sholat_isya']) ? 1 : 0;


$query = "UPDATE log_ibadah SET 
          tanggal = '$tanggal',
          sholat_subuh = '$subuh',
          sholat_dzuhur = '$dzuhur',
          sholat_ashar = '$ashar',
          sholat_maghrib = '$maghrib',
          sholat_isya = '$isya',
          tilawah_halaman = '$tilawah',
          catatan = '$catatan'
          WHERE id='$id_log'";
$result = mysqli_query($koneksi, $query);

if ($result) { 
    $_SESSION['pesan'] = "data ibadah berhasil di edit";
    header("Location: siswa/dashboard.php");
} else {
    echo "Error: Gagal memperbarui data. ";
}
?>