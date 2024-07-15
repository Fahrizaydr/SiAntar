<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SiAntar";

// Ambil data dari POST
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$address = $_POST['address'];

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check koneksi
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepared statement untuk menyimpan data marker biru ke tabel pemesanan
$sql = "INSERT INTO pemesanan (latitude, longitude, address) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);

// Bind parameter
$stmt->bind_param("dds", $lat, $lng, $address);

// Execute statement
if ($stmt->execute()) {
  // Redirect ke halaman lokasi-motor-b.php setelah berhasil menyimpan
  header("Location: lokasi-motor-b.php");
  exit(); // Penting: pastikan keluar dari skrip setelah redirect
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

// Tutup statement dan koneksi
$stmt->close();
$conn->close();
?>
