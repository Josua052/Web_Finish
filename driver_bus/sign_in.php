<?php
session_start();
require '../config/db.php';  // Pastikan koneksi db.php sudah menggunakan PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $plat_nomor = $_POST['plat_nomor'];  // Menggunakan plat_nomor sesuai dengan tabel

    // Query untuk mencari driver berdasarkan email
    $sql = "SELECT id, password FROM driver_bus WHERE email = :email";
    $stmt = $conn->prepare($sql);

    // Bind parameter untuk mencegah SQL Injection
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    // Eksekusi query
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Ambil hasil query
        $driver = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashed_password = $driver['password'];

        // Verifikasi password
        if (password_verify($password, $hashed_password)) {
            // Set session jika login berhasil
            $_SESSION['email'] = $email;
            $_SESSION['loggedin'] = true;

            // Cari ID bus berdasarkan plat_nomor
            $sqlBus = "SELECT id FROM bus WHERE plat_nomor = :plat_nomor";  // Menggunakan plat_nomor
            $stmtBus = $conn->prepare($sqlBus);
            $stmtBus->bindParam(':plat_nomor', $plat_nomor, PDO::PARAM_STR);  // Bind plat_nomor
            $stmtBus->execute();

            if ($stmtBus->rowCount() > 0) {
                $bus = $stmtBus->fetch(PDO::FETCH_ASSOC);
                $bus_id = $bus['id'];

                // Ambil koordinat (latitude, longitude) dari driver (misal: hardcoded atau dari perangkat)
                $latitude = 0.0;  // Gantilah dengan data latitude yang sesuai
                $longitude = 0.0; // Gantilah dengan data longitude yang sesuai

                // Periksa apakah sudah ada entri di tabel driver_location untuk driver ini
                $checkLocation = $conn->prepare("SELECT id FROM driver_location WHERE driver_id = :driver_id AND bus_id = :bus_id");
                $checkLocation->bindParam(':driver_id', $driver['id']);
                $checkLocation->bindParam(':bus_id', $bus_id);
                $checkLocation->execute();

                if ($checkLocation->rowCount() > 0) {
                    // Jika sudah ada entri, update is_active menjadi TRUE
                    $updateLocation = $conn->prepare("UPDATE driver_location 
                                                      SET is_active = TRUE, latitude = :latitude, longitude = :longitude, timestamp = CURRENT_TIMESTAMP 
                                                      WHERE driver_id = :driver_id AND bus_id = :bus_id");
                    $updateLocation->bindParam(':latitude', $latitude);
                    $updateLocation->bindParam(':longitude', $longitude);
                    $updateLocation->bindParam(':driver_id', $driver['id']);
                    $updateLocation->bindParam(':bus_id', $bus_id);
                    $updateLocation->execute();
                } else {
                    // Jika belum ada entri, masukkan data lokasi pengemudi ke tabel driver_location
                    $insertLocation = $conn->prepare("INSERT INTO driver_location (driver_id, bus_id, latitude, longitude, is_active) 
                                                      VALUES (:driver_id, :bus_id, :latitude, :longitude, TRUE)");
                    $insertLocation->bindParam(':driver_id', $driver['id']);
                    $insertLocation->bindParam(':bus_id', $bus_id);
                    $insertLocation->bindParam(':latitude', $latitude);
                    $insertLocation->bindParam(':longitude', $longitude);
                    $insertLocation->execute();
                }

                // Arahkan driver ke halaman dashboard
                $_SESSION['plat_nomor'] = $plat_nomor;  // Simpan plat nomor yang dipilih di session
                $_SESSION['bus_id'] = $bus_id;  // Simpan ID bus untuk referensi selanjutnya

                // Alihkan ke halaman index setelah berhasil
                header('Location: index.php');
                exit;
            } else {
                $error = "Plat nomor bus tidak ditemukan!";
            }
        } else {
            // Pesan jika password salah
            $error = "Password salah!";
        }
    } else {
        // Pesan jika email tidak ditemukan
        $error = "Email tidak ditemukan!";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <title>Sign In Driver Bus</title>
    <link rel="stylesheet" href="css/driver_signin.css">
</head>
<body>
<div class="overlay"></div>
    <div class="signin">
        <div class="content">
            <h1>Sign In Driver Bus</h1>
            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
            <form method="post" class="signin-form">
                <div class="inputBox">
                    <input type="text" name="email" required>
                    <i>Email</i>
                </div>
                <div class="inputBox">
                    <input type="password" name="password" required>
                    <i>Password</i>
                </div>
                <div class="inputBox">
                    <select name="plat_nomor" required>  <!-- Ganti plate_number menjadi plat_nomor -->
                        <option value="">Pilih Plat Nomor Bus</option>
                        <?php
                        // Ambil daftar plat_nomor bus dari tabel bus
                        $sql = "SELECT plat_nomor FROM bus";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();

                        // Tampilkan semua plat_nomor dalam dropdown
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['plat_nomor']}'>{$row['plat_nomor']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="links">
                    <a href="change_password._driver.php">Forgot Password</a> 
                    <a href="sign_up.php">Signup</a>
                </div>
                <button type="submit">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>
