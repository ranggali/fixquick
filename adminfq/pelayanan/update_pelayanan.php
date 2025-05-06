<?php
// Koneksi ke database
include '../php/connection_db.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data && isset($data['id'])) {
    $id = $data['id'];

    // Array untuk menyimpan bagian query yang akan diupdate
    $columnsToUpdate = [];
    $values = [];

    if (isset($data['nama_penyedia_jasa'])) {
        $columnsToUpdate[] = "nama_penyedia_jasa = ?";
        $values[] = $data['nama_penyedia_jasa'];
    }
    if (isset($data['email'])) {
        $columnsToUpdate[] = "email = ?";
        $values[] = $data['email'];
    }
    if (isset($data['kategori_layanan'])) {
        $columnsToUpdate[] = "kategori_layanan = ?";
        $values[] = $data['kategori_layanan'];
    }
    if (isset($data['no_telepon'])) {
        $columnsToUpdate[] = "no_telepon = ?";
        $values[] = $data['no_telepon'];
    }
    if (isset($data['alamat'])) {
        $columnsToUpdate[] = "alamat = ?";
        $values[] = $data['alamat'];
    }
    if (isset($data['is_aktif'])) {
        $columnsToUpdate[] = "is_aktif = ?";
        $values[] = $data['is_aktif'];
    }

    // Pastikan ada kolom yang diupdate
    if (!empty($columnsToUpdate)) {
        $query = "UPDATE pelayanan_jasa SET " . implode(", ", $columnsToUpdate) . " WHERE id_pelayanan_jasa = ?";
        $values[] = $id; // Tambahkan ID ke akhir array values

        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat("s", count($values) - 1) . "i", ...$values);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Data berhasil diperbarui!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Query gagal: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Tidak ada kolom yang diperbarui!"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Data tidak valid atau ID tidak ditemukan!"]);
}

$conn->close();
?>
