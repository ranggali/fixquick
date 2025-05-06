<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Admin FixQuick</title>
    <link rel="icon" href="assets/logo/logo1.png">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="wrapper">
        <img src="assets/logo/logo.png" alt="logo" height="100" width="100">
        <header>Daftar Admin</header>
        <form action="php/register_admin.php" method="POST" id="registerForm">
            <div class="field nama">
                <div class="input-area">
                    <input type="text" id="nama_admin" name="nama_admin" placeholder="Nama Admin">
                    <i class="icon fas fa-user"></i>
                    <i class="error error-icon fas fa-exclamation-circle"></i>
                </div>
                <div class="error error-txt">Nama tidak boleh kosong!</div>
            </div>
            <div class="field email">
                <div class="input-area">
                    <input type="text" id="email" name="email" placeholder="Email">
                    <i class="icon fas fa-envelope"></i>
                    <i class="error error-icon fas fa-exclamation-circle"></i>
                </div>
                <div class="error error-txt">Email tidak boleh kosong!</div>
            </div>
            <div class="field password">
                <div class="input-area">
                    <input type="password" id="kata_sandi" name="kata_sandi" placeholder="Kata Sandi">
                    <i class="icon fas fa-lock"></i>
                    <i class="error error-icon fas fa-exclamation-circle"></i>
                </div>
                <div class="error error-txt">Kata sandi tidak boleh kosong!</div>
            </div>
            <input type="submit" value="Daftar" id="daftar">
        </form>
        <div class="sign-txt"> <a href="login.php">Login</a></div>
    </div>
    <script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah form refresh
        const namaAdmin = document.getElementById('nama_admin').value;
        const email = document.getElementById('email').value;
        const kataSandi = document.getElementById('kata_sandi').value;

        // Validasi sederhana
        if (!namaAdmin || !email || !kataSandi) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Nama, Email, dan Kata Sandi tidak boleh kosong!',
            });
            return;
        }

        // Kirim data ke PHP menggunakan Fetch API
        fetch('php/register_admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `nama_admin=${encodeURIComponent(namaAdmin)}&email=${encodeURIComponent(email)}&kata_sandi=${encodeURIComponent(kataSandi)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                    }).then(() => {
                        // Redirect ke halaman login.html setelah notifikasi
                        window.location.href = 'login.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan pada server!',
                });
            });
    });
    </script>

</body>
</html>