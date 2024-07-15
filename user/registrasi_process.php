<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SiAntar";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan data dari formulir
$user = $_POST['username'];
$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Menyiapkan dan mengikat
$stmt = $conn->prepare("INSERT INTO user (username, fullname, phone, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $user, $fullname, $phone, $pass);

// Eksekusi statement
if ($stmt->execute()) {
    // Jika berhasil, redirect ke halaman login dengan pesan
    session_start();
    $_SESSION['message'] = "Registrasi berhasil. Silahkan login untuk melanjutkan.";
    header("Location: login.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
