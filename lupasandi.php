<?php
require 'php/koneksi_resetpss.php'; // Pastikan koneksi database berhasil

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $userType = ''; // Untuk menyimpan tipe pengguna

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Cek email di tabel Warga
        $stmt = $pdo->prepare("SELECT id_warga FROM warga WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $userType = 'warga';
            $userId = $stmt->fetchColumn();
        }

        // Cek email di tabel Perumahan
        if (!$userType) {
            $stmt = $pdo->prepare("SELECT id_perumahan FROM perumahan WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $userType = 'perumahan';
                $userId = $stmt->fetchColumn();
            }
        }

        // Cek email di tabel Pelayanan Jasa
        if (!$userType) {
            $stmt = $pdo->prepare("SELECT id_pelayanan_jasa FROM pelayanan_jasa WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $userType = 'pelayanan_jasa';
                $userId = $stmt->fetchColumn();
            }
        }

        // Jika ditemukan, buat token reset password
        if ($userType) {
            $token = bin2hex(random_bytes(50));
            $stmt = $pdo->prepare("UPDATE $userType SET reset_token = ?, reset_token_expires = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email = ?");
            $stmt->execute([$token, $email]);

            // Kirim email
            // $resetLink = "http://localhost/PBL-TRPL503/FixQuickWebsite/resetpassword.php?token=$token&user=$userType";
            $resetLink = "https://fixquick.wuaze.com/reset_password.php?token=$token";
            $subject = "Reset Password Anda";
            $message = "
            Hai,

            Kami menerima permintaan untuk mengatur ulang kata sandi Anda.
            Klik tautan berikut untuk mengatur ulang sandi Anda:

            $resetLink

            Tautan ini berlaku selama 15 menit.

            Salam,
            Admin FixQuick";

            $headers = "From: admin@FixQuick.com";
            if (mail($email, $subject, $message, $headers)) {
                echo "Email telah dikirim ke $email. Periksa kotak masuk Anda.";
            } else {
                echo "Gagal mengirim email. Coba lagi nanti.";
            }
        } else {
            echo "Email tidak ditemukan di sistem kami.";
        }
    } else {
        echo "Email tidak valid.";
    }
}
?>

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

    <a href="login.php" class="back-button" aria-label="Kembali ke halaman login">
        <i class='bx bx-arrow-back'></i> Kembali
    </a>

    <!-- Form Start -->
    <section class="container forms">
        <div class="form login">
            <div class="form-content">
                <img src="img/logo1.png" alt="logo">
                <header>Lupa Kata Sandi?</header>
                <p style="text-align: center; color: #88939D;font-size: 12px;">Masukkan email yang anda gunakan untuk akun tersebut dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi</p>
                <!-- <form method="post">
        <label for="email">Masukkan Email Anda:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Kirim tautan ke email saya</button>
    </form> -->
                <form method="post"> <!-- Menambahkan method POST -->
                    <div class="field input-field">
                        <label for="email" style="font-size: 15px;">MASUKKAN EMAIL ANDA</label>
                        <input type="email" id="email" name="email" placeholder="Email" class="input" required> <!-- Menambahkan name dan required -->
                    </div><br>
                    <div class="field button-field">
                        <button type="submit">Kirim tautan ke email saya</button> <!-- Pastikan type adalah submit -->
                    </div>
                    <div class="line"></div>
                    <p style="text-align: center; color: #88939D;font-size: 10px; margin-top: 10px;">Jika anda tidak melihat tautan email untuk mengatur ulang kata sandi anda, silakan periksa folder spam anda</p>
                </form>
            </div>
        </div>


        <!-- Form berhasil mengirimkan email -->

        <div class="form signup">
            <div class="form-content">
                <img src="img/logo1.png" alt="logo">
                <img src="img/centang.png" alt="berhasil!" style="font-size: 30px; text-align: center;">
                <header>Link Tautan terkirim!</header>
                <p style="text-align: center; font-size: 12px; color: #88939D;">Periksa email anda untuk tautan pengaturan ulang kata sandi</p>
                <form action="#">

                    <div class="field button-field">
                        <button disabled style="cursor:default">Buka aplikasi email Anda!</button>
                    </div>
                    <div class="line"></div>
                    <p style="text-align: center; color: #88939D; font-size: 10px;">Jika anda tidak melihat tautan email untuk mengatur ulang kata sandi anda, silakan periksa folder spam di dalam email anda</p>
                </form>
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


    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="js/login.js"></script>
</body>
<!-- script tahunan -->
<script>
    document.getElementById("year").textContent = new Date().getFullYear();
</script>
<!-- JsMasuk -->
<script>
    document.getElementById('Masuk').addEventListener('click', function() {
        event.preventDefault();
        console.log("Tombol Masuk diklik");
        window.location.href = 'adminperumahan/perumdash.html';
    });
</script>
<script>
    document.getElementById('masukBtn').addEventListener('click', function() {
        event.preventDefault();
        console.log("Tombol Masuk diklik");
        window.location.href = 'pelayananjasa/jasadash.html';
    });
</script>

</html>