<?php
session_start();
require_once 'php/connection_db.php'; // Pastikan path benar

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'resident' || !isset($_SESSION['id_warga'])) {
    header('Location: ../FixQuickWebsite/login.php');
    exit;
}

$namaPengguna = $_SESSION['nama_warga'];
$id_warga = $_SESSION['id_warga'];

// Ambil data warga dan nama perumahan yang sedang login
$sql = "SELECT w.nama_warga, w.no_telepon, w.alamat, w.id_perumahan, p.nama_perumahan 
        FROM warga w 
        JOIN perumahan p ON w.id_perumahan = p.id_perumahan 
        WHERE w.id_warga = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_warga);
$stmt->execute();
$result = $stmt->get_result();
$warga = $result->fetch_assoc();

if (!$warga) {
    die("Data warga tidak ditemukan.");
}

// Proses form pengajuan layanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori = isset($_POST['kategori']) ? trim($_POST['kategori']) : '';
    $deskripsi = isset($_POST['deskripsi_permintaan']) ? trim($_POST['deskripsi_permintaan']) : '';

    // Validasi input tidak boleh kosong
    if (empty($kategori) || empty($deskripsi)) {
        echo "
            <style>
                /* Menambahkan font DM Sans */
                @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap');
                .swal2-popup {
                    font-family: 'DM Sans', sans-serif !important;
                }
            </style>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Kategori dan Deskripsi tidak boleh kosong!',
                        icon: 'info',
                        timer: 1000,
                        showConfirmButton: false,
                        width: '400px',
                        customClass: {
                            popup: 'responsive-swal'
                        }
                    }).then(() => {
                        window.history.back();
                    });
                });
            </script>
        ";
        exit;     // Hentikan eksekusi script setelah menampilkan alert
    }

    $sql = "INSERT INTO permintaan_layanan (id_warga, id_perumahan, nama_warga, no_telepon, alamat, kategori, deskripsi_permintaan) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssss", $id_warga, $warga['id_perumahan'], $warga['nama_warga'], $warga['no_telepon'], $warga['alamat'], $kategori, $deskripsi);

    if ($stmt->execute()) {
        // Output JavaScript untuk menampilkan SweetAlert dan redirect setelah 1 detik
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
                    title: 'Berhasil Mengajukan Permintaan Layanan!',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.back(); // Redirect ke halaman sebelumnya
                });
            });
        </script>";
    } else {
        die("Error: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/logo1.png">
    <title>Permintaan Layanan Tambahan FixQuick</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/ajukanlayanan.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    <!-- SweetAlert2 dan Animate.css (jika belum ditambahkan) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <!-- jQuery and jQuery UI CSS and JS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- Timepicker Addon CSS and JS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>

    <!-- Localization for Indonesian -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/i18n/datepicker-id.js"></script>


</head>

<body class="font-DM Sans">

    <div class="loader" id="loader">
        <img src="assets/load/loader.gif" alt="Loading...">
    </div>

    <a href="homepagewarga.php" class="back-button" aria-label="Kembali ke halaman utama">
        <i class='bx bx-arrow-back'></i> Kembali
    </a>

    <nav class="navbar">
        <div class="navbar-logo">
            <img src="assets/img/logo1.png" alt="FixQuick logo" class="logo">
            <span class="logo-text">FixQuick</span>
        </div>
    </nav>

    <main class="cd__main">
        <div class="container">
            <div class="title">
                <h2>Form Permintaan Layanan</h2>
            </div>
            <div class="d-flex">
                <form action="permintaanlayanan.php" method="post">
                    <label>
                        <span>Nama Warga <span class="required">*</span></span>
                        <input type="text" name="nama_warga" value="<?= htmlspecialchars($warga['nama_warga']) ?>" placeholder="Nama Anda">
                    </label>
                    <label>
                        <span>Nomor Telepon <span class="required">*</span></span>
                        <input type="text" name="no_telepon" value="<?= htmlspecialchars($warga['no_telepon']) ?>" placeholder="Nomor Telepon Anda">
                    </label>
                    <label>
                        <span>Alamat <span class="required">*</span></span>
                        <input type="text" name="alamat" value="<?= htmlspecialchars($warga['alamat']) ?>" placeholder="Alamat Anda">
                    </label>
                    <label>
                        <span>Perumahan <span class="required">*</span></span>
                        <input type="text" name="perumahan" value="<?= htmlspecialchars($warga['nama_perumahan']) ?>" placeholder="Perumahan Anda" readonly>
                    </label>
                    <label>
                        <span>Kategori Layanan Jasa <span class="required">*</span></span>
                        <input type="text" name="kategori" placeholder="misalnya: Kebersihan, Perbaikan, Keamanan" required>
                    </label>
                    <label>
                        <span>Deskripsi <span class="required">*</span></span>
                        <input type="text" name="deskripsi_permintaan" placeholder="Deskripsi singkat permintaan layanan" required>
                        <!-- <textarea name="deskripsi" placeholder="Deskripsi singkat layanan jasa Anda" required></textarea> -->
                    </label>
                    <div style="display: flex; justify-content: center; margin-top: 10px;">
                        <button type="submit" style="background-color: #009688; color: white; border-radius: 10px; padding: 10px 20px; cursor: pointer;">Ajukan Pelayanan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>


    <footer class="py-8" style="border-top: 2px solid #bdbebf;">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-between items-center">
                <!-- Social media icons: will stack on small screens -->
                <div class="flex space-x-4 mb-4 md:mb-0 w-full md:w-auto">
                    <a href="#" class="text-gray-800"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-800"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-800"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-800"><i class="fab fa-linkedin-in"></i></a>
                </div>

                <!-- Company, Services, and Support sections: will stack on small screens -->
                <div class="flex space-x-16 w-full md:w-auto">
                    <div class="mb-4 md:mb-0 w-full md:w-auto">
                        <h5 class="font-bold text-gray-800 mb-2">COMPANY</h5>
                        <ul class="space-y-1">
                            <li><a href="#" class="text-gray-800">About Us</a></li>
                            <li><a href="#" class="text-gray-800">Career</a></li>
                            <li><a href="#" class="text-gray-800">Press</a></li>
                            <li><a href="#" class="text-gray-800">Blog</a></li>
                        </ul>
                    </div>
                    <div class="mb-4 md:mb-0 w-full md:w-auto">
                        <h5 class="font-bold text-gray-800 mb-2">SERVICES</h5>
                        <ul class="space-y-1">
                            <li><a href="#" class="text-gray-800">Residential</a></li>
                            <li><a href="#" class="text-gray-800">Office Cleaning</a></li>
                            <li><a href="#" class="text-gray-800">Commercial Cleaning</a></li>
                        </ul>
                    </div>
                    <!-- Support section will move to the bottom on small screens -->
                    <div class="w-full md:w-auto">
                        <h5 class="font-bold text-gray-800 mb-2">SUPPORT</h5>
                        <ul class="space-y-1">
                            <li><a href="#" class="text-gray-800">Contact Us</a></li>
                            <li><a href="#" class="text-gray-800">FAQ's</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mt-8 text-left text-gray-500 text-sm">
                © FixQuick 2024-<span id="year"></span>. All rights reserved. · <a href="#" class="text-gray-500">Terms of Service</a> · <a href="#" class="text-gray-500">Privacy Policy</a>
            </div>
        </div>
    </footer>

    <script>
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');

        // Toggle mobile menu visibility
        hamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
    <script>
        function redirectToProfilJasa() {
            window.location.href = 'profiljasa.php';
        }
    </script>
    <script>
        function redirectToWebsite() {
            window.location.href = '../FixQuickWebsite/index.php';
        }
    </script>
    <!-- script tahunan -->
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
    <script src="js/spinner.js"></script>
    <!-- <script src="js/pesananwarga.js"></script> -->
</body>

</html>