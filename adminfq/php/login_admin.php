<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'fixquick_db');
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$email = trim($_POST['email'] ?? '');
$kata_sandi = $_POST['kata_sandi'] ?? '';

if (empty($email) || empty($kata_sandi)) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Email dan Kata Sandi harus diisi!',
            }).then(() => {
                window.history.back();
            });
        });
    </script>";
    exit;
}

$sql = "SELECT * FROM admin_website WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();

    if (password_verify($kata_sandi, $admin['kata_sandi'])) {
        $_SESSION['id_admin'] = $admin['id_admin']; // Menyimpan ID admin dalam session
        $_SESSION['nama_admin'] = $admin['nama_admin']; // Menyimpan nama admin dalam session

        // Update status menjadi 'aktif' setelah login berhasil
        $id_admin = $admin['id_admin'];
        $update_status_sql = "UPDATE admin_website SET status = 'aktif' WHERE id_admin = ?";
        $update_status_stmt = $conn->prepare($update_status_sql);
        $update_status_stmt->bind_param('i', $id_admin);
        $update_status_stmt->execute();

        // Menyimpan log aktivitas
        $aksi = 'Login Berhasil';
        $tabel_terkait = null;
        $id_data_terkait = null;
        $waktu_aksi = date('Y-m-d H:i:s');
        $log_sql = "INSERT INTO log_aktivitas_admin (id_admin, aksi, tabel_terkait, id_data_terkait, waktu_aksi, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param('ississ', $id_admin, $aksi, $tabel_terkait, $id_data_terkait, $waktu_aksi, $waktu_aksi);
        $log_stmt->execute();

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
                    title: 'Login Berhasil!',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '../dashboard_admin.php'; // Arahkan ke halaman dashboard
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
                    title: 'Login Gagal!',
                    text: 'Kata sandi yang Anda masukkan salah.',
                }).then(() => {
                    window.history.back();
                });
            });
        </script>";
    }
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
                title: 'Login Gagal!',
                text: 'Email tidak terdaftar.',
            }).then(() => {
                window.history.back();
            });
        });
    </script>";
}

$conn->close();