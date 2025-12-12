<?php

session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !='guru'){
    header("location: ../login.php");
    exit();
}
require '../config.php';

$id_log = $_POST['id_log'];
$status = $_POST['status_validasi'];
$catatan = $_POST['catatan_guru'];

$query = "UPDATE log_ibadah SET status_validasi='$status', catatan_guru='$catatan' WHERE id= $id_log";
$result = mysqli_query($koneksi, $query);

if ($result) { 
    $_SESSION['pesan'] = "data review berhasil di simpan";
    header("Location: dashboard.php");
} else {
    $_SESSION['pesan'] = "terjadi kesalahan saat menyimpan data";
    header("Location: dashboard.php");
}
?>