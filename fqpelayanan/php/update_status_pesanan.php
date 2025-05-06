<?php
include('connection_db.php');

// Periksa apakah permintaan POST diterima
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data JSON dari permintaan
    $data = json_decode(file_get_contents('php://input'), true);

    $namaWarga = $data['namaWarga'];
    $namaPerumahan = $data['namaPerumahan'];
    $kategoriLayanan = $data['kategoriLayanan'];
    $newStatus = $data['newStatus'];

    // Update status di database
    $sql = "UPDATE pesanan_layanan pl
            JOIN warga w ON pl.id_warga = w.id_warga
            JOIN perumahan p ON w.id_perumahan = p.id_perumahan
            JOIN layanan l ON pl.id_layanan = l.id_layanan
            SET pl.status_pesanan = ?
            WHERE w.nama_warga = ? AND p.nama_perumahan = ? AND l.kategori_jasa = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $newStatus, $namaWarga, $namaPerumahan, $kategoriLayanan);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    echo json_encode(['success' => $success]);
} else {
    http_response_code(405); // Metode tidak diizinkan
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
}
?>
