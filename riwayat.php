<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: user/login.php");
    exit();
}

// Koneksi ke database
$host = 'localhost'; // Ganti dengan host database Anda
$dbname = 'SiAntar';
$username = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Ambil data riwayat pemesanan berdasarkan username
$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT username, fullname, phone, address, address_b, distance, price, created_at, kendaraan FROM riwayat_pemesanan WHERE username = :username ORDER BY created_at DESC");
$stmt->execute(['username' => $username]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Riwayat Pemesanan - SiAntar</title>
    <link href="asset/sb-admin/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="asset/image/logo.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #E5FFFE;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
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
            color: #fff;
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
        .container {
            width: 80%;
            max-width: 1200px;
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<header>
    <div class="logo" style="margin-left: 15px;" color="white">SiAntar</div>
    <div class="header-links">
        <a href="index.php">Home</a>
        <a href="riwayat.php">Riwayat</a>
        <a href="user/logout.php" style="margin-right: 15px;">Logout</a>
    </div>
</header>

<div class="container">
    <h2>Riwayat Pemesanan</h2>
    <?php if ($orders): ?>
        <table>
            <thead>
                <tr>
                    <th>Waktu Pemesanan</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Phone</th>
                    <th>Alamat Jemput</th>
                    <th>Alamat Tujuan</th>
                    <th>Jarak (km)</th>
                    <th>Harga</th>
                    <th>Kendaraan</th> <!-- Tambahkan kolom Kendaraan -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['created_at']) ?></td>
                        <td><?= htmlspecialchars($order['username']) ?></td>
                        <td><?= htmlspecialchars($order['fullname']) ?></td>
                        <td><?= htmlspecialchars($order['phone']) ?></td>
                        <td><?= htmlspecialchars($order['address']) ?></td>
                        <td><?= htmlspecialchars($order['address_b']) ?></td>
                        <td><?= htmlspecialchars($order['distance']) ?></td>
                        <td>Rp <?= number_format($order['price'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($order['kendaraan']) ?></td> <!-- Tampilkan data Kendaraan -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Tidak ada riwayat pemesanan.</p>
    <?php endif; ?>
</div>
</body>
</html>
