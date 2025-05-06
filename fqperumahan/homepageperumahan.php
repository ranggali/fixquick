<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
    alert('Anda harus login sebagai admin.');
    window.location.href = '../FixQuickWebsite/login.php';
</script>";
    exit;
}
$nama_pengguna = $_SESSION['nama_pengguna'];
$id_perumahan = $_SESSION['id_perumahan'];

// Cek apakah SweetAlert sudah ditampilkan
$showAlert = false;
if (!isset($_SESSION['alert_displayed'])) {
    $_SESSION['alert_displayed'] = true;
    $showAlert = true;
}

include('php/connection_db.php');

// Query untuk mendapatkan data dari tabel warga
$sql = "
    SELECT 
        nama_warga, 
        no_telepon, 
        alamat, 
        status_masuk 
    FROM warga 
    WHERE id_perumahan = ?
    ORDER BY created_at DESC
";

// Persiapkan statement
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Bind parameter dan eksekusi query
    mysqli_stmt_bind_param($stmt, "s", $id_perumahan);
    mysqli_stmt_execute($stmt);

    // Ambil hasil query
    $result = mysqli_stmt_get_result($stmt);

    // Fetch semua data sebagai array asosiatif
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Tutup statement
    mysqli_stmt_close($stmt);
} else {
    $data = [];
    echo "Error: " . mysqli_error($conn);
}

