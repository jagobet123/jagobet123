<?php
session_start();

// Hapus semua session yang berkaitan dengan siswa
unset($_SESSION['nis']);
unset($_SESSION['nama']);
session_destroy();

// Alihkan (redirect) kembali ke halaman login siswa 
// (Sesuaikan "login.php" dengan nama file halaman login siswa lu kalau namanya beda)
header("Location: login.php");
exit;
?>