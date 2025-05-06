<?php
// Koneksi ke database
include '../php/connection_db.php'; // Pastikan koneksi ke database

session_start();

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['id_admin'])) {
    session_regenerate_id(true);
    // Jika tidak ada session id_admin, redirect ke halaman login
    header('Location: ../login.php');
    exit();
}

// Jika session id_admin ada, ambil data admin
$admin_name = $_SESSION['nama_admin'];

// Query untuk membaca data dari tabel `perumahan`
$sql = "SELECT * FROM pelayanan_jasa";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" href="../assets/logo/logo1.png">
    <title>Data Pelayanan Jasa</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.html"><img src="../assets/logo/logo1.png" alt="Logo"
                style="height: 25px; width: 25px; margin-right: 10px;">Admin FixQuick</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">

        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="../profiladminweb.html">Profil</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="../php/logout.php">Keluar</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav"><br>

                        <a class="nav-link " href="../dashboard_admin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i>
                            </div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Warga</div>
                        <a class="nav-link " href="../warga/warga.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Warga
                        </a>

                        <div class="sb-sidenav-menu-heading">Perumahan</div>
                        <a class="nav-link" href="../perumahan/adminperum.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                            Perumahan
                        </a>
                        <a class="nav-link" href="../pembayaran/pembayaran.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                            Pembayaran
                        </a>

                        <div class="sb-sidenav-menu-heading">Pelayanan Jasa</div>
                        <a class="nav-link active" href="../pelayanan/pelayananjasa.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tools" style="color: white;"></i>
                            </div>
                            Pelayanan
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Login sebagai:</div>
                    <p class="nama admin"><?php echo htmlspecialchars($admin_name); ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Pelayanan Jasa Terdaftar</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html"
                                style="text-decoration: none; color: #6C757D;">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pelayanan</li>
                    </ol>
                    <div class="card mb-4">
                    </div>
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-table me-1"></i>
                                <span>Pelayanan Jasa</span>
                            </div>
                            <button class="btn btn-success" id="addPelayananJasaBtn" data-bs-toggle="modal"
                                data-bs-target="#addPelayananJasaModal">
                                <i class="fas fa-plus"></i> Tambah Pelayanan Jasa
                            </button>
                        </div>
                        <div class="card-body">
                        <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" id="searchName" class="form-control"
                                        placeholder="Cari berdasarkan nama">
                                </div>
                                <div class="col-md-4">
                                    <select id="filterStatus" class="form-select">
                                        <option value="">Semua Status</option>
                                        <?php
                                            // Query untuk mendapatkan status unik dari database
                                            $statusSql = "SELECT DISTINCT is_aktif FROM pelayanan_jasa";
                                            $statusResult = $conn->query($statusSql);

                                            if ($statusResult->num_rows > 0) {
                                                while ($row = $statusResult->fetch_assoc()) {
                                                    echo "<option value='" . $row['is_aktif'] . "'>" . ucfirst($row['is_aktif']) . "</option>";
                                                }
                                            }
                                            ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select id="filterPelayananJasa" class="form-select">
                                        <option value="">Semua Perumahan</option>
                                        <!-- Data perumahan dinamis -->
                                        <?php
                                            $housingSql = "SELECT DISTINCT kategori_layanan FROM pelayanan_jasa";
                                            $housingResult = $conn->query($housingSql);
                                            if ($housingResult->num_rows > 0) {
                                                while ($row = $housingResult->fetch_assoc()) {
                                                    echo "<option value='" . $row['kategori_layanan'] . "'>" . $row['kategori_layanan'] . "</option>";
                                                }
                                            }
                                            ?>
                                    </select>
                                </div>
                            </div>
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Kategori Layanan</th>
                                        <th>No Telepon</th>
                                        <th>Alamat</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Kategori Layanan</th>
                                        <th>No Telepon</th>
                                        <th>Alamat</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                        if ($result->num_rows > 0) {
                                            // Loop melalui setiap baris data
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $row['nama_penyedia_jasa'] . "</td>";
                                                echo "<td>" . $row['email'] . "</td>";
                                                echo "<td>" . $row['kategori_layanan'] . "</td>";
                                                echo "<td>" . $row['no_telepon'] . "</td>";
                                                echo "<td>" . $row['alamat'] . "</td>";
                                                echo "<td>" . ucfirst($row['is_aktif']) . "</td>";
                                                echo "<td class='d-flex justify-content-between align-items-center'>";
                                                echo "<button class='btn btn-primary btn-sm me-1 detail-btn' data-bs-toggle='modal' data-bs-target='#detailPelayananJasaModal' data-id='" . $row['id_pelayanan_jasa'] . "'>Detail</button>";
                                                echo "<button class='btn btn-warning btn-sm me-1 edit-btn' data-bs-toggle='modal' data-bs-target='#editPelayananJasaModal' 
                                                        data-id='" . $row['id_pelayanan_jasa'] . "' 
                                                        data-nama='" . $row['nama_penyedia_jasa'] . "' 
                                                        data-email='" . $row['email'] . "' 
                                                        data-kategorilayanan='" . $row['kategori_layanan'] . "' 
                                                        data-telepon='" . $row['no_telepon'] . "' 
                                                        data-alamat='" . $row['alamat'] . "' 
                                                        data-status='" . $row['is_aktif'] . "'>Edit</button>";
                                                echo "<button class='btn btn-danger btn-sm me-1 delete-btn' data-bs-toggle='modal' data-bs-target='#deletePelayananJasaModal' data-id='" . $row['id_pelayanan_jasa'] . "' data-nama='" . $row['nama_penyedia_jasa'] . "'>Hapus</button>";
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            // Jika tidak ada data, tampilkan pesan
                                            echo "<tr><td colspan='6' class='text-center'>Data tidak ditemukan</td></tr>";
                                        }
                                        ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Modal tambah pelayanan jasa-->
            <div class="modal fade" id="addPelayananJasaModal" tabindex="-1"
                aria-labelledby="addPelayananJasaModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="addPelayananJasaModalLabel">Tambah Pelayanan Jasa Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- Modal Body -->
                        <div class="modal-body">
                            <!-- Form atau Konten Modal -->
                            <form action="add_pelayanan.php" method="POST">
                                <div class="row mb-3">
                                    <label for="PenyediaJasaName" class="col-sm-3 col-form-label"
                                        style="font-weight: 400;">Nama Penyedia Jasa:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="PenyediaJasaName" name="PenyediaJasaName"
                                            placeholder="Masukkan nama Penyedia Jasa">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="PelayananJasaEmail" class="col-sm-3 col-form-label"
                                        style="font-weight: 400;">Email:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="PelayananJasaEmail" name="PelayananJasaEmail"
                                            placeholder="Masukkan Email PelayananJasa">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="KategoriPelayananJasa" class="col-sm-3 col-form-label"
                                        style="font-weight: 400;">Kategori Layanan:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="KategoriPelayananJasa" name="KategoriPelayananJasa"
                                            placeholder="Masukkan Kategori PelayananJasa">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="PelayananJasaNohp" class="col-sm-3 col-form-label"
                                        style="font-weight: 400;">No telepon:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="PelayananJasaNohp" name="PelayananJasaNohp"
                                            placeholder="Masukkan Nohp PelayananJasa">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="PelayananJasaAlamat" class="col-sm-3 col-form-label"
                                        style="font-weight: 400;">Alamat:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="PelayananJasaAlamat" name="PelayananJasaAlamat"
                                            placeholder="Masukkan alamat PelayananJasa">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="statusPelayananJasa" class="form-label">Status</label>
                                    <select id="statusPelayananJasa" name="statusPelayananJasa" class="form-select"
                                        required>
                                        <option value="aktif">Aktif</option>
                                        <option value="tidak aktif">Tidak Aktif</option>
                                    </select>
                                </div>
                                <!-- Tambahkan field lain jika diperlukan --> 
                                 <button type="submit" class="btn btn-primary" style="background-color: #009688; border: none;"> Simpan</button>
                            </form>
                        </div>
                        <!-- Modal Footer -->
                    </div>
                </div>
            </div>
            <!-- end tambah pelayanan jas -->

            <!-- Modal Detail Pelayanan Jasa -->
            <div class="modal fade" id="detailPelayananJasaModal" tabindex="-1" aria-labelledby="detailPelayananJasaModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailPelayananJasaModalLabel">Detail Perumahan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Isi modal akan diisi secara dinamis -->
                            <!-- <p>Memuat data...</p> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal Detail Pelayanan Jasa -->

            <!-- Modal Edit  PelayananJasa-->
            <div class="modal fade" id="editPelayananJasaModal" tabindex="-1" aria-labelledby="editPelayananJasaModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPelayananJasaLabel">Edit Data PelayananJasa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editPelayananJasaForm">
                                <!-- Form Data Edit PelayananJasa -->
                                <div class="mb-3">
                                    <label for="editNamaPenyedia" class="form-label">Nama Penyedia Jasa:</label>
                                    <input type="text" class="form-control" id="editNamaPenyedia">
                                </div>
                                <div class="mb-3">
                                    <label for="editPelayananJasaEmail" class="form-label">Email:</label>
                                    <input type="text" class="form-control" id="editPelayananJasaEmail">
                                </div>
                                <div class="mb-3">
                                    <label for="editPelayananJasaKategoriLayanan" class="form-label">Kategori:</label>
                                    <input type="text" class="form-control" id="editPelayananJasaKategoriLayanan">
                                </div>   
                                <div class="mb-3">
                                    <label for="editAlamatPelayananJasaAlamat" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="editAlamatPelayananJasa">
                                </div>
                                <div class="mb-3">
                                    <label for="editNoTeleponNohp" class="form-label">No Telepon</label>
                                    <input type="text" class="form-control" id="editPelayananJasaNoTelepon">
                                </div>
                                <div class="mb-3">
                                    <label for="editStatusPelayananJasa" class="form-label">Status</label>
                                    <select id="editStatusPelayananJasa" class="form-select">
                                        <option value="aktif">Aktif</option>
                                        <option value="tidak aktif">Nonaktif</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" id="updatePelayananJasaBtn">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end modal Edit PelayananJasa -->

            <!-- Modal Detail Pelayanan Jasa -->
            <div class="modal fade" id="detailPelayananJasaModal" tabindex="-1" aria-labelledby="detailPelayananJasaModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailPelayananJasaModalLabel">Detail Jasa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Nama:</strong> John Doe</p>
                            <p><strong>Perumahan:</strong> Perumahan A</p>
                            <p><strong>Alamat:</strong> Jl. Mawar</p>
                            <p><strong>No Telepon:</strong> 081234567890</p>
                            <p><strong>Status:</strong> Aktif</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal Detail Pelayanan Jasa -->

            <!-- Modal Hapus Pelayanan Jasa -->
            <div class="modal fade" id="deletePelayananJasaModal" tabindex="-1" aria-labelledby="deletePelayananJasaModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deletePelayananJasaModalLabel">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus data pelayanan jasa <strong>John Doe</strong>?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-danger" id="confirmDeletePelayananJasaBtn">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal Hapus Pelayanan Jasa -->

            <footer class="py-1 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- script detail data pelayanan jasa -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const detailButtons = document.querySelectorAll('.detail-btn');

        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');

                // Fetch data dari server menggunakan ID
                fetch(`detail_pelayanan.php?id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert('Data tidak ditemukan.');
                            return;
                        }

                        // Update isi modal dengan data yang didapat
                        const modalBody = document.querySelector(
                            '#detailPelayananJasaModal .modal-body');
                        modalBody.innerHTML = `
                        <p><strong>Nama Penyedia Jasa:</strong> ${data.nama_penyedia_jasa}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                        <p><strong>Kategori Layanan:</strong> ${data.kategori_layanan}</p>
                        <p><strong>No Telepon:</strong> ${data.no_telepon}</p>
                        <p><strong>Alamat:</strong> ${data.alamat}</p>
                        <p><strong>Status:</strong> ${data.is_aktif}</p>
                    `;
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
    </script>

    <!-- script hapus data pelayanan jasa -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll(".delete-btn");
        const confirmDeleteButton = document.getElementById("confirmDeletePelayananJasaBtn");
        const modalBody = document.querySelector("#deletePelayananJasaModal .modal-body p");
        let deleteId;

        deleteButtons.forEach(button => {
            button.addEventListener("click", function() {
                deleteId = this.getAttribute("data-id"); // Ambil ID perumahan
                const nama_penyedia_jasa = this.getAttribute("data-nama"); // Ambil nama pengguna
                modalBody.innerHTML =
                    `Apakah Anda yakin ingin menghapus data Pelayanan Jasa milik <strong>${nama_penyedia_jasa}</strong>?`;
            });
        });

        confirmDeleteButton.addEventListener("click", function() {
            if (deleteId) {
                window.location.href = "hapus_pelayanan.php?id=" + deleteId; // Kirim ID ke server
            }
        });
    });
    </script>

    <!-- script edit data pelayanan jasa -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
    const editButtons = document.querySelectorAll(".edit-btn");
    const editForm = document.getElementById("editPelayananJasaForm");
    const updateButton = document.getElementById("updatePelayananJasaBtn");

    let editId;

    editButtons.forEach(button => {
        button.addEventListener("click", function() {
            editId = this.getAttribute("data-id");
            document.getElementById("editNamaPenyedia").value = this.getAttribute("data-nama");
            document.getElementById("editPelayananJasaEmail").value = this.getAttribute("data-email");
            document.getElementById("editPelayananJasaKategoriLayanan").value = this.getAttribute("data-kategorilayanan");
            document.getElementById("editAlamatPelayananJasa").value = this.getAttribute("data-alamat");
            document.getElementById("editPelayananJasaNoTelepon").value = this.getAttribute("data-telepon");
            document.getElementById("editStatusPelayananJasa").value = this.getAttribute("data-status");
        });
    });

    updateButton.addEventListener("click", function() {
    const dataToUpdate = {};
    dataToUpdate.id = editId;

    // Ambil nilai dari form
    const namaPenyedia = document.getElementById("editNamaPenyedia").value.trim();
    const email = document.getElementById("editPelayananJasaEmail").value.trim();
    const kategori_layanan = document.getElementById("editPelayananJasaKategoriLayanan").value.trim();
    const alamat = document.getElementById("editAlamatPelayananJasa").value.trim();
    const telepon = document.getElementById("editPelayananJasaNoTelepon").value.trim();
    const status = document.getElementById("editStatusPelayananJasa").value.trim();

    // Cek hanya data yang diisi saja
    if (namaPenyedia) dataToUpdate.nama_penyedia_jasa = namaPenyedia;
    if (email) dataToUpdate.email = email;
    if (kategori_layanan) dataToUpdate.kategori_layanan = kategori_layanan;
    if (alamat) dataToUpdate.alamat = alamat;
    if (telepon) dataToUpdate.no_telepon = telepon;
    if (status) dataToUpdate.is_aktif = status;

    // Kirim data ke server melalui AJAX
    fetch("update_pelayanan.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(dataToUpdate)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Data berhasil diperbarui!");
            location.reload(); // Refresh halaman
        } else {
            alert("Gagal memperbarui data! " + (data.message || ""));
        }
    })
    .catch(error => console.error("Error:", error));
});

});

    </script>

    <!-- script pencarian filterPerumahan, filterStatus, searchName -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil elemen input dan select
        const searchName = document.getElementById("searchName");
        const filterStatus = document.getElementById("filterStatus");
        const filterPerumahan = document.getElementById("filterPelayananJasa");
        const tableRows = document.querySelectorAll("#datatablesSimple tbody tr");

        // Fungsi untuk memfilter tabel
        function filterTable() {
            const searchText = searchName.value.toLowerCase();
            const statusValue = filterStatus.value;
            const perumahanValue = filterPerumahan.value;

            tableRows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const status = row.cells[4].textContent.toLowerCase();
                const perumahan = row.cells[1].textContent.toLowerCase();

                // Cek apakah baris sesuai dengan filter
                const matchesSearch = name.includes(searchText);
                const matchesStatus = !statusValue || status === statusValue.toLowerCase();
                const matchesPerumahan = !perumahanValue || perumahan === perumahanValue.toLowerCase();

                // Tampilkan/hilangkan baris
                if (matchesSearch && matchesStatus && matchesPerumahan) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Tambahkan event listener untuk filter
        searchName.addEventListener("input", filterTable);
        filterStatus.addEventListener("change", filterTable);
        filterPerumahan.addEventListener("change", filterTable);
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
</body>

</html>