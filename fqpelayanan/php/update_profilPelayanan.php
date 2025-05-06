<?php
session_start();
require_once 'connection_db.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_pelayanan_jasa'])) {
    header('Location: ../../FixQuickWebsite/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_pelayanan_jasa = $_SESSION['id_pelayanan_jasa'];
        $nama_penyedia_jasa = trim($_POST['nama_penyedia_jasa'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');
        $telepon = trim($_POST['telepon'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $kata_sandi = trim($_POST['kata_sandi'] ?? '');
        $no_izin_usaha = trim($_POST['no_izin_usaha'] ?? '');
        $foto_profil = null;

        // Validasi input
        if (empty($nama_penyedia_jasa) || empty($alamat) || empty($telepon) || empty($email) || empty($no_izin_usaha)) {
            throw new Exception('Semua data wajib diisi kecuali kata sandi.');
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
            $foto_profil = $target_dir . $new_file_name;

            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $foto_profil)) {
                throw new Exception('Gagal mengunggah foto.');
            }
        }

        // Update data di database
        $query = "UPDATE pelayanan_jasa SET nama_penyedia_jasa = ?, alamat = ?, no_telepon = ?, email = ?, no_izin_usaha = ?";
        $params = [$nama_penyedia_jasa, $alamat, $telepon, $email, $no_izin_usaha];
        $types = "sssss";

        if ($foto_profil) {
            $query .= ", foto_profil = ?";
            $params[] = $foto_profil;
            $types .= "s";
        }

        if ($hashed_password) {
            $query .= ", kata_sandi = ?";
            $params[] = $hashed_password;
            $types .= "s";
        }

        $query .= " WHERE id_pelayanan_jasa = ?";
        $params[] = $id_pelayanan_jasa;
        $types .= "i";

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        // Redirect setelah berhasil
        header('Location: ../profiljasa.php');
        exit;
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo "<script>
            alert('Terjadi kesalahan: " . htmlspecialchars($e->getMessage()) . "');
            window.location.href = '../profiljasa.php';
        </script>";
    } finally {
        if (isset($stmt)) $stmt->close();
        if (isset($conn)) $conn->close();
    }
}
?>
