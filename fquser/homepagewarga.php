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

// Ambil id_perumahan dari id_warga yang sedang login
$id_warga = $_SESSION['id_warga'];
$sql_perumahan = "SELECT id_perumahan FROM warga WHERE id_warga = ?";
$stmt_perumahan = mysqli_prepare($conn, $sql_perumahan);
mysqli_stmt_bind_param($stmt_perumahan, "i", $id_warga);
mysqli_stmt_execute($stmt_perumahan);
$result_perumahan = mysqli_stmt_get_result($stmt_perumahan);
$row_perumahan = mysqli_fetch_assoc($result_perumahan);
$id_perumahan = $row_perumahan['id_perumahan'];

// Ambil data layanan berdasarkan id_perumahan
$sql_layanan = "
    SELECT 
        l.nama_penyedia_layanan, 
        l.harga, 
        l.kategori_jasa, 
        pj.foto_profil 
    FROM 
        layanan l 
    JOIN 
        pelayanan_jasa pj 
    ON 
        l.id_pelayanan_jasa = pj.id_pelayanan_jasa 
    WHERE 
        l.id_perumahan = ?
";
$stmt_layanan = mysqli_prepare($conn, $sql_layanan);
mysqli_stmt_bind_param($stmt_layanan, "i", $id_perumahan);
mysqli_stmt_execute($stmt_layanan);
$result_layanan = mysqli_stmt_get_result($stmt_layanan);
$layanan_data = mysqli_fetch_all($result_layanan, MYSQLI_ASSOC);

// Cek apakah SweetAlert sudah ditampilkan
$showAlertNotif = false;
$pesanan_message = '';

