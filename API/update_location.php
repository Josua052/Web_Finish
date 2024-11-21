<?php
session_start();
require '../config/db.php'; // Include the database configuration file

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    http_response_code(403); // Forbidden
    exit;
}

// Get the plat_nomor and username from the session
$plat_nomor = $_SESSION['plat_nomor']; // Plat nomor dari session
$username = $_SESSION['username']; // Username dari session
$lat = $_POST['lat']; // Latitude yang dikirim dari POST request
$lng = $_POST['lng']; // Longitude yang dikirim dari POST request

try {
    // Prepare the SQL query to update the driver's location in the driver_location table
    $query = "UPDATE driver_location 
              SET latitude = :lat, longitude = :lng 
              WHERE bus_id IN (SELECT id FROM bus WHERE plat_nomor = :plat_nomor)";

    // Prepare and execute the query using PDO
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':lat', $lat, PDO::PARAM_STR);
    $stmt->bindParam(':lng', $lng, PDO::PARAM_STR);
    $stmt->bindParam(':plat_nomor', $plat_nomor, PDO::PARAM_STR); // Gantilah plate_number dengan plat_nomor

    // Execute the statement
    if ($stmt->execute()) {
        // Send the response including plat_nomor and username to WebSocket
        $response = array(
            'plat_nomor' => $plat_nomor,
            'lat' => $lat,
            'lng' => $lng,
            'username' => $username
        );
        echo json_encode($response); // Send the updated data back to the client
    } else {
        echo "Error updating location.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
