<?php
include('../php/connection_db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_warga = $_POST['id_warga'];
    $nama = $_POST['nama'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    $is_aktif = $_POST['is_aktif'];

    if (!in_array($is_aktif, ['aktif', 'tidak aktif'])) {
        echo json_encode(['success' => false, 'message' => 'Status tidak valid']);
        exit;
    }
    
    $sql = "UPDATE warga SET nama_warga = ?, no_telepon = ?, alamat = ?, is_aktif = ? WHERE id_warga = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssi", $nama, $no_telepon, $alamat, $is_aktif, $id_warga);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui data']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Query error']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metode tidak valid']);
}
?>
