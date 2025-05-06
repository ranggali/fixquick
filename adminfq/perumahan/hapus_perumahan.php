<?php
// Koneksi ke database
include '../php/connection_db.php';

if (isset($_GET['id'])) {
    $id_perumahan = $_GET['id'];

    // Query hapus berdasarkan id_perumahan
    $query = "DELETE FROM perumahan WHERE id_perumahan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_perumahan);

    if ($stmt->execute()) {
        // Redirect dengan pesan sukses
        echo "<script>
                alert('Data perumahan berhasil dihapus!');
                window.location.href = 'adminperum.php'; // Ganti dengan halaman utama Anda
              </script>";
    } else {
        // Redirect dengan pesan error
        echo "<script>
                alert('Terjadi kesalahan saat menghapus data.');
                window.location.href = 'adminperum.php'; // Ganti dengan halaman utama Anda
              </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: adminperum.php");
}
?>