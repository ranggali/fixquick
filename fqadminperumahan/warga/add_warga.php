<?php
include('../php/connection_db.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
    alert('Anda harus login sebagai admin.');
    window.location.href = '../FixQuickWebsite/login.php';
</script>";
    exit;
}
$id_perumahan = $_SESSION['nama_pengguna'];
$id_perumahan = $_SESSION['id_perumahan'];

// Periksa apakah form sudah di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_warga = $_POST['nama_warga'];
    $email_warga = $_POST['email_warga'];
    $kata_sandi = $_POST['kata_sandi'];

    // Validasi input
    if (empty($nama_warga || $email_warga) || empty($kata_sandi)) {
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
            icon: 'warning',
            title: 'Input Tidak Lengkap',
            text: 'Harap isi semua field.',
            confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back();
                });
            });
        </script>";
        exit;
    }

    // Hash kata sandi
    $kata_sandi_hashed = password_hash($kata_sandi, PASSWORD_BCRYPT);

    // Query untuk menyisipkan data ke tabel warga
    $query = "INSERT INTO warga (nama_warga, email, kata_sandi, id_perumahan, is_aktif) VALUES (?, ?, ?, ?, ?)";

    // Siapkan dan eksekusi statement
    if ($stmt = $conn->prepare($query)) {
        $is_aktif = "aktif";
        $stmt->bind_param("sssis", $nama_warga, $email_warga, $kata_sandi_hashed, $id_perumahan, $is_aktif);

        if ($stmt->execute()) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <style>
                /* Menambahkan font DM Sans */
                @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap');
                .swal2-popup {
                    font-family: 'DM Sans', sans-serif !important;
                    width: 300px !important; /* Menyesuaikan lebar popup */
                    font-size: 14px !important; /* Menyesuaikan ukuran font */
                }
                .swal2-title {
                    font-size: 16px !important; /* Ukuran font judul */
                }
                .swal2-content {
                    font-size: 14px !important; /* Ukuran font konten */
                }
                .swal2-confirm {
                    padding: 5px 20px !important; /* Ukuran tombol */
                    font-size: 14px !important; /* Ukuran font tombol */
                }
            </style>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Warga berhasil ditambahkan.',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'custom-swal-size' // Kelas kustom yang sudah didefinisikan di CSS
                        }
                    }).then(() => {
                        window.history.back(); // Ganti dengan halaman tujuan
                    });
                });
            </script>";
        } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
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
                title: 'Gagal',
                text: 'Terjadi kesalahan saat menambahkan warga.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.history.back();
            });
            });
        </script>";
        }

        $stmt->close();
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
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
            title: 'Server Error',
            text: 'Terjadi kesalahan pada server.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.history.back();
        });
            });
        </script>";
    }
}
