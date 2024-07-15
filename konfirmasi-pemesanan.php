<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>SiAntar - Konfirmasi Pemesanan</title>
  <!-- CSS Styles -->
  <link href="<?= $main_url ?>asset/sb-admin/css/styles.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?= $main_url ?>asset/image/logo.png">
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <!-- Custom Styles -->
  <style>
    body {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      margin: 0;
      overflow-x: hidden;
      font-family: Arial, sans-serif;
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
    .content {
      margin-top: 80px; /* Adjust according to header height */
      width: 80%;
      text-align: center;
    }
    h2 {
      color: black;
      margin-bottom: 20px;
    }
    .table-container {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
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
    #nextButton {
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
    #map {
      width: 100%;
      height: 400px;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <div class="logo">SiAntar</div>
    <div class="header-links">
      <a href="#">Profil</a>
      <a href="#">Logout</a>
    </div>
  </header>

  <!-- Fitur -->
  <div class="content">
    <h2>SiAntar</h2>
    <h3>Konfirmasi Pemesanan Anda</h3>

    <!-- Tabel Data User dan Pemesanan -->
    <div class="table-container">
      <h3>Data User</h3>
      <table>
        <thead>
          <tr>
            <th>Username</th>
            <th>Fullname</th>
            <th>Phone</th>
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

          // Query untuk mendapatkan data user
          $sql = "SELECT username, fullname, phone FROM user ORDER BY id DESC LIMIT 1";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              echo "<tr><td>" . $row["username"]. "</td><td>" . $row["fullname"]. "</td><td>" . $row["phone"]. "</td></tr>";
            }
          } else {
            echo "<tr><td colspan='3'>No data found</td></tr>";
          }
          ?>
        </tbody>
      </table>

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
          // Query untuk mendapatkan data pemesanan
          $sql = "SELECT address, address_b, latitude, longitude, latitude_b, longitude_b FROM pemesanan ORDER BY id DESC LIMIT 1";
          $result = $conn->query($sql);

          $latitude = "";
          $longitude = "";
          $latitude_b = "";
          $longitude_b = "";

          if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              echo "<tr><td>" . $row["address"]. "</td><td>" . $row["address_b"]. "</td></tr>";
              $latitude = $row["latitude"];
              $longitude = $row["longitude"];
              $latitude_b = $row["latitude_b"];
              $longitude_b = $row["longitude_b"];
            }
          } else {
            echo "<tr><td colspan='2'>No data found</td></tr>";
          }

          $conn->close();
          ?>
        </tbody>
      </table>

      <!-- Map Container -->
      <div id="map"></div>
      <h3>Jarak: <span id="distance"></span> meter</h3>
      <h3>Harga: Rp <span id="price"></span></h3>

      <form id="nextForm" action="save_to_riwayat.php" method="POST">
        <input type="hidden" name="distance" id="distanceInput">
        <input type="hidden" name="price" id="priceInput">
        <button id="nextButton">Next</button>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Membuat peta dan menampilkan marker untuk lokasi jemput dan tujuan
      var map = L.map('map').setView([<?= $latitude ?>, <?= $longitude ?>], 13);

      // Menambahkan tile layer dari OpenStreetMap
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      // Menambahkan marker untuk lokasi jemput dan tujuan
      var markerA = L.marker([<?= $latitude ?>, <?= $longitude ?>]).addTo(map);
      var markerB = L.marker([<?= $latitude_b ?>, <?= $longitude_b ?>]).addTo(map);

      // Membuat objek latlng untuk lokasi jemput dan tujuan
      var latlngA = L.latLng(<?= $latitude ?>, <?= $longitude ?>);
      var latlngB = L.latLng(<?= $latitude_b ?>, <?= $longitude_b ?>);

      // Menggambar garis polyline antara lokasi jemput dan tujuan
      var polyline = L.polyline([latlngA, latlngB], {color: 'blue'}).addTo(map);

      // Menghitung jarak antara dua titik dalam meter
      var distance = map.distance(latlngA, latlngB);
      // Menggunakan rumus harga yang benar
      var price = 10000 + ((distance / 1000) * 2000);

      // Menampilkan jarak dan harga pada elemen HTML
      document.getElementById('distance').innerText = distance.toFixed(2);
      document.getElementById('price').innerText = price.toFixed(2);

      // Mengisi nilai jarak dan harga ke dalam input form
      document.getElementById('distanceInput').value = distance.toFixed(2);
      document.getElementById('priceInput').value = price.toFixed(2);
    });
  </script>
</body>
</html>
