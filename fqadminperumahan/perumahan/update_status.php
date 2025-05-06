<?php
include('../php/connection_db.php');
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data['id_pengajuan']) && !empty($data['status_pengajuan'])) {
    $id_pengajuan = $data['id_pengajuan'];
    $status_pengajuan = $data['status_pengajuan'];

    $sql = "UPDATE pengajuan_pelayanan SET status_pengajuan = ? WHERE id_pengajuan = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status_pengajuan, $id_pengajuan);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}

mysqli_close($conn);
?>
