<?php
// Panggil file koneksi database
include 'koneksi.php';

$pesan = "";

// Jika tombol daftar ditekan
if (isset($_POST['daftar'])) {
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $password = $_POST['password']; 
    $kelas = $_POST['kelas'];
    $cabang = $_POST['cabang'];
    $jurusan = $_POST['jurusan'];

    // Cek apakah NIS sudah terdaftar sebelumnya
    $cek_nis = mysqli_query($conn, "SELECT * FROM siswa WHERE nis = '$nis'");
    if (mysqli_num_rows($cek_nis) > 0) {
        $pesan = "<p style='color: red;'>Gagal! NIS sudah terdaftar.</p>";
    } else {
        // Simpan data ke database dengan kolom kelas dan cabang terpisah
        $query = "INSERT INTO siswa (nis, nama, password, kelas, cabang, jurusan, gems, streak_hari, role) 
                  VALUES ('$nis', '$nama', '$password', '$kelas', '$cabang', '$jurusan', 0, 0, 'siswa')";
        
        if (mysqli_query($conn, $query)) {
            $pesan = "<p style='color: green;'>Pendaftaran sukses! Silakan Login.</p>";
        } else {
            $pesan = "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Teka Teki LAB</title>
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

        /* Kotak Pembungkus Logo & Teks Berjalan */
        .brand-header-box {
            background: linear-gradient(135deg, #2e8b57, #3cb371);
            padding: 15px;
            border-radius: 12px;
            width: 100%;
            max-width: 350px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 15px;
            box-shadow: 0 4px 12px rgba(46, 139, 87, 0.3);
        }

        /* Styling Logo Transparan */
        .logo-img {
            width: 65px;
            height: auto;
            max-height: 70px;
            object-fit: contain;
            margin-bottom: 8px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.25)); /* Efek bayangan mengikuti bentuk logo */
        }

        /* Container Teks Berjalan */
        .marquee-container {
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
            text-align: center;
        }

        /* Teks Berjalan */
        .marquee-text {
            display: inline-block;
            color: #ffffff;
            font-size: 15px;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
            animation: marquee 9s linear infinite;
        }

        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
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
        
        h2 { text-align: center; color: #2e8b57; margin-top: 0; }
        
        input, select { 
            width: 100%; 
            padding: 10px; 
            margin: 8px 0; 
            border: 1px solid #ccc; 
            border-radius: 6px; 
            box-sizing: border-box; 
        }

        /* Box khusus kelas & cabang */
        .row-kelas {
            display: flex;
            gap: 10px;
        }

        button { 
            width: 100%; 
            padding: 10px; 
            background-color: #2e8b57; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: bold; 
            margin-top: 10px;
            transition: background 0.2s;
        }
        button:hover { background-color: #246b43; }
        .pesan { text-align: center; font-weight: bold; margin-bottom: 10px; }
    </style>
</head>
<body>

<!-- Kotak Header (Logo Transparan + Teks Berjalan) -->
<div class="brand-header-box">
    <img src="logo.png" alt="Logo SMK Laboratorium" class="logo-img">
    <div class="marquee-container">
        <div class="marquee-text">Selamat datang di Teka-teki LAB</div>
    </div>
</div>

<div class="box">
    <h2>Daftar Akun Baru</h2>
    
    <div class="pesan">
        <?= $pesan; ?>
    </div>

    <form method="POST" action="">
        <input type="text" name="nis" placeholder="Masukkan NIS" required>
        <input type="text" name="nama" placeholder="Nama Lengkap" required>
        <input type="password" name="password" placeholder="Password" required>
        
        <!-- Pilihan Kelas dan Cabang Terpisah -->
        <div class="row-kelas">
            <select name="kelas" required>
                <option value="">-- Kelas --</option>
                <option value="X">Kelas X</option>
                <option value="XI">Kelas XI</option>
                <option value="XII">Kelas XII</option>
            </select>

            <select name="cabang" required>
                <option value="">-- Cabang --</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
            </select>
        </div>

        <select name="jurusan" required>
            <option value="">-- Pilih Jurusan --</option>
            <option value="TKC">Tata Kecantikan Rambut</option>
            <option value="TKJ">Teknik Jaringan Kulit&Rambut</option>
            <option value="RPL">Rekayasa Perangkat Lunak</option>
            <option value="AP">Asisten Keperawatan</option>
        </select>

        <button type="submit" name="daftar">Daftar Sekarang</button>
    </form>
    
    <p style="text-align: center; font-size: 12px; margin-top: 15px;">
        Sudah punya akun? <a href="login.php" style="color: #2e8b57; font-weight: bold;">Login di sini</a>
    </p>
</div>

</body>
</html>