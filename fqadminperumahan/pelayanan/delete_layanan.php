<?php
include('../php/connection_db.php');
header('Content-Type: application/json');

// Ambil data dari permintaan AJAX
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['nama_penyedia_layanan'])) {
    $nama_penyedia_layanan = $data['nama_penyedia_layanan'];

    // Query untuk menghapus data
    $sql = "DELETE FROM layanan WHERE nama_penyedia_layanan = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $id_layanan);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ID layanan tidak ditemukan.']);
}
?>
