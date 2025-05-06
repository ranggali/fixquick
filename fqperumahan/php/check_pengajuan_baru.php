<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('connection_db.php'); // Pastikan untuk menyertakan koneksi database

$id_perumahan = $_SESSION['id_perumahan'];

// Query untuk menghitung total pengajuan dengan status 'Menunggu'
$sql = "
    SELECT COUNT(*) AS total_pengajuan 
    FROM pengajuan_pelayanan 
    WHERE id_perumahan = ? AND status_pengajuan = 'Menunggu'
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_perumahan);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode([
    'total_pengajuan' => $data['total_pengajuan']
]);
?>