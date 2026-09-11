<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['admin_logged'])) { header("Location: login_admin.php"); exit; }

$nis = isset($_GET['nis']) ? mysqli_real_escape_string($conn, $_GET['nis']) : '';
$query_siswa = mysqli_query($conn, "SELECT * FROM siswa WHERE nis = '$nis'");
$data_siswa = mysqli_fetch_assoc($query_siswa);

if (!$data_siswa) { echo "<script>alert('Data tidak ditemukan!'); window.location='admin_dashboard.php';</script>"; exit; }

$query_waktu = mysqli_query($conn, "SELECT * FROM riwayat_waktu WHERE nis = '$nis' ORDER BY tanggal DESC");
$query_teman = mysqli_query($conn, "SELECT s.nama, s.kelas, s.cabang, s.jurusan FROM pertemanan p JOIN siswa s ON p.nis_teman = s.nis WHERE p.nis_siswa = '$nis'");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><title>Detail - <?= $data_siswa['nama']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #e8f5e9; margin: 0; padding: 20px; box-sizing: border-box; }
        .container { max-width: 750px; margin: 0 auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .back-btn { display: inline-block; color: #2e8b57; text-decoration: none; font-weight: bold; margin-bottom: 15px; font-size: 14px; }
        .profile-header { background: linear-gradient(135deg, #2e8b57, #3cb371); color: white; padding: 20px; border-radius: 10px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        .section-title { color: #1b4d3e; border-bottom: 2px solid #edf2f7; padding-bottom: 8px; margin-top: 25px; margin-bottom: 15px; font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #edf2f7; font-size: 14px; }
        th { background-color: #f7fafc; color: #2d3748; }
        .badge-streak { background-color: #fff5f5; color: #e53e3e; padding: 5px 10px; border-radius: 6px; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <a href="admin_dashboard.php" class="back-btn">&larr; Kembali ke Dashboard</a>
    <div class="profile-header">
        <div>
            <h2><?= $data_siswa['nama']; ?></h2>
            <p style="margin:0; opacity:0.9; font-size:14px;">NIS: <?= $data_siswa['nis']; ?> | Kelas: <?= $data_siswa['kelas'] . ' ' . $data_siswa['cabang']; ?> | Jurusan: <?= $data_siswa['jurusan']; ?></p>
        </div>
        <div class="badge-streak"><i class="fa-solid fa-fire"></i> Streak: <?= $data_siswa['streak_hari']; ?> Hari</div>
    </div>

    <div class="section-title"><i class="fa-solid fa-clock"></i> Riwayat Durasi Akses Web (Menit/Hari)</div>
    <table>
        <thead><tr><th>Tanggal</th><th>Total Waktu Aktif</th></tr></thead>
        <tbody>
            <?php if(mysqli_num_rows($query_waktu) > 0) { while($w = mysqli_fetch_assoc($query_waktu)) { ?>
            <tr><td><?= $w['tanggal']; ?></td><td><b><?= $w['menit_aktif']; ?> Menit</b></td></tr>
            <?php } } else { ?>
            <tr><td colspan="2" style="text-align: center; color: #a0aec0;">Belum ada catatan waktu aktif.</td></tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="section-title"><i class="fa-solid fa-users"></i> Daftar Teman Mutualan</div>
    <table>
        <thead><tr><th>Nama Teman</th><th>Kelas & Cabang</th><th>Jurusan</th></tr></thead>
        <tbody>
            <?php if(mysqli_num_rows($query_teman) > 0) { while($t = mysqli_fetch_assoc($query_teman)) { ?>
            <tr><td><?= $t['nama']; ?></td><td><?= $t['kelas'] . ' ' . $t['cabang']; ?></td><td><?= $t['jurusan']; ?></td></tr>
            <?php } } else { ?>
            <tr><td colspan="3" style="text-align: center; color: #a0aec0;">Belum memiliki teman mutualan.</td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>