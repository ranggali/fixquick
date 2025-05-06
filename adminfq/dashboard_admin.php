<?php
// Include database connection
include('php/connection_db.php');

session_start();

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['id_admin'])) {
    session_regenerate_id(true);
    // Jika tidak ada session id_admin, redirect ke halaman login
    header('Location: login.php');
    exit();
}

// Jika session id_admin ada, ambil data admin
$admin_name = $_SESSION['nama_admin'];

// Query to get the total number of Warga
$sql_warga = "SELECT COUNT(*) AS total_warga FROM warga";
$result_warga = mysqli_query($conn, $sql_warga);
if ($result_warga) {
    $data_warga = mysqli_fetch_assoc($result_warga);
    $total_warga = $data_warga['total_warga'];
} else {
    $total_warga = 0;
}

// Query to get the total number of Perumahan
$sql_perumahan = "SELECT COUNT(*) AS total_perumahan FROM perumahan";
$result_perumahan = mysqli_query($conn, $sql_perumahan);
if ($result_perumahan) {
    $data_perumahan = mysqli_fetch_assoc($result_perumahan);
    $total_perumahan = $data_perumahan['total_perumahan'];
} else {
    $total_perumahan = 0;
}

// Query to get the total number of Pelayanan
$sql_pelayanan = "SELECT COUNT(*) AS total_pelayanan FROM pelayanan_jasa";
$result_pelayanan = mysqli_query($conn, $sql_pelayanan);
if ($result_pelayanan) {
    $data_pelayanan = mysqli_fetch_assoc($result_pelayanan);
    $total_pelayanan = $data_pelayanan['total_pelayanan'];
} else {
    $total_pelayanan = 0;
}

// Query untuk mendapatkan data pengguna terbaru dari 3 tabel dengan kolom Peran
$sql_latest_users = "
    (SELECT nama_warga AS nama, no_telepon, created_at, 'Warga' AS peran FROM warga)
    UNION
    (SELECT nama_perumahan AS nama, no_telepon, created_at, 'Perumahan' AS peran FROM perumahan)
    UNION
    (SELECT nama_penyedia_jasa AS nama, no_telepon, created_at, 'Pelayanan Jasa' AS peran FROM pelayanan_jasa)
    ORDER BY created_at DESC
    LIMIT 5";

// Eksekusi query
$result_latest_users = mysqli_query($conn, $sql_latest_users);

// Periksa apakah query berhasil
if ($result_latest_users === false) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

// Close database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" href="assets/logo/logo1.png">
    <title>Admin FixQuick</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html"><img src="assets/logo/logo1.png" alt="Logo"
                style="height: 25px; width: 25px; margin-right: 10px;">Admin FixQuick</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <p class="nama admin"><?php echo htmlspecialchars($admin_name); ?></p>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="profiladminweb.php">Profil</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="php/logout.php">Keluar</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav"><br>

                        <a class="nav-link active" href="dashboard_admin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt" style="color: white;"></i>
                            </div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Warga</div>
                        <a class="nav-link" href="warga/warga.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Warga
                        </a>

                        <div class="sb-sidenav-menu-heading">Perumahan</div>
                        <a class="nav-link" href="perumahan/adminperum.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                            Perumahan
                        </a>

                        <a class="nav-link" href="pembayaran/pembayaran.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                            Pembayaran
                        </a>

                        <div class="sb-sidenav-menu-heading">Pelayanan Jasa</div>
                        <a class="nav-link" href="pelayanan/pelayananjasa.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
                            Pelayanan
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Login sebagai:</div>
                    <?php echo htmlspecialchars($admin_name); ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card mb-4">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3" style="font-size: 2rem;"><?php echo $total_warga; ?></div>
                                    <div>Warga</div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small stretched-link" href="warga/warga.php"
                                        style="text-decoration: none; color:#009688">Lihat..</a>
                                    <div class="small"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card mb-4">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3" style="font-size: 2rem;"><?php echo $total_perumahan; ?></div>
                                    <div>Perumahan</div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small stretched-link" href="perumahan/adminperum.php"
                                        style="text-decoration: none; color: #009688;">Lihat..</a>
                                    <div class="small"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card mb-4">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3" style="font-size: 2rem;"><?php echo $total_pelayanan; ?></div>
                                    <div>Pelayanan</div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small stretched-link" href="pelayanan/pelayananjasa.php"
                                        style="text-decoration: none; color:#009688">Lihat..</a>
                                    <div class="small"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Daftar Pengguna Terbaru
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Peran</th>
                                        <th>No Telepon</th>
                                        <th>Tanggal Daftar</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Peran</th>
                                        <th>No Telepon</th>
                                        <th>Tanggal Daftar</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result_latest_users)) {
                                        echo "<tr>
                                                <td>" . $no++ . "</td>
                                                <td>" . (!empty($row['nama']) ? htmlspecialchars($row['nama']) : "<i>Data kosong!</i>") . "</td>
                                                <td>" . (!empty($row['peran']) ? htmlspecialchars($row['peran']) : "<i>Data kosong!</i>") . "</td>
                                                <td>" . (!empty($row['no_telepon']) ? htmlspecialchars($row['no_telepon']) : "<i>Data kosong!</i>") . "</td>
                                                <td>" . (!empty($row['created_at']) ? htmlspecialchars($row['created_at']) : "<i>Data kosong!</i>") . "</td>
                                            </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
</html>