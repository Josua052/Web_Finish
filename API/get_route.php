<?php
require '../config/db.php';

// Query untuk mendapatkan rute bus berdasarkan urutan sequence dan mengambil latitude, longitude dari tabel coordinates
$query = "
    SELECT c.latitude, c.longitude, br.sequence 
    FROM bus_route br
    JOIN coordinates c ON br.coordinate_id = c.id
    ORDER BY br.sequence ASC
";

// Eksekusi query
$stmt = $conn->prepare($query);
$stmt->execute();

// Ambil hasil query
$route = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $route[] = $row;
}

// Set header untuk output JSON
header('Content-Type: application/json');

// Kirim data rute dalam format JSON
echo json_encode($route);
?>
