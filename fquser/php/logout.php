<?php
session_start();
require_once 'connection_db.php'; // Pastikan untuk menyertakan koneksi database

if (isset($_SESSION['id_warga'])) {
    // Update status_masuk menjadi 'tidak aktif' untuk pengguna resident
    $updateQuery = $conn->prepare("UPDATE warga SET status_masuk='tidak aktif' WHERE id_warga=?");
    $updateQuery->bind_param("i", $_SESSION['id_warga']); // Menggunakan id_warga untuk update
    $updateQuery->execute();
    $updateQuery->close(); // Menutup query update
}

// Menghapus semua session
session_unset(); 
session_destroy(); // Mengakhiri session

header("Location: ../../login.php");
exit;
?>