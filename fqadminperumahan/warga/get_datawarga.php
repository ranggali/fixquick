<?php
include('../php/connection_db.php');

if (!isset($_GET['id_warga'])) {
    echo json_encode(['success' => false, 'message' => 'ID warga tidak disediakan']);
    exit;
}

$id_warga = $_GET['id_warga'];

// Query untuk mendapatkan detail warga
$sql = "SELECT id_warga, nama_warga AS nama, no_telepon, alamat, email, foto_profil, is_aktif FROM warga WHERE id_warga = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id_warga);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Buat path URL relatif untuk foto
        $foto_profil_path = $row['foto_profil'] 
            ? '../../fquser/php/' . $row['foto_profil'] 
            : null;

        echo json_encode([
            'success' => true,
            'nama' => $row['nama'],
            'no_telepon' => $row['no_telepon'],
            'alamat' => $row['alamat'],
            'email' => $row['email'],
            'foto_profil' => $foto_profil_path,
            'is_aktif' => $row['is_aktif']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'message' => 'Kesalahan pada query']);
}
?>
