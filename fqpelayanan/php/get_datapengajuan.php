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
$nama_penyedia_jasa = $_SESSION['nama_penyedia_jasa'];
$id_pelayanan_jasa = $_SESSION['id_pelayanan_jasa'];

// Query untuk mendapatkan data dari tabel pengajuan_layanan dan pelayanan_jasa
$sql = "
    SELECT 
        pl.id_pengajuan, 
        pl.kategori_jasa, 
        pl.status_pengajuan, 
        pl.created_at, 
        pj.nama_penyedia_jasa 
    FROM pengajuan_pelayanan pl
    JOIN pelayanan_jasa pj ON pl.id_pelayanan_jasa = pj.id_pelayanan_jasa
    WHERE pl.id_pelayanan_jasa = ?
    ORDER BY pl.created_at DESC
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