<?php
include('../php/connection_db.php');
session_start();

// Cek jika user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Anda harus login sebagai admin.']);
    exit;
}

$idPerumahan = $_SESSION['id_perumahan'];

// Ambil data dari request JSON
$data = json_decode(file_get_contents('php://input'), true);
$idPengajuan = $data['idPengajuan'];

// Query untuk mendapatkan detail pengajuan
$queryPengajuan = "
    SELECT 
        p.id_pelayanan_jasa, 
        p.kategori_jasa, 
        p.deskripsi_jasa, 
        p.harga, 
        pj.nama_penyedia_jasa
    FROM pengajuan_pelayanan p
    JOIN pelayanan_jasa pj 
        ON p.id_pelayanan_jasa = pj.id_pelayanan_jasa
    WHERE p.id_pengajuan = ?
";

$stmt = mysqli_prepare($conn, $queryPengajuan);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $idPengajuan);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $pengajuan = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($pengajuan) {
        // Insert data ke tabel layanan
        $queryInsertLayanan = "
            INSERT INTO layanan (id_pelayanan_jasa, id_perumahan, nama_penyedia_layanan, kategori_jasa, deskripsi, harga, status_layanan)
            VALUES (?, ?, ?, ?, ?, ?, 'aktif')
        ";
        $stmtInsert = mysqli_prepare($conn, $queryInsertLayanan);

        if ($stmtInsert) {
            mysqli_stmt_bind_param(
                $stmtInsert,
                "iisssi",
                $pengajuan['id_pelayanan_jasa'],
                $idPerumahan,
                $pengajuan['nama_penyedia_jasa'],
                $pengajuan['kategori_jasa'],
                $pengajuan['deskripsi_jasa'],
                $pengajuan['harga']
            );
            $insertSuccess = mysqli_stmt_execute($stmtInsert);
            mysqli_stmt_close($stmtInsert);

            if ($insertSuccess) {
                // Update status pengajuan di tabel pengajuan_pelayanan
                $queryUpdatePengajuan = "UPDATE pengajuan_pelayanan SET status_pengajuan = 'Disetujui' WHERE id_pengajuan = ?";
                $stmtUpdate = mysqli_prepare($conn, $queryUpdatePengajuan);

                if ($stmtUpdate) {
                    mysqli_stmt_bind_param($stmtUpdate, "i", $idPengajuan);
                    mysqli_stmt_execute($stmtUpdate);
                    mysqli_stmt_close($stmtUpdate);

                    echo json_encode(['success' => true]);
                    exit;
                }
            }
        }
    }
}

// Jika terjadi error
echo json_encode(['success' => false, 'message' => 'Gagal menambahkan data ke database.']);
exit;
?>
