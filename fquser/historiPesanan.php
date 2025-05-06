<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'resident' || !isset($_SESSION['id_warga'])) {
    header('Location: ../FixQuickWebsite/login.php'); // Redirect ke halaman login jika belum login
    exit;
}
$namaPengguna = $_SESSION['nama_warga'];

// Cek apakah SweetAlert sudah ditampilkan
$showAlert = false;
if (!isset($_SESSION['alert_displayed'])) {
    $_SESSION['alert_displayed'] = true; // Tandai SweetAlert sudah ditampilkan
    $showAlert = true;
}
include('php/connection_db.php');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/logo1.png">
    <title>FixQuick</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/profile.css">
    <!-- <link rel="stylesheet" href="css/tambahan.css"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
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
        <div class="navbar-menu">
            <a href="mailto:fixquick617@gmail.com?subject=Masukan untuk FixQuick&body=Halo, admin FixQuick!%0A%0ASaya ingin mengirimkan masukan..." class="menu-link">Hubungi kami</a>
            <button class="logout-button desktop-only" onclick="confirmLogout()">Keluar</button>
            <a href="profilwarga.php" class="profile-link">
                <i class="fas fa-user-circle menu-icon desktop-only" id="profil_warga"></i>
                <span class="tooltip">Profile</span>
            </a>
            <!-- <i class="fas fa-user-circle menu-icon desktop-only"></i> -->
            <!-- <i class="fas fa-cog menu-icon desktop-only"></i> -->
        </div>
        <button class="hamburger-button" id="hamburger">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu hidden" id="mobileMenu">
        <a href="mailto:fixquick617@gmail.com?subject=Masukan untuk FixQuick&body=Halo, admin FixQuick!%0A%0ASaya ingin mengirimkan masukan..." class="menu-link mobile-only">Hubungi kami</a>
        <a href="profilwarga.php" class="profile-button mobile-only">Profil</a>
        <button class="setting-button mobile-only" onclick="window.location.href = 'historiPesanan.php';">Histori Pesanan</button>
        <button class="logout-button mobile-only" onclick="confirmLogout()">Keluar</button>
    </div>

    <main class="main-content">
        <!-- Tabel daftar pesanan -->
        <div class="services-intro" id="pesanan-layanan-jasa">
            <h1 class="services-title">Histori Pesanan</h1>
            <p class="services-description">Berikut ini histori pesanan Anda.</p>
        </div><br>
        <div class="table-container">
            <!-- <div class="search-container">
                <input type="text" id="searchInput" placeholder="Masukkan Nomor Invoice Anda..."
                    onkeyup="searchTable()">
            </div> -->

            <?php
            // Memanggil file untuk mendapatkan data
            include('php/get_datapesananKhusus.php');
            ?>
            <table id="invoiceTable" class="striped-table display">
                <thead class="judultabel">
                    <tr>
                        <th>Nomor Invoice</th>
                        <th>Nama Warga</th>
                        <th>Penyedia Layanan</th>
                        <th>Jenis Layanan</th>
                        <th>Tanggal pesan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $index => $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nomor_invoice']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_warga']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_penyedia_layanan']); ?></td>
                                <td><?php echo htmlspecialchars($row['kategori_jasa']); ?></td>


                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($row['tanggal_pesanan']))); ?></td>
                                <td>
                                    <?php
                                    // Menentukan status dan ikon yang sesuai
                                    $status = htmlspecialchars($row['status_pesanan']);
                                    if ($status === 'Menunggu') {
                                        echo '<span class="status-waiting">Menunggu</span>';
                                    } elseif ($status === 'Dalam Proses') {
                                        echo '<span class="status-in-process">Dalam Proses</span>';
                                    } elseif ($status === 'Selesai') {
                                        echo '<span class="status-done">Selesai</span>';
                                    } elseif ($status === 'Ditolak') {
                                        echo '<span class="status-rejected">Ditolak</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Tidak ada data yang tersedia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div id="pagination" style="margin-top: 10px;"></div>
        </div>
        <!-- End tabel daftar pesanan -->
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
        function confirmLogout() {
            // Menampilkan SweetAlert untuk konfirmasi
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan keluar dari akun Anda!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#009688',
                cancelButtonColor: '#93c7c2',
                confirmButtonText: 'Iya, Keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'php/logout.php';
                }
            });
        }
    </script>

    <!-- SEARCH LAYANAN -->
    <script>
        function searchLayanan() {
            // Ambil nilai input pencarian
            const input = document.querySelector('.search-box input').value.toLowerCase();

            // Ambil semua card layanan
            const cards = document.querySelectorAll('.card.service-card');

            // Loop melalui setiap card
            cards.forEach(card => {
                // Ambil data nama dan kategori dari card
                const nama = card.getAttribute('data-nama').toLowerCase();
                const kategori = card.getAttribute('data-kategori').toLowerCase();

                // Cek apakah input cocok dengan nama atau kategori
                if (nama.includes(input) || kategori.includes(input)) {
                    card.style.display = 'block'; // Tampilkan card jika cocok
                } else {
                    card.style.display = 'none'; // Sembunyikan card jika tidak cocok
                }
            });
        }

        // Tambahkan event listener untuk input pencarian
        document.querySelector('.search-box input').addEventListener('input', searchLayanan);
    </script>
    <!-- script tabel -->
    <script>
        $(document).ready(function() {
            $('#invoiceTable').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ entri per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(difilter dari total _MAX_ entri)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        });
    </script>
    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     const table = document.getElementById('invoiceTable');
        //     const rows = Array.from(table.querySelectorAll('tbody tr'));
        //     const pagination = document.getElementById('pagination');
        //     const rowsPerPageSelect = document.getElementById('rowsPerPage');

        //     let currentPage = 1;
        //     let rowsPerPage = parseInt(rowsPerPageSelect.value);

        //     function displayRows() {
        //         const start = (currentPage - 1) * rowsPerPage;
        //         const end = start + rowsPerPage;

        //         rows.forEach((row, index) => {
        //             row.style.display = index >= start && index < end ? '' : 'none';
        //         });
        //     }

        //     function setupPagination() {
        //         pagination.innerHTML = '';
        //         const pageCount = Math.ceil(rows.length / rowsPerPage);

        //         for (let i = 1; i <= pageCount; i++) {
        //             const btn = document.createElement('button');
        //             btn.innerText = i;
        //             btn.className = (i === currentPage) ? 'active' : '';
        //             btn.addEventListener('click', () => {
        //                 currentPage = i;
        //                 displayRows();
        //                 setupPagination();
        //             });
        //             pagination.appendChild(btn);
        //         }
        //     }

        //     rowsPerPageSelect.addEventListener('change', () => {
        //         rowsPerPage = parseInt(rowsPerPageSelect.value);
        //         currentPage = 1;
        //         displayRows();
        //         setupPagination();
        //     });

        //     // Inisialisasi awal
        //     displayRows();
        //     setupPagination();
        // });
    </script>

    <style>
        #pagination button {
            margin: 2px;
            padding: 4px 8px;
            cursor: pointer;
        }

        #pagination .active {
            font-weight: bold;
            background-color: #ddd;
        }
    </style>
    <!-- script tahunan -->
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="js/spinner.js"></script>
    <script src="js/tabelpesanan.js"></script>
    <script src="js/carilayanan.js"></script>
</body>

</html>