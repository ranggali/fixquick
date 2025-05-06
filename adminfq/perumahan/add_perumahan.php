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
    $nama_pengguna = $conn->real_escape_string($_POST['NamaPengguna']);
    $nama_perumahan = $conn->real_escape_string($_POST['Perumahan']);
    $alamat = $conn->real_escape_string($_POST['AlamatPerum']);
    $no_telepon = $conn->real_escape_string($_POST['NoTeleponPerum']);
    $status = $conn->real_escape_string($_POST['statusPerumahan']);

    // Query untuk menyimpan data
    $sql = "INSERT INTO perumahan (nama_pengguna, nama_perumahan, alamat, no_telepon, is_aktif, created_at, updated_at) 
            VALUES ('$nama_pengguna', '$nama_perumahan', '$alamat', '$no_telepon', '$status', NOW(), NOW())";

    // Eksekusi query
    if ($conn->query($sql) === TRUE) {
        echo "Data perumahan berhasil ditambahkan.";
        // Redirect atau bisa menambahkan logic lain untuk menampilkan pesan sukses
        header("Location: adminperum.php"); // Redirect ke halaman utama setelah sukses
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Menutup koneksi database
$conn->close();
?>
