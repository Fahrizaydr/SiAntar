<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SiAntar";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil data dari formulir
$form_username = $_POST['username'];
$form_password = $_POST['password'];
$is_admin = isset($_POST['admin']);

// Memilih tabel berdasarkan status admin atau user
$table_name = $is_admin ? 'admin' : 'user';

// Melakukan query untuk mengambil data user/admin berdasarkan username
$sql = "SELECT * FROM $table_name WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $form_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Memeriksa apakah password sesuai
    if (password_verify($form_password, $row['password'])) {
        // Menyimpan data user/admin ke session
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['is_admin'] = $is_admin; // Menyimpan status admin di session

        // Redirect ke halaman utama (sesuaikan dengan halaman admin jika login sebagai admin)
        if ($is_admin) {
            header('Location: ../admin/home.php');
        } else {
            header('Location: ../index.php');
        }
        exit; // Menghentikan eksekusi script setelah redirect
    } else {
        // Password tidak sesuai, tampilkan pesan error
        $_SESSION['error_message'] = "Username atau password salah.";
    }
} else {
    // Username tidak ditemukan, tampilkan pesan error
    $_SESSION['error_message'] = "Username atau password salah.";
}

// Mengarahkan kembali ke halaman login setelah error
header('Location: login.php');
exit;


?>
