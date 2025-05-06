<?php
session_start();
include('php/connection_db.php'); // Sambungkan ke database

// Cek autentikasi
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'provider') {
  echo "<script>
        alert('Anda harus login sebagai admin.');
        window.location.href = '../FixQuickWebsite/login.php';
    </script>";
  exit;
}

$id_pelayanan_jasa = $_SESSION['id_pelayanan_jasa'];

// Ambil data penyedia jasa
$query_provider = "SELECT nama_penyedia_jasa, email, no_telepon, no_izin_usaha, alamat 
                   FROM pelayanan_jasa 
                   WHERE id_pelayanan_jasa = ?";
$stmt = $conn->prepare($query_provider);
$stmt->bind_param("i", $id_pelayanan_jasa);
$stmt->execute();
$result_provider = $stmt->get_result();
$data_provider = $result_provider->fetch_assoc();

// Ambil data perumahan
$query_perumahan = "SELECT id_perumahan, nama_perumahan FROM perumahan";
$result_perumahan = $conn->query($query_perumahan);

// Handle submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_perumahan = $_POST['id_perumahan'];
  $kategori_jasa = $_POST['kategori_jasa'];
  $deskripsi = $_POST['deskripsi'];
  $harga = $_POST['harga'];

  $query_insert = "INSERT INTO pengajuan_pelayanan 
                     (id_pelayanan_jasa, id_perumahan, kategori_jasa, deskripsi_jasa, harga, status_pengajuan, created_at) 
                     VALUES (?, ?, ?, ?, ?, 'Menunggu', NOW())";
  $stmt_insert = $conn->prepare($query_insert);
  $stmt_insert->bind_param("iisss", $id_pelayanan_jasa, $id_perumahan, $kategori_jasa, $deskripsi, $harga);
  $stmt_insert->execute();

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
                    title: 'Berhasil Mengajukan Layanan!',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'homepagejasa.php';
                });
            });
        </script>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/img/logo1.png">
  <title>Ajuan Pelayanan FixQuick</title>
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

  <a href="homepagejasa.php" class="back-button" aria-label="Kembali ke halaman utama">
    <i class='bx bx-arrow-back'></i> Kembali
  </a>

  <nav class="navbar">
    <div class="navbar-logo">
      <img src="assets/img/logo1.png" alt="FixQuick logo" class="logo">
      <span class="logo-text">FixQuick</span>
    </div>
    <!-- <div class="navbar-menu">
      <a href="#" class="menu-link">Hubungi kami</a>
      <button class="logout-button desktop-only" onclick="redirectToWebsite()">Keluar</button>
      <i class="fas fa-user-circle menu-icon desktop-only" onclick="redirectToProfilJasa()"></i>
      <i class="fas fa-cog menu-icon desktop-only"></i>
    </div> -->
    <!-- <button class="hamburger-button" id="hamburger">
      <i class="fas fa-bars"></i>
    </button> -->
  </nav>

  <!-- Mobile Menu -->
  <!-- <div class="mobile-menu hidden" id="mobileMenu">
    <a href="#" class="menu-link mobile-only">Hubungi kami</a>
    <button class="profile-button mobile-only" onclick="redirectToProfilJasa()">Profil</button>
    <button class="setting-button mobile-only">Pengaturan</button>
    <button class="logout-button mobile-only" onclick="redirectToWebsite()">Keluar</button>
  </div> -->

  <main class="cd__main">
    <div class="container">
      <div class="title">
        <h2>Form Pengajuan Layanan</h2>
      </div>
      <div class="d-flex">
        <form action="" method="POST">
          <label>
            <span>Nama Penyedia <span class="required">*</span></span>
            <input type="text" name="nama_penyedia" value="<?= $data_provider['nama_penyedia_jasa'] ?? '' ?>" placeholder="Masukkan Nama Anda">
          </label>
          <label>
            <span>Email <span class="required">*</span></span>
            <input type="text" name="email" value="<?= $data_provider['email'] ?? '' ?>" placeholder="Masukkan Email Anda">
          </label>
          <label>
            <span>Nomor Telepon <span class="required">*</span></span>
            <input type="text" name="no_telepon" value="<?= $data_provider['no_telepon'] ?? '' ?>" placeholder="Masukkan Nomor Telepon Anda">
          </label>
          <label>
            <span>Nomor Izin Usaha (Optional)</span>
            <input type="text" name="no_izin_usaha" value="<?= $data_provider['no_izin_usaha'] ?? '' ?>" placeholder="Nomor Izin Usaha (Jika Ada)">
          </label>
          <label>
            <span>Alamat <span class="required">*</span></span>
            <input type="text" name="alamat" value="<?= $data_provider['alamat'] ?? '' ?>" placeholder="Masukkan Alamat Jasa Anda">
          </label>
          <label>
            <span>Perumahan <span class="required">*</span></span>
            <select name="id_perumahan" required>
              <option value="">Pilih Perumahan...</option>
              <?php while ($row = $result_perumahan->fetch_assoc()): ?>
                <option value="<?= $row['id_perumahan'] ?>"><?= $row['nama_perumahan'] ?></option>
              <?php endwhile; ?>
            </select>
          </label>
          <label>
            <span>Kategori Jasa <span class="required">*</span></span>
            <input type="text" name="kategori_jasa" placeholder="misalnya: Kebersihan, Perbaikan, Keamanan" required>
          </label>
          <label>
            <span>Deskripsi <span class="required">*</span></span>
            <input type="text" name="deskripsi" placeholder="Deskripsi singkat layanan jasa Anda" required>
            <!-- <textarea name="deskripsi" placeholder="Deskripsi singkat layanan jasa Anda" required></textarea> -->
          </label>
          <label>
            <span>Harga <span class="required">*</span></span>
            <input type="text" name="harga" placeholder="Masukkan Harga layanan Anda" required>
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