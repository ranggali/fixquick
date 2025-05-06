<?php
// Konfigurasi koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$database = "fixquick_db"; // Ganti dengan nama database Anda

$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tangkap action dari URL
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'add') {
    // Pastikan request menggunakan POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_perumahan = isset($_POST['id_perumahan']) ? $_POST['id_perumahan'] : null; // Tambahkan sesuai kebutuhan
        $nama_warga = isset($_POST['namaWarga']) ? $_POST['namaWarga'] : '';
        $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
        $no_telepon = isset($_POST['noTelepon']) ? $_POST['noTelepon'] : '';
        $foto_profil = ''; // Default foto profil jika tidak disediakan
        $kata_sandi = password_hash('123456', PASSWORD_DEFAULT); // Default password
        $is_aktif = isset($_POST['statusWarga']) && $_POST['statusWarga'] === 'Aktif' ? 'aktif' : 'tidak aktif';
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        // Query untuk insert data
        $sql = "INSERT INTO warga (id_perumahan, nama_warga, alamat, no_telepon, foto_profil, kata_sandi, is_aktif, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "issssssss",
            $id_perumahan,
            $nama_warga,
            $alamat,
            $no_telepon,
            $foto_profil,
            $kata_sandi,
            $is_aktif,
            $created_at,
            $updated_at
        );

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Data warga berhasil ditambahkan.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan data warga.']);
        }

        $stmt->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Aksi tidak valid.']);
}

$conn->close();
?>