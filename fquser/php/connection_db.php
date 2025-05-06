<?php
// db.php
$servername = "localhost"; // Sesuaikan dengan server Anda
$username = "root"; // Sesuaikan dengan username Anda
$password = ""; // Sesuaikan dengan password Anda
$dbname = "fixquick_db"; // Nama database

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>