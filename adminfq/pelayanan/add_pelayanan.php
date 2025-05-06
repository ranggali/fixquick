<?php
// Konfigurasi database
$host = "localhost"; // Ganti dengan host database Anda
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "fixquick_db"; // Ganti dengan nama database Anda

// Membuat koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_pengguna = $conn->real_escape_string($_POST['PenyediaJasaName']);
    $email = $conn->real_escape_string($_POST['PelayananJasaEmail']);
    $kategori_layanan = $conn->real_escape_string($_POST['KategoriPelayananJasa']);
    $no_telepon = $conn->real_escape_string($_POST['PelayananJasaNohp']);
    $alamat = $conn->real_escape_string($_POST['PelayananJasaAlamat']);
    $status = $conn->real_escape_string($_POST['statusPelayananJasa']);

    // Query untuk menyimpan data
    $sql = "INSERT INTO pelayanan_jasa (nama_penyedia_jasa, email, kategori_layanan, no_telepon, alamat, is_aktif,created_at, updated_at) 
            VALUES ('$nama_pengguna', '$email','$kategori_layanan', '$no_telepon', '$alamat', '$status',NOW(),NOW())";

    // Eksekusi query
    if ($conn->query($sql) === TRUE) {
        // Menampilkan alert melalui JavaScript
        echo "<script>
                alert('Berhasil tambah data!');
                window.location.href = 'pelayananjasa.php'; // Redirect ke halaman utama setelah sukses
              </script>";
    } else {
        // Menampilkan alert gagal
        echo "<script>
                alert('Gagal menambah data. Error: " . $conn->error . "');
                window.history.back(); // Kembali ke halaman sebelumnya
              </script>";
    }
}

// Menutup koneksi database
$conn->close();
?>
