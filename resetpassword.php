<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'php/koneksi_resetpss.php';

    $token = $_POST['token'];
    $userType = $_POST['user'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $validUserTypes = ['warga', 'perumahan', 'pelayanan_jasa'];
    if (!in_array($userType, $validUserTypes)) {
        die("Tipe pengguna tidak valid.");
    }

    $stmt = $pdo->prepare("SELECT id_$userType FROM $userType WHERE reset_token = ? AND reset_token_expires > NOW()");
    $stmt->execute([$token]);
    
    if ($stmt->rowCount() > 0) {
        $userId = $stmt->fetchColumn();
        $stmt = $pdo->prepare("UPDATE $userType SET kata_sandi = ?, reset_token = NULL, reset_token_expires = NULL WHERE id_$userType = ?");
        $stmt->execute([$password, $userId]);
        echo "Kata sandi berhasil diperbarui.";
    } else {
        echo "Token tidak valid atau telah kedaluwarsa.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="icon" href="img/logo1.png">
    <title>Reset Kata Sandi</title>
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

    <!-- Form Start -->
    <section class="container forms">
        <div class="form login">
            <div class="form-content">
                <img src="img/logo1.png" alt="logo">
                <header>Reset Ulang Kata Sandi?</header>
                <p style="text-align: center; color: #88939D;font-size: 12px;">Masukkan kata sandi baru anda pada kolom diawah ini!</p>
                <!-- <form method="post">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                        <input type="hidden" name="user" value="<?php echo htmlspecialchars($_GET['user']); ?>">
                        <label for="password">Masukkan Password Baru:</label>
                        <input type="password" name="password" id="password" required>
                        <button type="submit">Reset Password</button>
                    </form> -->
                <form method="post">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                    <input type="hidden" name="user" value="<?php echo htmlspecialchars($_GET['user']); ?>">
                    <div class="field input-field">
                        <label for="password" style="font-size: 15px;">MASUKKAN KATA SANDI BARU ANDA</label>
                        <input type="text" id="passowrd" name="password" required placeholder="kata sandi baru" class="input">
                    </div><br>

                    <div class="field button-field">
                        <button type="submit">Kirim</button>
                    </div>
                    <div class="line"></div>
                    <p style="text-align: center; color: #88939D;font-size: 10px; margin-top: 10px;">Jika anda tidak melihat tautan email untuk mengatur ulang kata sandi anda, silakan periksa folder spam anda</p>
                </form>
            </div>
        </div>


        <!-- Signup Form -->

        <div class="form signup">
            <div class="form-content">
                <img src="img/logo1.png" alt="logo">
                <img src="img/centang.png" alt="berhasil!" style="font-size: 30px; text-align: center;">
                <header>Selamat!</header>
                <p style="text-align: center; font-size: 12px; color: #88939D;">Kata sandi anda telah diperbarui!</p>
                <form action="#">

                    <div class="field button-field">
                        <button type="button" onclick="masukAplikasi()">Masuk aplikasi</button>
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
<script>
    function masukAplikasi() {
        window.location.href = "login.php";
    }
</script>

</html>