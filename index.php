<?php
session_start();

// Periksa apakah user telah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: user/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>SiAntar</title>
    <link href="<?= $main_url ?>asset/sb-admin/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="<?= $main_url ?>asset/image/logo.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow-x: hidden;
            font-family: Arial, sans-serif;
            background-image: url('img/bg2.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }

        header {
            width: 100%;
            background-color: #000;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .header-links {
            display: flex;
            align-items: center;
        }

        .header-links a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
        }

        .additional-cards {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .additional-card {
            width: 300px;
            padding: 20px;
            margin-top: 19px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #333;
            cursor: pointer;
        }

        .card1 {
            background-color: #80bfff;
        }

        .card2 {
            background-color: #99ffcc;
        }

        h3 {
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
        }

        h2 {
            color: black;
            margin-left: 120px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo" style="margin-left: 15px;" color="white">SiAntar</div>
        <div class="header-links">
            <a href="#">Profil</a>
            <a href="#">Riwayat</a>
            <a href="user/logout.php" style="margin-right: 15px;">Logout</a>
        </div>
    </header>

    <div>
        <h2 style="font-size: 70px; margin-top: 10px;  color : black;">SiAntar</h2> 
        <h3 class="p">Halo, Ingin naik apa hari ini? SiAntar siap mengantarkan!</h3>
    </div>

    <div class="additional-cards">
        <div class="additional-card card1" onclick="location.href='lokasi-motor.php'">
            <h3><i class="fa-solid fa-motorcycle"></i> Motor</h3>
            <p>Siap antar dengan harga yang lebih ramah di kantong.</p>
        </div>
        <div class="additional-card card2" onclick="location.href='lokasi-mobil.php'">
            <h3><i class="fa-solid fa-car"></i> Mobil</h3>
            <p>Siap antar dengan penumpang lebih banyak dan kenyamanan ekstra</p>
        </div>
    </div>
</body>
</html>