if (!isset($_SESSION['alert_displayed_notif'])) {
    // Cek pesanan layanan baru
    $sql_pesanan = "
    SELECT nomor_invoice, status_pesanan, kategori_jasa 
    FROM pesanan_layanan 
    WHERE id_warga = ? AND status_pesanan IN ('Dalam Proses', 'Ditolak')
    ";

    $stmt_pesanan = mysqli_prepare($conn, $sql_pesanan);
    if ($stmt_pesanan) {
        mysqli_stmt_bind_param($stmt_pesanan, "s", $_SESSION['id_warga']);
        mysqli_stmt_execute($stmt_pesanan);
        $result_pesanan = mysqli_stmt_get_result($stmt_pesanan);

        // Cek apakah ada pesanan
        if (mysqli_num_rows($result_pesanan) > 0) {
            while ($data_pesanan = mysqli_fetch_assoc($result_pesanan)) {
                $kategori_jasa = htmlspecialchars($data_pesanan['kategori_jasa']); // Ambil kategori jasa
                if ($data_pesanan['status_pesanan'] === 'Dalam Proses') {
                    $pesanan_message = 'Pesanan <strong>' . $kategori_jasa . '</strong> dengan nomor invoice ' . htmlspecialchars($data_pesanan['nomor_invoice']) . ' telah <span style="color: green; font-weight: bold; font-style: italic;">diterima</span> oleh penyedia jasa!';
                } elseif ($data_pesanan['status_pesanan'] === 'Ditolak') {
                    $pesanan_message = 'Pesanan <strong>' . $kategori_jasa . '</strong> dengan nomor invoice ' . htmlspecialchars($data_pesanan['nomor_invoice']) . ' telah <span style="color: red; font-weight: bold; font-style: italic;">ditolak</span> oleh penyedia jasa!';
                }
            }
            $_SESSION['alert_displayed_notif'] = true; // Tandai bahwa notifikasi sudah ditampilkan
            $showAlertNotif = true;
        }
        mysqli_stmt_close($stmt_pesanan);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
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
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- <link rel="stylesheet" href="css/tambahan.css"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-DM Sans">

    <div class="loader" id="loader">
        <img src="assets/load/loader.gif" alt="Loading...">
    </div>

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
        <a href="permintaanlayanan.php" class="menu-link mobile-only">Ajukan Permintaan</a>
        <button class="logout-button mobile-only" onclick="confirmLogout()">Keluar</button>
    </div>

    <main class="main-content">
        <div class="hero">
            <p class="hero-text">Pelayanan yang ramah dan handal</p>
            <h1 class="hero-title">Pelayanan</h1>
            <div class="hero-services">
                <div class="service-category">jasa</div>
                <h1 class="service-title">profesional</h1>
            </div>
            <p class="hero-description">Saat anda melakukan sesuatu yang penting atau sedang tidak bisa menangani
                masalah sendiri, kami akan sedia membantu segala sesuatu yang tidak bisa anda selesaikan</p>
        </div><br>

        <div class="services-intro">
            <h1 class="services-title">Pelayanan jasa <br>yang kami sediakan</h1>
            <p class="services-description">Anda bisa memilih salah satu pelayanan jasa yang kami sediakan sesuai dengan
                kebutuhan anda saat ini.</p>
            <p class="services-description">Pesan layanan yang tersedia dengan klik tombol pesan di bawah ini.</p>
        </div><br>
        <div class="search-box">
            <input type="text" placeholder="Cari Layanan yang tersedia..." oninput="searchLayanan()">
            <button class="search-btn desktop-only" onclick="window.location.href='permintaanlayanan.php'">Ajukan Permintaan</button>
        </div><br>
        <br>

        <div class="card-container">
            <?php foreach ($layanan_data as $layanan): ?>
                <div id="card-<?php echo htmlspecialchars(strtolower(str_replace(' ', '-', $layanan['kategori_jasa']))); ?>"
                    class="card service-card"
                    data-nama="<?php echo htmlspecialchars($layanan['nama_penyedia_layanan']); ?>"
                    data-kategori="<?php echo htmlspecialchars($layanan['kategori_jasa']); ?>">
                    <div class="card-header">
                        <h2 class="card-title"><?php echo htmlspecialchars($layanan['nama_penyedia_layanan']); ?></h2>
                        <div class="rating">
                            <i class="fas fa-star star-icon"></i>
                            <i class="fas fa-star star-icon"></i>
                            <i class="fas fa-star star-icon"></i>
                            <i class="fas fa-star star-icon"></i>
                            <i class="fas fa-star star-icon"></i>
                        </div>
                    </div>
                    <!-- Tampilkan foto_profil atau gambar default jika kosong -->
                    <img src="<?php echo !empty($layanan['foto_profil']) ? 'php/' . htmlspecialchars($layanan['foto_profil']) : 'assets/img/404.png'; ?>"
                        alt="<?php echo htmlspecialchars($layanan['kategori_jasa']); ?>"
                        class="card-image">
                    <div class="card-footer">
                        <p class="card-price mr-3">Rp <?php echo number_format($layanan['harga'], 0, ',', '.'); ?>/jam</p>
                        <button class="order-button" onclick="redirectToPesanan()">Pesan</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Tabel daftar pesanan -->
        <div class="services-intro" id="pesanan-layanan-jasa">
            <h1 class="services-title">Pesanan Real-Time</h1>
            <p class="services-description">Berikut ini Real-time data pesanan masuk terbaru FixQuick.</p>
            <p class="services-description">Lihat <a href="historiPesanan.php" style="color:rgb(8, 92, 83); font-weight: 700;">Histori Pesanan</a> Anda.</p>
        </div><br>
        <div class="table-container">
            <!-- <div class="search-container">
                <input type="text" id="searchInput" placeholder="Masukkan Nomor Invoice Anda..."
                    onkeyup="searchTable()">
            </div> -->

            <?php
            // Memanggil file untuk mendapatkan data
            include('php/get_datapesananwarga.php');
            ?>
            <!-- <input type="text" id="searchInput" placeholder="Cari data..." class="search-input" onkeyup="searchTable()"> -->
            <table id="invoiceTable" class="striped-table">
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
        function redirectToPesanan() {
            window.location.href = 'pesananwarga.php';
        }
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tampilkan notifikasi selamat datang terlebih dahulu
            <?php if ($showAlert): ?>
                Swal.fire({
                    html: `
                <div style="display: flex; align-items: center; justify-content: center;">
                    <div style="margin-right: 10px;">
                        <i class="fa fa-check-circle" style="font-size: 24px; color: green;"></i>
                    </div>
                    <div style="font-size: 18px; font-weight: bold;">
                       Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_warga'], ENT_QUOTES, 'UTF-8'); ?>!
                    </div>
                </div>
            `,
                    showConfirmButton: false,
                    timer: 1500,
                    width: 350,
                    padding: '1em',
                    position: 'top',
                    background: '#f9f9f9'
                }).then(() => {
                    // Setelah notifikasi selamat datang ditutup, tampilkan notifikasi pesanan layanan baru
                    <?php if ($showAlertNotif && !empty($pesanan_message)): ?>
                        Swal.fire({
                            // title: 'Pemberitahuan!',
                            html: '<?php echo $pesanan_message; ?>',
                            icon: 'info',
                            iconColor: '#93c7c2',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#009688'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Arahkan ke bagian Pesanan Layanan Jasa
                                window.location.hash = 'pesanan-layanan-jasa'; // Ganti dengan ID yang sesuai
                            }
                        });
                    <?php endif; ?>
                });
            <?php else: ?>
                // Jika tidak ada notifikasi selamat datang, langsung tampilkan notifikasi pesanan
                <?php if ($showAlertNotif && !empty($pesanan_message)): ?>
                    Swal.fire({
                        // title: 'Notifika',
                        html: '<?php echo $pesanan_message; ?>',
                        icon: 'info',
                        iconColor: '#93c7c2',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#009688'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Arahkan ke bagian Pesanan Layanan Jasa
                            window.location.hash = 'pesanan-layanan-jasa'; // Ganti dengan ID yang sesuai
                        }
                    });
                <?php endif; ?>
            <?php endif; ?>
        });
    </script>
    <!-- notfikasi real-time -->
    <script>
        let displayedInvoices = {}; // Objek untuk menyimpan status terakhir dari setiap nomor invoice

        function checkPesananBaru() {
            $.ajax({
                url: 'php/check_pesanan_baru.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.data.length > 0) {
                        response.data.forEach(function(pesanan) {
                            // Cek apakah status pesanan sudah ditampilkan
                            if (!displayedInvoices[pesanan.nomor_invoice] || displayedInvoices[pesanan.nomor_invoice] !== pesanan.status_pesanan) {
                                let message;
                                if (pesanan.status_pesanan === 'Dalam Proses') {
                                    message = 'Pesanan <strong>' + pesanan.kategori_jasa + '</strong> dengan nomor invoice ' + pesanan.nomor_invoice + ' telah <span style="color: green; font-weight: bold; font-style: italic;">diterima</span> oleh penyedia jasa!';
                                } else if (pesanan.status_pesanan === 'Ditolak') {
                                    message = 'Pesanan <strong>' + pesanan.kategori_jasa + '</strong> dengan nomor invoice ' + pesanan.nomor_invoice + ' telah <span style="color: red; font-weight: bold; font-style: italic;">ditolak</span> oleh penyedia jasa!';
                                }
                                Swal.fire({
                                    html: message,
                                    icon: 'info',
                                    iconColor: '#93c7c2',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#009688'
                                });
                                // Simpan status terakhir dari nomor invoice
                                displayedInvoices[pesanan.nomor_invoice] = pesanan.status_pesanan;
                            }
                        });
                    }
                },
                error: function() {
                    console.error('Error checking new orders');
                }
            });
        }

        // Cek pesanan baru setiap 10 detik
        setInterval(checkPesananBaru, 1000);
    </script>

    <!-- SEEARCH LAYANAN -->
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