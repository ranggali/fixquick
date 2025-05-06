<?php
session_start();
require_once 'php/connection_db.php';

// Cek apakah pengguna telah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'provider') {
    echo "<script>
        alert('Anda harus login sebagai pelayanan jasa.');
        window.location.href = '../login.php';
    </script>";
    exit;
}

// Ambil data admin dari session
$id_pelayanan_jasa = $_SESSION['id_pelayanan_jasa'];

try {
    // Query untuk mengambil data admin
    $query = $conn->prepare("SELECT * FROM pelayanan_jasa WHERE id_pelayanan_jasa = ?");
    $query->bind_param("i", $id_pelayanan_jasa);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $pelayanan_jasa = $result->fetch_assoc();
        // Tampilkan data admin di halaman profil
        // Validasi foto_profil
        $fotoProfilPath = !empty($pelayanan_jasa['foto_profil'])
            ? 'http://localhost/PBL-TRPL503/fqpelayanan/php/' . $pelayanan_jasa['foto_profil']
            : 'assets/img/404.png'; // Gambar default jika foto_profil tidak valid

    } else {
        echo "<script>
            alert('Data penyedia tidak ditemukan.');
            window.location.href = 'homepagejasa.php';
        </script>";
        exit;
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo "<script>
        alert('Terjadi kesalahan pada sistem.');
        window.location.href = 'homepagejasa.php';
    </script>";
    exit;
} finally {
    if (isset($query)) {
        $query->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/logo1.png">
    <title>Profil FixQuick</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/profile.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- SweetAlert -->
    <!-- SweetAlert2 dan Animate.css (jika belum ditambahkan) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

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
        <div class="navbar-menu">
            <a href="mailto:fixquick617@gmail.com?subject=Masukan untuk FixQuick&body=Halo, admin FixQuick!%0A%0ASaya ingin mengirimkan masukan..." class="menu-link">Hubungi kami</a>
            <!-- <button class="logout-button desktop-only" onclick="window.location.href='homepagejasa.php'">Keluar</button> -->
            <i class="fas fa-user-circle menu-icon desktop-only" id="profil_jasa"></i>
            <!-- <i class="fas fa-cog menu-icon desktop-only"></i> -->
        </div>
        <button class="hamburger-button" id="hamburger">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu hidden" id="mobileMenu">
        <a href="mailto:fixquick617@gmail.com?subject=Masukan untuk FixQuick&body=Halo, admin FixQuick!%0A%0ASaya ingin mengirimkan masukan..." class="menu-link mobile-only">Hubungi kami</a>
        <button class="profile-button mobile-only" id="profil_mobile_jasa">Profil</button>
        <!-- <button class="setting-button mobile-only">Pengaturan</button> -->
        <button class="logout-button mobile-only" onclick="window.location.href='homepagejasa.php'">Kembali</button>
    </div>

    <main class="main-content">
        <div class="center-wrapper">
            <div class="edit-container">
                <h1>Edit Data Anda!</h1>
                <form id="editForm" action="php/update_profilPelayanan.php" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama_penyedia_jasa">Nama Penyedia:</label>
                            <input type="text" id="nama_penyedia_jasa" name="nama_penyedia_jasa" placeholder="Nama pemilik akun" value="<?php echo htmlspecialchars($pelayanan_jasa['nama_penyedia_jasa'] ?? ''); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat Lengkap:</label>
                            <input type="text" id="alamat" name="alamat" placeholder="Masukkan alamat lengkap" value="<?php echo nl2br(htmlspecialchars($pelayanan_jasa['alamat']) ?? ''); ?>" disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="telepon">Nomor Telepon:</label>
                            <input type="text" id="telepon" name="telepon" placeholder="Masukkan nomor telepon" value="<?php echo htmlspecialchars($pelayanan_jasa['no_telepon'] ?? ''); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" placeholder="Masukkan email" value="<?php echo htmlspecialchars($pelayanan_jasa['email'] ?? ''); ?>" disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="foto">Foto Perumahan:</label>
                            <input type="file" id="foto" name="foto" accept="image/*" disabled onchange="previewImage(event)">
                            <div class="preview-container">
                                <img
                                    id="preview"
                                    src="<?php echo $fotoProfilPath; ?>"
                                    alt="Pratinjau Foto"
                                    style="<?php echo empty($pelayanan_jasa['foto_profil']) ? 'display: none;' : ''; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_daftar">Tanggal Masuk:</label>
                            <input type="date" id="tanggal_daftar" name="tanggal_daftar" value="<?php echo htmlspecialchars(date('Y-m-d', strtotime($pelayanan_jasa['created_at']))); ?>" disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="kata_sandi">Kata Sandi:</label>
                            <input type="text" id="kata_sandi" name="kata_sandi" placeholder="********" disabled>
                        </div>
                        <div class="form-group">
                            <label for="no_izin_usaha">Nomor izin usaha:</label>
                            <input type="text" id="no_izin_usaha" name="no_izin_usaha" placeholder="optional" value="<?php echo htmlspecialchars($pelayanan_jasa['no_izin_usaha'] ?? ''); ?>" disabled>
                        </div>
                    </div>

                    <input type="submit" id="editButton" value="Edit Data Diri" onclick="toggleEdit(event)">
                </form>
                <p id="editNotice" class="edit-notice">*Tekan 'Edit Data Diri' untuk mengubah data.</p>
            </div>
        </div>




    </main><br>

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
        function toggleEdit(event) {
            event.preventDefault();
            const form = document.getElementById('editForm');
            const inputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="date"], select, input[type="file"]');
            const editButton = document.getElementById('editButton');

            if (editButton.value === "Edit Data Diri") {
                inputs.forEach(input => input.disabled = false);
                editButton.value = "Simpan Perubahan";
            } else {

                Swal.fire({
                    title: 'Data Berhasil Diubah!',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1000,
                    width: '400px',
                    customClass: {
                        popup: 'responsive-swal'
                    }
                });

                setTimeout(() => {
                    form.submit();
                }, 1000);
            }
        }
    </script>
    <script>
        document.getElementById("profil_jasa").addEventListener("click", function() {
            var profileModal = new bootstrap.Modal(document.getElementById('profileModal'));
            profileModal.show();
        });
    </script>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // Tampilkan gambar
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
    <script>
        const tabs = document.querySelectorAll('.service-tab');
        const cards = document.querySelectorAll('.service-card');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Sembunyikan semua card
                cards.forEach(card => card.style.display = 'none');

                // Tampilkan card sesuai layanan yang diklik
                const serviceId = `card-${this.id}`;
                document.getElementById(serviceId).style.display = 'block';

                // Tambahkan kelas active pada tab yang dipilih
                tabs.forEach(tab => tab.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>

    <script>
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');

        // Toggle mobile menu visibility
        hamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
    <script>
        document.querySelectorAll("#profil_jasa, #profil_mobile_jasa").forEach(function(element) {
            element.addEventListener("click", function() {
                Swal.fire({
                    html: `
                    <div style="display: flex; align-items: center; justify-content: center;">
                        <div style="margin-right: 5px;">
                            <i class="fas fa-exclamation-circle" style="font-size: 24px; color: #f00c0c;"></i>
                        </div>
                        <div style="font-size: 18px; font-weight: bold;">
                            Anda sudah berada di halaman profil!
                        </div>
                    </div>
                `,
                    position: "top",
                    // title: "Anda sudah berada di halaman profil",
                    showConfirmButton: false,
                    timer: 900,
                    showClass: {
                        popup: `
                animate__animated
                animate__fadeInUp
                animate__faster
              `
                    },
                    hideClass: {
                        popup: `
                animate__animated
                animate__fadeOutDown
                animate__faster
              `
                    }
                });
            });
        });
    </script>
    <!-- script tahunan -->
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
    <script src="js/spinner.js"></script>
</body>

</html>