<?php
session_start();
require_once 'connection_db.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_perumahan'])) {
    header('Location: ../../FixQuickWebsite/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_perumahan = $_SESSION['id_perumahan'];
        $nama_pengguna = trim($_POST['nama_pengguna'] ?? '');
        $nama_perumahan = trim($_POST['nama_perumahan'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');
        $no_telepon = trim($_POST['no_telepon'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $kata_sandi = trim($_POST['kata_sandi'] ?? '');
        $foto_profil = null;

        // Validasi input
        if (empty($nama_pengguna) || empty($nama_perumahan) || empty($alamat) || empty($no_telepon) || empty($email)) {
            throw new Exception('Semua data kecuali kata sandi wajib diisi!');
        }

        // Hash kata sandi jika diisi
        $hashed_password = null;
        if (!empty($kata_sandi)) {
            if (strlen($kata_sandi) < 8) {
                throw new Exception('Kata sandi harus memiliki panjang minimal 8 karakter.');
            }
            $hashed_password = password_hash($kata_sandi, PASSWORD_DEFAULT);
        }

        // Proses file upload
        if (!empty($_FILES['foto']['name'])) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = mime_content_type($_FILES['foto']['tmp_name']);
            if (!in_array($file_type, $allowed_types)) {
                throw new Exception('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
            }

            $target_dir = "uploads/";
            $file_ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $new_file_name = uniqid('profile_', true) . '.' . $file_ext;
            $foto_perumahan = $target_dir . $new_file_name;

            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $foto_perumahan)) {
                throw new Exception('Gagal mengunggah foto.');
            }
        }

        // Update data warga
        $query = "UPDATE perumahan SET nama_pengguna = ?, nama_perumahan = ?, alamat = ?, no_telepon = ?, email = ?";
        $params = [$nama_pengguna, $nama_perumahan, $alamat, $no_telepon, $email ];
        $types = "sssss";

        if ($foto_perumahan) {
            $query .= ", foto_perumahan = ?";
            $params[] = $foto_perumahan;
            $types .= "s";
        }

        if ($hashed_password) {
            $query .= ", kata_sandi = ?";
            $params[] = $hashed_password;
            $types .= "s";
        }

        $query .= " WHERE id_perumahan = ?";
        $params[] = $id_perumahan;
        $types .= "i";

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        // Redirect setelah berhasil
        header('Location: ../profilperumahan.php');
        exit;
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo "<script>
            alert('Terjadi kesalahan: " . htmlspecialchars($e->getMessage()) . "');
            window.location.href = '../profilperumahan.php';
        </script>";
    } finally {
        if (isset($stmt)) $stmt->close();
        if (isset($conn)) $conn->close();
    }
}
?>
