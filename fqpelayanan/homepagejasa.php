<?php
session_start();

// autentikasi login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'provider') {
    echo "<script>
    alert('Anda harus login sebagai admin.');
    window.location.href = '../FixQuickWebsite/login.php';
</script>";
    exit;
}
$nama_penyedia_jasa = $_SESSION['nama_penyedia_jasa'];
$id_pelayanan_jasa = $_SESSION['id_pelayanan_jasa'];

// Cek apakah SweetAlert sudah ditampilkan
$showAlert = false;
if (!isset($_SESSION['alert_displayed'])) {
    $_SESSION['alert_displayed'] = true; // Tandai SweetAlert sudah ditampilkan
    $showAlert = true;
}

include('php/connection_db.php');

// Cek apakah SweetAlert pesanan saat baru masuk sudah ditampilkan
$showAlertNotif = false;
if (!isset($_SESSION['alert_displayed_notif'])) {
    // Cek pesanan layanan baru
    $sql_pesanan = "
        SELECT COUNT(*) as total_pesanan 
        FROM pesanan_layanan 
        WHERE id_pelayanan_jasa = ? AND status_pesanan = 'Menunggu'
    ";

    $stmt_pesanan = mysqli_prepare($conn, $sql_pesanan);
    if ($stmt_pesanan) {
        mysqli_stmt_bind_param($stmt_pesanan, "s", $id_pelayanan_jasa);
        mysqli_stmt_execute($stmt_pesanan);
        $result_pesanan = mysqli_stmt_get_result($stmt_pesanan);
        $data_pesanan = mysqli_fetch_assoc($result_pesanan);
        $total_pesanan = $data_pesanan['total_pesanan'];
        mysqli_stmt_close($stmt_pesanan);
    } else {
        $total_pesanan = 0;
        echo "Error: " . mysqli_error($conn);
    }

    // Jika ada pesanan baru, set showAlert menjadi true dan simpan status di session
    if ($total_pesanan > 0) {
        $_SESSION['alert_displayed_notif'] = true; // Tandai bahwa notifikasi sudah ditampilkan
        $showAlertNotif = true;
    }
}

// Cek apakah SweetAlert Pengajuan saat baru masuk sudah ditampilkan
$showAlertPengajuan = false;
$pengajuan_message = '';

