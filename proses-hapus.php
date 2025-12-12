<?php
session_start();
require 'config.php';

$id_log = $_GET['id'];

$query = "DELETE FROM log_ibadah WHERE id= '$id_log'";
$result = mysqli_query($koneksi, $query);

if ($result) {
    $_SESSION['pesan'] = "data berhasil dihapus";
    header("Location: siswa/dashboard.php");
}else{
    echo "Error: Gagal menghapus data.";
}
?>
// if (isset($_GET['id'])) {
//     $id_log = mysqli_real_escape_string($koneksi, $_GET['id']);   
//     $id_user = $_SESSION['id_user'];
//     $query_hapus = "DELETE FROM log_ibadah WHERE id = '$id_log' AND id_user = '$id_user'";

//     if (mysqli_query($koneksi, $query_hapus)) {
//         header("location: dashboard.php?status=hapus_sukses");
//         exit();
//     } else {
//         header("location: dashboard.php?status=hapus_gagal&error=");
//         exit();
//     }
// } else {
//     header("location: dashboard.php?id=tidak_ditemukan");
//     exit();
// }