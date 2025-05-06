<?php
include '../php/connection_db.php'; // Pastikan file koneksi database Anda benar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_metode = $conn->real_escape_string($_POST['nama_metode']);
    $kode_metode = $conn->real_escape_string($_POST['kode_metode']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);

    $query = "INSERT INTO metode_pembayaran (nama_metode, kode_metode, deskripsi, is_aktif, created_at, updated_at) 
              VALUES ('$nama_metode', '$kode_metode', '$deskripsi', 1, NOW(), NOW())";

    if ($conn->query($query)) {
        echo "Metode pembayaran berhasil ditambahkan!";
    } else {
        echo "Error: " . $conn->error;
    }

    header("Location: halaman_pembayaran.php");
    exit();
}
?>
