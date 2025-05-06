<?php
include('../php/connection_db.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
    alert('Anda harus login sebagai admin.');
    window.location.href = '../Fix QuickWebsite/login.php';
</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_layanan = $_POST['id_layanan'];
    $nama_penyedia_layanan = $_POST['nama_penyedia_layanan'];
    $kategori_jasa = $_POST['kategori_jasa'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $status_layanan = $_POST['status_layanan']; // Ambil status layanan

    // Query untuk memperbarui data layanan
    $sql = "UPDATE layanan SET 
                nama_penyedia_layanan = ?, 
                kategori_jasa = ?, 
                deskripsi = ?, 
                harga = ?, 
                status_layanan = ? 
            WHERE id_layanan = ?";

    // Persiapkan statement
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind parameter
        mysqli_stmt_bind_param($stmt, "sssssi", $nama_penyedia_layanan, $kategori_jasa, $deskripsi, $harga, $status_layanan, $id_layanan);
        
        // Eksekusi query
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
            alert('Data layanan berhasil diperbarui.');
            window.location.href = 'pelayananjasa.php'; // Ganti dengan halaman yang sesuai
            </script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        // Tutup statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
