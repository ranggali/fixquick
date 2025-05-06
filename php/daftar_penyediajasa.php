<?php
require_once 'connection_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $nama_penyedia_jasa = $_POST['nama_penyedia'] ?? '';
    $email = $_POST['email'] ?? '';
    $kata_sandi = $_POST['password'] ?? '';
    $no_telepon = $_POST['no_hp'] ?? '';
    $alamat = $_POST['alamat'] ?? '';

    // Validasi sederhana
    if (empty($nama_penyedia_jasa) || empty($email) || empty($kata_sandi) || empty($no_telepon) || empty($alamat)) {
        echo "Semua kolom harus diisi!";
        exit;
    }

    // Hash kata sandi untuk keamanan
    $kata_sandi_hashed = password_hash($kata_sandi, PASSWORD_BCRYPT);

    // Query untuk menyimpan data ke database
    $sql = "INSERT INTO pelayanan_jasa (nama_penyedia_jasa, email, kata_sandi, no_telepon, alamat, is_aktif, created_at) 
            VALUES (?, ?, ?, ?, ?, 'aktif', NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nama_penyedia_jasa, $email, $kata_sandi_hashed, $no_telepon, $alamat);

    if ($stmt->execute()) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <style>
        /* Menambahkan font DM Sans */
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap');
        .swal2-popup {
            font-family: 'DM Sans', sans-serif !important;
        }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Pendaftaran Berhasil!',
                    text: 'Akun Anda telah terdaftar.',
                    timer: 1000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '../login.php'; // Arahkan ke halaman dashboard
                });
            });
        </script>";
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <style>
        /* Menambahkan font DM Sans */
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap');
        .swal2-popup {
            font-family: 'DM Sans', sans-serif !important;
        }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mendaftar!',
                    text: 'Tidak bisa mendaftar.',
                }).then(() => {
                    window.history.back();
                });
            });
        </script>";
    }

    // Menutup statement dan koneksi
    $stmt->close();
}

$conn->close();
