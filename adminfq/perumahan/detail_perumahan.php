<?php
include '../php/connection_db.php'; // Pastikan koneksi database benar

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Query untuk mengambil detail data
    $query = "SELECT nama_pengguna, nama_perumahan, alamat, no_telepon, is_aktif 
              FROM perumahan 
              WHERE id_perumahan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data); // Mengirim data dalam format JSON
    } else {
        echo json_encode(["error" => "Data tidak ditemukan"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "ID tidak diberikan"]);
}
?>