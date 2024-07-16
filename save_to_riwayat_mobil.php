<?php
// Ambil data jarak dan harga dari POST
$distance = $_POST['distance'];
$price = $_POST['price'];

// Data lain yang akan disimpan, sesuaikan dengan kebutuhan
$address = "Alamat Lokasi Jemput";
$address_b = "Alamat Lokasi Tujuan";
$latitude = "Latitude Jemput";
$longitude = "Longitude Jemput";
$latitude_b = "Latitude Tujuan";
$longitude_b = "Longitude Tujuan";

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SiAntar";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek apakah sudah ada data pemesanan sebelumnya
$sql_check = "SELECT * FROM pemesanan ORDER BY id DESC LIMIT 1";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    // Jika sudah ada, update data yang sudah ada
    $row = $result_check->fetch_assoc();
    $id = $row['id'];
    
    $sql_update = "UPDATE pemesanan SET distance='$distance', price='$price' WHERE id='$id'";
    
    if ($conn->query($sql_update) === TRUE) {
        // Redirect ke halaman kondisi-pemesanan.php setelah penyimpanan berhasil
        header("Location: kondisi-pemesanan-mobil.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    // Jika belum ada, masukkan baris baru dengan data lengkap
    $sql_insert = "INSERT INTO pemesanan (address, address_b, latitude, longitude, latitude_b, longitude_b, distance, price) 
                    VALUES ('$address', '$address_b', '$latitude', '$longitude', '$latitude_b', '$longitude_b', '$distance', '$price')";

    if ($conn->query($sql_insert) === TRUE) {
        // Redirect ke halaman kondisi-pemesanan.php setelah penyimpanan berhasil
        header("Location: kondisi-pemesanan-mobil.php");
        exit();
    } else {
        echo "Error inserting record: " . $conn->error;
    }
}

$conn->close();
?>
