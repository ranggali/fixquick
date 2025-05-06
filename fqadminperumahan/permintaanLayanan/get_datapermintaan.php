<?php
include('../php/connection_db.php');

header('Content-Type: application/json');

if (!isset($_GET['id_permintaan'])) {
    echo json_encode(['success' => false, 'message' => 'ID permintaan tidak disediakan']);
    exit;
}

$id_permintaan = $_GET['id_permintaan'];

// Query untuk mendapatkan detail permintaan layanan
$sql = "SELECT 
            id_permintaan,
            id_warga,
            id_perumahan,
            nama_warga,
            no_telepon,
            alamat,
            kategori,
            deskripsi_permintaan,
            created_at
        FROM permintaan_layanan 
        WHERE id_permintaan = ?";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id_permintaan);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode(['success' => true] + $row); // merge data ke dalam response
    } else {
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'message' => 'Kesalahan pada query']);
}
?>
