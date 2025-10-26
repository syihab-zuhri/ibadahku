<?php
session_start();
require "config.php";

$username = $_POST['username'];
$password = $_POST['password'];


$query = "SELECT * FROM users WHERE username ='$username'";

$result = mysqli_query ($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];
        
        header("Location: dashboard.php");
        // exit();
    } else {
        header("Location: login.php?error=password"); 
        exit();
    }
} else {
    header("Location: login.php?error=notfound");
    exit();
}
?>