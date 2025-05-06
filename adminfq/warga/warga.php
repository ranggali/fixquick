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

// Mengecek tindakan yang diminta
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Tindakan untuk mengambil data warga
    if ($action === 'get_data') {
        // Ambil data warga dengan nama perumahan berdasarkan id_perumahan
        $query = "
            SELECT warga.*, perumahan.nama_perumahan 
            FROM warga 
            LEFT JOIN perumahan ON warga.id_perumahan = perumahan.id_perumahan
        ";
        $result = mysqli_query($conn, $query);
        $data = [];
    
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    
        echo json_encode($data);
        exit;
    }     
    // Tindakan untuk menghapus warga
    elseif ($action === 'delete') {
        // Hapus warga berdasarkan ID
        $id_warga = intval($_GET['id']);
        $stmt = $conn->prepare("DELETE FROM warga WHERE id_warga = ?");
        $stmt->bind_param("i", $id_warga);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Warga berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus warga']);
        }
        $stmt->close();
        $conn->close();
        exit;
    } 
    // Tindakan untuk menambahkan warga
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add_warga') {
        // Ambil data yang dikirim
        $data = json_decode(file_get_contents("php://input"), true);
        $nama_warga = $data['nama_warga'];
        $no_telepon = $data['no_telepon'];
        $is_aktif = $data['is_aktif'];

        // Siapkan query untuk memasukkan data ke dalam database
        $query = "INSERT INTO warga (nama_warga, no_telepon, is_aktif, alamat, foto_profil, kata_sandi, created_at, updated_at) 
                  VALUES (?, ?, ?, '', '', '', NOW(), NOW())";
        
        // Persiapkan dan jalankan query
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("sss", $nama_warga, $no_telepon, $is_aktif);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menambahkan warga.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Kesalahan pada query.']);
        }
        $conn->close();
        exit;
    }
