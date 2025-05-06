<?php
include('../php/connection_db.php');

if (isset($_GET['id_warga'])) {
    $id_warga = $_GET['id_warga'];

    $sql = "SELECT id_warga, nama_warga AS nama, no_telepon, alamat, is_aktif FROM warga WHERE id_warga = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_warga);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);

        echo json_encode(['success' => true, 'warga' => $data]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mendapatkan data']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID warga tidak valid']);
}
?>
