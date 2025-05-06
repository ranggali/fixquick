<?php
include('php/connection_db.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
    alert('Anda harus login sebagai admin.');
    window.location.href = '../FixQuickWebsite/login.php';
</script>";
    exit;
}
$id_perumahan = $_SESSION['nama_pengguna'];
$id_perumahan = $_SESSION['id_perumahan'];

// Query untuk menggabungkan data warga dan layanan
$sql = "
    SELECT 
        nama_warga AS nama, 
        'Warga' AS peran, 
        created_at 
    FROM warga 
    WHERE id_perumahan = ?
    UNION ALL
    SELECT 
        nama_penyedia_layanan AS nama, 
        'Layanan' AS peran, 
        created_at AS created_at 
    FROM layanan 
    WHERE id_perumahan = ?
    ORDER BY created_at DESC
";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Bind parameter dan eksekusi query
    mysqli_stmt_bind_param($stmt, "ss", $id_perumahan, $id_perumahan);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Fetch semua data
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
} else {
    $data = [];
}

// Query untuk menghitung jumlah warga sesuai id_perumahan
$sql_warga = "SELECT COUNT(*) AS total_warga FROM warga WHERE id_perumahan = ?";
$stmt = mysqli_prepare($conn, $sql_warga);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $id_perumahan);
    mysqli_stmt_execute($stmt);
    $result_warga = mysqli_stmt_get_result($stmt);
    if ($result_warga) {
        $data_warga = mysqli_fetch_assoc($result_warga);
        $total_warga = $data_warga['total_warga'];
    } else {
        $total_warga = 0;
    }
    mysqli_stmt_close($stmt);
} else {
    $total_warga = 0;
}

// Query to get the total number of Pelayanan
$sql_pelayanan = "SELECT COUNT(*) AS total_pelayanan FROM layanan WHERE id_perumahan = ?";
$stmt = mysqli_prepare($conn, $sql_pelayanan);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $id_perumahan);
    mysqli_stmt_execute($stmt);
    $result_pelayanan = mysqli_stmt_get_result($stmt);
    if ($result_pelayanan) {
        $data_pelayanan = mysqli_fetch_assoc($result_pelayanan);
        $total_pelayanan = $data_pelayanan['total_pelayanan'];
    } else {
        $total_pelayanan = 0;
    }
    mysqli_stmt_close($stmt);
} else {
    $total_pelayanan = 0;
}

// Query to get the total number of Pelayanan
$sql_pengajuan_pelayanan = "SELECT COUNT(*) AS total_pelayanan FROM pengajuan_pelayanan WHERE id_perumahan = ?";
$stmt = mysqli_prepare($conn, $sql_pengajuan_pelayanan);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $id_perumahan);
    mysqli_stmt_execute($stmt);
    $result_pengajuan_pelayanan = mysqli_stmt_get_result($stmt);
    if ($result_pengajuan_pelayanan) {
        $data_pengajuan_pelayanan = mysqli_fetch_assoc($result_pengajuan_pelayanan);
        $total_pengajuan_pelayanan = $data_pengajuan_pelayanan['total_pelayanan'];
    } else {
        $total_pengajuan_pelayanan = 0;
    }
    mysqli_stmt_close($stmt);
} else {
    $total_pengajuan_pelayanan = 0;
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
    <link rel="icon" href="assets/logo/logo1.png">
    <title>Admin Perumahan FixQuick</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php"><img src="assets/logo/logo1.png" alt="Logo"
                style="height: 25px; width: 25px; margin-right: 10px; ;">Admin Perumahan</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class='bx bx-log-out-circle' style="font-size: 24px;"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="../fqperumahan/homepageperumahan.php">Kembali</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav"><br><br>

                        <a class="nav-link active" style="font-weight:400;" href="index.php">
                            <div class="sb-nav-link-icon"><i class="bx bxs-dashboard" style="color: #009688;"></i>
                            </div>
                            Dashboard
                        </a>

                        <div class="sb-sidenav-menu-heading">Warga</div>
                        <a class="nav-link" style="font-weight:400;" href="warga/warga.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Manajemen Warga
                        </a>

                        <div class="sb-sidenav-menu-heading">Pelayanan</div>
                        <a class="nav-link" style="font-weight:400;" href="pelayanan/pelayananjasa.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
                            Manajemen layanan
                        </a>
                        <a class="nav-link" style="font-weight:400;" href="perumahan/adminperum.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                            Pengajuan Layanan
                        </a>
                        <div class="sb-sidenav-menu-heading">Permintaan</div>
                        <a class="nav-link" style="font-weight:400;" href="permintaanLayanan/permintaanLayanan.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                            Permintaan Layanan
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Masuk sebagai:</div>
                    <?php echo $_SESSION['nama_pengguna']; ?>
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
                            <div class="card mb-4" style="box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3" style="font-size: 2rem;"><?php echo $total_warga; ?></div> <!-- Jumlah Warga -->
                                    <div>Warga</div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small stretched-link" href="warga/warga.php" style="text-decoration: none; color: #009688;">Lihat..</a>
                                    <div class="small"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card mb-4" style="box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3" style="font-size: 2rem;"><?php echo $total_pelayanan; ?></div> <!-- Jumlah Pelayanan -->
                                    <div>Layanan</div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small stretched-link" href="pelayanan/pelayananjasa.php" style="text-decoration: none; color: #009688;">Lihat..</a>
                                    <div class="small"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card mb-4" style="box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3" style="font-size: 2rem;"><?php echo $total_pengajuan_pelayanan; ?></div> <!-- Jumlah Pelayanan -->
                                    <div>Pengajuan Pelayanan</div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small stretched-link" href="perumahan/adminiperum.php" style="text-decoration: none; color: #009688;">Lihat..</a>
                                    <div class="small"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4" style="box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Daftar Pengguna Terbaru Pada Perumahan
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Peran</th>
                                        <th>Tanggal Daftar</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Peran</th>
                                        <th>Tanggal Daftar</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    if (!empty($data)) {
                                        $no = 1;
                                        foreach ($data as $row) {
                                            echo "<tr>
                                                    <td>{$no}</td>
                                                    <td>{$row['nama']}</td>
                                                    <td>{$row['peran']}</td>
                                                    <td>{$row['created_at']}</td>
                                                </tr>";
                                            $no++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>Tidak ada data tersedia.</td></tr>";
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
                        <div class="text-muted">Copyright &copy; FixQuick 2024-<span id="year"></span></div>
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
    <!-- script tahunan -->
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
    <!-- end script tahunan -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>