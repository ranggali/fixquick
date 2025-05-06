<?php
// Pastikan Anda sudah terhubung dengan database
if (isset($_GET['id_pembayaran'])) {
    $id_pembayaran = $_GET['id_pembayaran'];

    // Query untuk menghapus data pembayaran
    $query = "DELETE FROM pembayaran_langganan WHERE id_pembayaran = $id_pembayaran";

    // Eksekusi query
    if ($conn->query($query)) {
        echo "Data pembayaran berhasil dihapus.";
        header("Location: pembayaran.php"); // Arahkan kembali ke halaman pembayaran
        exit;
    } else {
        echo "Gagal menghapus data pembayaran.";
    }
}
?>
