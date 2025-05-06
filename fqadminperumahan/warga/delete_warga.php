<?php
// Pastikan ini file `hapus_warga.php`
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Koneksi ke database
    include '../php/connection_db.php'; // Sesuaikan dengan file koneksi Anda

    // Validasi input
    $id_warga = isset($_GET['id']) ? intval($_GET['id']) : null;

    if ($id_warga) {
        // Query untuk menghapus data
        $query = "DELETE FROM warga WHERE id_warga = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_warga);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'ID tidak valid']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Metode tidak valid']);
}
