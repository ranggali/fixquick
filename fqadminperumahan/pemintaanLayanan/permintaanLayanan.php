<?php
include('../php/connection_db.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
    alert('Anda harus login sebagai admin.');
    window.location.href = '../FixQuickWebsite/login.php';
</script>";
    exit;
}

$id_perumahan = $_SESSION['id_perumahan'];

// Query untuk mendapatkan data dari tabel permintaan_layanan
$sql = "
    SELECT 
        id_permintaan,
        id_warga,
        id_perumahan,
        nama_warga,
        no_telepon,
        alamat,
        kategori,
        deskripsi_permintaan,
        created_at,
        updated_at
    FROM permintaan_layanan
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php"><img src="../assets/logo/logo1.png" alt="Logo"
                style="height: 25px; width: 25px; margin-right: 10px;">Admin Perumahan</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">

        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class='bx bx-log-out-circle' style="font-size: 24px;"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <!-- <li><a class="dropdown-item" href="../profiladminweb.html">Profil</a></li> -->
                    <!-- <li>
                        <hr class="dropdown-divider" />
                    </li> -->
                    <li><a class="dropdown-item" href="../../fqperumahan/homepageperumahan.php">Kembali</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav"><br><br>

                        <a class="nav-link" style="font-weight:400;" href="../index.php">
                            <div class="sb-nav-link-icon"><i class="bx bxs-dashboard"></i>
                            </div>
                            Dashboard
                        </a>

                        <div class="sb-sidenav-menu-heading">Warga</div>
                        <a class="nav-link" style="font-weight:400;" href="../warga/warga.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Manajemen Warga
                        </a>

                        <div class="sb-sidenav-menu-heading">Pelayanan</div>
                        <a class="nav-link" style="font-weight:400;" href="../pelayanan/pelayananjasa.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
                            Manajemen layanan
                        </a>
                        <a class="nav-link" style="font-weight:400;" href="../perumahan/adminperum.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                            Pengajuan Layanan
                        </a>
                        <div class="sb-sidenav-menu-heading">Permintaan</div>
                        <a class="nav-link active" style="font-weight:400;" href="#">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list" style="color: #009688;"></i></div>
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
                    <h1 class="mt-4">Data Permintaan Layanan dari Warga</h1>
                    <ol class="breadcrumb mb-4">
                        <!-- <li class="breadcrumb-item"><a href="index.html"
                                style="text-decoration: none; color: #6C757D;">Dashboard</a></li> -->
                        <li class="breadcrumb-item active">Manajemen Permintaan Layanan</li>
                    </ol>
                    <div class="card mb-4">
                    </div>
                    <div class="card mb-4" style="box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-table me-1"></i>
                                <span>Permintaan Layanan</span>
                            </div>
                            <button class="btn btn-success" id="addWargaBtn" data-bs-toggle="modal"
                                data-bs-target="#addWargaModal">
                                <i class="fas fa-plus"></i> Tambah Warga
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Telepon</th>
                                        <th>Alamat</th>
                                        <th>Kategori</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Telepon</th>
                                        <th>Alamat</th>
                                        <th>Kategori</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    if (!empty($data)) {
                                        foreach ($data as $permintaan) {
                                            // Gunakan null coalescing untuk menghindari warning dan tampilkan fallback text
                                            $nama = !empty($permintaan['nama_warga']) ? htmlspecialchars($permintaan['nama_warga']) : '<span class="fst-italic text-muted">Data Kosong</span>';
                                            $noTelepon = !empty($permintaan['no_telepon']) ? htmlspecialchars($permintaan['no_telepon']) : '<span class="fst-italic text-muted">Data Kosong</span>';
                                            $alamat = !empty($permintaan['alamat']) ? htmlspecialchars($permintaan['alamat']) : '<span class="fst-italic text-muted">Data Kosong</span>';
                                            $kategori = !empty($permintaan['kategori']) ? htmlspecialchars($permintaan['kategori']) : '<span class="fst-italic text-muted">Data Kosong</span>';
                                            $deskripsi = !empty($permintaan['deskripsi_permintaan']) ? htmlspecialchars($permintaan['deskripsi_permintaan']) : '<span class="fst-italic text-muted">Data Kosong</span>';
                                    ?>
                                            <tr>
                                                <td><?= $nama ?></td>
                                                <td><?= $noTelepon ?></td>
                                                <td><?= $alamat ?></td>
                                                <td><?= $kategori ?></td>
                                                <td><?= $deskripsi ?></td>
                                                <td class="d-flex justify-content-between align-items-center">
                                                    <!-- Sesuaikan ID permintaan untuk aksi -->
                                                    <button class="btn btn-primary me-2" onclick="tampilkanDetailPermintaan(<?= $permintaan['id_permintaan'] ?>)">
                                                        <i class="fas fa-address-book" style="font-size: 15px; color: white;"></i>
                                                    </button>
                                                    <button class="btn btn-danger" onclick="hapusPermintaan(<?= $permintaan['id_permintaan'] ?>, '<?= htmlspecialchars($permintaan['nama_warga'], ENT_QUOTES, 'UTF-8') ?>')">
                                                        <i class="far fa-trash-alt" style="font-size: 15px; color: white;"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        // Tampilkan baris kosong dengan pesan "Data Kosong!"
                                        ?>
                                        <tr>
                                            <td class="text-center fst-italic" colspan="6">Data Kosong!</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Modal Detail Permintaan Layanan -->
            <div class="modal fade" id="detailPermintaanModal" tabindex="-1" aria-labelledby="detailPermintaanLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content shadow-lg border-0 rounded-3">
                        <div class="modal-header text-white" style="background-color: #009688;">
                            <h5 class="modal-title" id="detailPermintaanLabel"><i class="fas fa-info-circle me-2"></i>Detail Permintaan Layanan</h5>
                            <!-- <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button> -->
                        </div>
                        <div class="modal-body p-4">
                            <div class="row">
                                <!-- Kiri -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold text-secondary">Nama Warga:</label>
                                        <div id="detailNama" class="fs-6 text-dark"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-bold text-secondary">Telepon:</label>
                                        <div id="detailTelepon" class="fs-6 text-dark"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-bold text-secondary">Alamat:</label>
                                        <div id="detailAlamat" class="fs-6 text-dark"></div>
                                    </div>
                                </div>

                                <!-- Kanan -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold text-secondary">Kategori:</label>
                                        <!-- <div id="detailKategori" class="badge bg-info text-dark fs-6 px-3 py-2"></div> -->
                                        <div id="detailKategori" class="fs-6 text-dark"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-bold text-secondary">Deskripsi Permintaan:</label>
                                        <div id="detailDeskripsi" class="fs-6 text-dark"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-bold text-secondary">Dibuat pada:</label>
                                        <div id="detailTanggal" class="fs-6 text-dark"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end modal detail permintaan layanan -->

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; FixQuick 2024-<span id="year"></span></div>
                        <!-- <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div> -->
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        function hapusPermintaan(id, nama) {
            Swal.fire({
                title: `Yakin menghapus permintaan dari "${nama}"?`,
                text: "Data yang telah dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Batal",
                confirmButtonText: "Hapus",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim permintaan ke server
                    fetch(`delete_permintaan.php?id=${id}`, {
                            method: 'POST',
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: `Permintaan dari ${nama} berhasil dihapus.`,
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false,
                                }).then(() => {
                                    // Refresh halaman atau hapus baris dari tabel
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Gagal!",
                                    text: `Permintaan dari ${nama} gagal dihapus.`,
                                    icon: "error",
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: "Kesalahan!",
                                text: "Terjadi kesalahan pada server.",
                                icon: "error",
                            });
                        });
                }
            });
        }
    </script>
    <!-- script detail warga -->
    <script>
        function tampilkanDetailPermintaan(idPermintaan) {
            fetch(`get_datapermintaan.php?id_permintaan=${idPermintaan}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('detailNama').innerText = data.nama_warga || 'Tidak tersedia';
                        document.getElementById('detailTelepon').innerText = data.no_telepon || 'Tidak tersedia';
                        document.getElementById('detailAlamat').innerText = data.alamat || 'Tidak tersedia';
                        document.getElementById('detailKategori').innerText = data.kategori || 'Tidak tersedia';
                        document.getElementById('detailDeskripsi').innerText = data.deskripsi_permintaan || 'Tidak tersedia';
                        document.getElementById('detailTanggal').innerText = data.created_at || 'Tidak tersedia';

                        const modal = new bootstrap.Modal(document.getElementById('detailPermintaanModal'));
                        modal.show();
                    } else {
                        alert('Data tidak ditemukan!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data.');
                });
        }
    </script>

    </script>
    <!-- script tahunan -->
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
    <!-- end script tahunan -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <script src="../js/statuslogin.js"></script>
</body>

</html>
