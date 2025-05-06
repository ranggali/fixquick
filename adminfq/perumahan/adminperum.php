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
$sql = "SELECT * FROM perumahan";
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
    <title>Data Perumahan</title>
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
            =
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
                        <a class="nav-link" href="../warga/warga.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Warga
                        </a>

                        <div class="sb-sidenav-menu-heading">Perumahan</div>
                        <a class="nav-link active" href="../perumahan/adminperum.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-home" style="color: white;"></i>
                            </div>
                            Perumahan
                        </a>
                        <a class="nav-link" href="../pembayaran/pembayaran.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                            Pembayaran
                        </a>

                        <div class="sb-sidenav-menu-heading">Pelayanan Jasa</div>
                        <a class="nav-link" href="../pelayanan/pelayananjasa.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
                            Pelayanan
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Login Sebagai:</div>
                    <p class="nama admin"><?php echo htmlspecialchars($admin_name); ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Perumahan Terdaftar</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html"
                                style="text-decoration: none; color: #6C757D;">Dashboard</a></li>
                        <li class="breadcrumb-item active">Perumahan</li>
                    </ol>
                    <div class="card mb-4">
                    </div>
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-table me-1"></i>
                                <span>Perumahan</span>
                            </div>
                            <button class="btn btn-success" id="addPerumahanBtn" data-bs-toggle="modal"
                                data-bs-target="#addPerumahanModal">
                                <i class="fas fa-plus"></i> Tambah Perumahan
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
                                            $statusSql = "SELECT DISTINCT is_aktif FROM perumahan";
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
                                    <select id="filterPerumahan" class="form-select">
                                        <option value="">Semua Perumahan</option>
                                        <!-- Data perumahan dinamis -->
                                        <?php
                                            $housingSql = "SELECT DISTINCT nama_perumahan FROM perumahan";
                                            $housingResult = $conn->query($housingSql);
                                            if ($housingResult->num_rows > 0) {
                                                while ($row = $housingResult->fetch_assoc()) {
                                                    echo "<option value='" . $row['nama_perumahan'] . "'>" . $row['nama_perumahan'] . "</option>";
                                                }
                                            }
                                            ?>
                                    </select>
                                </div>
                            </div>
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Nama Pengguna</th>
                                        <th>Perumahan</th>
                                        <th>Alamat</th>
                                        <th>No Telepon</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Nama Pengguna</th>
                                        <th>Perumahan</th>
                                        <th>Alamat</th>
                                        <th>No Telepon</th>
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
                                                echo "<td>" . $row['nama_pengguna'] . "</td>";
                                                echo "<td>" . $row['nama_perumahan'] . "</td>";
                                                echo "<td>" . $row['alamat'] . "</td>";
                                                echo "<td>" . $row['no_telepon'] . "</td>";
                                                echo "<td>" . ucfirst($row['is_aktif']) . "</td>";
                                                echo "<td class='d-flex justify-content-between align-items-center'>";
                                                echo "<button class='btn btn-info btn-sm me-1 detail-btn' data-bs-toggle='modal' data-bs-target='#detailPerumahanModal' data-id='" . $row['id_perumahan'] . "'>Detail</button>";
                                                echo "<button class='btn btn-warning btn-sm me-1 edit-btn' data-bs-toggle='modal' data-bs-target='#editPerumahanModal' 
                                                        data-id='" . $row['id_perumahan'] . "' 
                                                        data-nama='" . $row['nama_pengguna'] . "' 
                                                        data-perumahan='" . $row['nama_perumahan'] . "' 
                                                        data-alamat='" . $row['alamat'] . "' 
                                                        data-telepon='" . $row['no_telepon'] . "' 
                                                        data-status='" . $row['is_aktif'] . "'>Edit</button>";
                                                echo "<button class='btn btn-danger btn-sm me-1 delete-btn' data-bs-toggle='modal' data-bs-target='#deletePerumahanModal' data-id='" . $row['id_perumahan'] . "' data-nama='" . $row['nama_pengguna'] . "'>Hapus</button>";
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

            <!-- Modal Tambah Perumahan Baru -->
            <div class="modal fade" id="addPerumahanModal" tabindex="-1" aria-labelledby="addPerumahanModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="addPerumahanModalLabel">Tambah Perumahan Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- Modal Body -->
                        <div class="modal-body">
                            <!-- Form untuk mengirim data -->
                            <form action="add_perumahan.php" method="POST">
                                <div class="row mb-3">
                                    <label for="NamaPengguna" class="col-sm-3 col-form-label"
                                        style="font-weight: 400;">Nama Pengguna:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="NamaPengguna" name="NamaPengguna"
                                            placeholder="Masukkan nama Pengguna" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="Perumahan" class="col-sm-3 col-form-label"
                                        style="font-weight: 400;">Perumahan:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="Perumahan" name="Perumahan"
                                            placeholder="Masukkan Perumahan" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="AlamatPerum" class="col-sm-3 col-form-label"
                                        style="font-weight: 400;">Alamat:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="AlamatPerum" name="AlamatPerum"
                                            placeholder="Masukkan Alamat Perumahan" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="NoTeleponPerum" class="col-sm-3 col-form-label"
                                        style="font-weight: 400;">No Telepon:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="NoTeleponPerum"
                                            name="NoTeleponPerum" placeholder="Masukkan Telepon Perumahan" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="statusPerumahan" class="form-label">Status</label>
                                    <select id="statusPerumahan" name="statusPerumahan" class="form-select" required>
                                        <option value="Aktif">Aktif</option>
                                        <option value="tidak aktif">Tidak Aktif</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary"
                                    style="background-color: #009688; border: none;"> Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end modal tambah perumahan baru -->


            <!-- Modal Detail Perumahan -->
            <div class="modal fade" id="detailPerumahanModal" tabindex="-1" aria-labelledby="detailPerumahanModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailPerumahanModalLabel">Detail Perumahan</h5>
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
            <!-- End Modal Detail Perumahan -->

            <!-- Modal Edit  Perumahan-->
            <div class="modal fade" id="editPerumahanModal" tabindex="-1" aria-labelledby="editPerumahanModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPerumahanLabel">Edit Data Perumahan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editPerumahanForm">
                                <!-- Form Data Edit Perumahan -->
                                <div class="mb-3">
                                    <label for="editNamaPengguna" class="form-label">Nama Pengguna:</label>
                                    <input type="text" class="form-control" id="editNamaPengguna">
                                </div>
                                <div class="mb-3">
                                    <label for="editPerumahan" class="form-label">Perumahan:</label>
                                    <input type="text" class="form-control" id="editPerumahan">
                                </div>
                                <div class="mb-3">
                                    <label for="editAlamatPerumahan" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="editAlamatPerumahan">
                                </div>
                                <div class="mb-3">
                                    <label for="editNoTelepon" class="form-label">No Telepon</label>
                                    <input type="text" class="form-control" id="editNoTelepon">
                                </div>
                                <div class="mb-3">
                                    <label for="editStatusPerumahn" class="form-label">Status</label>
                                    <select id="editStatusPerumahan" class="form-select">
                                        <option value="aktif">Aktif</option>
                                        <option value="tidak aktif">Nonaktif</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" id="updatePerumahanBtn">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end modal Edit Perumahan -->

            <!-- Modal Hapus Perumahan -->
            <div class="modal fade" id="deletePerumahanModal" tabindex="-1" aria-labelledby="deletePerumahanModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deletePerumahanModalLabel">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus data Perumahan <strong>John Doe</strong>?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-danger" id="confirmDeletePerumahanBtn">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal Hapus Perumahan -->

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; FixQuick 2024</div>
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

    <!-- script detail data perumahan -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const detailButtons = document.querySelectorAll('.detail-btn');

        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');

                // Fetch data dari server menggunakan ID
                fetch(`detail_perumahan.php?id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert('Data tidak ditemukan.');
                            return;
                        }

                        // Update isi modal dengan data yang didapat
                        const modalBody = document.querySelector(
                            '#detailPerumahanModal .modal-body');
                        modalBody.innerHTML = `
                        <p><strong>Nama Pengguna:</strong> ${data.nama_pengguna}</p>
                        <p><strong>Perumahan:</strong> ${data.nama_perumahan}</p>
                        <p><strong>Alamat:</strong> ${data.alamat}</p>
                        <p><strong>No Telepon:</strong> ${data.no_telepon}</p>
                        <p><strong>Status:</strong> ${data.is_aktif}</p>
                    `;
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
    </script>

    <!-- script hapus data perumahan -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll(".delete-btn");
        const confirmDeleteButton = document.getElementById("confirmDeletePerumahanBtn");
        const modalBody = document.querySelector("#deletePerumahanModal .modal-body p");
        let deleteId;

        deleteButtons.forEach(button => {
            button.addEventListener("click", function() {
                deleteId = this.getAttribute("data-id"); // Ambil ID perumahan
                const namaPengguna = this.getAttribute("data-nama"); // Ambil nama pengguna
                modalBody.innerHTML =
                    `Apakah Anda yakin ingin menghapus data Perumahan milik <strong>${namaPengguna}</strong>?`;
            });
        });

        confirmDeleteButton.addEventListener("click", function() {
            if (deleteId) {
                window.location.href = "hapus_perumahan.php?id=" + deleteId; // Kirim ID ke server
            }
        });
    });
    </script>

    <!-- script edit data perumahan -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const editButtons = document.querySelectorAll(".edit-btn");
        const editForm = document.getElementById("editPerumahanForm");
        const updateButton = document.getElementById("updatePerumahanBtn");

        let editId; // Variabel untuk menyimpan ID yang sedang diedit

        editButtons.forEach(button => {
            button.addEventListener("click", function() {
                // Ambil data dari tombol
                editId = this.getAttribute("data-id");
                const namaPengguna = this.getAttribute("data-nama");
                const perumahan = this.getAttribute("data-perumahan");
                const alamat = this.getAttribute("data-alamat");
                const telepon = this.getAttribute("data-telepon");
                const status = this.getAttribute("data-status");

                // Isi form modal dengan data
                document.getElementById("editNamaPengguna").value = namaPengguna;
                document.getElementById("editPerumahan").value = perumahan;
                document.getElementById("editAlamatPerumahan").value = alamat;
                document.getElementById("editNoTelepon").value = telepon;
                document.getElementById("editStatusPerumahan").value = status;
            });
        });

        // Proses Update Data
        updateButton.addEventListener("click", function() {
            // Ambil data dari form
            const namaPengguna = document.getElementById("editNamaPengguna").value;
            const perumahan = document.getElementById("editPerumahan").value;
            const alamat = document.getElementById("editAlamatPerumahan").value;
            const telepon = document.getElementById("editNoTelepon").value;
            const status = document.getElementById("editStatusPerumahan").value;

            // Kirim data ke server melalui AJAX
            fetch("update_perumahan.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id: editId,
                        nama_pengguna: namaPengguna,
                        nama_perumahan: perumahan,
                        alamat: alamat,
                        no_telepon: telepon,
                        is_aktif: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Data berhasil diperbarui!");
                        location.reload(); // Refresh halaman
                    } else {
                        alert("Gagal memperbarui data!");
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
        const filterPerumahan = document.getElementById("filterPerumahan");
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