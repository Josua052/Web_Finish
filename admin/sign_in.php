<?php
// Sertakan file koneksi database
require '../config/db.php';

// Mulai sesi
session_start();

// Variabel pesan untuk umpan balik
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sign_in'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        try {
            // Ambil hashed password dari database berdasarkan username
            $stmt = $conn->prepare("SELECT password FROM admin WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && password_verify($password, $result['password'])) {
                // Simpan informasi username di sesi
                $_SESSION['username'] = $username;
                
                // Alihkan ke halaman index admin setelah login berhasil
                header("Location: index.php");
                exit();
            } else {
                $message = "Username atau password salah.";
            }
        } catch (PDOException $e) {
            $message = "Kesalahan: " . $e->getMessage();
        }
    } else {
        $message = "Harap isi username dan password.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Admin</title>
    <link rel="stylesheet" href="adminsignin.css"></style>
</head>
<body>
    <div class="imagehalf"></div>
    <div class="formloginadmin">
        <h2>Sign In</h2>
        <form method="POST" action="">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <input type="submit" name="sign_in" value="Sign In">
        </form>

        <p><?php echo $message; ?></p>

        <p>Belum punya akun? <a href="sign_up.php">Sign Up</a></p>
    </div>
    
</body>
</html>
