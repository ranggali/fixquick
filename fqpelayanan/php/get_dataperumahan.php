<?php
include "connection_db.php";

// Query untuk mendapatkan data dari tabel pengajuan_layanan dan pelayanan_jasa
$sql = "
    SELECT
         id_perumahan,
         nama_pengguna,
         nama_perumahan,
         alamat,
         deskripsi_perumahan,
         no_telepon,
          CONCAT('../fqperumahan/php/', foto_perumahan) AS foto_url,
         email
    FROM perumahan 
";

// Persiapkan statement
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Eksekusi query langsung
    mysqli_stmt_execute($stmt);

    // Ambil semua hasil sebagai array asosiatif
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Tutup statement dan koneksi
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    $data = [];
    echo "Error: " . mysqli_error($conn);
}
?>