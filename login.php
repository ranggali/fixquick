<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="icon" href="img/logo1.png">
    <title>FixQuick Website</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700&family=Rubik:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Sweetaleert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <a href="index.php" class="back-button" aria-label="Kembali ke halaman utama">
        <i class='bx bx-arrow-back'></i> Kembali
    </a>

    <!-- Form Start -->
    <section class="container forms">
        <div class="form login">
            <div class="form-content">
                <img src="img/logo1.png" alt="logo">
                <header>Masuk</header>
                <div class="line"></div>

                <form action="php/login.php" method="POST" id="loginForm">
                    <div class="field input-field">
                        <label for="role">Pilih Peran</label>
                        <div class="select-container">
                            <select id="role" name="role" class="input" required>
                                <option value="" disabled selected>Pilih peran Anda</option>
                                <option value="admin">Admin Perumahan</option>
                                <option value="provider">Penyedia Jasa</option>
                                <option value="resident">Warga</option>
                            </select>
                            <i class='bx bx-chevron-down arrow-icon'></i>
                        </div>
                    </div><br>

                    <div id="dynamicFields">
                        <!-- Input fields akan berubah di sini -->
                        <div class="field input-field">
                            <label for="user_email">Email</label>
                            <input type="email" id="user_email" name="email" placeholder="Email" class="input" required>
                        </div><br>

                        <div class="field input-field">
                            <label for="user_password">Kata Sandi</label>
                            <input type="password" id="user_password" name="password" placeholder="Kata Sandi" class="password" required>
                            <i class='bx bx-hide eye-icon'></i>
                        </div><br>
                    </div>

                    <div class="field button-field">
                        <button type="submit" id="Masuk">Masuk</button>
                    </div>
                </form>

                <div class="form-link">
                    <span>Daftar sebagai Pelayanan Jasa? <a href="#" class="link signup-link">Daftar disini!</a></span><br>
                    <span><a href="lupasandi.php">Lupa kata sandi?</a></span>
                </div>
            </div>
        </div>


        <!-- Signup Form -->

        <div class="form signup">
            <div class="form-content">
                <img src="img/logo1.png" alt="logo">
                <header>Daftar Pelayanan Jasa</header>
                <div class="line"></div>
                <form action="php/daftar_penyediajasa.php" method="POST" id="signupForm">
                    <div class="field input-field">
                        <label for="nama-pelayanan">Nama Penyedia Jasa</label>
                        <input type="text" id="nama_penyedia" name="nama_penyedia" placeholder="Nama Penyedia Jasa" class="input" required>
                    </div><br>

                    <div class="field input-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email Bisnis" class="input" required>
                    </div><br>

                    <div class="field input-field">
                        <label for="password">Kata Sandi</label>
                        <input type="password" id="password" name="password" placeholder="Buat Kata Sandi" class="password" required>
                    </div><br>

                    <div class="field input-field">
                        <label for="no-hp">Nomor Telepon</label>
                        <input type="text" id="no_hp" name="no_hp" placeholder="Nomor Telepon Bisnis" class="input" required>
                    </div><br>

                    <div class="field input-field">
                        <label for="alamat">Alamat Pelayanan</label>
                        <input type="text" id="alamat" name="alamat" placeholder="Alamat Lengkap Jasa Anda" class="input" required>
                    </div><br>

                    <div class="field button-field">
                        <button id="masukBtn" type="submit">Daftar sebagai Penyedia Jasa</button>
                    </div>
                </form>

                <div class="form-link">
                    <span>Sudah punya akun? <a href="#" class="link login-link">Masuk</a></span>
                </div>
            </div>
        </div>

    </section>

    <!-- Footer Start -->
    <div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-dark">Layanan</h4>
                        <p style="color:black">Kami dapat membantu anda dalam memberikan layanan untuk warga perumahan.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-dark">Info Kontak</h4>
                        <a href=""><i class="fa fa-map-marker-alt me-2"></i> Polibatam</a>
                        <a href=""><i class="fas fa-envelope me-2"></i> fixquick@rpl503.com</a>
                        <a href=""><i class="fas fa-phone me-2"></i> +62 895 6036 19387</a>
                        <div class="d-flex align-items-center">
                            <a class="btn-square btn btn-primary border border-teal rounded mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn-square btn btn-primary border border-teal rounded mx-1" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn-square btn btn-primary border border-teal rounded mx-1" href=""><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Copyright Start -->
    <div class="container-fluid copyright py-1">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-6 text-center text-md-start mb-md-0">
                    <span class="text-white"><i class="fas fa-copyright text-light me-2"></i>2024-<span id="year"></span> Project Base Learning |<a href="#"> FixQuick</a></span>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->


    <script>
        document.getElementById("role").addEventListener("change", function() {
            const role = this.value; // Mendapatkan nilai role
            const dynamicFields = document.getElementById("dynamicFields"); // Tempat field akan berubah

            // Bersihkan konten sebelumnya
            dynamicFields.innerHTML = "";

            if (role === "resident") {
                // Jika role adalah 'resident', tampilkan Nama dan No Telepon
                dynamicFields.innerHTML = `
            <div class="field input-field">
                <label for="user_email">Email</label>
                <input type="text" id="user_email" name="email_warga" placeholder="Email" class="input" required>
            </div><br>

            <div class="field input-field">
                <label for="sandiwarga">Kata Sandi</label>
                <input type="password" id="sandiwarga" name="sandi_warga" placeholder="Kata Sandi" class="password" required>
                <i class='bx bx-hide eye-icon' onclick="togglePasswordVisibility('sandiwarga', this)"></i>
            </div><br>
        `;
            } else {
                // Jika bukan 'resident', kembalikan ke Email dan Password
                dynamicFields.innerHTML = `
            <div class="field input-field">
                <label for="user_email">Email</label>
                <input type="email" id="user_email" name="email" placeholder="Email" class="input" required>
            </div><br>

            <div class="field input-field">
                <label for="user_password">Kata Sandi</label>
                <input type="password" id="user_password" name="password" placeholder="Kata Sandi" class="password" required>
                 <i class='bx bx-hide eye-icon' onclick="togglePasswordVisibility('user_password', this)"></i>
            </div><br>
        `;
            }
        });

        function togglePasswordVisibility(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                input.type = "password";
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }
    </script>
    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- script tahunan -->
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>

    <script src="js/main.js"></script>
    <script src="js/login.js"></script>
</body>

</html>



<!-- JsMasuk -->
<!-- <script>
        document.getElementById('Masuk').addEventListener('click', function(event) {
            event.preventDefault();
            console.log("Tombol Masuk diklik"); 
            Swal.fire({
                position: "top",
                icon: "success",
                title: "Berhasil Login",
                showConfirmButton: false,
                timer: 1000
            });
            setTimeout(function() {
                window.location.href = '../fqadminperumahan/index.html';
            }, 1000); 
        });
    </script>     -->
<!-- <script>
        document.getElementById('masukBtn').addEventListener('click', function() {
            event.preventDefault();
            console.log("Tombol Masuk diklik"); 
            window.location.href = 'pelayananjasa/jasadash.html';
        });
    </script> -->