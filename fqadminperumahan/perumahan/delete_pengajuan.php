<?php
include('../php/connection_db.php');

// Cek apakah request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari body JSON
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['id_pengajuan'])) {
        $id_pengajuan = $input['id_pengajuan'];

        // Query untuk menghapus data berdasarkan id_pengajuan
        $sql = "DELETE FROM pengajuan_pelayanan WHERE id_pengajuan = ?";

        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $id_pengajuan);
            if (mysqli_stmt_execute($stmt)) {
                // Hapus berhasil
                echo json_encode(["success" => true]);
            } else {
                // Gagal eksekusi query
                echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
            }
            mysqli_stmt_close($stmt);
        } else {
            // Gagal prepare query
            echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
        }
    } else {
        // ID tidak ada di request
        echo json_encode(["success" => false, "error" => "ID pengajuan tidak ditemukan."]);
    }
} else {
    // Request bukan POST
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
}
?>
