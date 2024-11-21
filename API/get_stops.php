<?php
require '../config/db.php';

// Query untuk mendapatkan data halte bus dengan mengambil nama halte dan koordinat dari tabel coordinates
$query = "
    SELECT bs.name, c.latitude, c.longitude
    FROM bus_stop bs
    JOIN coordinates c ON bs.coordinate_id = c.id
";

// Menjalankan query dengan PDO
$stmt = $conn->prepare($query);
$stmt->execute();

// Ambil hasil query
$stops = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $stops[] = $row;
}

// Set header untuk output JSON
header('Content-Type: application/json');

// Kirim data halte dalam format JSON
echo json_encode($stops);
?>
