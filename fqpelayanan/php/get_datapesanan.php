<?php
// Memeriksa apakah sesi sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('connection_db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'provider') {
    echo "<script>
    alert('Anda harus login sebagai admin.');
    window.location.href = '../FixQuickWebsite/login.php';
</script>";
    exit;
}

$id_pelayanan_jasa = $_SESSION['id_pelayanan_jasa'];

// Query untuk mendapatkan data dari tabel pesanan_layanan, warga, layanan, dan perumahan
$sql = "
    SELECT 
        pl.id_pesanan, 
        pl.status_pesanan, 
        pl.id_layanan, 
        l.kategori_jasa, 
        pl.status_pembayaran, 
        pl.tanggal_pesanan, 
        pl.total_pembayaran, 
        pl.catatan_tambahan, 
        w.nama_warga, 
        p.nama_perumahan
    FROM pesanan_layanan pl
    JOIN warga w ON pl.id_warga = w.id_warga
    JOIN layanan l ON pl.id_layanan = l.id_layanan
    JOIN perumahan p ON w.id_perumahan = p.id_perumahan
    WHERE pl.id_pelayanan_jasa = ?
    ORDER BY pl.tanggal_pesanan DESC
";

// Persiapkan statement
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Bind parameter dan eksekusi query
    mysqli_stmt_bind_param($stmt, "s", $id_pelayanan_jasa);
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
