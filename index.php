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
        <div class="spinner-border text-teal" style="width: 5rem; height: 5rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar & Hero Start -->
    <div class="container-fluid header position-relative overflow-hidden p-0">
        <nav class="navbar navbar-expand-lg fixed-top navbar-light px-4 px-lg-5 py-3 py-lg-0">
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
                    <a href="index.php" class="nav-item nav-link active">Beranda</a>
                    <a href="about.html" class="nav-item nav-link">Tentang</a>
                    <!-- <a href="service.html" class="nav-item nav-link">Services</a> -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Menu</a>
                        <div class="dropdown-menu m-0">
                            <a href="feature.html" class="dropdown-item">Fitur</a>
                            <a href="service.html" class="dropdown-item">Layanan</a>
                            <a href="alur.html" class="dropdown-item">Alur</a>
                            <a href="contact.php" class="dropdown-item">Kontak Kami</a>
                            <!-- <a href="404.html" class="dropdown-item">404 Page</a> -->
                        </div>
                    </div>
                    <!-- <a href="contact.html" class="nav-item nav-link">Kontak Kami</a> -->
                </div>
                <a href="login.php"
                    class="btn btn-primary border border-teal rounded-2 text-white py-2 px-4 me-4">Masuk</a>
                <!-- <a href="signup.html" class="btn btn-primary border border-teal rounded-pill text-white py-2 px-4">Sign Up</a> -->
            </div>
        </nav>


        <!-- Hero Header Start -->
        <div class="hero-header overflow-hidden px-5">
            <div class="rotate-img">
                <img src="img/sty-1.png" class="img-fluid w-100" alt="">
                <div class="rotate-sty-2"></div>
            </div>
            <div class="row gy-5 align-items-center">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                    <h1 class="display-4 text-dark mb-4 wow fadeInUp" data-wow-delay="0.3s">Permudah Pemeliharaan dan
                        Pesanan Layanan di Rumah Anda</h1>
                    <p class="fs-4 mb-4 wow fadeInUp" data-wow-delay="0.5s">Kelola semua kebutuhan perbaikan rumah Anda
                        dengan mudah melalui FixQuick, platform terpercaya untuk memesan layanan yang andal di berbagai
                        perumahan. Dapatkan pembaruan secara real-time dan pastikan layanan berkualitas, semuanya dalam
                        satu tempat.</p>
                    <a href="login.php" class="btn btn-primary border border-teal rounded-2 py-3 px-5 wow fadeInUp"
                        data-wow-delay="0.7s">Mulai Sekarang</a>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.2s">
                    <img src="img/compete.png" class="img-fluid w-100 h-100" alt="">
                </div>
            </div>
        </div>
        <!-- Hero Header End -->
    </div>
    <!-- Navbar & Hero End -->


    <!-- About Start -->
    <div class="container-fluid overflow-hidden py-5" style="margin-top: 6rem;">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="RotateMoveLeft">
                        <img src="img/Prpm.png" class="img-fluid w-100" alt="">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <h4 class="mb-1 text-primary">Alur Pendaftaran Aplikasi</h4>
                    <h1 class="display-5 mb-4">Hubungkan Penghuni, Perumahan, dan Penyedia Jasa</h1>
                    <p class="mb-4">FixQuick adalah penghubung bagi semua pihak di perumahan. Perumahan mendaftar, menambahkan warga dan layanan. Penyedia jasa mendaftar dan mengelola pesanan. Warga dengan mudah memesan layanan yang dibutuhkan. Semuanya terintegrasi dalam satu platform.</p>
                    <a href="alur.html" class="btn btn-primary border border-teal rounded-2 py-3 px-5">Selengkapnya</a>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Service Start -->
    <div class="container-fluid bg-light service py-5">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
                <h4 class="mb-1 text-primary">Layanan Kami</h4>
                <h1 class="display-5 mb-4">Apa yang Kami Tawarkan untuk Anda</h1>
                <p class="mb-0">Kami menyediakan berbagai layanan yang dapat memudahkan kebutuhan perawatan dan
                    pemeliharaan hunian Anda. Dengan tenaga profesional dan berpengalaman, kami siap memberikan solusi
                    cepat, aman, dan efisien. Baik itu perbaikan, pemeliharaan, atau layanan khusus lainnya, tim kami
                    selalu siap membantu menjaga kenyamanan dan keamanan hunian Anda. Nikmati pengalaman layanan
                    berkualitas yang dapat diandalkan setiap saat.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="service-item text-center rounded p-4">
                        <div class="service-icon d-inline-block bg-light rounded p-4 mb-4"><img src="lib/icon/clock.svg"
                                alt="Key Icon" class="img-fluid" style="width: 70px; height: auto;"></div>
                        <div class="service-content">
                            <h4 class="mb-4">Layanan 24/7</h4>
                            <p class="mb-4">Kami akan selalu siap membantu Anda. Jika terjadi kendala dalam
                                pengoperasian aplikasi FixQuick, jangan ragu untuk menghubungi kami.
                            </p>
                            <a href="service.html"
                                class="btn btn-light rounded-pill text-primary py-2 px-4">Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item text-center rounded p-4">
                        <div class="service-icon d-inline-block bg-light rounded p-4 mb-4"><img src="lib/icon/lock.svg"
                                alt="Key Icon" class="img-fluid" style="width: 70px; height: auto;"></div>
                        <div class="service-content">
                            <h4 class="mb-4">Keamanan Data dan Privasi</h4>
                            <p class="mb-4">Menjamin informasi pengguna aman dan tidak akan disalahgunakan.
                            </p>
                            <a href="service.html"
                                class="btn btn-light rounded-pill text-primary py-2 px-4">Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item text-center rounded p-4">
                        <div class="service-icon d-inline-block bg-light rounded p-4 mb-4"><img src="lib/icon/key.svg"
                                alt="Key Icon" class="img-fluid" style="width: 70px; height: auto;"></div>
                        <div class="service-content">
                            <h4 class="mb-4">Sistem Manajemen Akses </h4>
                            <p class="mb-4">Meningkatkan keamanan kompleks dan memberikan rasa aman bagi penghuni.
                            </p>
                            <a href="service.html"
                                class="btn btn-light rounded-pill text-primary py-2 px-4">Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item text-center rounded p-4">
                        <div class="service-icon d-inline-block bg-light rounded p-4 mb-4"><img
                                src="lib/icon/search.svg" alt="Key Icon" class="img-fluid"
                                style="width: 70px; height: auto;"></div>
                        <div class="service-content">
                            <h4 class="mb-4">Audit Keamanan dan Penilaian Risiko</h4>
                            <p class="mb-4">Membantu pengelola perumahan mengidentifikasi potensi risiko dan
                                mengimplementasikan solusi yang sesuai.
                            </p>
                            <a href="service.html"
                                class="btn btn-light rounded-pill text-primary py-2 px-4">Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->


    <!-- Feature Start -->
    <div class="container-fluid feature overflow-hidden py-5">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
                <h4 class="text-primary">Fitur Kami</h4>
                <h1 class="display-5 mb-4">Fitur Penting pada FixQuick: Platform Pemesanan dan Perawatan Multi-Residence
                </h1>
                <p class="mb-0">FixQuick memudahkan penghuni perumahan dalam mengajukan permintaan layanan perawatan,
                    memantau status permintaan secara real-time, dan menerima notifikasi dari penyedia layanan maupun
                    admin. Platform ini juga menyediakan sistem manajemen yang efisien untuk admin perumahan dan
                    penyedia layanan, memastikan koordinasi yang lancar dan cepat.</p>
            </div>
            <div class="row g-4 justify-content-center text-center mb-5">
                <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="text-center p-4">
                        <div class="d-inline-block rounded bg-light p-4 mb-4"><img src="lib/icon/status-info.svg"
                                alt="Key Icon" class="img-fluid" style="width: 70px; height: auto;"></div>
                        <div class="feature-content">
                            <a href="#" class="h4">Pemantauan Status Layanan <i class="fas fa-check"></i></a>
                            <p class="mt-4 mb-0">pengguna dapat melihat status layanan yang sedang berjalan secara
                                real-time.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="text-center p-4">
                        <div class="d-inline-block rounded bg-light p-4 mb-4"><img src="lib/icon/paper-plane.svg"
                                alt="Key Icon" class="img-fluid" style="width: 70px; height: auto;"></div>
                        <div class="feature-content">
                            <a href="#" class="h4">Permintan Layanan Mudah <i class="fas fa-check"></i></a>
                            <p class="mt-4 mb-0">Pengguna dapat dengan mudah mengajukan permintaan layanan melalui
                                antarmuka yang intuitif dan sederhana.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="text-center rounded p-4">
                        <div class="d-inline-block rounded bg-light p-4 mb-4"><img src="lib/icon/notification.svg"
                                alt="Key Icon" class="img-fluid" style="width: 70px; height: auto;"></div>
                        <div class="feature-content">
                            <a href="#" class="h4">Notifikasi Real-Time <i class="fas fa-check"></i></a>
                            <p class="mt-4 mb-0">Sistem notifikasi otomatis memberi tahu pengguna tentang status
                                permintaan layanan mereka, termasuk konfirmasi permintaan, pembaruan progres, dan
                                penyelesaian layanan.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="text-center rounded p-4">
                        <div class="d-inline-block rounded bg-light p-4 mb-4"><img src="lib/icon/list.svg"
                                alt="Key Icon" class="img-fluid" style="width: 70px; height: auto;"></div>
                        <div class="feature-content">
                            <a href="#" class="h4">Riwayat Pesanan <i class="fas fa-check"></i></a>
                            <p class="mt-4 mb-0">Pengguna dapat mengakses riwayat lengkap layanan yang telah mereka
                                gunakan, termasuk detail tentang jenis layanan, tanggal, dan biaya yang dikeluarkan.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="my-3">
                        <a href="feature.html"
                            class="btn btn-primary border border-teal d-inline rounded-2 px-5 py-3">Fitur Lainnya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Feature End -->

    <!-- FAQ Start -->
    <div class="container-fluid FAQ bg-light overflow-hidden py-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item mb-4">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button rounded-top " type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Bagaimana cara mendaftar di FixQuick?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body my-2">
                                    <h5>Caranya mudah.</h5>
                                    <p>Untuk mendaftar, Pengelola perumahan perlu menghubungi Admin Website di <a href="mailto:ikans657@gmail.com" style="color: #045048; font-family: 'DM Sans', sans-serif; font-weight: bold; font-style: italic;">fixquick_trpl503@gmail.com</a> agar
                                        didaftarkan ke sistem FixQuick.</p>
                                    <p>Setelah terdaftar, pengguna akan menerima informasi login melalui email atau SMS.
                                        Selanjutnya, pengguna dapat login menggunakan informasi yang diberikan dan mulai
                                        menggunakan layanan yang tersedia.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-4">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTTwo">
                                    Apa itu FixQuick dan bagaimana cara kerjanya?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body my-2">
                                    <h5>FixQuick?</h5>
                                    <p>FixQuick adalah platform yang memungkinkan penghuni perumahan untuk memesan
                                        layanan pemeliharaan dengan mudah. Pengguna (penghuni) bisa membuat permintaan
                                        layanan seperti perbaikan listrik, kebersihan, atau perbaikan lainnya.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed rounded-down" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                                    aria-controls="collapseThree">
                                    Apakah saya bisa memesan layanan dari penyedia jasa di luar perumahan saya?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body my-2">
                                    <h5>Tidak!</h5>
                                    <p> layanan FixQuick dirancang untuk mendukung pemesanan jasa yang telah terdaftar
                                        dan bekerja sama dengan perumahan Anda. </p>
                                    <p>Hal ini untuk memastikan bahwa layanan yang diberikan sudah diverifikasi dan
                                        sesuai dengan standar kualitas yang ditetapkan oleh pengelola perumahan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                    <div class="FAQ-img RotateMoveRight rounded">
                        <img src="img/consultant1.png" class="img-fluid w-100" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FAQ End -->

    <!-- client Start -->
    <div class="container-fluid feature overflow-hidden py-5">
        <div class="container py-5">
            <div class="row g-5 pt-5" style="margin-top: 6rem;">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                    <div class="feature-img RotateMoveLeft h-100" style="object-fit: cover;">
                        <img src="img/client-meeting.png" class="img-fluid w-100 h-100" alt="">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.1s"><br><br><br>
                    <h4 class="text-primary">Pengguna FixQuick</h4>
                    <h1 class="display-5 mb-4">Daftarkan diri anda segera dan jadi bagian dari kami!</h1>
                    <!-- <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, suscipit itaque quaerat dicta porro illum, autem, molestias ut animi ab aspernatur dolorum officia nam dolore. Voluptatibus aliquam earum labore atque.
                        </p> -->
                    <div class="row g-4">
                        <div class="col-6">
                            <div class="d-flex">
                                <i class="fas fa-home fa-4x text-dark"></i>
                                <div class="d-flex flex-column ms-3">
                                    <h2 class="mb-0 fw-bold">100</h2>
                                    <small class="text-dark">Pengelola Perumahan</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex">
                                <i class="fas fa-users fa-4x text-dark"></i>
                                <div class="d-flex flex-column ms-3">
                                    <h2 class="mb-0 fw-bold">230</h2>
                                    <small class="text-dark">Pelayanan Jasa</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="my-4">
                        <a href="login.php" class="btn btn-primary border border-teal rounded-2 py-3 px-5">Daftar
                            Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- client stop -->

    <!-- Blog Start -->

    <!-- Blog End -->

    <!-- Footer Start -->
    <div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-dark">Layanan</h4>
                        <p style="color:black">Kami dapat membantu anda dalam memberikan layanan untuk warga perumahan.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-dark">Info Kontak</h4>
                        <a href=""><i class="fa fa-map-marker-alt me-2"></i> Polibatam</a>
                        <a href=""><i class="fas fa-envelope me-2"></i> fixquick@rpl503.com</a>
                        <a href=""><i class="fas fa-phone me-2"></i> +62 895 6036 19387</a>
                        <div class="d-flex align-items-center">
                            <a class="btn-square btn btn-primary border border-teal rounded mx-1" href=""><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn-square btn btn-primary border border-teal rounded mx-1" href=""><i
                                    class="fab fa-twitter"></i></a>
                            <a class="btn-square btn btn-primary border border-teal rounded mx-1" href=""><i
                                    class="fab fa-instagram"></i></a>
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
                    <span class="text-white"><i class="fas fa-copyright text-light me-2"></i>2024-<span id="year"></span> Project Base Learning
                        |<a href="#"> FixQuick</a></span>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border border-teal btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>


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