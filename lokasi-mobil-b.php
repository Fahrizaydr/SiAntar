<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>SiAntar - Lokasi Antar</title>
  <!-- CSS Styles -->
 
  <!-- Font Awesome -->
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?= $main_url ?>asset/image/logo.png">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="asset/leaflet/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <!-- Leaflet JavaScript -->
  <script src="asset/leaflet/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <!-- Custom Styles -->
  <style>
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
      height: 100vh; /* agar halaman mengisi tinggi layar */
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
    h3 {
      margin-bottom: 10px;
    }
    h2 {
      color: black;
      margin-left: 120px;
      margin-bottom: 20px;
    }
    #map {
      width: 60%;
      height: 390px;
      margin-bottom: 20px;
    }
    #locationButton,
    #nextButton {
      margin: 20px;
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    /* Menengahkan tombol di tengah halaman */
    .button-container {
      display: flex;
      justify-content: center;
      width: 100%;
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
  <div>
    <h2 style="font-size: 70px; margin-top: 10px; margin-left: 3px">SiAntar</h2>
    <h3 class="p">Tentukan lokasi tujuan anda</h3>
  </div>
  <!-- Peta -->
  <div id="map">
    <div>
      <div id="mapid" style="width: 100%; height: 400px;"></div>
    </div>
  </div>
  <!-- Tombol Lokasi Saya dan Lanjut -->
  <div class="button-container">
    <button id="locationButton">Lokasi Saya</button>
    <button id="nextButton">Lanjut</button>
  </div>
  <!-- Formulir untuk menyimpan data marker -->
  <form id="markerForm" action="save_marker_antar_mobil.php" method="POST">
    <input type="hidden" id="latInput" name="lat-b">
    <input type="hidden" id="lngInput" name="lng-b">
    <input type="hidden" id="addressInput" name="address-b">
  </form>
  <!-- JavaScript -->
  <script>
    // Lokasi awal untuk pusat peta
    var curLocation = [-6.307723411599442, 106.75663473537229];
    var map = L.map('mapid').setView(curLocation, 16);

    // Menambahkan tile layer dari OpenStreetMap
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Variabel untuk menyimpan marker oren
    var orangeMarker;
    var orangeMarkerPosition;

    // Fungsi untuk menambahkan atau memindahkan marker oren
    function addOrMoveOrangeMarker(lat, lng, address) {
      var markerIcon = L.icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.0/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      });

      // Jika marker oren sudah ada, pindahkan ke lokasi baru
      if (orangeMarker) {
        orangeMarker.setLatLng([lat, lng]);
      } else {
        // Jika marker oren belum ada, tambahkan marker baru
        orangeMarker = L.marker([lat, lng], {
          icon: markerIcon
        }).addTo(map);
      }

      // Tambahkan popup ke marker
      orangeMarker.bindPopup("<b>Lokasi Tujuan</b><br/>Lat: " + lat + "<br/>Lng: " + lng + "<br/>Alamat: " + address).openPopup();

      // Simpan posisi marker oren
      orangeMarkerPosition = { lat: lat, lng: lng, address: address };
    }

    // Fungsi untuk mendapatkan alamat dari koordinat menggunakan Geocoding API
    function getAddress(lat, lng, callback) {
      var url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
      fetch(url)
        .then(response => response.json())
        .then(data => {
          callback(data.display_name);
        })
        .catch(error => {
          console.error('Error:', error);
          callback("Alamat tidak ditemukan");
        });
    }

    // Event listener untuk menambahkan atau memindahkan marker oren saat peta diklik
    map.on('click', function (e) {
      var lat = e.latlng.lat;
      var lng = e.latlng.lng;
      getAddress(lat, lng, function (address) {
        addOrMoveOrangeMarker(lat, lng, address);
      });
    });

    // Event listener untuk tombol "Lokasi Saya"
    document.getElementById('locationButton').addEventListener('click', function () {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
          var lat = position.coords.latitude;
          var lng = position.coords.longitude;
          map.setView([lat, lng], 16); // Memindahkan pusat peta ke lokasi terkini
          getAddress(lat, lng, function (address) {
            addOrMoveOrangeMarker(lat, lng, address);
          });
        }, function (error) {
          alert('Gagal mendapatkan lokasi: ' + error.message);
        });
      } else {
        alert('Geolocation tidak didukung oleh browser ini.');
      }
    });

    // Event listener untuk tombol "Lanjut"
    document.getElementById('nextButton').addEventListener('click', function () {
      if (orangeMarkerPosition) {
        // Mengisi input tersembunyi dengan posisi marker oren
        document.getElementById('latInput').value = orangeMarkerPosition.lat;
        document.getElementById('lngInput').value = orangeMarkerPosition.lng;
        document.getElementById('addressInput').value = orangeMarkerPosition.address;

        // Submit formulir ke save_marker_antar.php
        var form = document.getElementById('markerForm');
        form.submit();
      } else {
        alert('Tentukan lokasi terlebih dahulu.');
        // Mencegah pengiriman formulir jika lokasi belum ditentukan
        event.preventDefault();
      }
    });

    // Mendapatkan marker biru dari database dan menampilkannya di peta
    document.addEventListener("DOMContentLoaded", function() {
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

      // Query untuk mendapatkan marker biru dari database
      $sql = "SELECT latitude, longitude, address FROM pemesanan ORDER BY id DESC LIMIT 1";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $latitude = $row['latitude'];
        $longitude = $row['longitude'];
        $address = $row['address'];
        echo "addBlueMarker($latitude, $longitude, '$address');";
      }

      // Tutup koneksi
      $conn->close();
      ?>
    });

    // Fungsi untuk menambahkan marker biru
    function addBlueMarker(lat, lng, address) {
      var markerIcon = L.icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.0/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      });

      var blueMarker = L.marker([lat, lng], {
        icon: markerIcon
      }).addTo(map);

      blueMarker.bindPopup("<b>Lokasi Jemput</b><br/>Lat: " + lat + "<br/>Lng: " + lng + "<br/>Alamat: " + address).openPopup();
    }
  </script>
</body>
</html>
