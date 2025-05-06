<?php
session_start();
require_once '../php/connection_db.php';

$id_warga = $_SESSION['id_warga'] ?? null;

if ($id_warga) {
    $query = $conn->prepare("
        SELECT 
            id_layanan, 
            kategori_jasa, 
            id_pelayanan_jasa,
            harga 
        FROM layanan 
        WHERE id_perumahan = (SELECT id_perumahan FROM warga WHERE id_warga = ?)
    ");
    $query->bind_param("i", $id_warga);
    $query->execute();
    $result = $query->get_result();

    $kategoriJasa = [];
    while ($row = $result->fetch_assoc()) {
        $kategoriJasa[] = $row;
    }

    echo json_encode($kategoriJasa);
    $query->close();
}
?>
