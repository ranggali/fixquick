<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('connection_db.php'); 

// Query untuk memeriksa pesanan baru
$sql_pesanan = "
SELECT nomor_invoice, status_pesanan, kategori_jasa 
FROM pesanan_layanan 
WHERE id_warga = ? AND status_pesanan IN ('Dalam Proses', 'Ditolak')
";

$stmt_pesanan = mysqli_prepare($conn, $sql_pesanan);
if ($stmt_pesanan) {
    mysqli_stmt_bind_param($stmt_pesanan, "s", $_SESSION['id_warga']);
    mysqli_stmt_execute($stmt_pesanan);
    $result_pesanan = mysqli_stmt_get_result($stmt_pesanan);

    $pesanan_baru = [];
    while ($data_pesanan = mysqli_fetch_assoc($result_pesanan)) {
        $pesanan_baru[] = $data_pesanan;
    }

    mysqli_stmt_close($stmt_pesanan);
    echo json_encode(['status' => 'success', 'data' => $pesanan_baru]);
} else {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
}
?>