<?php
session_start();
require '../config/db.php';

// Periksa apakah driver sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: sign_in.php');
    exit;
}

// Ambil email dari session
$email = $_SESSION['email'];

// Ambil username berdasarkan email dari tabel driver_bus
try {
    $stmt = $conn->prepare("SELECT username FROM driver_bus WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Jika data ditemukan
    if ($stmt->rowCount() > 0) {
        $driver = $stmt->fetch(PDO::FETCH_ASSOC);
        $username = $driver['username'];
    } else {
        // Jika email tidak ditemukan (seharusnya tidak terjadi jika login benar)
        $username = "Tidak Diketahui";
    }
} catch (PDOException $e) {
    echo "Kesalahan: " . $e->getMessage();
    exit;
}

// Ambil plat nomor dari session
$plat_nomor = $_SESSION['plat_nomor']; // Plat nomor yang dipilih saat login

// Ambil data driver dan plat nomor dari tabel driver_location
try {
    $stmt = $conn->prepare("SELECT bus.plat_nomor, driver_location.latitude, driver_location.longitude 
                            FROM driver_location 
                            JOIN bus ON driver_location.bus_id = bus.id
                            JOIN driver_bus ON driver_location.driver_id = driver_bus.id
                            WHERE driver_bus.email = :email AND bus.plat_nomor = :plat_nomor");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':plat_nomor', $plat_nomor, PDO::PARAM_STR);
    $stmt->execute();

    // Jika data ditemukan
    if ($stmt->rowCount() > 0) {
        $driver_location = $stmt->fetch(PDO::FETCH_ASSOC);
        $latitude = $driver_location['latitude'];
        $longitude = $driver_location['longitude'];

        // Ambil nama bus jika ada
        $bus_name = 'Linus';  // Ganti dengan nama bus yang sesuai
    } else {
        // Jika tidak ditemukan
        $bus_name = 'Bus tidak ditemukan';
        $latitude = $longitude = null;
    }
} catch (PDOException $e) {
    echo "Kesalahan: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Driver</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
        }
        #map {
            height: 100%;
            width: 100%;
            position: relative;
        }
        .info-box {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div id="map"></div>
    <div class="info-box">
        <h1>Selamat Datang, <?php echo htmlspecialchars($username); ?></h1>
        <p>Anda sedang mengendarai bus <strong><?php echo htmlspecialchars($bus_name); ?></strong> dengan plat nomor <strong><?php echo htmlspecialchars($plat_nomor); ?></strong>.</p>
        <p>Lokasi Anda akan diperbarui secara otomatis setiap detik</p>
        <a href="sign_out.php">Sign Out</a>
    </div>

    <script>
    let map;
    let driverMarker;
    let ws;

    function initMap() {
        // Inisialisasi Google Map
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: 3.559263, lng: 98.660454 }, // Koordinat awal
            zoom: 13
        });

        // Marker driver
        driverMarker = new google.maps.Marker({
            map: map,
            title: "Lokasi Anda",
            icon: {
                url: "../img/bus-driver.png", // Ganti dengan ikon khusus jika ada
                scaledSize: new google.maps.Size(50, 50)
            }
        });

        // Tampilkan rute
        fetchRoute();

        // Tampilkan halte
        fetchStops();

        // WebSocket untuk real-time location
        ws = new WebSocket('ws://localhost:8080/bus-location');
        ws.onmessage = updateMarker;
        setInterval(updateLocation, 10000);
    }

    // Fungsi untuk menggambar rute pada peta
    function drawRoute(coordinates) {
        const routePath = new google.maps.Polyline({
            path: coordinates,
            geodesic: true,
            strokeColor: '#008000',
            strokeOpacity: 1.0,
            strokeWeight: 4
        });
        routePath.setMap(map);

        // Sesuaikan tampilan peta agar rute terlihat jelas
        const bounds = new google.maps.LatLngBounds();
        coordinates.forEach(coord => bounds.extend(coord));
        map.fitBounds(bounds);
    }

    // Fungsi untuk menambahkan ikon halte pada peta
    function addBusStop(stop) {
        new google.maps.Marker({
            position: { lat: parseFloat(stop.latitude), lng: parseFloat(stop.longitude) },
            map: map,
            title: stop.name,
            icon: {
                url: "img/halte.png", // Ganti dengan ikon khusus jika ada
                scaledSize: new google.maps.Size(40, 40)
            }
        });
    }

    // Fungsi untuk memuat rute dari API
    async function fetchRoute() {
        try {
            const response = await fetch('../API/get_route.php');
            const routeData = await response.json();

        // Urutkan berdasarkan sequence
        routeData.sort((a, b) => a.sequence - b.sequence);

        // Ambil koordinat untuk rute
        const coordinates = routeData.map(point => ({
            lat: parseFloat(point.latitude),
            lng: parseFloat(point.longitude)
        }));

        // Sambungkan titik terakhir ke titik pertama untuk membuat lintasan tertutup
        if (coordinates.length > 0) {
            coordinates.push(coordinates[0]);
        }

        // Gambar rute
        drawRoute(coordinates);
        } catch (error) {
        console.error('Gagal memuat rute:', error);
        }
    }

    // Fungsi untuk memuat halte dari API
    async function fetchStops() {
        try {
            const response = await fetch('../API/get_stops.php');
            const stopsData = await response.json();
            stopsData.forEach(stop => addBusStop(stop));
        } catch (error) {
            console.error('Gagal memuat halte:', error);
        }
    }

    // Fungsi untuk memperbarui lokasi driver
    function updateLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const data = JSON.stringify({
                    username: "<?php echo $username; ?>",
                    plat_nomor: "<?php echo $plat_nomor; ?>",
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                });
                ws.send(data);

                // Update posisi marker driver
                driverMarker.setPosition({
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                });
                map.panTo({
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                });
            });
        } else {
            alert("Geolocation tidak didukung oleh browser ini.");
        }
    }

    // Fungsi untuk memperbarui marker driver
    function updateMarker(event) {
        const locationData = JSON.parse(event.data);
        driverMarker.setPosition({
            lat: locationData.lat,
            lng: locationData.lng
        });
    }
</script>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQysCqG7Sro0uY27iNvQ6MJ86oWkiLccs&callback=initMap" async defer></script>
</body>
</html>


