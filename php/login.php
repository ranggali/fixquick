<?php
session_start();
require_once 'connection_db.php';

try {
    // Validasi input umum
    if (empty($_POST['role'])) {
        echo "<script>
            alert('Peran wajib dipilih.');
            window.location.href = '../login.php';
        </script>";
        exit;
    }

    $role = htmlspecialchars($_POST['role']);
    $redirects = [
        'resident' => '../fquser/homepagewarga.php',
        'admin' => '../fqperumahan/homepageperumahan.php',
        'provider' => '../fqpelayanan/homepagejasa.php'
    ];

    if (!isset($redirects[$role])) {
        echo "<script>
            alert('Peran tidak valid.');
            window.location.href = '../login.php';
        </script>";
        exit;
    }

    // Validasi input spesifik berdasarkan role
    if ($role === 'resident') {
        if (empty($_POST['email_warga']) || empty($_POST['sandi_warga'])) {
            echo "<script>
                alert('Nama dan No Telepon wajib diisi untuk Warga.');
                window.location.href = '../login.php';
            </script>";
            exit;
        }
        $email_warga = htmlspecialchars($_POST['email_warga']);
        $sandi_warga = $_POST['sandi_warga'];
    } elseif (empty($_POST['email']) || empty($_POST['password'])) {
        echo "<script>
            alert('Email dan kata sandi wajib diisi.');
            window.location.href = '../login.php';
        </script>";
        exit;
    }

    // Variabel umum
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? ''; // Jangan hash ulang kata sandi di sisi login

    // Query berdasarkan role
    $query = null;
    if ($role === 'resident') {
        $query = $conn->prepare("SELECT * FROM warga WHERE email=? AND is_aktif='aktif'");
        $query->bind_param("s", $email_warga); // Menggunakan nama untuk mencari warga
    } elseif ($role === 'admin') {
        $query = $conn->prepare("SELECT * FROM perumahan WHERE email=? AND is_aktif='aktif'");
        $query->bind_param("s", $email);
    } elseif ($role === 'provider') {
        $query = $conn->prepare("SELECT * FROM pelayanan_jasa WHERE email=? AND is_aktif='aktif'");
        $query->bind_param("s", $email);
    }

    $query->execute();
    $result = $query->get_result();

    // Proses hasil query
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    
        if ($role === 'resident') {
            if (password_verify($sandi_warga, $user['kata_sandi'])) {
                // Login berhasil untuk warga
                $_SESSION['role'] = $role;
                $_SESSION['nama_warga'] = $user['nama_warga'];
                $_SESSION['id_warga'] = $user['id_warga'];
    
                $updateQuery = $conn->prepare("UPDATE warga SET status_masuk='aktif' WHERE id_warga=?");
                $updateQuery->bind_param("i", $_SESSION['id_warga']);
                $updateQuery->execute();
                $updateQuery->close();
    
                echo "
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <style>
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
                                window.location.href = '{$redirects[$role]}'; 
                            });
                        });
                    </script>";
                exit;
            } else {
                error_log("Kata sandi salah untuk warga dengan email: $email_warga.");
                echo "<script>
                    alert('Kata sandi salah.');
                    window.location.href = '../login.php';
                </script>";
                exit;
            }
        } elseif ($role === 'admin' || $role === 'provider') {
            if (password_verify($password, $user['kata_sandi'])) {
                // Login berhasil untuk admin atau provider
                $_SESSION['role'] = $role;
                if ($role === 'admin') {
                    $_SESSION['id_perumahan'] = $user['id_perumahan'];
                    $_SESSION['nama_pengguna'] = $user['nama_pengguna'];
                } else {
                    $_SESSION['id_pelayanan_jasa'] = $user['id_pelayanan_jasa'];
                    $_SESSION['nama_penyedia_jasa'] = $user['nama_penyedia_jasa'];
                }
    
                echo "
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <style>
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
                                window.location.href = '{$redirects[$role]}'; 
                            });
                        });
                    </script>";
                exit;
            } else {
                error_log("Kata sandi salah untuk $role dengan email: $email.");
                echo "<script>
                    alert('Kata sandi salah.');
                    window.location.href = '../login.php';
                </script>";
                exit;
            }
        }
    } else {
        // Data tidak ditemukan atau tidak aktif
        $inputEmail = ($role === 'resident') ? $email_warga : $email;
        error_log("Tidak ada akun aktif dengan email: $inputEmail");
        echo "<script>
            alert('Data tidak ditemukan atau akun tidak aktif.');
            window.location.href = '../login.php';
        </script>";
        exit;
    }
    
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo "<script>
        alert('Terjadi kesalahan pada sistem.');
        window.location.href = '../login.php';
    </script>";
    exit;
} finally {
    if (isset($query)) {
        $query->close();
    }
    $conn->close();
}
