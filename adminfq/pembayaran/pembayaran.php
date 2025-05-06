<?php
// Include database connection
include('../php/connection_db.php');

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

// Query gabungan untuk mengambil data pembayaran
$query = "
   SELECT 
        w.nama_warga AS nama_warga,
        l.kategori_jasa AS kategori_layanan,
        pl.nomor_invoice AS nomor_invoice,
        pl.tanggal_pesanan AS tanggal,
        w.no_telepon AS no_telepon,
        pl.status_pembayaran AS status,
        pl.total_pembayaran AS jumlah
    FROM 
        pesanan_layanan pl
    INNER JOIN 
        warga w ON pl.id_warga = w.id_warga
    INNER JOIN 
        layanan l ON pl.id_layanan = l.id_layanan
    INNER JOIN 
        perumahan p ON w.id_perumahan = p.id_perumahan
";
$result = $conn->query($query);

if (!$result) {
    die("Query error: " . $conn->error);
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
    <title>Data Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="../index.html"><img src="../assets/logo/logo1.png" alt="Logo"
                style="height: 25px; width: 25px; margin-right: 10px;">Admin FixQuick</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
                <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                    =
                </form>
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
                        <a class="nav-link" href="../dashboard_admin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Warga</div>
                        <a class="nav-link" href="../warga/warga.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Warga
                        </a>
                        <div class="sb-sidenav-menu-heading">Perumahan</div>
                        <a class="nav-link" href="../perumahan/adminperum.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i>
                            </div>
                            Perumahan
                        </a>
                        <a class="nav-link active" href="pembayaran.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave" style="color: white;"></i></div>
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
                    <h1 class="mt-4">Data Pembayaran</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html" style="text-decoration: none; color: #6C757D;">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pembayaran</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-table me-1"></i> Daftar Pembayaran Langganan Perumahan</span>
                            <div>
                                <!-- Tombol untuk membuka modal -->
                                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#kelolaMetodeModal">
                                    <i class="fas fa-cog"></i> Kelola Metode Pembayaran
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <button id="downloadCSV" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Unduh Laporan CSV
                                </button>
                                <button id="downloadPDF" class="btn btn-secondary">
                                    <i class="fas fa-download"></i> Unduh Laporan PDF
                                </button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-group me-2">
                                    <label for="filterStatus" class="form-label">Status:</label>
                                    <select id="filterStatus" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="Lunas">Lunas</option>
                                        <option value="Belum Lunas">Belum Lunas</option>
                                    </select>
                                </div>
                                <div class="form-group me-2">
                                    <label for="filterDueDate" class="form-label">Jatuh Tempo:</label>
                                    <input type="date" id="filterDueDate" class="form-control">
                                </div>
                                <button class="btn btn-warning"><i class="fas fa-bell"></i> Kirim Notifikasi</button>
                            </div>
                            <table id="datatablesSimple" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Warga</th>
                                        <th>Kategori Layanan</th>
                                        <th>Nomor Invoice</th>
                                        <th>Tanggal</th>
                                        <th>No Telepon</th>
                                        <th>Status</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Nama Warga</th>
                                        <th>Kategori Layanan</th>
                                        <th>Nomor Invoice</th>
                                        <th>Tanggal</th>
                                        <th>No Telepon</th>
                                        <th>Status</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                <?php
                                    // Loop melalui hasil query dan tampilkan data
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['nama_warga']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['kategori_layanan']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['nomor_invoice']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['no_telepon']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                        echo "<td>Rp" . number_format($row['jumlah'], 0, ',', '.') . "</td>";
                                        echo "<td><button class='btn btn-danger' onclick='deletePayment()'>Hapus</button></td>";
                                        echo "</tr>";
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div><br><br>
                    <!-- Pembayaran Langganan Perumahan -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-table me-1"></i> Daftar Pembayaran Langganan Perumahan</span>
                            <div>
                                <!-- Tombol untuk membuka modal -->
                                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#kelolaMetodeModal">
                                    <i class="fas fa-cog"></i> Kelola Metode Pembayaran
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <button id="downloadCSV" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Unduh Laporan CSV
                                </button>
                                <button id="downloadPDF" class="btn btn-secondary">
                                    <i class="fas fa-download"></i> Unduh Laporan PDF
                                </button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-group me-2">
                                    <label for="filterStatus" class="form-label">Status:</label>
                                    <select id="filterStatus" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="Lunas">Lunas</option>
                                        <option value="Belum Lunas">Belum Lunas</option>
                                    </select>
                                </div>
                                <div class="form-group me-2">
                                    <label for="filterDueDate" class="form-label">Jatuh Tempo:</label>
                                    <input type="date" id="filterDueDate" class="form-control">
                                </div>
                                <button class="btn btn-warning"><i class="fas fa-bell"></i> Kirim Notifikasi</button>
                            </div>
                            <table id="datatablesSimple" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Perumahan</th>
                                        <th>Nomor Invoice</th>
                                        <th>Status</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include('../php/connection_db.php');

                                    $query = "
                                        SELECT 
                                            pl.id_pembayaran, 
                                            p.nama_perumahan AS perumahan, 
                                            pl.nomor_invoice, 
                                            pl.status_pembayaran AS status, 
                                            pl.jatuh_tempo, 
                                            pl.total_pembayaran AS jumlah
                                        FROM pembayaran_langganan pl
                                        JOIN perumahan p ON pl.id_perumahan = p.id_perumahan
                                    ";
                                    $result = $conn->query($query);

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['perumahan']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nomor_invoice']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['jatuh_tempo']) . "</td>";
                                            echo "<td>Rp" . number_format($row['jumlah'], 0, ',', '.') . "</td>";
                                            echo "<td>
                                                    <button class='btn btn-danger' onclick='deleteLangganan(" . $row['id_pembayaran'] . ")'>Hapus</button>
                                                </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>Tidak ada data pembayaran langganan ditemukan.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Modal Kelola Metode Pembayaran -->
            <div class="modal fade" id="kelolaMetodeModal" tabindex="-1" aria-labelledby="kelolaMetodeLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="kelolaMetodeLabel">Kelola Metode Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Tabel Metode Pembayaran -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Metode</th>
                                        <th>Kode</th>
                                        <!-- <th>Deskripsi</th> -->
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM metode_pembayaran";
                                    $result = $conn->query($query);
                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id_metode'] . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nama_metode']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['kode_metode']) . "</td>";
                                            // echo "<td>" . htmlspecialchars($row['deskripsi']) . "</td>";
                                            echo "<td>" . ($row['is_aktif'] ? 'Aktif' : 'Tidak Aktif') . "</td>";
                                            echo "<td>
                                                <button class='btn btn-primary btn-sm' onclick='editMetode(" . $row['id_metode'] . ")'>Edit</button>
                                                <button class='btn btn-danger btn-sm' onclick='deleteMetode(" . $row['id_metode'] . ")'>Hapus</button>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>Tidak ada metode pembayaran</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <!-- Form Tambah Metode -->
                            <h5>Tambah Metode Pembayaran</h5>
                            <form action="tambah_metode.php" method="POST">
                                <div class="mb-3">
                                    <label for="nama_metode" class="form-label">Nama Metode</label>
                                    <input type="text" class="form-control" id="nama_metode" name="nama_metode" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kode_metode" class="form-label">Kode Metode</label>
                                    <input type="text" class="form-control" id="kode_metode" name="kode_metode" required>
                                </div>
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">Tambah Metode</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

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
    // Fungsi untuk konfirmasi dan menghapus pembayaran
    function deletePayment(perumahan) {
        if (confirm("Apakah Anda yakin ingin menghapus data pembayaran untuk " + perumahan + "?")) {
            window.location.href = "delete_payment.php?perumahan=" + encodeURIComponent(perumahan);
        }
    }
    </script>
    <!-- edit metode -->
    <script>
        function editMetode(idMetode) {
    // Redirect ke halaman edit dengan ID metode
    window.location.href = "edit_metode.php?id=" + idMetode;
    }
    </script>
    <!-- hapus metode -->
    <script>
    function deleteMetode(idMetode) {
        if (confirm("Apakah Anda yakin ingin menghapus metode pembayaran ini?")) {
            window.location.href = "hapus_metode.php?id=" + idMetode;
        }
    }
    </script>
    <script>
        function deleteLangganan(idPembayaran) {
        if (confirm("Apakah Anda yakin ingin menghapus pembayaran ini?")) {
            // Melakukan request ke server untuk menghapus data pembayaran
            window.location.href = "hapus_langganan.php?id_pembayaran=" + idPembayaran;
        }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <script src="../js/unduhfile.js"></script>
</body>
</html>
