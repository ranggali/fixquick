<?php
// Koneksi ke database
include '../php/connection_db.php';

// Ambil data dari request
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $id = $data['id'];
    $nama_pengguna = $data['nama_pengguna'];
    $nama_perumahan = $data['nama_perumahan'];
    $alamat = $data['alamat'];
    $no_telepon = $data['no_telepon'];
    $is_aktif = $data['is_aktif'];

    // Query update data
    $query = "UPDATE perumahan SET 
                nama_pengguna = ?, 
                nama_perumahan = ?, 
                alamat = ?, 
                no_telepon = ?, 
                is_aktif = ? 
              WHERE id_perumahan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $nama_pengguna, $nama_perumahan, $alamat, $no_telepon, $is_aktif, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }

    $stmt->close();
    $conn->close();
}
?>
