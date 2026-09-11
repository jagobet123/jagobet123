<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['admin_logged'])) {
    header("Location: login_admin.php");
    exit;
}

$filter_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$filter_cabang = isset($_GET['cabang']) ? $_GET['cabang'] : '';
$filter_jurusan = isset($_GET['jurusan']) ? $_GET['jurusan'] : '';

$sql = "SELECT * FROM siswa WHERE 1=1";
if ($filter_kelas != '') { $sql .= " AND kelas = '$filter_kelas'"; }
if ($filter_cabang != '') { $sql .= " AND cabang = '$filter_cabang'"; }
if ($filter_jurusan != '') { $sql .= " AND jurusan = '$filter_jurusan'"; }
$sql .= " ORDER BY nama ASC";

$query_siswa = mysqli_query($conn, $sql);
$total_siswa = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM siswa"));
$total_admin = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM admin"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Teka Teki LAB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #e8f5e9; margin: 0; padding: 20px; box-sizing: border-box; }
        .container { max-width: 950px; margin: 0 auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .header-dash { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #edf2f7; padding-bottom: 15px; margin-bottom: 20px; }
        .header-dash h2 { color: #1b4d3e; margin: 0; }
        .btn-logout { background-color: #ff6b6b; color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: bold; }
        .btn-logout:hover { background-color: #fa5252; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px; }
        .stat-card { background: linear-gradient(135deg, #2e8b57, #3cb371); color: white; padding: 15px; border-radius: 10px; }
        .stat-card h3 { margin: 0; font-size: 24px; }
        .stat-card p { margin: 5px 0 0; font-size: 13px; opacity: 0.9; }
        .filter-box { background-color: #f7fafc; padding: 15px; border-radius: 8px; border: 1px solid #edf2f7; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
        .filter-box select { padding: 8px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 13px; background: white; }
        .btn-filter { background-color: #2e8b57; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 13px; }
        .btn-filter:hover { background-color: #246b43; }
        .btn-reset { background-color: #718096; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #edf2f7; font-size: 14px; }
        th { background-color: #f7fafc; color: #2d3748; }
        .siswa-link { color: #2e8b57; text-decoration: none; font-weight: bold; }
        .siswa-link:hover { text-decoration: underline; color: #1b4d3e; }
        .badge { background-color: #e2e8f0; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; color: #4a5568; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-dash">
        <h2><i class="fa-solid fa-gauge-high"></i> Dashboard Admin</h2>
        <div>
            <span style="margin-right: 15px; font-weight: bold; color: #4a5568;">Halo, <?= $_SESSION['admin_nama']; ?></span>
            <a href="logout_admin.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3><?= $total_siswa; ?></h3>
            <p>Total Seluruh Siswa Terdaftar</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #1b4d3e, #2e8b57);">
            <h3><?= $total_admin; ?></h3>
            <p>Administrator Aktif</p>
        </div>
    </div>

    <form method="GET" action="" class="filter-box">
        <span style="font-weight: bold; font-size: 13px; color: #4a5568;"><i class="fa-solid fa-filter"></i> Filter:</span>
        <select name="kelas">
            <option value="">-- Semua Kelas --</option>
            <option value="X" <?= ($filter_kelas == 'X') ? 'selected' : ''; ?>>Kelas X</option>
            <option value="XI" <?= ($filter_kelas == 'XI') ? 'selected' : ''; ?>>Kelas XI</option>
            <option value="XII" <?= ($filter_kelas == 'XII') ? 'selected' : ''; ?>>Kelas XII</option>
        </select>
        <select name="cabang">
            <option value="">-- Semua Cabang --</option>
            <option value="A" <?= ($filter_cabang == 'A') ? 'selected' : ''; ?>>Cabang A</option>
            <option value="B" <?= ($filter_cabang == 'B') ? 'selected' : ''; ?>>Cabang B</option>
            <option value="C" <?= ($filter_cabang == 'C') ? 'selected' : ''; ?>>Cabang C</option>
            <option value="D" <?= ($filter_cabang == 'D') ? 'selected' : ''; ?>>Cabang D</option>
            <option value="E" <?= ($filter_cabang == 'E') ? 'selected' : ''; ?>>Cabang E</option>
        </select>
        <select name="jurusan">
            <option value="">-- Semua Jurusan --</option>
            <option value="TKC" <?= ($filter_jurusan == 'TKC') ? 'selected' : ''; ?>>Tata Kecantikan Rambut</option>
            <option value="TKL" <?= ($filter_jurusan == 'TKL') ? 'selected' : ''; ?>>Tata Kulit</option>
            <option value="RPL" <?= ($filter_jurusan == 'RPL') ? 'selected' : ''; ?>>Rekayasa Perangkat Lunak</option>
        </select>
        <button type="submit" class="btn-filter">Terapkan</button>
        <a href="admin_dashboard.php" class="btn-reset">Reset</a>
    </form>

    <h3 style="color: #2d3748; margin-bottom: 10px;">Daftar Siswa (Klik Nama untuk Analitik Detail)</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Lengkap</th>
                <th>Kelas & Cabang</th>
                <th>Jurusan</th>
                <th>Streak Harian</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if(mysqli_num_rows($query_siswa) > 0) {
                while($siswa = mysqli_fetch_assoc($query_siswa)) {
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $siswa['nis']; ?></td>
                <td>
                    <a href="detail_siswa.php?nis=<?= $siswa['nis']; ?>" class="siswa-link">
                        <i class="fa-solid fa-user-graduate"></i> <?= $siswa['nama']; ?>
                    </a>
                </td>
                <td><span class="badge"><?= $siswa['kelas'] . ' ' . $siswa['cabang']; ?></span></td>
                <td><?= $siswa['jurusan']; ?></td>
                <td><i class="fa-solid fa-fire" style="color: #ff6b6b;"></i> <b><?= $siswa['streak_hari']; ?></b> Hari</td>
            </tr>
            <?php } } else { ?>
            <tr>
                <td colspan="6" style="text-align: center; color: #a0aec0; padding: 20px;">Tidak ada data siswa yang sesuai dengan filter.</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>