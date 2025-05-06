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
        id_warga,
        nama_warga AS nama, 
        no_telepon, 
        alamat, 
        email,
        foto_profil,
        created_at,
        is_aktif  
    FROM warga 
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
                        <a class="nav-link active" style="font-weight:400;" href="#">
                            <div class="sb-nav-link-icon"><i class="fas fa-users" style="color: #009688;"></i></div>
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
                        <a class="nav-link" style="font-weight:400;" href="../permintaanLayanan/permintaanLayanan.php">
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
                    <h1 class="mt-4">Data Warga Terdaftar</h1>
                    <ol class="breadcrumb mb-4">
                        <!-- <li class="breadcrumb-item"><a href="index.html"
                                style="text-decoration: none; color: #6C757D;">Dashboard</a></li> -->
                        <li class="breadcrumb-item active">Manajemen Warga</li>
                    </ol>
                    <div class="card mb-4">
                    </div>
                    <div class="card mb-4" style="box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-table me-1"></i>
                                <span>Warga</span>
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
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Telepon</th>
                                        <th>Alamat</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    if (!empty($data)) {
                                        foreach ($data as $warga) {
                                            // Menggunakan null coalescing operator untuk menghindari peringatan
                                            $nama = !empty($warga['nama']) ? htmlspecialchars($warga['nama']) : '<span class="fst-italic text-muted">Data Kosong</span>';
                                            $noTelepon = !empty($warga['no_telepon']) ? htmlspecialchars($warga['no_telepon']) : '<span class="fst-italic text-muted">Data Kosong</span>';
                                            $alamat = !empty($warga['alamat']) ? htmlspecialchars($warga['alamat']) : '<span class="fst-italic text-muted">Data Kosong</span>';

                                            // Tentukan status aktif
                                            $statusBadge = $warga['is_aktif'] === 'aktif' ?
                                                '<span class="badge bg-success">Aktif</span>' :
                                                '<span class="badge bg-danger">Tidak Aktif</span>';
                                    ?>
                                            <tr>
                                                <td><?= $nama ?></td>
                                                <td><?= $noTelepon ?></td>
                                                <td><?= $alamat ?></td>
                                                <td><?= $statusBadge ?></td>
                                                <td class="d-flex justify-content-between align-items-center">
                                                    <button class="btn btn-info me-2" onclick="editWarga(<?= $warga['id_warga'] ?>)"><i class="fas fa-edit" style="font-size: 15px; color: white;"></i>
                                                    </button>
                                                    <button class="btn btn-primary me-2" onclick="tampilkanDetail(<?= $warga['id_warga'] ?>)"><i class="fas fa-address-book" style="font-size: 15px; color: white;"></i>
                                                    </button>
                                                    <button class="btn btn-danger" onclick="hapusWarga(<?= $warga['id_warga'] ?>, '<?= htmlspecialchars($warga['nama'], ENT_QUOTES, 'UTF-8') ?>')"><i class="far fa-trash-alt" style="font-size: 15px; color: white;"></i>
                                                    </button>
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

            <!-- Modal Tambah warga-->
            <div class="modal fade" id="addWargaModal" tabindex="-1" aria-labelledby="addWargaModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="addWargaModalLabel">Tambah Warga Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- Modal Body -->
                        <div class="modal-body">
                            <!-- Form atau Konten Modal -->
                            <form action="add_warga.php" method="POST">
                                <div class="row mb-3">
                                    <label for="nama_warga" class="col-sm-3 col-form-label" style="font-weight: 400;">Nama:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="nama_warga" placeholder="Nama Warga" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="email_warga" class="col-sm-3 col-form-label" style="font-weight: 400;">Email:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="email_warga" placeholder="Email Warga" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="kata_sandi" class="col-sm-3 col-form-label" style="font-weight: 400;">Kata Sandi:</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="kata_sandi" name="kata_sandi" placeholder="Masukkan Kata Sandi" required>
                                    </div>
                                </div>
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
            <!-- End Modal Tambah warga -->

            <!-- modal detail warga -->
            <div class="modal fade" id="detailWargaModal" tabindex="-1" aria-labelledby="detailWargaModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailWargaModalLabel">Detail Warga</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Nama:</strong> <span id="detailNama"></span></p>
                            <p><strong>Telepon:</strong> <span id="detailTelepon"></span></p>
                            <p><strong>Alamat:</strong> <span id="detailAlamat"></span></p>
                            <p><strong>Email:</strong> <span id="detailEmail"></span></p>
                            <p><strong>Status:</strong> <span id="detailStatus"></span></p>
                            <p><strong>Foto Profil:</strong></p>
                            <img id="detailFoto" src="" alt="Foto Profil" class="img-fluid d-none" style="max-width: 150px; max-height: 150px; object-fit: cover; border-radius: 10px;">
                        </div>
                    </div>
                </div>
            </div>
            <!-- end modal detail warga -->

            <!-- Modal Edit Warga -->
            <div class="modal fade" id="editWargaModal" tabindex="-1" aria-labelledby="editWargaModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editWargaModalLabel">Edit Data Warga</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editWargaForm">
                            <div class="modal-body">
                                <input type="hidden" id="editIdWarga" name="id_warga">
                                <div class="mb-3">
                                    <label for="editNamaWarga" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="editNamaWarga" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editNoTelepon" class="form-label">No Telepon</label>
                                    <input type="number" class="form-control" id="editNoTelepon" name="no_telepon" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editAlamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="editAlamat" name="alamat" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="editStatus" class="form-label">Status</label>
                                    <select class="form-select" id="editStatus" name="is_aktif" required>
                                        <option value="aktif">Aktif</option>
                                        <option value="tidak aktif">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- end modal edit warga -->

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
        function hapusWarga(id, nama) {
            Swal.fire({
                title: `Yakin menghapus data "${nama}"?`,
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
                    fetch(`delete_warga.php?id=${id}`, {
                            method: 'POST',
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: `Data ${nama} berhasil dihapus.`,
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
                                    text: `Data ${nama} gagal dihapus.`,
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
        function tampilkanDetail(idWarga) {
            fetch(`get_datawarga.php?id_warga=${idWarga}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('detailNama').innerText = data.nama;
                        document.getElementById('detailTelepon').innerText = data.no_telepon || 'Tidak tersedia';
                        document.getElementById('detailAlamat').innerText = data.alamat || 'Tidak tersedia';
                        document.getElementById('detailEmail').innerText = data.email || 'Tidak tersedia';
                        document.getElementById('detailStatus').innerText = data.is_aktif ? 'Aktif' : 'Tidak Aktif';

                        const foto = document.getElementById('detailFoto');
                        if (data.foto_profil) {
                            foto.src = data.foto_profil;
                            foto.alt = `Foto Profil ${data.nama}`;
                            foto.classList.remove('d-none');
                        } else {
                            foto.src = '';
                            foto.alt = 'Foto tidak tersedia';
                            foto.classList.add('d-none');
                        }

                        const modal = new bootstrap.Modal(document.getElementById('detailWargaModal'));
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
    <!-- script edit warga -->
    <script>
        function editWarga(idWarga) {
            // Ambil data dari server
            fetch(`get_warga.php?id_warga=${idWarga}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Isi modal dengan data warga
                        document.getElementById('editIdWarga').value = data.warga.id_warga;
                        document.getElementById('editNamaWarga').value = data.warga.nama;
                        document.getElementById('editNoTelepon').value = data.warga.no_telepon;
                        document.getElementById('editAlamat').value = data.warga.alamat;

                        // Isi dropdown status
                        document.getElementById('editStatus').value = data.warga.is_aktif;

                        // Tampilkan modal
                        const editWargaModal = new bootstrap.Modal(document.getElementById('editWargaModal'));
                        editWargaModal.show();
                    } else {
                        alert('Gagal mengambil data warga!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data!');
                });
        }
    </script>
    <script>
        document.getElementById('editWargaForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('edit_datawarga.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Data warga berhasil diperbarui!');
                        location.reload(); // Reload halaman untuk memperbarui tabel
                    } else {
                        alert('Gagal memperbarui data!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan data!');
                });
        });
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