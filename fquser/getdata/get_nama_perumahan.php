<?php
session_start();
require_once '../php/connection_db.php';

$id_warga = $_SESSION['id_warga'] ?? null;

if ($id_warga) {
    $query = $conn->prepare("SELECT p.nama_perumahan FROM warga w JOIN perumahan p ON w.id_perumahan = p.id_perumahan WHERE w.id_warga = ?");
    $query->bind_param("i", $id_warga);
    $query->execute();
    $result = $query->get_result();
    $data = $result->fetch_assoc();
    echo json_encode($data['nama_perumahan'] ?? 'Perumahan tidak ditemukan');
    $query->close();
}
?>