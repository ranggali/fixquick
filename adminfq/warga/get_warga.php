<?php
// Konfigurasi koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$database = "fixquick_db"; // Ganti dengan nama database Anda

$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data warga
$sql = "SELECT id_warga, nama_warga, perumahan, alamat, no_telepon, is_aktif FROM warga";
$result = $conn->query($sql);

// Memeriksa apakah ada data
if ($result->num_rows > 0) {
    $warga = [];
    while ($row = $result->fetch_assoc()) {
        $warga[] = $row;
    }
    echo json_encode($warga);
} else {
    echo json_encode([]);
}

$conn->close();
?>