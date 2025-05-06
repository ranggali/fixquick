<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('connection_db.php');

$namaPengguna = $_SESSION['nama_warga'];
$id_warga = $_SESSION['id_warga'];

// Query untuk mendapatkan data hanya berdasarkan id_warga yang sedang login
$sql = "
    SELECT 
        pl.nomor_invoice, 
        pl.tanggal_pesanan, 
        pl.status_pesanan, 
        w.nama_warga, 
        l.nama_penyedia_layanan, 
        l.kategori_jasa, 
        p.id_perumahan
    FROM pesanan_layanan pl
    JOIN warga w ON pl.id_warga = w.id_warga
    JOIN layanan l ON pl.id_layanan = l.id_layanan
    JOIN perumahan p ON w.id_perumahan = p.id_perumahan
    WHERE w.id_warga = ?
    ORDER BY pl.tanggal_pesanan DESC
";

// Persiapkan statement
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Bind parameter dan eksekusi query
    mysqli_stmt_bind_param($stmt, "s", $id_warga);
    mysqli_stmt_execute($stmt);

    // Ambil hasil query
    $result = mysqli_stmt_get_result($stmt);

    // Fetch semua data sebagai array asosiatif
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Tutup statement
    mysqli_stmt_close($stmt);
} else {
    $data = [];
    echo "Error: " . mysqli_error($conn);
}
?>
