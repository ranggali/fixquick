<?php
include '../php/connection_db.php';

if (isset($_GET['perumahan'])) {
    $perumahan = $conn->real_escape_string($_GET['perumahan']);

    $query = "DELETE FROM pembayaran WHERE perumahan = '$perumahan'";
    if ($conn->query($query)) {
        echo "Data berhasil dihapus.";
        header("Location: pembayaran.php"); // Ganti dengan nama file halaman tabel
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
