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
        p.id_pengajuan,
        p.kategori_jasa, 
        p.deskripsi_jasa, 
        p.harga, 
        p.status_pengajuan,
        pj.nama_penyedia_jasa, -- Ambil nama penyedia jasa
        p.created_at 
    FROM pengajuan_pelayanan p
    JOIN pelayanan_jasa pj 
        ON p.id_pelayanan_jasa = pj.id_pelayanan_jasa -- Join dengan pelayanan_jasa
    WHERE p.id_perumahan = ?
    ORDER BY p.created_at DESC
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
    <title>Data Pengajuan Layanan</title>
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
                        <a class="nav-link" style="font-weight:400;" href="../pelayanan/pelayananjasa.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
                            Manajemen layanan
                        </a>
                        <a class="nav-link active" style="font-weight:400;" href="#">
                            <div class="sb-nav-link-icon"><i class="fas fa-home" style="color: #009688;"></i></div>
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
                    <h1 class="mt-4">Data Pengajuan Layanan</h1>
                    <ol class="breadcrumb mb-4">
                        <!-- <li class="breadcrumb-item"><a href="index.html"
                                style="text-decoration: none; color: #6C757D;">Dashboard</a></li> -->
                        <li class="breadcrumb-item active">Permintaan Layanan</li>
                    </ol>
                    <div class="card mb-4">
                    </div>
                    <div class="card mb-4" style="box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-table me-1"></i>
                                <span>Daftar Pengajuan Layanan Oleh Penyedia</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="statusFilter" class="form-label">Filter Status:</label>
                                <select id="statusFilter" class="form-select" onchange="filterStatus()">
                                    <option value="">Semua</option>
                                    <option value="Menunggu">Menunggu</option>
                                    <option value="Disetujui">Disetujui</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Nama Penyedia</th>
                                        <th>Kategori Layanan</th>
                                        <th>Deskripsi</th>
                                        <th>Harga</th>
                                        <th>Tanggal Permintaan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Nama Penyedia</th>
                                        <th>Kategori Layanan</th>
                                        <th>Deskripsi</th>
                                        <th>Harga</th>
                                        <th>Tanggal Permintaan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody id="tableBody">
                                    <?php
                                    if (!empty($data)) {
                                        foreach ($data as $row) {
                                            // Format tanggal untuk Tanggal Permintaan
                                            $formattedDate = date("d-m-Y", strtotime($row['created_at']));
                                            // Status dengan badge
                                            $statusBadge = '';
                                            switch ($row['status_pengajuan']) {
                                                case 'Menunggu':
                                                    $statusBadge = '<span class="badge bg-secondary">Menunggu</span>';
                                                    break;
                                                case 'Disetujui':
                                                    $statusBadge = '<span class="badge bg-success">Disetujui</span>';
                                                    break;
                                                case 'Ditolak':
                                                    $statusBadge = '<span class="badge bg-danger">Ditolak</span>';
                                                    break;
                                                default:
                                                    $statusBadge = '<span class="badge bg-secondary">' . htmlspecialchars($row['status_pengajuan']) . '</span>';
                                                    break;
                                            }
                                            echo "<tr>
                                                <td>" . htmlspecialchars($row['nama_penyedia_jasa']) . "</td>
                                                <td>" . htmlspecialchars($row['kategori_jasa']) . "</td>
                                                <td>" . htmlspecialchars($row['deskripsi_jasa']) . "</td>
                                                <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
                                                <td>" . $formattedDate . "</td>
                                                <td>" . $statusBadge . "</td>
                                                <td class='d-flex justify-content-between align-items-center'>
                                                    <button class='btn btn-info me-2' 
                                                            onclick='showDetail(this)' 
                                                            data-id='" . htmlspecialchars($row['id_pengajuan']) . "' 
                                                            data-nama='" . htmlspecialchars($row['nama_penyedia_jasa']) . "' 
                                                            data-kategori='" . htmlspecialchars($row['kategori_jasa']) . "' 
                                                            data-deskripsi='" . htmlspecialchars($row['deskripsi_jasa']) . "' 
                                                            data-harga='" . htmlspecialchars(number_format($row['harga'], 0, ',', '.')) . "' 
                                                            data-tanggal='" . htmlspecialchars($formattedDate) . "' 
                                                            data-status='" . htmlspecialchars($row['status_pengajuan']) . "'>
                                                        <i class='fas fa-address-book' style='font-size: 15px; color: white;'></i>
                                                    </button>
                                                    <button class='btn btn-success btn-sm me-2' onclick='toggleTambah(this)' data-nama='" . htmlspecialchars($row['nama_penyedia_jasa']) . "' data-id='" . htmlspecialchars($row['id_pengajuan']) . "'>Tambah</button>
                                                    <button class='btn btn-danger' onclick='confirmDelete(this)' data-id='" . htmlspecialchars($row['id_pengajuan']) . "' data-nama='" . htmlspecialchars($row['nama_penyedia_jasa']) . "'><i class='far fa-trash-alt' style='font-size: 15px; color: white;'></i></button>
                                                    <button class='btn btn-secondary btn-sm me-2' onclick='toggleStatus(this)' data-nama='" . htmlspecialchars($row['nama_penyedia_jasa']) . "' data-id='" . htmlspecialchars($row['id_pengajuan']) . "'>Ubah Status</button>
                                                    
                                                </td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>Tidak ada data tersedia</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Modal detail pengajuan-->
            <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailModalLabel">Detail Pengajuan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Nama Penyedia:</strong> <span id="detailNama"></span></p>
                            <p><strong>Kategori Layanan:</strong> <span id="detailKategori"></span></p>
                            <p><strong>Deskripsi:</strong> <span id="detailDeskripsi"></span></p>
                            <p><strong>Harga:</strong> <span id="detailHarga"></span></p>
                            <p><strong>Tanggal Permintaan:</strong> <span id="detailTanggal"></span></p>
                            <p><strong>Status:</strong> <span id="detailStatus"></span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir modal detail pengajuan-->

            <!-- modal perubahan status pengajuan -->
            <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="statusModalLabel">Ubah Status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Ubah status untuk: <strong id="statusNama"></strong></p>
                            <div class="form-group">
                                <label for="statusSelect">Pilih Status:</label>
                                <select id="statusSelect" class="form-select">
                                    <option value="Menunggu">Menunggu</option>
                                    <option value="Disetujui">Disetujui</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" onclick="saveStatus()">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir modal perubahan status pengajuan-->

            <!-- modal tambah layanan -->
            <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahModalLabel">Tambah Pelayanan Jasa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Tambah Pelayanan dengan nama <strong id="NamaPenyedia"></strong> ke perumahan anda?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-success" onclick="tambahLayanan()">Tambah</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir modal tambah layanan-->

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

    <!-- detail pengajuan -->
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
    <!-- tambah layanan dari pengajuan -->
    <script>
        // let currentPengajuanId = null; // Untuk menyimpan ID pengajuan saat ini

        function toggleTambah(button) {
            // Ambil data dari atribut tombol
            const nama = button.getAttribute('data-nama');
            const id = button.getAttribute('data-id');
            currentPengajuanId = id; // Simpan ID pengajuan untuk digunakan saat menyimpan

            // Isi modal dengan nama penyedia
            document.getElementById('NamaPenyedia').textContent = nama;

            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('tambahModal'));
            modal.show();
        }

        function tambahLayanan() {
            // Kirim data ke server dengan AJAX
            const idPengajuan = currentPengajuanId; // ID pengajuan yang disimpan sebelumnya

            fetch('tambah_layanan.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        idPengajuan: idPengajuan
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert('Pelayanan berhasil ditambahkan.');
                        window.location.reload(); // Reload halaman untuk memuat data terbaru
                    } else {
                        alert('Gagal menambahkan pelayanan: ' + data.message);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambahkan pelayanan.');
                });
        }
    </script>
    <!-- ubah status pengajuan -->
    <script>
        let currentPengajuanId = null; // Untuk menyimpan ID pengajuan saat ini

        function toggleStatus(button) {
            // Ambil data dari atribut tombol
            const nama = button.getAttribute('data-nama');
            const id = button.getAttribute('data-id');
            currentPengajuanId = id; // Simpan ID pengajuan untuk digunakan saat menyimpan

            // Isi modal dengan nama penyedia
            document.getElementById('statusNama').textContent = nama;

            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        }

        function saveStatus() {
            const status = document.getElementById('statusSelect').value;

            // Kirim data menggunakan fetch untuk memperbarui status di database
            fetch('update_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id_pengajuan: currentPengajuanId,
                        status_pengajuan: status,
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        Swal.fire({
                            position: "top",
                            icon: "success",
                            title: "Status berhasil diperbarui!",
                            showConfirmButton: false,
                            timer: 1000,
                        });

                        // Perbarui status di tabel
                        const row = document.querySelector(`button[data-id='${currentPengajuanId}']`).closest('tr');
                        row.querySelector('td:nth-child(6)').innerHTML = `<span class="badge bg-${getStatusBadge(status)}">${status}</span>`;

                        // Tutup modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
                        modal.hide();
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: "Gagal memperbarui status.",
                            icon: "error",
                        });
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    Swal.fire({
                        title: "Error!",
                        text: "Terjadi kesalahan.",
                        icon: "error",
                    });
                });
        }

        function getStatusBadge(status) {
            switch (status) {
                case 'Menunggu':
                    return 'secondary';
                case 'Disetujui':
                    return 'success';
                case 'Ditolak':
                    return 'danger';
                default:
                    return 'secondary';
            }
        }
    </script>
    <!-- hapus pengajuan -->
    <script>
        function confirmDelete(button) {
            const id_pengajuan = button.getAttribute('data-id');
            const nama_penyedia = button.getAttribute('data-nama'); // Ambil nama penyedia jasa

            Swal.fire({
                title: `Yakin menghapus data ${nama_penyedia}?`, // Tampilkan nama penyedia
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Batal",
                confirmButtonText: "Hapus",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim AJAX untuk hapus data
                    fetch('delete_pengajuan.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                id_pengajuan: id_pengajuan
                            }),
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.success) {
                                Swal.fire({
                                    position: "top",
                                    icon: "success",
                                    title: "Berhasil dihapus!",
                                    showConfirmButton: false,
                                    timer: 1000,
                                });
                                // Hapus baris dari tabel
                                button.closest('tr').remove();
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Gagal menghapus data.",
                                    icon: "error",
                                });
                            }
                        })
                        .catch((error) => {
                            console.error("Error:", error);
                            Swal.fire({
                                title: "Error!",
                                text: "Terjadi kesalahan.",
                                icon: "error",
                            });
                        });
                }
            });
        }
    </script>
    <!-- filter status -->
    <script>
        function filterStatus() {
            const filter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('#tableBody tr');

            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                if (filter === "" || status === filter) {
                    row.style.display = ""; // Tampilkan baris
                } else {
                    row.style.display = "none"; // Sembunyikan baris
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
    <!-- <script src="../js/statuspermintaan.js"></script> -->
    <!-- <script src="../js/hapusdatapermintaan.js"></script> -->

</body>

</html>