<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SiAntar</title>
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            background-image: url('../img/bglogin.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }
        
        .card {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background: white;
            max-width: 400px;
            width: 100%;
            padding: 20px;
            text-align: center;
        }

        .form-control {
            border-radius: 5px;
            margin-bottom: 15px;
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
        }

        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .text-muted a {
            color: #0083b0;
            text-decoration: none;
        }

        .text-muted a:hover {
            color: #00b4db;
        }

        .alert {
            margin-bottom: 20px;
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px;
            border-radius: 5px;
        }

        .card-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-container h1 {
            margin-bottom: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        .form-check-input {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h1>Login</h1>
            <p>Silahkan masuk menggunakan akun Anda</p>
        </div>

        <?php
        // Menampilkan pesan error jika ada
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']); // Menghapus pesan error setelah ditampilkan
        }
        ?>

        <form action="proses-login.php" method="POST">
            <input type="text" class="form-control" name="username" placeholder="Username" required>
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <input type="checkbox" class="form-check-input" name="admin" id="admin">
            <label for="admin">Login sebagai admin</label>
            <button type="submit" class="btn-primary">Login</button>
        </form>
        <div class="text-muted">
            <p>Belum punya akun? <a href="register.php">Registrasi</a></p>
        </div>
    </div>
</body>
</html>
