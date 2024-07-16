<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SiAntar";

// Ambil data dari POST
$latB = $_POST['lat-b'];
$lngB = $_POST['lng-b'];
$addressB = $_POST['address-b'];

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check koneksi
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Query untuk mendapatkan id dari record terbaru
$sql_get_latest_id = "SELECT id FROM pemesanan ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql_get_latest_id);
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $latest_id = $row['id'];

  // Prepared statement untuk menyimpan data marker oren ke tabel pemesanan
  $sql_update = "UPDATE pemesanan SET latitude_b = ?, longitude_b = ?, address_b = ? WHERE id = ?";
  $stmt = $conn->prepare($sql_update);

  // Bind parameter
  $stmt->bind_param("ddsi", $latB, $lngB, $addressB, $latest_id);

  // Execute statement
  if ($stmt->execute()) {
    // Redirect ke halaman selanjutnya setelah berhasil menyimpan
    header("Location: konfirmasi-pemesanan-mobil.php"); // Ganti dengan halaman yang sesuai
    exit(); // Penting: pastikan keluar dari skrip setelah redirect
  } else {
    echo "Error: " . $sql_update . "<br>" . $conn->error;
  }

  // Tutup statement
  $stmt->close();
} else {
  echo "No pemesanan record found to update.";
}

// Tutup koneksi
$conn->close();
?>
