<?php
session_start();
require '../config/db.php';

// Periksa apakah driver sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: sign_in.php');
    exit;
}

// Ambil username dari session
$username = $_SESSION['username'];

try {
    // Menghapus data driver dari tabel driver_location
    $stmt = $conn->prepare("DELETE FROM driver_location 
                            WHERE driver_id IN (SELECT id FROM driver_bus WHERE username = :username)");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Hapus session yang ada
    session_destroy();

    // Arahkan pengguna kembali ke halaman login
    header('Location: sign_in.php');
    exit;

} catch (PDOException $e) {
    echo "Kesalahan: " . $e->getMessage();
}
?>