if (!isset($_SESSION['alert_displayed_pengajuan'])) {
    // Cek pesanan layanan baru
    $sql_pengajuan = "
    SELECT pp.status_pengajuan, p.nama_perumahan
    FROM pengajuan_pelayanan AS pp
    JOIN perumahan AS p ON pp.id_perumahan = p.id_perumahan
    WHERE pp.id_pelayanan_jasa = ? AND pp.status_pengajuan IN ('Disetujui', 'Ditolak')
    ";

    $stmt_pengajuan = mysqli_prepare($conn, $sql_pengajuan);
    if ($stmt_pengajuan) {
        mysqli_stmt_bind_param($stmt_pengajuan, "s", $_SESSION['id_pelayanan_jasa']);
        mysqli_stmt_execute($stmt_pengajuan);
        $result_pengajuan = mysqli_stmt_get_result($stmt_pengajuan);

        // Cek apakah ada pengajuan
        if (mysqli_num_rows($result_pengajuan) > 0) {
            while ($data_pengajuan = mysqli_fetch_assoc($result_pengajuan)) {
                $nama_perumahan = htmlspecialchars($data_pengajuan['nama_perumahan']); // Ambil kategori jasa
                if ($data_pengajuan['status_pengajuan'] === 'Disetujui') {
                    $pengajuan_message = 'Pengajuan anda pada perumahan <strong>' . $nama_perumahan . '</strong>' . ' telah <span style="color: green; font-weight: bold; font-style: italic;">diterima</span> !';
                } elseif ($data_pengajuan['status_pengajuan'] === 'Ditolak') {
                    $pengajuan_message = 'Pengajuan anda pada perumahan <strong>' . $nama_perumahan . '</strong>' . ' telah <span style="color: red; font-weight: bold; font-style: italic;">ditolak</span> !';
                }
            }
            $_SESSION['alert_displayed_pengajuan'] = true; // Tandai bahwa notifikasi sudah ditampilkan
            $showAlertPengajuan = true;
        }
        mysqli_stmt_close($stmt_pengajuan);
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
    <link rel="stylesheet" href="css/modal.css">
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
            <a href="profiljasa.php" class="profile-link">
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
        <a href="profiljasa.php" class="profile-button mobile-only">Profil</a>
        <!-- <button class="setting-button mobile-only">Pengaturan</button> -->
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
            <p class="hero-description">Saat anda melakukan sesuatu yang penting atau sedang tidak bisa menangani masalah sendiri, kami akan sedia membantu segala sesuatu yang tidak bisa anda selesaikan</p>
        </div><br>

        <!-- Tabel daftar pesanan -->
        <div class="services-intro">
            <h1 class="services-title">Data Perumahan Terdaftar</h1>
            <p class="services-description">Berikut ini daftar perumahan yang terdaftar pada FixQuick. <br> Anda bisa mengajukan layanan jasa dengan klik detail kemudian ajukan pelayanan</p>
        </div><br>
        <div class="table-container">
            <!-- <div class="search-container">
                <input type="text" id="searchInput" placeholder="Cari Perumahan..." onkeyup="searchTable()">
            </div>
            <div class="pagination-container">
                <label for="rowsPerPagePerumahan">Tampilkan</label>
                <select id="rowsPerPagePerumahan" onchange="updateTable()">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
                <span>baris per halaman</span>
            </div> -->
            <!-- <input type="text" id="searchInput" placeholder="Cari data..." class="search-input" onkeyup="searchTable()"> -->
            <?php
            // Memanggil file untuk mendapatkan data
            include('php/get_dataperumahan.php');
            ?>
            <table class="striped-table" id="daftarRumahTable">
                <thead class="judultabel">
                    <tr>
                        <th>No</th>
                        <th>Nama Perumahan</th>
                        <th>Alamat</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $index => $row): ?>
                            <tr>
                                <td><?php echo ($index + 1); ?></td>
                                <td><?php echo !empty($row['nama_perumahan']) ? htmlspecialchars($row['nama_perumahan']) : "<em>Data Kosong</em>"; ?></td>
                                <td><?php echo !empty($row['alamat']) ? htmlspecialchars($row['alamat']) : "<em>Data Kosong</em>"; ?></td>
                                <td><?php echo !empty($row['email']) ? htmlspecialchars($row['email']) : "<em>Data Kosong</em>"; ?></td>
                                <td>
                                    <!-- <button type="button" class="btn btn-info" onclick="DetailPerumahan(<?php echo $row['id_perumahan']; ?>)">Detail</button> -->
                                    <button class="btn-detail" onclick="showDetailPerumahan(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                        Detail
                                    </button>
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



    <!-- Struktur Modal Detail Perumahan -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="tutupModal()">&times;</span>
            <div class="modal-header">Detail Perumahan</div>
            <div class="modal-body">
                <!-- Foto di bagian atas -->
                <img id="modalFoto" src="" alt="Foto Perumahan" class="modal-foto">

                <!-- Deskripsi di bawah foto -->
                <div class="modal-detail">
                    <p><strong>Nama Perumahan:</strong> <span id="modalNama"></span></p>
                    <p><strong>Admin Perumahan:</strong> <span id="modalAdminPerumahan"></span></p>
                    <p><strong>Alamat:</strong> <span id="modalAlamat"></span></p>
                    <p><strong>Telepon:</strong> <span id="modalTelepon"></span></p>
                    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                    <p><strong>Deskripsi:</strong><span id="modalDeskripsi"></span></p>
                    <button id="ajukanPelayananBtn" onclick="redirectToServicePage()" class="ajukan-pelayanan-btn">Ajukan Pelayanan</button>
                </div>
            </div>
        </div>
    </div>

    <main class="main-content">
        <!-- Start Ajuan Layanan Jasa  -->
        <div class="services-intro">
            <h1 class="services-title">Status Pengajuan Layanan Jasa</h1>
            <p class="services-description">Lihat status pengajuan Anda disini.</p>
        </div><br>
        <div class="table-container">
            <!-- <div class="control-container">
                <div class="search-container">
                    <input type="text" id="searchInputPengajuan" placeholder="Masukkan ID pengajuan jasa Anda..." onkeyup="searchTablePengajuan()">
                </div>
            </div>

            <div class="pagination-container">
                <label for="rowsPerPagePengajuan">Tampilkan</label>
                <select id="rowsPerPagePengajuan" onchange="updateTable()">
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

            <table class="striped-table" id="statusPengajuan">
                <thead class="judultabel">
                    <tr>
                        <th>No Pengajuan</th>
                        <th>Nama Penyedia Jasa</th>
                        <th>Kategori Layanan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
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

    <main class="main-content" id="pesanan-layanan-jasa">
        <!-- Start Ajuan Layanan Jasa  -->
        <div class="services-intro">
            <h1 class="services-title">Pesanan Layanan Masuk</h1>
            <p class="services-description">Lihat pesanan layanan yang masuk Anda disini.</p>
        </div><br>
        <div class="table-container">
            <!-- <div class="control-container">
                <div class="search-container">
                    <input type="text" id="searchInputPengajuan" placeholder="Masukkan ID pengajuan jasa Anda..." onkeyup="searchTablePengajuan()">
                </div>
            </div>

            <div class="pagination-container">
                <label for="rowsPerPagePengajuan">Tampilkan</label>
                <select id="rowsPerPagePengajuan" onchange="updateTable()">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
                <span>baris per halaman</span>
            </div> -->

            <?php
            // Memanggil file untuk mendapatkan data
            include('php/get_datapesanan.php');
            ?>

            <table class="striped-table" id="pesananMasuk">
                <thead class="judultabel">
                    <tr>
                        <th>No</th>
                        <th>Nama Warga</th>
                        <th>Kategori Layanan</th>
                        <th>Tanggal Pesanan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $index => $row): ?>
                            <tr>
                                <td><?php echo "PL-" . ($index + 1); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_warga']); ?></td>
                                <td><?php echo htmlspecialchars($row['kategori_jasa']); ?></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($row['tanggal_pesanan']))); ?></td>
                                <td>
                                    <?php
                                    // Menentukan status dan ikon yang sesuai
                                    $status = htmlspecialchars($row['status_pesanan']);
                                    if ($status === 'Menunggu') {
                                        echo '<img src="assets/icons/iconmenunggu.svg" alt="Icon Menunggu" style="width: 16px; vertical-align: middle;"> Menunggu';
                                    } elseif ($status === 'Dalam Proses') {
                                        echo '<img src="assets/icons/iconprogress.svg" alt="Icon Disetujui" style="width: 16px; vertical-align: middle;"> Dalam Proses';
                                    } elseif ($status === 'Selesai') {
                                        echo '<img src="assets/icons/icondone.svg" alt="Icon Selesai" style="width: 16px; vertical-align: middle;"> Selesai';
                                    } elseif ($status === 'Ditolak') {
                                        echo '<img src="assets/icons/icontolak.svg" alt="Icon Ditolak" style="width: 16px; vertical-align: middle;"> Ditolak';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <button class="btn-detail" onclick="showDetailWarga(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                        Detail
                                    </button>
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
    </main><br>

    <!-- Modal Detail Pesanan Dari Warga -->
    <div id="detailModal" class="modal" style="display:none;">
        <div class="modal-content">
            <!-- Close Button -->
            <span class="close" onclick="closeModal()">&times;</span>

            <!-- Modal Title -->
            <h3 class="modal-title">Detail Pesanan</h3>

            <!-- Pesanan Details -->
            <div class="modal-details">
                <p><strong>Nama Warga:</strong> <span id="detailNamaWarga"></span></p>
                <p><strong>Nama Perumahan:</strong> <span id="detailNamaPerumahan"></span></p>
                <p><strong>Kategori Layanan:</strong> <span id="detailKategoriLayanan"></span></p>
                <p><strong>Tanggal Pesan:</strong> <span id="detailTanggalPesan"></span></p>
                <p><strong>Status Pembayaran:</strong> <span id="detailStatusPembayaran"></span></p>
                <p><strong>Status Pesanan:</strong> <span id="detailStatusPesanan"></span></p>
                <p><strong>Total:</strong> Rp <span id="detailTotalPembayaran"></span></p>
                <p><strong>Catatan:</strong> <span id="detailCatatanTambahan"></span></p>
            </div>

            <!-- Update Status Form -->
            <div class="status-update">
                <label for="updateStatus">Ubah Status Pesanan:</label>
                <select id="updateStatus">
                    <option value="" disabled>Ubah Status Pesanan</option>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Dalam Proses">Dalam Proses</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>

            <!-- Save Button -->
            <div class="modal-footer">
                <button id="btnSaveStatus" onclick="saveStatusWarga()">Simpan Perubahan</button>
            </div>
        </div>
    </div>




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
        function showDetailPerumahan(rowData) {
            // Isi data ke dalam modal
            document.getElementById("modalNama").textContent = rowData.nama_perumahan;
            document.getElementById("modalAdminPerumahan").textContent = rowData.nama_pengguna;
            document.getElementById("modalAlamat").textContent = rowData.alamat;
            document.getElementById("modalTelepon").textContent = rowData.no_telepon;
            document.getElementById("modalEmail").textContent = rowData.email;
            document.getElementById("modalDeskripsi").textContent = rowData.deskripsi;
            // Set src pada elemen img
            document.getElementById("modalFoto").src = rowData.foto_url || "assets/img/contohrumah1.jpg";

            // Tampilkan modal
            document.getElementById("myModal").style.display = "block";
        }

        function tutupModal() {
            document.getElementById("myModal").style.display = "none";
        }
    </script>
    <script>
        function showDetailWarga(rowData) {
            // Isi data ke dalam modal
            document.getElementById("detailNamaWarga").textContent = rowData.nama_warga;
            document.getElementById("detailNamaPerumahan").textContent = rowData.nama_perumahan;
            document.getElementById("detailKategoriLayanan").textContent = rowData.kategori_jasa;
            document.getElementById("detailTanggalPesan").textContent = rowData.tanggal_pesanan;
            document.getElementById("detailStatusPembayaran").textContent = rowData.status_pembayaran;
            document.getElementById("detailStatusPesanan").textContent = rowData.status_pesanan;
            document.getElementById("detailTotalPembayaran").textContent = rowData.total_pembayaran;
            document.getElementById("detailCatatanTambahan").textContent = rowData.catatan_tambahan;

            // Tampilkan modal
            document.getElementById("detailModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("detailModal").style.display = "none";
        }

        function saveStatusWarga() {
            const newStatus = document.getElementById("updateStatus").value;
            alert("Status pesanan akan diubah menjadi: " + newStatus);
            // Tambahkan logika untuk menyimpan perubahan status (AJAX atau pengiriman form)
            closeModal();
        }
    </script>
    <script>
        function saveStatusWarga() {
            const newStatus = document.getElementById("updateStatus").value;
            const namaWarga = document.getElementById("detailNamaWarga").textContent;
            const namaPerumahan = document.getElementById("detailNamaPerumahan").textContent;
            const kategoriLayanan = document.getElementById("detailKategoriLayanan").textContent;

            // Debug: Tampilkan data yang akan disimpan
            console.log("Simpan perubahan status pesanan:");
            console.log("Nama Warga:", namaWarga);
            console.log("Nama Perumahan:", namaPerumahan);
            console.log("Kategori Layanan:", kategoriLayanan);
            console.log("Status Baru:", newStatus);

            // Kirim perubahan ke backend dengan AJAX
            fetch('php/update_status_pesanan.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        namaWarga: namaWarga,
                        namaPerumahan: namaPerumahan,
                        kategoriLayanan: kategoriLayanan,
                        newStatus: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Status berhasil diperbarui!");
                        closeModal();
                        location.reload(); // Muat ulang halaman untuk menampilkan perubahan
                    } else {
                        alert("Gagal memperbarui status. Silakan coba lagi.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan. Silakan coba lagi.");
                });
        }
    </script>
    <script>
        function updateTable(tableId, rowsPerPageId) {
            const rowsPerPage = parseInt(document.getElementById(rowsPerPageId).value, 10);
            const table = document.getElementById(tableId);
            const tr = table.getElementsByTagName("tr");
            let count = 0;

            for (let i = 1; i < tr.length; i++) {
                if (count < rowsPerPage) {
                    tr[i].style.display = "";
                    count++;
                } else {
                    tr[i].style.display = "none";
                }
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            updateTable("dataTablePerumahan", "rowsPerPagePerumahan"); // Initial load for Perumahan table
            updateTable("dataTablePengajuan", "rowsPerPagePengajuan"); // Initial load for Pengajuan table

            // Add event listeners for rows per page changes
            document.getElementById("rowsPerPagePerumahan").addEventListener("change", () => {
                updateTable("dataTablePerumahan", "rowsPerPagePerumahan");
            });

            document.getElementById("rowsPerPagePengajuan").addEventListener("change", () => {
                updateTable("dataTablePengajuan", "rowsPerPagePengajuan");
            });
        });
    </script>
    <script>
        function redirectToServicePage() {
            window.location.href = "ajukanpelayanan.php";
        }
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
                            Selamat Datang, <?php echo $_SESSION['nama_penyedia_jasa']; ?>!
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

    <!-- script notifikasi saat berhasil login untuk pesanan layanan, pengajuan pelayanan -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tampilkan notifikasi selamat datang terlebih dahulu jika ada
            <?php if ($showAlert): ?>
                Swal.fire({
                    html: `
                <div style="display: flex; align-items: center; justify-content: center;">
                    <div style="margin-right: 10px;">
                        <i class="fa fa-check-circle" style="font-size: 24px; color: green;"></i>
                    </div>
                    <div style="font-size: 18px; font-weight: bold;">
                       Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_penyedia_jasa'], ENT_QUOTES, 'UTF-8'); ?>!
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
                    showCombinedNotifications(); // Panggil fungsi untuk notifikasi berikutnya
                });
            <?php else: ?>
                showCombinedNotifications(); // Langsung panggil fungsi jika tidak ada notifikasi selamat datang
            <?php endif; ?>

            // Fungsi untuk menampilkan notifikasi kombinasi
            function showCombinedNotifications() {
                let notificationContent = '';

                // Tambahkan notifikasi untuk pesanan layanan baru jika ada
                <?php if ($showAlertNotif): ?>
                    notificationContent += `
                    <div style="margin-bottom: 10px;">
                        <strong>Notifikasi:</strong> Ada <?php echo $total_pesanan; ?> pesanan layanan baru masuk!
                    </div>
                `;
                <?php endif; ?>

                // Tambahkan notifikasi untuk status pengajuan jika ada
                <?php if ($showAlertPengajuan && !empty($pengajuan_message)): ?>
                    notificationContent += `
                    <div style="margin-bottom: 10px;">
                        <?php echo $pengajuan_message; ?>
                    </div>
                `;
                <?php endif; ?>

                // Jika ada konten notifikasi, tampilkan
                if (notificationContent) {
                    Swal.fire({
                        title: 'Pemberitahuan!',
                        html: notificationContent,
                        icon: 'info',
                        iconColor: '#93c7c2',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#009688'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Arahkan ke bagian pesanan layanan jika hanya notifikasi pesanan
                            <?php if ($showAlertNotif): ?>
                                window.location.hash = 'pesanan-layanan-jasa'; // Ganti dengan ID yang sesuai
                            <?php endif; ?>
                        }
                    });
                }
            }
        });
    </script>

    <!-- script notfikasi real-time pesanan dan pengajuan saat pengguna sudah login -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let lastPesananNotificationShown = false; // State untuk melacak notifikasi pesanan terbaru
            let lastPengajuanNotificationShown = false; // State untuk melacak notifikasi pengajuan terbaru
            let lastTotalPesanan = <?php echo $total_pesanan; ?>; // Menyimpan total pesanan terakhir
            let lastPengajuanMessage = ''; // Menyimpan pesan pengajuan terakhir

            // Fungsi untuk memeriksa notifikasi baru
            function checkNotifications() {
                // Lakukan fetch untuk kedua API secara paralel
                Promise.all([
                        fetch('php/check_pesanan_baru.php').then(response => response.json()),
                        fetch('php/check_pengajuan_baru.php').then(response => response.json())
                    ])
                    .then(([pesananData, pengajuanData]) => {
                        let notificationContent = ''; // Variabel untuk menyimpan konten notifikasi

                        // Cek pesanan baru
                        if (pesananData.status === 'success' && pesananData.message === 'Pesanan baru ditemukan') {
                            if (lastTotalPesanan !== pesananData.total_pesanan) { // Bandingkan dengan total pesanan terakhir
                                notificationContent += `
                                <div style="margin-bottom: 10px;">
                                    <strong>Pesanan Baru:</strong> Ada pesanan baru dengan status "${pesananData.status_pesanan}"!
                                </div>
                            `;
                                lastTotalPesanan = pesananData.total_pesanan; // Update total pesanan terakhir
                                lastPesananNotificationShown = true; // Tandai notifikasi pesanan sudah ditampilkan
                            }
                        } else if (pesananData.status === 'no_new') {
                            lastPesananNotificationShown = false; // Reset jika tidak ada pesanan baru
                        }

                        // Cek pengajuan baru
                        if (pengajuanData.status === 'success') {
                            if (lastPengajuanMessage !== pengajuanData.message) { // Bandingkan dengan pesan pengajuan terakhir
                                notificationContent += `
                                <div style="margin-bottom: 10px;">
                                    ${pengajuanData.message}
                                </div>
                            `;
                                lastPengajuanMessage = pengajuanData.message; // Update pesan pengajuan terakhir
                                lastPengajuanNotificationShown = true; // Tandai notifikasi pengajuan sudah ditampilkan
                            }
                        } else if (pengajuanData.status === 'no_new') {
                            lastPengajuanNotificationShown = false; // Reset jika tidak ada pengajuan baru
                        }

                        // Tampilkan notifikasi jika ada konten
                        if (notificationContent) {
                            Swal.fire({
                                title: 'Notifikasi Baru!',
                                html: notificationContent,
                                icon: 'info',
                                iconColor: '#93c7c2',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#009688'
                            }).then((result) => {
                                if (result.isConfirmed && pesananData.status === 'success') {
                                    // Arahkan ke bagian pesanan layanan jika ada pesanan baru
                                    window.location.hash = 'pesanan-layanan-jasa'; // Sesuaikan ID
                                }
                            });
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Jalankan polling setiap 10 detik
            setInterval(checkNotifications, 10000); // 10 detik
        });
    </script>
    <script>
        $(document).ready(function() {
            const tableIds = ['#daftarRumahTable', '#statusPengajuan', '#pesananMasuk'];

            tableIds.forEach(function(id) {
                $(id).DataTable({
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
        });
    </script>
    <!-- script tahunan -->
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="js/spinner.js"></script>
    <script src="js/tabelpelayanan.js"></script>
</body>

</html>