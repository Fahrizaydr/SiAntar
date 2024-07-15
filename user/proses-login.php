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

// Melakukan query untuk mengambil data user berdasarkan username
$sql = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $form_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Memeriksa apakah password sesuai
    if (password_verify($form_password, $row['password'])) {
        // Menyimpan data user ke session
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        // Redirect ke halaman utama
        header('Location: ../index.php');
        exit;
    } else {
        echo "Password salah.";
    }
} else {
    echo "Username tidak ditemukan.";
}

$stmt->close();
$conn->close();
?>

<?php
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

// Data user baru
$new_username = "user1";
$new_password = password_hash("password123", PASSWORD_DEFAULT); // Hash password
$new_no_telpon = "081234567890";

// Menyimpan data user ke tabel 'user'
$sql = "INSERT INTO user (username, password, no_telpon) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $new_username, $new_password, $new_no_telpon);

if ($stmt->execute()) {
    echo "User berhasil ditambahkan.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>
