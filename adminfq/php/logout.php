<?php
session_start();

if (isset($_SESSION['id_admin'])) {
    // Ambil id_admin dari session
    $id_admin = $_SESSION['id_admin'];

    // Koneksi ke database
    $conn = new mysqli('localhost', 'root', '', 'fixquick_db');
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Update status menjadi 'tidak aktif' saat logout
    $update_status_sql = "UPDATE admin_website SET status = 'tidak aktif' WHERE id_admin = ?";
    $update_status_stmt = $conn->prepare($update_status_sql);
    $update_status_stmt->bind_param('i', $id_admin);
    $update_status_stmt->execute();

    // Menyimpan log aktivitas Logout
    $aksi = 'Logout Berhasil';
    $tabel_terkait = null; // Tidak ada tabel terkait, jadi set null
    $id_data_terkait = null; // Tidak ada ID data terkait, jadi set null
    $waktu_aksi = date('Y-m-d H:i:s');
    $created_at = $waktu_aksi; // Waktu pembuatan log
    $log_sql = "INSERT INTO log_aktivitas_admin (id_admin, aksi, tabel_terkait, id_data_terkait, waktu_aksi, created_at) 
                VALUES (?, ?, ?, ?, ?, ?)";
    $log_stmt = $conn->prepare($log_sql);
    $log_stmt->bind_param('ississ', $id_admin, $aksi, $tabel_terkait, $id_data_terkait, $waktu_aksi, $created_at);
    $log_stmt->execute();

    // Hapus session admin
    session_unset();
    session_destroy();

    // Alihkan ke halaman login setelah logout
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
                title: 'Anda Telah Logout!',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '../login.php'; // Arahkan kembali ke halaman login
            });
        });
    </script>";
} else {
    // Jika tidak ada session, arahkan ke halaman login
    header("Location: login.php");
    exit();
}
?>