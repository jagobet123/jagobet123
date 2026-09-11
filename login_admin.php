<?php
session_start();
include 'koneksi.php';

$pesan = "";

if (isset($_POST['login_admin'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");
    
    if (mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        
        if ($password === $row['password']) {
            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_nama'] = $row['nama'];
            
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $pesan = "<p style='color: red;'>Password salah!</p>";
        }
    } else {
        $pesan = "<p style='color: red;'>Username admin tidak ditemukan!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Teka Teki LAB</title>
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
            border: 1px solid #ccc; 
            border-radius: 6px; 
            box-sizing: border-box; 
        }

        button { 
            width: 100%; 
            padding: 11px; 
            background-color: #1b4d3e; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: bold; 
            margin-top: 10px;
        }
        button:hover { background-color: #123329; }
        
        .pesan { text-align: center; font-weight: bold; margin-bottom: 10px; font-size: 14px; }
        .back-link { text-align: center; font-size: 12px; margin-top: 15px; }
        .back-link a { color: #2e8b57; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="brand-header-box">
    <img src="logo.png" alt="Logo SMK Laboratorium" class="logo-img">
    <div class="brand-title">Portal Administrator</div>
</div>

<div class="box">
    <h2>Login Admin</h2>
    <div class="pesan"><?= $pesan; ?></div>

    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username Admin" required autocomplete="off">
        <input type="password" name="password" placeholder="Password Admin" required>
        <button type="submit" name="login_admin">Masuk Sebagai Admin</button>
    </form>
    
    <div class="back-link">
        <a href="register.php">&larr; Kembali ke Pendaftaran Siswa</a>
    </div>
</div>

</body>
</html>