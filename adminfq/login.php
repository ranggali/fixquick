<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin FixQuick</title>
    <link rel="icon" href="assets/logo/logo1.png">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="wrapper">
        <img src="assets/logo/logo.png" alt="logo" height="100" width="100">
        <header>Login Admin</header>
        <form id="loginForm" action="php/login_admin.php" method="POST">
            <div class="field email">
                <div class="input-area">
                    <input type="text" name="email" placeholder="Email">
                    <i class="icon fas fa-envelope"></i>
                    <i class="error error-icon fas fa-exclamation-circle"></i>
                </div>
                <div class="error error-txt">Email tidak boleh kosong!</div>
            </div>
            <div class="field password">
                <div class="input-area">
                    <input type="password" name="kata_sandi" placeholder="Kata Sandi">
                    <i class="icon fas fa-lock"></i>
                    <i class="error error-icon fas fa-exclamation-circle"></i>
                </div>
                <div class="error error-txt">Kata sandi tidak boleh kosong!</div>
            </div>
            <input type="submit" value="Login" id="masuk">
        </form>
        <div class="sign-txt"> <a href="register.php">Daftar Sebagai Admin</a></div>
    </div>
    <script>
    document.getElementById("loginForm").addEventListener("submit", function(event) {
        const emailField = document.querySelector("input[name='email']");
        const passwordField = document.querySelector("input[name='kata_sandi']");

        let isValid = true;

        if (!emailField.value.trim()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Email tidak boleh kosong!'
            });
            isValid = false;
        }
        if (!passwordField.value.trim()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Kata Sandi tidak boleh kosong!'
            });
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault(); // Cegah form terkirim
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
