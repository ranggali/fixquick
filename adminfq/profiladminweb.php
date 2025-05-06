<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" href="assets/logo/logo1.png">
    <title>Profil Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/profil.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html"><img src="assets/logo/logo1.png" alt="Logo"
                style="height: 25px; width: 25px; margin-right: 10px;">Profil Admin</a>
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
                    <li><a class="dropdown-item" href="../profil.html">Profil</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="login.html">Keluar</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav"><br>

                        <a class="nav-link " href="index.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i>
                            </div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Warga</div>
                        <a class="nav-link active" href="warga/warga.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i>
                            </div>
                            Warga
                        </a>

                        <div class="sb-sidenav-menu-heading">Perumahan</div>
                        <a class="nav-link" href="perumahan/adminperum.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                            Perumahan
                        </a>

                        <div class="sb-sidenav-menu-heading">Pelayanan Jasa</div>
                        <a class="nav-link" href="pelayanan/pelayananjasa.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
                            Pelayanan
                        </a>

                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Login sebagai:</div>
                    ..Nama Admin..
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <div class="container light-style flex-grow-1 container-p-y">
                <h4 class="font-weight-bold py-3 mb-4">
                    Setting Akun
                </h4>
                <div class="card overflow-hidden">
                    <div class="row no-gutters row-bordered row-border-light">
                        <div class="col-md-3 pt-0">
                            <div class="list-group list-group-flush account-settings-links">
                                <a class="list-group-item list-group-item-action active" data-toggle="list"
                                    href="#account-general">Profil</a>
                                <a class="list-group-item list-group-item-action" data-toggle="list"
                                    href="#account-change-password">Ubah Kata Sandi</a>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="account-general">
                                    <div class="card-body media align-items-center">
                                        <img src="assets/img/admin1.png" alt class="d-block ui-w-80">
                                        <div class="media-body ml-4"><br>
                                            <label class="btn btn-outline-primary">
                                                Ubah Foto
                                                <input type="file" class="account-settings-fileinput">
                                            </label> &nbsp;
                                            <!-- <button type="button" class="btn btn-default md-btn-flat">Reset</button> -->
                                            <div class="text-light small mt-1">Allowed JPG, GIF or PNG. Max size of 800K
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="border-light m-0">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="form-label">Nama</label>
                                            <input type="text" class="form-control mb-1">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="account-change-password">
                                    <div class="card-body pb-2">
                                        <div class="form-group">
                                            <label class="form-label">Kata Sandi saat ini</label>
                                            <input type="password" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Kata Sandi baru</label>
                                            <input type="password" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Ulangi kata sandi</label>
                                            <input type="password" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-3">
                    <button id="ubahAkunBtn" type="button" class="btn btn-primary">Ubah Akun</button>&nbsp;
                    <button id="batalBtn" type="button" class="btn btn-default">Batal</button>
                </div>
            </div>
            <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
            <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
            <script type="text/javascript">

            </script>


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
        document.getElementById("ubahAkunBtn").addEventListener("click", function () {
            var btn = document.getElementById("ubahAkunBtn");



            // Ubah teks dan warna tombol
            if (btn.innerHTML === "Ubah Akun") {
                btn.innerHTML = "Simpan Perubahan";
                btn.classList.remove("btn-primary");
                btn.classList.add("btn-success");
            } else {
                btn.innerHTML = "Ubah Akun";
                btn.classList.remove("btn-success");
                btn.classList.add("btn-primary");
                // Menampilkan SweetAlert
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Berhasil Login!",
                    showConfirmButton: false,
                    timer: 900
                });
            }
        });
        // Event listener untuk tombol "Batal"
        document.getElementById("batalBtn").addEventListener("click", function () {
            var btn = document.getElementById("ubahAkunBtn");

            // Kembali ke "Ubah Akun" dan mengembalikan warna tombol
            if (btn.innerHTML === "Simpan Perubahan") {
                btn.innerHTML = "Ubah Akun";
                btn.classList.remove("btn-success");  // Menghapus warna hijau
                btn.classList.add("btn-primary");     // Mengembalikan warna biru

                // Menampilkan SweetAlert untuk "Perubahan Dibatalkan"
                Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "Perubahan Dibatalkan",
                    showConfirmButton: false,
                    timer: 900
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
</html>