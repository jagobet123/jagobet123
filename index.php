<?php
session_start();
include 'koneksi.php';

// Cek apakah siswa sudah login
if (!isset($_SESSION['nis'])) {
    header("Location: login.php");
    exit;
}

// Ambil data terbaru dari database
$nis = $_SESSION['nis'];
$query = mysqli_query($conn, "SELECT * FROM siswa WHERE nis = '$nis'");
$siswa = mysqli_fetch_assoc($query);

// Set nilai default jika data belum ada
$gems = isset($siswa['gems']) ? $siswa['gems'] : 0;
$streak = isset($siswa['streak_hari']) ? $siswa['streak_hari'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jalur Belajar - Teka Teki LAB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* GAYA DASAR MOBILE-FIRST */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e8f5e9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        .mobile-container {
            width: 100%;
            max-width: 480px;
            background-color: #f8faf9;
            height: 100vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        /* AREA SCROLL KONTEN UTAMA */
        .content-area {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 100px;
        }

        .content-area::-webkit-scrollbar { width: 6px; }
        .content-area::-webkit-scrollbar-track { background: transparent; margin: 10px 0; }
        .content-area::-webkit-scrollbar-thumb { background-color: #bcbcbc; border-radius: 10px; }

        /* HEADER SECTION */
        .header-top {
            background-color: #218838;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-profile img {
            width: 30px;
            height: 30px;
            background: white;
            border-radius: 50%;
            padding: 3px;
            object-fit: contain;
        }

        .user-profile span {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .btn-logout {
            color: white;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
        }

        /* SUB-HEADER STATISTIK */
        .stats-bar {
            background: white;
            display: flex;
            justify-content: space-around;
            padding: 12px 0;
            border-bottom: 2px solid #edf2f7;
            font-size: 13px;
            font-weight: bold;
            color: #4a5568;
        }

        .stat-item { display: flex; align-items: center; gap: 6px; }
        .stat-item.jurusan i { color: #4a5568; }
        .stat-item.streak i { color: #ff9800; font-size: 15px; }
        .stat-item.gems i { color: #00bcd4; font-size: 15px; }

        /* KARTU MATERI AKTIF */
        .materi-card-container { padding: 20px 20px 10px 20px; }
        .materi-card {
            background-color: #218838;
            color: white;
            border-radius: 12px;
            padding: 15px;
            text-align: left;
            box-shadow: 0 4px 10px rgba(33, 136, 56, 0.25);
        }

        .materi-badge {
            background: rgba(255, 255, 255, 0.25);
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .materi-card h3 { margin: 0; font-size: 15px; }

        /* ROADMAP PATH (ZIG-ZAG) */
        .path-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0 40px 0;
            gap: 25px;
        }

        .step-wrapper {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .step-wrapper.step-right { margin-left: 80px; }

        /* DESAIN BULETAN (CIRCLE) */
        .circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 26px;
            font-weight: bold;
            position: relative;
            z-index: 2;
        }

        /* Langkah 1 (Pembungkus) */
        .circle-active-wrapper {
            border-radius: 50%;
            padding: 6px;
            cursor: pointer;
            position: relative;
        }

        /* --- INI DIA ANIMASI MUTERNYA! --- */
        /* Kita bikin layer bayangan buat nampung garis putus-putus */
        .circle-active-wrapper::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            border: 3px dashed #218838;
            border-radius: 50%;
            animation: muter 10s linear infinite; /* 10 detik sekali putaran penuh */
            z-index: 1; /* Biar tetap di belakang angka */
            pointer-events: none; /* Biar gak menghalangi saat diklik */
        }

        /* Logika rotasi 360 derajat */
        @keyframes muter {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        /* --------------------------------- */

        .circle.active {
            background-color: #58cc02;
            color: white;
            box-shadow: 0 5px 0 #46a302;
            width: 75px;
            height: 75px;
        }
        .circle-active-wrapper:active .circle.active {
            transform: translateY(4px);
            box-shadow: 0 1px 0 #46a302;
        }

        .circle-label {
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            color: #555;
            font-size: 11px;
            padding: 2px 10px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            z-index: 3;
        }

        /* Langkah Terkunci */
        .circle.locked {
            background-color: #e5e5e5;
            color: #afafaf;
            box-shadow: 0 5px 0 #d4d4d4;
            cursor: not-allowed;
        }

        .lock-icon-label {
            position: absolute;
            bottom: -12px;
            background: white;
            color: #ff9800;
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* OPSI TINGKATAN (ACCORDION) */
        .opsi-container {
            width: 85%;
            margin-top: 25px;
            background: #ffffff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            animation: slideDown 0.3s ease forwards;
        }

        .opsi-card {
            display: block;
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            border-left: 5px solid #58cc02;
            text-decoration: none;
            color: #333;
            transition: all 0.2s;
        }
        .opsi-card:last-child { margin-bottom: 0; }
        .opsi-card:hover { background: #e9f5ec; transform: translateY(-2px); }
        .opsi-card h4 { margin: 0 0 5px 0; font-size: 15px; color: #218838; }
        .opsi-card p { margin: 0; font-size: 12px; color: #666; line-height: 1.4; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* BOTTOM NAVIGATION BAR */
        .bottom-nav {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: white;
            display: flex;
            justify-content: space-around;
            padding: 12px 5px;
            box-sizing: border-box;
            border-top: 2px solid #f1f1f1;
            z-index: 10;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            font-size: 10px;
            font-weight: bold;
            gap: 6px;
            color: #a0aec0;
            cursor: pointer;
        }

        .nav-item i { font-size: 22px; }
        .nav-item.active { color: #58cc02; }
        .nav-item.active i { color: #58cc02; }
        .nav-hadiah i { color: #ff9800; }
        .nav-pesan i { color: #cbd5e0; }
        .nav-feed i { color: #f44336; }
        .nav-mapel i { color: #4caf50; }
        .nav-menu i { color: #a0aec0; }

        /* MODAL POPUP MENU */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-box {
            background: white;
            width: 85%;
            max-width: 380px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            animation: popUp 0.25s ease-out;
        }

        @keyframes popUp {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .modal-header-menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px 10px 20px;
        }

        .modal-header-menu h3 { margin: 0; font-size: 15px; color: #2d3748; }

        .btn-close {
            background: none;
            border: none;
            font-size: 20px;
            color: #a0aec0;
            cursor: pointer;
        }

        .modal-body-menu {
            padding: 10px 20px 20px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .menu-list-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 15px;
            border-radius: 8px;
            text-decoration: none;
            color: #4a5568;
            font-size: 13px;
            font-weight: bold;
            border: 1px solid #edf2f7;
            transition: all 0.2s;
        }

        .menu-list-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .menu-list-item.active-menu {
            background-color: #f0fdf4;
            color: #218838;
            border-color: #c6f6d5;
        }
    </style>
</head>
<body>

<div class="mobile-container">
    
    <div class="content-area">
        <!-- 1. Header Profile -->
        <div class="header-top">
            <div class="user-profile">
                <!-- Fallback icon jika file smk.png tidak ada -->
                <img src="smk.png" alt="Logo" onerror="this.src='https://cdn-icons-png.flaticon.com/512/149/149071.png'">
                <span>HALO, <?= $siswa['nama']; ?></span>
            </div>
            <a href="logout_siswa.php" class="btn-logout">Keluar</a>
        </div>

        <!-- 2. Bar Statistik -->
        <div class="stats-bar">
            <div class="stat-item jurusan">
                <i class="fa-solid fa-graduation-cap"></i> 
                <?= $siswa['kelas'] . '-' . $siswa['cabang']; ?>
            </div>
            <div class="stat-item streak">
                <i class="fa-solid fa-fire"></i> <?= $streak; ?>
            </div>
            <div class="stat-item gems">
                <i class="fa-solid fa-gem"></i> <?= $gems; ?>
            </div>
        </div>

        <!-- 3. Kartu Materi Aktif -->
        <div class="materi-card-container">
            <div class="materi-card">
                <div class="materi-badge">MATERI AKTIF</div>
                <h3>Bahasa Inggris (English for Vocational)</h3>
            </div>
        </div>

        <!-- 4. Roadmap / Path Section -->
        <div class="path-container">
            
            <!-- Langkah 1 (Tengah, Aktif, Border Animasi Muter) -->
            <div class="step-wrapper">
                <div class="circle-active-wrapper" onclick="toggleTingkat()">
                    <div class="circle active">1</div>
                    <div class="circle-label">Materi</div>
                </div>
                
                <!-- Accordion Tingkatan -->
                <div id="opsi-tingkat" class="opsi-container" style="display: none;">
                    <a href="kuis.php?materi=1&tingkat=1" class="opsi-card">
                        <h4>Tingkat 1</h4>
                        <p>Pertanyaan dasar seputar 1 materi sesuai pembahasan.</p>
                    </a>
                    <a href="kuis.php?materi=1&tingkat=2" class="opsi-card">
                        <h4>Tingkat 2</h4>
                        <p>PG Kompleks (Jawaban benar lebih dari satu).</p>
                    </a>
                    <a href="kuis.php?materi=1&tingkat=3" class="opsi-card">
                        <h4>Tingkat 3</h4>
                        <p>Studi kasus yang berhubungan dengan materi pembelajaran.</p>
                    </a>
                </div>
            </div>

            <!-- Langkah 2 (Geser Kanan) -->
            <div class="step-wrapper step-right">
                <div class="circle locked">2</div>
                <div class="lock-icon-label"><i class="fa-solid fa-lock"></i></div>
            </div>

            <!-- Langkah 3 (Tengah) -->
            <div class="step-wrapper">
                <div class="circle locked">3</div>
                <div class="lock-icon-label"><i class="fa-solid fa-lock"></i></div>
            </div>

        </div>
    </div>

    <!-- 5. Bottom Navigation Bar -->
    <div class="bottom-nav">
        <a href="index.php" class="nav-item active">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <a href="#" class="nav-item nav-hadiah">
            <i class="fa-solid fa-gift"></i>
            <span>Hadiah</span>
        </a>
        <a href="#" class="nav-item nav-pesan">
            <i class="fa-solid fa-comment-dots"></i>
            <span>Pesan</span>
        </a>
        <a href="#" class="nav-item nav-feed">
            <i class="fa-solid fa-newspaper"></i>
            <span>Feed</span>
        </a>
        <a href="#" class="nav-item nav-mapel">
            <i class="fa-solid fa-book-bookmark"></i>
            <span>Mapel</span>
        </a>
        <div class="nav-item nav-menu" onclick="bukaModalMenu()">
            <i class="fa-solid fa-bars"></i>
            <span>Menu</span>
        </div>
    </div>

    <!-- 6. MODAL POPUP MENU FITUR LAINNYA -->
    <div id="modalMenu" class="modal-overlay" style="display: none;">
        <div class="modal-box">
            <div class="modal-header-menu">
                <h3>Menu Fitur Lainnya</h3>
                <button class="btn-close" onclick="tutupModalMenu()">&times;</button>
            </div>
            <div class="modal-body-menu">
                <a href="profil_detail.php" class="menu-list-item active-menu">
                    <i class="fa-solid fa-user" style="color: #673ab7;"></i> Profil Siswa
                </a>
                <a href="#" class="menu-list-item">
                    <i class="fa-solid fa-book-open" style="color: #64b5f6;"></i> Materi & Teori Pembelajaran
                </a>
                <a href="#" class="menu-list-item">
                    <i class="fa-solid fa-clapperboard" style="color: #4a5568;"></i> Video Praktik Jurusan & Mapel
                </a>
                <a href="#" class="menu-list-item">
                    <i class="fa-solid fa-gamepad" style="color: #673ab7;"></i> Game Praktik / Simulasi Interaktif
                </a>
            </div>
        </div>
    </div>

</div>

<!-- LOGIKA SCRIPT TOGGLE ACCORDION & MODAL MENU -->
<script>
    function toggleTingkat() {
        var opsi = document.getElementById("opsi-tingkat");
        if (opsi.style.display === "none" || opsi.style.display === "") {
            opsi.style.display = "block";
            opsi.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            opsi.style.display = "none";
        }
    }

    function bukaModalMenu() {
        document.getElementById("modalMenu").style.display = "flex";
    }

    function tutupModalMenu() {
        document.getElementById("modalMenu").style.display = "none";
    }
</script>

</body>
</html>