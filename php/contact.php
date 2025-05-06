<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST["phone"]);
    $subject = htmlspecialchars($_POST["subject"]);
    $message = htmlspecialchars($_POST["message"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email tidak valid.'); window.location.reload();</script>";
        exit;
    }

    $to = "pagis0469@gmail.com";
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $body = "Nama: $name\nEmail: $email\nNomor Telepon: $phone\nPesan:\n$message";

    if (!empty($subject) && mail($to, $subject, $body, $headers)) {
        echo "<script>
                alert('Pesan berhasil dikirim!');
                window.location.reload();
              </script>";
    } else {
        echo "<script>
                alert('Gagal mengirim pesan. Harap periksa input Anda.');
                window.location.reload();
              </script>";
    }
}
?>