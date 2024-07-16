<?php
session_start();

// Periksa apakah user telah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: user/login.php");
    exit();
}

// Fungsi untuk menyimpan data ke tabel riwayat_pemesanan
function save_to_riwayat() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "SiAntar";

    // Membuat koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query untuk mendapatkan data pemesanan dan user terbaru
    $sql_get_data = "
      SELECT 
        u.username, u.fullname, u.phone,
        p.address, p.address_b, p.distance, p.price, p.created_at
      FROM user u
      CROSS JOIN pemesanan p
      ORDER BY u.id DESC, p.id DESC
      LIMIT 1
    ";
    $result = $conn->query($sql_get_data);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $fullname = $row['fullname'];
        $phone = $row['phone'];
        $address = $row['address'];
        $address_b = $row['address_b'];
        $distance = $row['distance'];
        $price = $row['price'];
        $created_at = $row['created_at'];
        $kendaraan = "Mobil"; // Ubah dari "Motor" ke "Mobil"

        // Prepared statement untuk menyimpan data ke tabel riwayat_pemesanan
        $sql_insert = "
          INSERT INTO riwayat_pemesanan (username, fullname, phone, address, address_b, distance, price, created_at, kendaraan) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt_insert = $conn->prepare($sql_insert);

        // Bind parameter
        $stmt_insert->bind_param("sssssssss", $username, $fullname, $phone, $address, $address_b, $distance, $price, $created_at, $kendaraan);

        // Eksekusi query
        if ($stmt_insert->execute()) {
            echo "Data berhasil disimpan ke riwayat_pemesanan.";
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }

        // Tutup statement
        $stmt_insert->close();
    } else {
        echo "No data found to save.";
    }

    // Tutup koneksi
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_to_riwayat'])) {
    save_to_riwayat();
    echo "<script>alert('Terima kasih sudah menggunakan jasa SiAntar'); window.location.href = 'index.php';</script>";
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
  <title>SiAntar - Kondisi Pemesanan</title>
  <!-- CSS Styles -->
  <link href="<?= $main_url ?>asset/sb-admin/css/styles.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?= $main_url ?>asset/image/logo.png">
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <!-- Custom Styles -->
  <style>
    /* Salin CSS dari file sebelumnya */
    body {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      margin: 0;
      overflow-x: hidden;
      font-family: Arial, sans-serif;
      background-color: #E5FFFE;
      background-size: cover;
      background-repeat: no-repeat;
      height: 100vh;
    }
    header {
      width: 100%;
      background-color: #000;
      color: #fff;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: fixed;
      top: 0;
      z-index: 1000;
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
    h3 {
      margin-bottom: 10px;
    }
    h2 {
      color: black;
      margin-left: 120px;
      margin-bottom: 20px;
    }
    .table-container {
      width: 80%;
      margin: 20px auto;
      background-color: rgba(255, 255, 255, 0.8);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
    #map {
      width: 100%;
      height: 400px;
      margin-top: 20px;
    }
    .details {
      margin-top: 20px;
      text-align: center;
      font-size: 18px;
    }
    .buttons {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }
    .buttons button {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 4px 2px;
      cursor: pointer;
      border: none;
      border-radius: 5px;
    }
    .buttons button.cancel {
      background-color: #f44336;
    }
    .buttons .left-button {
      margin-right: auto;
    }
    .buttons .right-button {
      margin-left: auto;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <div class="logo" style="margin-left: 15px;" color="white">SiAntar</div>
    <div class="header-links">
        <a href="index.php">Home</a>
        <a href="riwayat.php">Riwayat</a>
        <a href="user/logout.php" style="margin-right: 15px;">Logout</a>
    </div>
</header>
  <!-- Fitur -->
  <div style="margin-top: 170px; text-align: center;">
    <h2 style="font-size: 70px; margin-top: 10px; margin-right: 120px">SiAntar</h2>
    <h3 class="p">Kondisi Pemesanan Anda</h3>
  </div>
  <!-- Tabel Data Pemesanan dan Peta -->
  <div class="table-container">
    <h3>Data Pemesanan</h3>
    <table>
      <thead>
        <tr>
          <th>Lokasi Jemput</th>
          <th>Lokasi Tujuan</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "SiAntar";

        // Membuat koneksi
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check koneksi
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        // Query untuk mendapatkan data pemesanan terbaru
        $sql = "SELECT address, address_b FROM pemesanan ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["address"] . "</td>";
            echo "<td>" . $row["address_b"] . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='2'>No data available</td></tr>";
        }
        $conn->close();
        ?>
      </tbody>
    </table>
    <!-- Peta Lokasi -->
    <div id="map"></div>
    <!-- Detail Pemesanan -->
    <div class="details">
      <?php
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "SiAntar";

      // Membuat koneksi
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check koneksi
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // Query untuk mendapatkan data pemesanan terbaru
      $sql = "SELECT distance, price, created_at FROM pemesanan ORDER BY id DESC LIMIT 1";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "Jarak: " . $row["distance"] . " km<br>";
          echo "Harga: Rp " . $row["price"] . "<br>";
          echo "Tanggal Pemesanan: " . $row["created_at"] . "<br>";
        }
      } else {
        echo "No data available";
      }
      $conn->close();
      ?>
    </div>
    <!-- Tombol -->
    <form method="post" action="">
      <div class="buttons">
        <button type="button" class="left-button cancel" onclick="confirmCancel()">Batal</button>
        <button type="submit" class="right-button" name="save_to_riwayat">Selesai</button>
      </div>
    </form>
  </div>
  <!-- Leaflet Map Script -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      var map = L.map('map').setView([-2.9623, 104.7418], 13); // Koordinat Palembang

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      <?php
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "SiAntar";

      // Membuat koneksi
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check koneksi
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // Query untuk mendapatkan data pemesanan terbaru
      $sql = "SELECT address, address_b FROM pemesanan ORDER BY id DESC LIMIT 1";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "var marker = L.marker([-2.9623, 104.7418]).addTo(map);";
          echo "marker.bindPopup('<b>Lokasi Jemput:</b> " . $row["address"] . "<br><b>Lokasi Tujuan:</b> " . $row["address_b"] . "').openPopup();";
        }
      }
      $conn->close();
      ?>
    });

    function confirmCancel() {
      if (confirm("Apakah Anda yakin ingin membatalkan pemesanan?")) {
        window.location.href = 'index.php';
      }
    }
  </script>
</body>
</html>
