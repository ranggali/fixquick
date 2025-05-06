<?php
include('../php/connection_db.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
    alert('Anda harus login sebagai admin.');
    window.location.href = '../FixQuickWebsite/login.php';
</script>";
    exit;
}

$id_perumahan = $_SESSION['id_perumahan'];

// Periksa apakah form sudah di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_penyedia_layanan = $_POST['nama_penyedia_layanan'];
    $kategori_jasa = $_POST['kategori_jasa'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];

    // Validasi input
    if (empty($nama_penyedia_layanan) || empty($kategori_jasa) || empty($deskripsi) || empty($harga)) {
        echo "<script>
            alert('Semua kolom wajib diisi.');
            window.history.back();
        </script>";
        exit;
    }

    // Query untuk menyisipkan data ke tabel layanan dengan status_layanan default 'aktif'
    $query = "INSERT INTO layanan (nama_penyedia_layanan, kategori_jasa, id_perumahan, harga, deskripsi, status_layanan) 
              VALUES (?, ?, ?, ?, ?, 'aktif')";

    // Siapkan dan eksekusi statement
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssis", $nama_penyedia_layanan, $kategori_jasa, $id_perumahan, $harga, $deskripsi);

        if ($stmt->execute()) {
            echo "<script>
                alert('Layanan berhasil ditambahkan.');
                window.location.href = 'pelayananjasa.php'; // Ganti dengan halaman tujuan
            </script>";
        } else {
            echo "<script>
                alert('Terjadi kesalahan saat menambahkan layanan.');
                window.history.back();
            </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
            alert('Kesalahan pada server: " . $conn->error . "');
            window.history.back();
        </script>";
    }
}
?>