// Query untuk mengambil data layanan berdasarkan id_perumahan
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
if (!isset($_SESSION['alert_displayed_notif'])) {
    // Cek pengajuan layanan baru
    $sql_pengajuan = "
        SELECT COUNT(*) as total_pengajuan 
        FROM pengajuan_pelayanan 
        WHERE id_perumahan = ? AND status_pengajuan = 'Menunggu'
    ";

    $stmt_pengajuan = mysqli_prepare($conn, $sql_pengajuan);
    if ($stmt_pengajuan) {
        mysqli_stmt_bind_param($stmt_pengajuan, "s", $id_perumahan);
        mysqli_stmt_execute($stmt_pengajuan);
        $result_pengajuan = mysqli_stmt_get_result($stmt_pengajuan);
        $data_pengajuan = mysqli_fetch_assoc($result_pengajuan);
        $total_pengajuan = $data_pengajuan['total_pengajuan'];
        mysqli_stmt_close($stmt_pengajuan);
    } else {
        $total_pengajuan = 0;
        echo "Error: " . mysqli_error($conn);
    }

    // Jika ada pengajuan baru, set showAlert menjadi true dan simpan status di session
    if ($total_pengajuan > 0) {
        $_SESSION['alert_displayed_notif'] = true; // Tandai bahwa notifikasi sudah ditampilkan
        $showAlertNotif = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" href="assets/img/logo1.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixQuick</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/table.css">
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

    <nav class="navbar">
        <div class="navbar-logo">
            <img src="assets/img/logo1.png" alt="FixQuick logo" class="logo">
            <span class="logo-text">FixQuick</span>
        </div>
        <div class="navbar-menu">
            <a href="mailto:fixquick617@gmail.com?subject=Masukan untuk FixQuick&body=Halo, admin FixQuick!%0A%0ASaya ingin mengirimkan masukan..." class="menu-link">Hubungi kami</a>
            <button class="logout-button desktop-only" onclick="confirmLogout()">Keluar</button>
            <a href="profilperumahan.php" class="profile-link">
                <i class="fas fa-user-circle menu-icon desktop-only" id="profil_perumahan"></i>
                <span class="tooltip">Profile</span>
            </a>
            <!-- <i class="fas fa-user-circle menu-icon desktop-only"></i> -->
            <span class="profile-link">
                <i class="fas fa-tasks menu-icon desktop-only" onclick="redirectToDashboard()"></i>
                <span class="tooltip">Dashboard</span>
            </span>
        </div>
        <button class="hamburger-button" id="hamburger">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu hidden" id="mobileMenu">
        <a href="mailto:fixquick617@gmail.com?subject=Masukan untuk FixQuick&body=Halo, admin FixQuick!%0A%0ASaya ingin mengirimkan masukan..." class="menu-link mobile-only">Hubungi kami</a>
        <a href="profilperumahan.php" class="profile-button mobile-only">Profil</a>
        <button class="setting-button mobile-only" onclick="redirectToDashboard()">Pengaturan</button>
        <button class="logout-button mobile-only" onclick="confirmLogout()">Keluar</button>
    </div>
    <p style="font-family:'DM Sans', sans serif; padding-top: 90px;
    margin-left: 10px;
    text-align: center; 
    font-size: 30px;
    font-weight: 600;">Hi, Admin <?php echo htmlspecialchars($_SESSION['nama_pengguna'], ENT_QUOTES, 'UTF-8'); ?>! 👋</p>
    <main class="main-content">
        <div class="services-intro">
            <h1 class="services-title">Daftar Layanan <br>yang ada diperumahan anda</h1>
        </div>
        <button class="tambahdatalayanan" onclick="redirectToTambahLayanan()" style="margin-top: 10px;">
            <i class="fas fa-plus"></i> Tambah Layanan
        </button><br>
        <br>

        <div class="card-container">
            <?php foreach ($layanan_data as $layanan): ?>
                <div id="card-<?php echo htmlspecialchars(strtolower(str_replace(' ', '-', $layanan['kategori_jasa']))); ?>" class="card service-card">
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
                    <!-- Tampilkan foto_profil dari php/uploads atau gambar default jika kosong -->
                    <img src="<?php echo !empty($layanan['foto_profil']) ? '../fquser/php/' . htmlspecialchars($layanan['foto_profil']) : 'assets/img/404.png'; ?>"
                        alt="<?php echo htmlspecialchars($layanan['kategori_jasa']); ?>"
                        class="card-image">
                    <div class="card-footer">
                        <p class="card-price mr-3">Rp <?php echo number_format($layanan['harga'], 0, ',', '.'); ?>/jam</p>
                        <button class="order-button" onclick="redirectToPesanan()">Detail</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Data Warga  -->
        <!-- Tabel daftar pesanan -->
        <div class="services-intro">
            <h1 class="services-title">Data Warga Aktif</h1>
            <p class="services-description">Berikut ini Daftar Warga perumahan anda yang sedang aktif.
            </p>
        </div><br>
        <div class="table-container">
            <!-- <div class="control-container"> -->
            <!-- <button class="add-button" onclick="openModalWarga()"><span>Tambah Warga</span></button> -->
            <!-- <div class="search-container">
                    <input type="text" id="searchInputWarga" placeholder="Cari berdasarkan nama..." onkeyup="searchTableWarga()">
                </div>
            </div>

            <div class="pagination-container">
                <label for="rowsPerPageWarga">Tampilkan</label>
                <select id="rowsPerPageWarga" onchange="updateTable()">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
                <span>baris per halaman</span>
            </div> -->
            <!-- <input type="text" id="searchInput" placeholder="Cari data..." class="search-input" onkeyup="searchTable()"> -->

            <table class="striped-table" id="pengajuanTable">
                <thead class="judultabel">
                    <tr>
                        <th>No</th>
                        <th>Nama Warga</th>
                        <th>Alamat</th>
                        <th>No Telepon</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $index => $row): ?>
                            <?php if ($row['status_masuk'] === 'aktif'): ?> <!-- Menambahkan kondisi di sini -->
                                <tr>
                                    <td><?php echo "FQW" . ($index + 1); ?></td>
                                    <td><?php echo !empty($row['nama_warga']) ? htmlspecialchars($row['nama_warga']) : "<em>Data Kosong</em>"; ?></td>
                                    <td><?php echo !empty($row['alamat']) ? htmlspecialchars($row['alamat']) : "<em>Data Kosong</em>"; ?></td>
                                    <td><?php echo !empty($row['no_telepon']) ? htmlspecialchars($row['no_telepon']) : "<em>Data Kosong</em>"; ?></td>
                                    <td>
                                        <span class="status-aktif">Aktif</span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Tidak ada data warga.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div id="pagination" style="margin-top: 10px;"></div>
        </div>
        <!-- End tabel daftar pesanan -->
    </main><br>

    <!-- Modal for adding a resident -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModalWarga()">&times;</span>
            <h2>Tambah Data Warga</h2>
            <form id="addResidentForm">
                <input type="text" id="name" placeholder="Nama Warga" required>
                <input type="text" id="serviceProvider" placeholder="Penyedia Layanan" required>
                <input type="text" id="serviceType" placeholder="Jenis Layanan" required>
                <input type="date" id="orderDate" required>
                <button type="button" onclick="addResident()">Simpan</button>
            </form>
        </div>
    </div>

    <!-- Modal for resident details -->
    <div id="detailModalWarga" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDetailModalWarga()">&times;</span>
            <h2>Data Detail Warga</h2>
            <p><strong>Nama Warga:</strong> <span id="detailName"></span></p>
            <p><strong>Penyedia Layanan:</strong> <span id="detailServiceProvider"></span></p>
            <p><strong>Jenis Layanan:</strong> <span id="detailServiceType"></span></p>
            <p><strong>Tanggal Pesan:</strong> <span id="detailOrderDate"></span></p>
        </div>
    </div>
    <!--End Data Warga -->

    <main class="main-content" id="ajuan-layanan-jasa">
        <!-- Start Ajuan Layanan Jasa  -->
        <div class="services-intro">
            <h1 class="services-title">Pengajuan Layanan Jasa</h1>
            <p class="services-description">Berikut ini adalah daftar pengajuan layanan jasa dari penyedia yang ingin
                bekerja sama di perumahan Anda.</p>
        </div><br>
        <div class="table-container">
            <!-- <div class="control-container-layanan">
                <div class="search-container">
                    <input type="text" id="searchInputLayanan" placeholder="Cari berdasarkan nama penyedia jasa..."
                        onkeyup="searchTableLayanan()">
                </div>
            </div> -->

            <!-- <div class="pagination-container">
                <label for="rowsPerPageLayanan">Tampilkan</label>
                <select id="rowsPerPageLayanan" onchange="updateTable()">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
                <span>baris per halaman</span>
            </div> -->

            <?php
            // Memanggil file untuk mendapatkan data
            include('php/get_datapengajuan.php');
            ?>

            <table class="striped-table" id="daftarRumahTable">
                <thead class="judultabel">
                    <tr>
                        <th>No Pengajuan</th>
                        <th>Nama Penyedia Jasa</th>
                        <th>Kategori Layanan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $index => $row): ?>
                            <tr>
                                <td><?php echo "PL-" . ($index + 1); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_penyedia_jasa']); ?></td>
                                <td><?php echo htmlspecialchars($row['kategori_jasa']); ?></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($row['created_at']))); ?></td>
                                <td>
                                    <?php
                                    // Menentukan status dan ikon yang sesuai
                                    $status = htmlspecialchars($row['status_pengajuan']);
                                    if ($status === 'Menunggu') {
                                        echo '<img src="assets/icons/iconmenunggu.svg" alt="Icon Menunggu" style="width: 16px; vertical-align: middle;"> Menunggu';
                                    } elseif ($status === 'Disetujui') {
                                        echo '<img src="assets/icons/iconsetuju.svg" alt="Icon Disetujui" style="width: 16px; vertical-align: middle;"> Disetujui';
                                    } elseif ($status === 'Ditolak') {
                                        echo '<img src="assets/icons/icontolak.svg" alt="Icon Ditolak" style="width: 16px; vertical-align: middle;"> Ditolak';
                                    }
                                    ?>
                                </td>
                                <td><button onclick="openPengajuanPeLayanan()">Lihat</button></td>
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
    </main><br>

    <!-- Modal untuk Detail Pengajuan Layanan Jasa -->
    <div id="detailModalLayanan" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDetailModalLayanan()">&times;</span>
            <h2>Detail Pengajuan Layanan Jasa</h2>
            <p><strong>Nama Penyedia:</strong> <span id="detailNamaPenyedia"></span></p>
            <p><strong>Email:</strong> <span id="detailEmailPenyedia"></span></p>
            <p><strong>Nomor Telepon:</strong> <span id="detailNohpPenyedia"></span></p>
            <p><strong>No Izin Usaha:</strong> <span id="detailNoIzinUsaha"></span></p>
            <p><strong>Alamat:</strong> <span id="detailAlamatPenyedia"></span></p>
            <p><strong>Kategori Layanan:</strong> <span id="detailKategoriPenyedia"></span></p>
            <p><strong>Tanggal Pengajuan:</strong> <span id="detailTanggalPenyedia"></span></p>
            <p><strong>Deskripsi:</strong> <span id="detailDeskripsiPenyedia"></span></p>
            <p><strong>Status:</strong> <span id="detailStatusPenyedia"></span></p>
            <!-- Tombol Setuju dan Tolak -->
            <div class="modal-actions">
                <button id="approveButton" onclick="approveService()">Setuju</button>
                <button id="rejectButton" onclick="rejectService()">Tolak</button>
            </div>
        </div>
    </div>
    <!-- End Pengajuan layanan Jasa -->

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
        // function updateTable(tableId, rowsPerPageId) {
        //     const rowsPerPage = parseInt(document.getElementById(rowsPerPageId).value, 10);
        //     const table = document.getElementById(tableId);
        //     const tr = table.getElementsByTagName("tr");
        //     let count = 0;

        //     for (let i = 1; i < tr.length; i++) {
        //         if (count < rowsPerPage) {
        //             tr[i].style.display = "";
        //             count++;
        //         } else {
        //             tr[i].style.display = "none";
        //         }
        //     }
        // }

        // document.addEventListener("DOMContentLoaded", () => {
        //     updateTable("dataTableWarga", "rowsPerPageWarga"); // Initial load for Warga table
        //     updateTable("dataTableLayanan", "rowsPerPageLayanan"); // Initial load for Layanan table

        //     // Add event listeners for rows per page changes
        //     document.getElementById("rowsPerPageWarga").addEventListener("change", () => {
        //         updateTable("dataTableWarga", "rowsPerPageWarga");
        //     });

        //     document.getElementById("rowsPerPageLayanan").addEventListener("change", () => {
        //         updateTable("dataTableLayanan", "rowsPerPageLayanan");
        //     });
        // });
        $(document).ready(function() {
            $('#daftarRumahTable').DataTable({
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
        $(document).ready(function() {
            $('#pengajuanTable').DataTable({
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
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');

        // Toggle mobile menu visibility
        hamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
    <script>
        function redirectToPesanan() {
            window.location.href = '../fqadminperumahan/pelayanan/pelayananjasa.php';
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
        function redirectToDashboard() {
            window.location.href = '../fqadminperumahan/index.php';
        }
    </script>
    <script>
        function openPengajuanPeLayanan() {
            window.location.href = '../fqadminperumahan/perumahan/adminperum.php';
        }
    </script>
    <script>
        function redirectToTambahLayanan() {
            window.location.href = '../fqadminperumahan/pelayanan/pelayananjasa.php';
        }
    </script>
    <!-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($showAlert): ?>
                Swal.fire({
                    html: `
                    <div style="display: flex; align-items: center; justify-content: center;">
                        <div style="margin-right: 10px;">
                            <i class="fa fa-check-circle" style="font-size: 24px; color: green;"></i>
                        </div>
                        <div style="font-size: 18px; font-weight: bold;">
                           Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_pengguna'], ENT_QUOTES, 'UTF-8'); ?>!
                        </div>
                    </div>
                `,
                    showConfirmButton: false,
                    timer: 1500,
                    width: 350,
                    padding: '1em',
                    position: 'top',
                    background: '#f9f9f9'
                });
            <?php endif; ?>
        });
    </script> -->
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
                       Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_pengguna'], ENT_QUOTES, 'UTF-8'); ?>!
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
                    // Setelah notifikasi selamat datang ditutup, tampilkan notifikasi pengajuan layanan baru
                    <?php if ($showAlertNotif): ?>
                        Swal.fire({
                            title: 'Notifikasi',
                            text: 'Ada <?php echo $total_pengajuan; ?> pengajuan layanan baru masuk!',
                            icon: 'info',
                            iconColor: '#93c7c2',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#009668'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Arahkan ke bagian Ajuan Layanan Jasa
                                window.location.hash = 'ajuan-layanan-jasa'; // Ganti dengan ID yang sesuai
                            }
                        });
                    <?php endif; ?>
                });
            <?php else: ?>
                // Jika tidak ada notifikasi selamat datang, langsung tampilkan notifikasi pengajuan
                <?php if ($showAlertNotif): ?>
                    Swal.fire({
                        title: 'Notifikasi',
                        text: 'Ada <?php echo $total_pengajuan; ?> pengajuan layanan baru masuk!',
                        icon: 'info',
                        iconColor: '#93c7c2',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#009688'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Arahkan ke bagian Ajuan Layanan Jasa
                            window.location.hash = 'ajuan-layanan-jasa'; // Ganti dengan ID yang sesuai
                        }
                    });
                <?php endif; ?>
            <?php endif; ?>
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let lastSubmissionCount = 0; // Menyimpan jumlah pengajuan terakhir untuk perbandingan

            function checkNewSubmissions() {
                // Kirim AJAX request untuk memeriksa pengajuan baru
                $.ajax({
                    url: 'php/check_pengajuan_baru.php', // File PHP yang kita buat
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.total_pengajuan > lastSubmissionCount) {
                            // Jika ada pengajuan baru yang belum ditampilkan, perbarui lastSubmissionCount
                            lastSubmissionCount = response.total_pengajuan;

                            // Tampilkan notifikasi
                            Swal.fire({
                                title: 'Notifikasi',
                                text: `Ada ${response.total_pengajuan} pengajuan layanan baru masuk!`,
                                icon: 'info',
                                iconColor: '#93c7c2',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#009688'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Arahkan ke bagian Ajuan Layanan Jasa
                                    window.location.hash = 'ajuan-layanan-jasa'; // Ganti dengan ID yang sesuai
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error checking new submissions:', error);
                    }
                });
            }

            // Periksa pengajuan baru setiap 10 detik
            setInterval(checkNewSubmissions, 1000);
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
</body>

</html>