// } else {
//     echo json_encode(['success' => false, 'message' => 'Tindakan tidak ditemukan']);
//     $conn->close();
}
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
    <title>Data Warga</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html"><img src="../assets/logo/logo1.png" alt="Logo"
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
                        <a class="nav-link active" href="../warga/warga.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users" style="color: white;"></i>
                            </div>
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
                        <a class="nav-link" href="../pelayanan/pelayananjasa.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
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
                    <h1 class="mt-4">Data Warga Terdaftar</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html"
                                style="text-decoration: none; color: #6C757D;">Dashboard</a></li>
                        <li class="breadcrumb-item active">Warga</li>
                    </ol>
                    <div class="card mb-4">
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-table me-1"></i>
                                <span>Warga</span>
                            </div>
                            <!-- Tombol Tambah Warga -->
                            <button class="btn btn-success" id="addWargaBtn" data-bs-toggle="modal"
                                data-bs-target="#addWargaModal">
                                <i class="fas fa-plus"></i> Tambah Warga
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Filter dan Pencarian -->
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
                                            $statusSql = "SELECT DISTINCT is_aktif FROM warga";
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
                                    <select id="filterWarga" class="form-select">
                                        <option value="">Semua Warga Perumahan</option>
                                        <!-- Data perumahan dinamis -->
                                        <?php
                                            $housingSql = "SELECT DISTINCT id_perumahan FROM warga";
                                            $housingResult = $conn->query($housingSql);
                                            if ($housingResult->num_rows > 0) {
                                                while ($row = $housingResult->fetch_assoc()) {
                                                    echo "<option value='" . $row['id_perumahan'] . "'>" . $row['id_perumahan'] . "</option>";
                                                }
                                            }
                                            ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Tabel Data Warga -->
                            <table id="datatablesSimple" class="table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Perumahan</th>
                                        <th>Alamat</th>
                                        <th>No Telepon</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="wargaTableBody">
                                    <!-- Data Warga akan di-load melalui PHP atau JavaScript -->
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </main>

            <!-- Modal Tambah Warga baru -->
            <div class="modal fade" id="addWargaModal" tabindex="-1" aria-labelledby="addWargaModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addWargaModalLabel">Tambah Warga Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addWargaForm">
                                <div class="mb-3">
                                    <label for="namaWarga" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="namaWarga" name="namaWarga"
                                        placeholder="Masukkan nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="noTelepon" class="form-label">No Telepon</label>
                                    <input type="text" class="form-control" id="noTelepon" name="noTelepon"
                                        placeholder="Masukkan no telepon" required>
                                </div>
                                <div class="mb-3">
                                    <label for="statusWarga" class="form-label">Status</label>
                                    <select id="statusWarga" name="statusWarga" class="form-select" required>
                                        <option value="Aktif">Aktif</option>
                                        <option value="Tidak Aktif">Nonaktif</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" id="saveWargaBtn">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Modal Tambah Warga Baru-->

            <!-- Modal edit  warga-->
            <div class="modal fade" id="editWargaModal" tabindex="-1" aria-labelledby="editWargaModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editWargaModalLabel">Edit Data Warga</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editWargaForm">
                                <!-- Form Data Edit Warga -->
                                <div class="mb-3">
                                    <label for="editNamaWarga" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="editNamaWarga">
                                </div>
                                <div class="mb-3">
                                    <label for="editAlamatWarga" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="editAlamatWarga">
                                </div>
                                <div class="mb-3">
                                    <label for="editStatusWarga" class="form-label">Status</label>
                                    <select id="editStatusWarga" class="form-select">
                                        <option value="Aktif">Aktif</option>
                                        <option value="Tidak Aktif">Nonaktif</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" id="updateWargaBtn">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end modal edit warga -->

            <!-- Modal Detail Warga -->
            <div class="modal fade" id="detailWargaModal" tabindex="-1" aria-labelledby="detailWargaModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailWargaModalLabel">Detail Warga</h5>
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
            <!-- End Modal Detail Warga -->

            <!-- Modal Hapus Warga -->
            <div class="modal fade" id="deleteWargaModal" tabindex="-1" aria-labelledby="deleteWargaModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteWargaModalLabel">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus data warga <strong>John Doe</strong>?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteWargaBtn">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal Hapus Warga -->



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
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Fetch data from the server
        fetch('warga.php?action=get_data')
            .then(response => response.json())
            .then(data => {
                const wargaTableBody = document.getElementById("wargaTableBody");

                // Check if the table body exists
                if (!wargaTableBody) {
                    console.error('Table body element not found!');
                    return; // Exit if the table body is not found
                }

                // Clear existing rows
                wargaTableBody.innerHTML = '';

                if (data.length === 0) {
                    wargaTableBody.innerHTML =
                        '<tr><td colspan="6" class="text-center">Tidak ada data warga.</td></tr>';
                } else {
                    data.forEach(warga => {
                        const statusClass = warga.is_aktif === 'aktif' ? 'statusactive' :
                            'statusTidakactive';
                        const newRow = `
                        <tr>
                            <td>${warga.nama_warga ? warga.nama_warga : "<i>Data kosong!</i>"}</td>
                            <td>${warga.nama_perumahan ? warga.nama_perumahan : "<i>Data kosong!</i>"}</td>
                            <td>${warga.alamat ? warga.alamat : "<i>Data kosong!</i>"}</td>
                            <td>${warga.no_telepon ? warga.no_telepon : "<i>Data kosong!</i>"}</td>
                            <td class="${statusClass}">${warga.is_aktif === 'aktif' ? 'Aktif' : 'Tidak Aktif'}</td>
                            <td class="d-flex justify-content-between">
                                <button class="btn btn-info btn-sm me-1 detail-btn" 
                                    data-id="${warga.id_warga}" 
                                    data-nama="${warga.nama_warga}" 
                                    data-perumahan="${warga.nama_perumahan}" 
                                    data-alamat="${warga.alamat}" 
                                    data-no="${warga.no_telepon}" 
                                    data-status="${warga.is_aktif}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#detailWargaModal" 
                                    style="color: white;">
                                    Detail
                                </button>
                                <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editWargaModal">
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-sm" 
                                data-id="${warga.id_warga}" 
                                data-nama="${warga.nama_warga}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteWargaModal">
                                Hapus
                            </button>
                            </td>
                        </tr>`;
                        wargaTableBody.insertAdjacentHTML('beforeend', newRow);
                    });
                }

                // Now initialize Simple DataTables after populating the data
                const datatablesSimple = document.getElementById('datatablesSimple');
                if (datatablesSimple) {
                    new simpleDatatables.DataTable(datatablesSimple);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
    });
    </script>
    <!-- tambah warga -->
    <script>
    document.getElementById('saveWargaBtn').addEventListener('click', function() {
        const namaWarga = document.getElementById('namaWarga').value;
        const noTelepon = document.getElementById('noTelepon').value;
        const statusWarga = document.getElementById('statusWarga').value;

        // Kirim data ke server menggunakan Fetch API
        fetch('warga.php?action=add_warga', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    nama_warga: namaWarga,
                    no_telepon: noTelepon,
                    is_aktif: statusWarga === 'Aktif' ? 'aktif' : 'tidak aktif',
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Warga berhasil ditambahkan!');
                    window.location.reload(); // Reload halaman untuk memperbarui tabel
                } else {
                    alert('Terjadi kesalahan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
    });
    </script>

    <!--  detail script -->
    <script>
    document.addEventListener("click", function(event) {
        if (event.target.classList.contains("detail-btn")) {
            const modal = document.getElementById("detailWargaModal");
            const nama = event.target.getAttribute("data-nama");
            const perumahan = event.target.getAttribute("data-perumahan");
            const alamat = event.target.getAttribute("data-alamat");
            const no = event.target.getAttribute("data-no");
            const status = event.target.getAttribute("data-status");

            // Isi data ke dalam modal
            modal.querySelector(".modal-body").innerHTML = `
            <p><strong>Nama:</strong> ${nama ? nama : "<i>Data kosong!</i>"}</p>
            <p><strong>Perumahan:</strong> ${perumahan ? perumahan : "<i>Data kosong!</i>"}</p>
            <p><strong>Alamat:</strong> ${alamat ? alamat : "<i>Data kosong!</i>"}</p>
            <p><strong>No Telepon:</strong> ${no ? no : "<i>Data kosong!</i>"}</p>
            <p><strong>Status:</strong> ${status === 'aktif' ? 'Aktif' : 'Tidak Aktif'}</p>
        `;
        }
    });
    </script>
    <!-- hapus script -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let wargaIdToDelete = null; // Untuk menyimpan ID warga yang akan dihapus
        let wargaNameToDelete = ''; // Untuk menyimpan nama warga yang akan dihapus

        // Event listener untuk tombol hapus
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("btn-danger")) {
                const wargaId = event.target.getAttribute("data-id");
                const wargaName = event.target.getAttribute("data-nama");

                wargaIdToDelete = wargaId; // Simpan ID warga
                wargaNameToDelete = wargaName; // Simpan nama warga

                // Update modal dengan nama warga yang akan dihapus
                const deleteModalBody = document.querySelector("#deleteWargaModal .modal-body");
                deleteModalBody.innerHTML = `
                    <p>Apakah Anda yakin ingin menghapus data warga <strong>${wargaName}</strong>?</p>
                `;
            }
        });

        // Event listener untuk tombol konfirmasi hapus
        document.getElementById("confirmDeleteWargaBtn").addEventListener("click", function() {
            if (wargaIdToDelete) {
                // Lakukan request DELETE ke server
                fetch(`warga.php?action=delete&id=${wargaIdToDelete}`, {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert(`Warga ${wargaNameToDelete} berhasil dihapus.`);
                            // Reload data warga setelah berhasil menghapus
                            location.reload();
                        } else {
                            alert(`Gagal menghapus warga: ${result.message}`);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    });
            }
        });
    });
    </script>

    <!-- script pencarian filterPerumahan, filterStatus, searchName -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil elemen input dan select
        const searchName = document.getElementById("searchName");
        const filterStatus = document.getElementById("filterStatus");
        const filterPerumahan = document.getElementById("filterWarga");
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
    <!-- end js search box -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <!-- <script src="../js/datatables-simple-demo.js"></script> -->
</body>
</html>