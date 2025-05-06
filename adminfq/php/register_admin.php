<?php
// Konfigurasi koneksi database
$host = 'localhost';
$dbname = 'fixquick_db'; // Nama database
$username = 'root';   // Sesuaikan dengan username database Anda
$password = '';       // Sesuaikan dengan password database Anda

try {
    // Membuat koneksi ke database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
}

// Proses form pendaftaran admin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $namaAdmin = trim($_POST['nama_admin']);
    $email = trim($_POST['email']);
    $kataSandi = trim($_POST['kata_sandi']);

    // Validasi input
    if (empty($namaAdmin) || empty($email) || empty($kataSandi)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua kolom wajib diisi!']);
        exit;
    }

    // Hash kata sandi untuk keamanan
    $hashedPassword = password_hash($kataSandi, PASSWORD_DEFAULT);

    try {
        // Menyimpan data admin ke database
        $stmt = $pdo->prepare("INSERT INTO admin_website (nama_admin, email, kata_sandi, created_at, updated_at) 
                                VALUES (:nama_admin, :email, :kata_sandi, NOW(), NOW())");
        $stmt->execute([
            ':nama_admin' => $namaAdmin,
            ':email' => $email,
            ':kata_sandi' => $hashedPassword
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Admin berhasil didaftarkan']);
    } catch (PDOException $e) {
        // Tangani kesalahan (contoh: email duplikat)
        if ($e->getCode() == 23000) { // Kode 23000 untuk duplikat entri
            echo json_encode(['status' => 'error', 'message' => 'Email sudah terdaftar']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan pada server']);
        }
    }
}
?>