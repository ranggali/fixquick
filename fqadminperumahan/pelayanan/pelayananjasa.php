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
$id_perumahan = $_SESSION['nama_pengguna'];
$id_perumahan = $_SESSION['id_perumahan'];

// Query untuk mendapatkan data dari tabel warga
$sql = "
    SELECT 
        id_layanan,
        nama_penyedia_layanan, 
        kategori_jasa,
        deskripsi,
        harga, 
        status_layanan,
        created_at 
    FROM layanan 
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
    <title>Data Layanan</title>
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
        <a class="navbar-brand ps-3" href="../index.php"><img src="../assets/logo/logo1.png" alt="Logo"
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
                        <a class="nav-link active" style="font-weight:400;" href="../pelayanan/pelayananjasa.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tools" style="color: #009688;"></i></div>
                            Manajemen layanan
                        </a>
                        <a class="nav-link" style="font-weight:400;" href="../perumahan/adminperum.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                            Pengajuan Layanan
                        </a>
                        <div class="sb-sidenav-menu-heading">Permintaan</div>
                        <a class="nav-link" style="font-weight:400;" href="../permintaanLayanan/permintaanLayanan.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                            Permintaan Layanan
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Masuk Sebagai:</div>
                    <?php echo $_SESSION['nama_pengguna']; ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Layanan Terdaftar</h1>
                    <ol class="breadcrumb mb-4">
                        <!-- <li class="breadcrumb-item"><a href="index.html"
                                style="text-decoration: none; color: #6C757D;">Dashboard</a></li> -->
                        <li class="breadcrumb-item active">Manajemen Layanan</li>
                    </ol>
                    <div class="card mb-4">
                    </div>
                    <div class="card mb-4" style="box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-table me-1"></i>
                                <span>Pelayanan</span>
                            </div>
                            <button class="btn btn-success" id="addPelayananBtn" data-bs-toggle="modal"
                                data-bs-target="#addPelayananModal">
                                <i class="fas fa-plus"></i> Tambah Layanan
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    if (!empty($data)) {
                                        foreach ($data as $pelayanan_jasa) {
                                            // Menggunakan null coalescing operator untuk menghindari peringatan
                                            $nama_penyedia_layanan = htmlspecialchars($pelayanan_jasa['nama_penyedia_layanan'] ?? '');
                                            $kategori_layanan = htmlspecialchars($pelayanan_jasa['kategori_jasa'] ?? '');

                                            // Tentukan status aktif
                                            $statusBadge = $pelayanan_jasa['status_layanan']=== 'aktif' ?
                                                '<span class="badge bg-success">Aktif</span>' :
                                                '<span class="badge bg-danger">Tidak Aktif</span>';
                                    ?>
                                            <tr>
                                                <td><?= $nama_penyedia_layanan ?></td>
                                                <td><?= $kategori_layanan ?></td>
                                                <td><?= $statusBadge ?></td>
                                                <td class="d-flex justify-content-between align-items-center">
                                                <button class="btn btn-info me-2" id="editLayananBtn" data-bs-toggle="modal" data-bs-target="#editLayananModal" alt="edit data layanan"
                                                    onclick="showEditDetail(this)"
                                                    data-id="<?= htmlspecialchars($pelayanan_jasa['id_layanan']) ?>"
                                                    data-nama="<?= htmlspecialchars($pelayanan_jasa['nama_penyedia_layanan']) ?>"
                                                    data-kategori="<?= htmlspecialchars($pelayanan_jasa['kategori_jasa']) ?>"
                                                    data-deskripsi="<?= htmlspecialchars($pelayanan_jasa['deskripsi']) ?>"
                                                    data-harga="<?= htmlspecialchars($pelayanan_jasa['harga']) ?>"
                                                    data-status="<?= htmlspecialchars($pelayanan_jasa['status_layanan']) ?>"> <!-- Menambahkan data-status -->
                                                    <i class="fas fa-edit" style="font-size: 15px; color: white;"></i>
                                                </button>
                                                    <button class="btn btn-primary me-2" id="detailLayananBtn" data-bs-toggle="modal" data-bs-target="#detailLayananModal" alt="detail data layanan"
                                                        onclick="showDetail(this)"
                                                        data-id="<?= htmlspecialchars($pelayanan_jasa['id_layanan']) ?>"
                                                        data-nama="<?= htmlspecialchars($pelayanan_jasa['nama_penyedia_layanan']) ?>"
                                                        data-kategori="<?= htmlspecialchars($pelayanan_jasa['kategori_jasa']) ?>"
                                                        data-deskripsi="<?= htmlspecialchars($pelayanan_jasa['deskripsi']) ?>"
                                                        data-status="<?= htmlspecialchars($pelayanan_jasa['status_layanan']) ?>"
                                                        data-harga="<?= number_format($pelayanan_jasa['harga'], 0, ',', '.') ?>"
                                                        data-tanggal="<?= date('d-m-Y', strtotime($pelayanan_jasa['created_at'])) ?>">
                                                        <i class="fas fa-address-book" style="font-size: 15px; color: white;"></i>
                                                    </button>
                                                    <button class="btn btn-danger" onclick="confirmDelete(this)" data-nama="<?= htmlspecialchars($pelayanan_jasa['nama_penyedia_layanan']) ?>" alt="hapus data layanan"><i class="far fa-trash-alt" style="font-size: 15px; color: white;"></i></button>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        // Tampilkan baris kosong dengan pesan "Data Kosong!"
                                        ?>
                                        <tr>
                                            <td class="text-center fst-italic" colspan="5">Data Kosong!</td>
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

            <!-- Modal Tambah Layanan -->
            <div class="modal fade" id="addPelayananModal" tabindex="-1" aria-labelledby="addPelayananModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="addPelayananModalLabel">Tambah Layanan Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- Modal Body -->
                        <div class="modal-body">
                            <!-- Form atau Konten Modal -->
                            <form action="add_layanan.php" method="POST">
                                <div class="row mb-3">
                                    <label for="LayananName" class="col-sm-3 col-form-label"
                                        style="font-weight: 400;">Nama:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="LayananName" name="nama_penyedia_layanan"
                                            placeholder="Masukkan Nama Layanan">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="LayananName" class="col-sm-3 col-form-label"
                                        style="font-weight: 400;">Kategori:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="LayananName" name="kategori_jasa"
                                            placeholder="Masukkan Nama Layanan">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="LayananName" class="col-sm-3 col-form-label"
                                        style="font-weight: 400;">Deskripsi:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="LayananName" name="deskripsi"
                                            placeholder="Masukkan Nama Layanan">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="LayananName" class="col-sm-3 col-form-label"
                                        style="font-weight: 400;">harga:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="LayananName" name="harga"
                                            placeholder="Masukkan Nama Layanan">
                                    </div>
                                </div>
                                <!-- Tambahkan field lain jika diperlukan -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary"
                                        style="background-color: #009688; border: none;">Tambah</button>
                                </div>
                            </form>
                        </div>
                        <!-- Modal Footer -->

                    </div>
                </div>
            </div>
            <!-- End modal tambah Layanan -->

            <!-- Modal detail layanan Terdaftar-->
            <div class="modal fade" id="detailLayananModal" tabindex="-1" aria-labelledby="detailLayananModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailLayananModalLabel">Detail Pengajuan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Nama Penyedia:</strong> <span id="detailNama"></span></p>
                            <p><strong>Kategori Layanan:</strong> <span id="detailKategori"></span></p>
                            <p><strong>Deskripsi:</strong> <span id="detailDeskripsi"></span></p>
                            <p><strong>Harga:</strong> <span id="detailHarga"></span></p>
                            <p><strong>Tanggal Daftar:</strong> <span id="detailTanggal"></span></p>
                            <p><strong>Status:</strong> <span id="detailStatus"></span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir modal detail layanan Terdaftar-->

            <!-- Modal Edit Layanan -->
            <div class="modal fade" id="editLayananModal" tabindex="-1" aria-labelledby="editLayananModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editLayananModalLabel">Edit Layanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="update_layanan.php" method="POST">
                                <input type="hidden" id="editIdLayanan" name="id_layanan">
                                <div class="mb-3">
                                    <label for="editNama" class="form-label">Nama Penyedia Layanan</label>
                                    <input type="text" class="form-control" id="editNama" name="nama_penyedia_layanan" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editKategori" class="form-label">Kategori</label>
                                    <input type="text" class="form-control" id="editKategori" name="kategori_jasa" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editDeskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="editDeskripsi" name="deskripsi" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="editHarga" class="form-label">Harga</label>
                                    <input type="text" class="form-control" id="editHarga" name="harga" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editStatus" class="form-label">Status Layanan</label>
                                    <select class="form-select" id="editStatus" name="status_layanan" required>
                                        <option value="aktif">Aktif</option>
                                        <option value="tidak aktif">Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir modal edit layanan -->

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
    <!-- edit layanan -->
    <script>
        function showEditDetail(button) {
            // Ambil data dari atribut tombol
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const kategori = button.getAttribute('data-kategori');
            const deskripsi = button.getAttribute('data-deskripsi');
            const harga = button.getAttribute('data-harga');
            const status = button.getAttribute('data-status');

            // Isi modal dengan data
            document.getElementById('editIdLayanan').value = id;
            document.getElementById('editNama').value = nama;
            document.getElementById('editKategori').value = kategori;
            document.getElementById('editDeskripsi').value = deskripsi;
            document.getElementById('editHarga').value = harga;

            // Set status layanan
            document.getElementById('editStatus').value = status; // Sesuaikan dengan nilai yang diterima
        }
    </script>

    <!-- detail layanan Terdaftar -->
    <script>
        function showDetail(button) {
            // Ambil data dari atribut tombol
            const nama = button.getAttribute('data-nama');
            const kategori = button.getAttribute('data-kategori');
            const deskripsi = button.getAttribute('data-deskripsi');
            const harga = button.getAttribute('data-harga');
            const tanggal = button.getAttribute('data-tanggal');
            const status = button.getAttribute('data-status');

            // Isi modal dengan data
            document.getElementById('detailNama').textContent = nama;
            document.getElementById('detailKategori').textContent = kategori;
            document.getElementById('detailDeskripsi').textContent = deskripsi;
            document.getElementById('detailHarga').textContent = `Rp ${harga}`;
            document.getElementById('detailTanggal').textContent = tanggal;
            document.getElementById('detailStatus').textContent = status;

            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        }
    </script>

    <!-- hapus layanan Terdaftar -->
    <script>
        function confirmDelete(button) {
            const nama_penyedia_layanan = button.getAttribute('data-nama'); // Ambil ID layanan dari atribut tombol

            Swal.fire({
                title: `Yakin menghapus data layanan dengan nama ${nama_penyedia_layanan}?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Batal",
                confirmButtonText: "Hapus",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim permintaan AJAX ke PHP untuk menghapus data
                    fetch('delete_layanan.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                nama_penyedia_layanan: nama_penyedia_layanan
                            }),
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.success) {
                                Swal.fire({
                                    position: "top",
                                    icon: "success",
                                    title: "Data berhasil dihapus!",
                                    showConfirmButton: false,
                                    timer: 1000,
                                });
                                // Hapus baris dari tabel
                                button.closest('tr').remove();
                            } else {
                                Swal.fire({
                                    title: "Gagal!",
                                    text: "Data gagal dihapus.",
                                    icon: "error",
                                });
                            }
                        })
                        .catch((error) => {
                            console.error("Error:", error);
                            Swal.fire({
                                title: "Kesalahan!",
                                text: "Terjadi kesalahan saat menghapus data.",
                                icon: "error",
                            });
                        });
                }
            });
        }
    </script>
    <!-- script tahunan -->
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
    <!-- end script tahunan -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <!-- <script src="../js/hapusdatalayanan.js"></script> -->
    <script src="../js/statuslayanan.js"></script>
</body>

</html>