<?php
session_start();
include 'koneksi.php';

$pesan = "";

if (isset($_POST['login_siswa'])) {
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);

    // Cek apakah NIS terdaftar di tabel siswa
    $query = mysqli_query($conn, "SELECT * FROM siswa WHERE nis = '$nis'");
    
    if (mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        
        // Simpan data penting ke dalam session
        $_SESSION['nis'] = $row['nis'];
        $_SESSION['nama'] = $row['nama'];
        $_SESSION['kelas'] = $row['kelas'];
        $_SESSION['cabang'] = $row['cabang'];
        $_SESSION['jurusan'] = $row['jurusan'];
        
        // Arahkan ke halaman utama/dashboard siswa (bisa disesuaikan namanya, misal index.php)
        header("Location: index.php");
        exit;
    } else {
        $pesan = "<p style='color: red;'>NIS tidak ditemukan! Silakan daftar terlebih dahulu.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa - Teka Teki LAB</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #e8f5e9; 
            display: flex; 
            flex-direction: column;
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0; 
            padding: 20px;
            box-sizing: border-box;
        }

        .brand-header-box {
            background: linear-gradient(135deg, #1b4d3e, #2e8b57);
            padding: 15px;
            border-radius: 12px;
            width: 100%;
            max-width: 350px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 15px;
            box-shadow: 0 4px 12px rgba(27, 77, 62, 0.3);
        }

        .logo-img {
            width: 65px;
            height: auto;
            max-height: 70px;
            object-fit: contain;
            margin-bottom: 8px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.25));
        }

        .brand-title {
            color: #ffffff;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .box { 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.08); 
            width: 100%; 
            max-width: 350px; 
            box-sizing: border-box;
        }
        
        h2 { text-align: center; color: #1b4d3e; margin-top: 0; font-size: 22px; }
        
        input { 
            width: 100%; 
            padding: 11px; 
            margin: 10px 0; 
            border: 1px solid #cbd5e0; 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-size: 14px;
        }

        button { 
            width: 100%; 
            padding: 11px; 
            background-color: #2e8b57; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: bold; 
            margin-top: 10px;
            font-size: 14px;
        }
        button:hover { background-color: #246b43; }
        
        .pesan { text-align: center; font-weight: bold; margin-bottom: 10px; font-size: 13px; }
        
        .links { 
            display: flex; 
            justify-content: space-between; 
            font-size: 12px; 
            margin-top: 15px; 
        }
        .links a { color: #2e8b57; text-decoration: none; font-weight: bold; }
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="brand-header-box">
    <img src="logo;
    .png" alt="Logo SMK Laboratorium" class="logo-img">
    <div class="brand-title">Teka Teki LAB</div>
</div>

<div class="box">
    <h2>Login Siswa</h2>
    <div class="pesan"><?= $pesan; ?></div>

    <form method="POST" action="">
        <input type="text" name="nis" placeholder="Masukkan NIS Anda" required autocomplete="off">
        <button type="submit" name="login_siswa">Masuk ke Game</button>
    </form>
    
    <div class="links">
        <a href="register.php">Belum punya akun? Daftar</a>
        <a href="login_admin.php" style="color: #718096;">Masuk Admin</a>
    </div>
</div>

</body>
</html>