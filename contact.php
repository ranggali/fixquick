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
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700&family=Rubik:wght@400;500&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar & Hero Start -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg navbar-light bg-transparent px-4 px-lg-5 py-3 py-lg-0">
            <a href="index.php" class="navbar-brand p-0">
                <h1 class="m-0"
                    style="font-family: 'Afacad Flux', ital; font-weight: 700; color: #333333; display: flex; align-items: center;">
                    <img src="img/logo1.png" alt="Logo"></i> FixQuick
                </h1>
                <!-- <img src="img/logo.png" alt="Logo"> -->
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.php" class="nav-item nav-link">Beranda</a>
                    <a href="about.html" class="nav-item nav-link">Tentang</a>
                    <!-- <a href="service.html" class="nav-item nav-link">Services</a> -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Menu</a>
                        <div class="dropdown-menu m-0">
                            <a href="feature.html" class="dropdown-item">Fitur</a>
                            <a href="service.html" class="dropdown-item">Layanan</a>
                            <a href="alur.html" class="dropdown-item">Alur</a>
                            <a href="contact.html" class="dropdown-item active">Kontak Kami</a>
                            <!-- <a href="404.html" class="dropdown-item">404 Page</a> -->
                        </div>
                    </div>
                    <!-- <a href="contact.html" class="nav-item nav-link">Kontak Kami</a> -->
                </div>
                <a href="login.php"
                    class="btn btn-primary border border-teal rounded-pill text-white py-2 px-4 me-4">Masuk</a>
                <!-- <a href="signup.html" class="btn btn-primary border border-teal rounded-pill text-white py-2 px-4">Sign Up</a> -->
            </div>
        </nav>
    </div>
    <!-- Navbar & Hero End -->


    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb">
        <ul class="breadcrumb-animation">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
        <div class="container text-center py-5" style="max-width: 900px;">
            <h3 class="display-3 mb-4 wow fadeInDown" data-wow-delay="0.1s">Kontak Kami</h1>
                <ol class="breadcrumb justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                    <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="#">Menu</a></li>
                    <li class="breadcrumb-item active text-primary">Kontak</li>
                </ol>
        </div>
    </div>
    <!-- Header End -->


    <!-- Contact Start -->
    <div class="container-fluid contact py-5">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
                <h4 class="text-primary mb-4">Kontak Kami</h4>
                <h1 class="display-5 mb-4">Hubungi Kami</h1>
                <p class="mb-0">Kami siap membantu Anda! Jika Anda memiliki pertanyaan, saran, atau membutuhkan bantuan,
                    jangan ragu untuk menghubungi kami. Tim kami akan segera merespons untuk memberikan dukungan yang
                    Anda butuhkan.</p>
            </div>
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                    <h2 class="display-5 mb-2">Senang Terhubung dengan Anda! </h2>
                    <p class="mb-4">Silahkan isi formulir di bawah ini untuk menghubungi kami. Tim kami akan segera
                        merespons pertanyaan atau permintaan Anda sesegera mungkin. Kami siap membantu Anda dengan
                        layanan terbaik. </p>
                    <form action="php/contact.php" method="POST">
                        <div class="row g-3">
                            <div class="col-lg-12 col-xl-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nama Lengkap">
                                    <label for="name">Nama Lengkap</label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xl-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Aktif">
                                    <label for="email">Email</label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xl-6">
                                <div class="form-floating">
                                    <input type="phone" class="form-control" id="phone" name="phone" placeholder="Nomor Telepon">
                                    <label for="phone">Nomor Telepon</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject">
                                    <label for="subject">Subjek</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a message here" id="message" name="message"
                                        style="height: 160px"></textarea>
                                    <label for="message">Pesan</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Kirim Pesan</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-light d-flex align-items-center justify-content-center mb-3"
                            style="width: 90px; height: 90px; border-radius: 50px;"><i
                                class="fa fa-map-marker-alt fa-2x text-primary"></i></div>
                        <div class="ms-4">
                            <h4>Alamat</h4>
                            <p class="mb-0">Polibatam</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-light d-flex align-items-center justify-content-center mb-3"
                            style="width: 90px; height: 90px; border-radius: 50px;"><i
                                class="fa fa-phone-alt fa-2x text-primary"></i></div>
                        <div class="ms-4">
                            <h4>Mobile</h4>
                            <p class="mb-0">+62 895 6036 19387</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-light d-flex align-items-center justify-content-center mb-3"
                            style="width: 90px; height: 90px; border-radius: 50px;"><i
                                class="fa fa-envelope-open fa-2x text-primary"></i></div>
                        <div class="ms-4">
                            <h4>Email</h4>
                            <p class="mb-0">fixquick@trpl503.com</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                style="width: 90px; height: 90px; border-radius: 50px;"><i
                                    class="fas fa-share fa-2x text-primary"></i></div>
                        </div>
                        <div class="d-flex">
                            <a class="btn btn-lg-square btn-primary rounded-circle me-2" href=""><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-lg-square btn-primary rounded-circle mx-2" href=""><i
                                    class="fab fa-twitter"></i></a>
                            <a class="btn btn-lg-square btn-primary rounded-circle mx-2" href=""><i
                                    class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="rounded h-100">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.057868674703!2d104.04609977496546!3d1.1186745988706095!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d98921856ddfab%3A0xf9d9fc65ca00c9d!2sPoliteknik%20Negeri%20Batam!5e0!3m2!1sid!2sid!4v1728906078876!5m2!1sid!2sid"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->


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


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>

    <!-- script tahunan -->
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>