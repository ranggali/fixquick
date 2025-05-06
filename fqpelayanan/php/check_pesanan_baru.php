<?php
session_start();
include('connection_db.php');

// Cek apakah pengguna login
if (!isset($_SESSION['id_pelayanan_jasa'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$id_pelayanan_jasa = $_SESSION['id_pelayanan_jasa'];

// Ambil pesanan terbaru dengan status "Menunggu"
$sql_pesanan = "
    SELECT id_pesanan, status_pesanan 
    FROM pesanan_layanan 
    WHERE id_pelayanan_jasa = ? AND status_pesanan = 'Menunggu' 
    ORDER BY id_pesanan DESC 
    LIMIT 1
";

$stmt_pesanan = mysqli_prepare($conn, $sql_pesanan);
if ($stmt_pesanan) {
    mysqli_stmt_bind_param($stmt_pesanan, "s", $id_pelayanan_jasa);
    mysqli_stmt_execute($stmt_pesanan);
    $result_pesanan = mysqli_stmt_get_result($stmt_pesanan);
    $data_pesanan = mysqli_fetch_assoc($result_pesanan);
    mysqli_stmt_close($stmt_pesanan);

    // Ambil ID pesanan terbaru
    $id_pesanan_baru = $data_pesanan['id_pesanan'] ?? null;

    // Cek apakah ada pesanan baru
    if ($id_pesanan_baru && (!isset($_SESSION['id_pesanan_terakhir']) || $_SESSION['id_pesanan_terakhir'] != $id_pesanan_baru)) {
        // Update session ID pesanan terakhir
        $_SESSION['id_pesanan_terakhir'] = $id_pesanan_baru;

        echo json_encode([
            'status' => 'success',
            'message' => 'Pesanan baru ditemukan',
            'id_pesanan' => $id_pesanan_baru,
            'status_pesanan' => $data_pesanan['status_pesanan']
        ]);
    } else {
        echo json_encode(['status' => 'success', 'message' => 'Tidak ada pesanan baru']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
}
?>
