<?php
session_start();
include('connection_db.php'); // Pastikan untuk menyertakan koneksi database

// Cek apakah pengguna sudah login dan memiliki id_pelayanan_jasa
if (isset($_SESSION['id_pelayanan_jasa'])) {
    $id_pelayanan_jasa = $_SESSION['id_pelayanan_jasa'];

    // Query untuk memeriksa pengajuan terbaru yang belum ditampilkan
    $sql_pengajuan = "
    SELECT pp.id_pengajuan, pp.status_pengajuan, p.nama_perumahan
    FROM pengajuan_pelayanan AS pp
    JOIN perumahan AS p ON pp.id_perumahan = p.id_perumahan
    WHERE pp.id_pelayanan_jasa = ? 
      AND pp.status_pengajuan IN ('Disetujui', 'Ditolak') 
      AND pp.alert_displayed = 0
    ORDER BY pp.created_at DESC LIMIT 1
    ";

    $stmt_pengajuan = mysqli_prepare($conn, $sql_pengajuan);
    if ($stmt_pengajuan) {
        mysqli_stmt_bind_param($stmt_pengajuan, "s", $id_pelayanan_jasa);
        mysqli_stmt_execute($stmt_pengajuan);
        $result_pengajuan = mysqli_stmt_get_result($stmt_pengajuan);

        // Cek apakah ada pengajuan baru
        if (mysqli_num_rows($result_pengajuan) > 0) {
            $data_pengajuan = mysqli_fetch_assoc($result_pengajuan);
            $id_pengajuan = $data_pengajuan['id_pengajuan'];
            $nama_perumahan = htmlspecialchars($data_pengajuan['nama_perumahan']);
            $status_pengajuan = $data_pengajuan['status_pengajuan'];

            // Set notifikasi message
            if ($status_pengajuan === 'Disetujui') {
                $message = 'Pengajuan anda pada perumahan <strong>' . $nama_perumahan . '</strong> telah <span style="color: green; font-weight: bold; font-style: italic;">diterima</span>!';
            } elseif ($status_pengajuan === 'Ditolak') {
                $message = 'Pengajuan anda pada perumahan <strong>' . $nama_perumahan . '</strong> telah <span style="color: red; font-weight: bold; font-style: italic;">ditolak</span>!';
            }

            // Update status alert_displayed
            $update_sql = "UPDATE pengajuan_pelayanan SET alert_displayed = 1 WHERE id_pengajuan = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($update_stmt, "i", $id_pengajuan);
            mysqli_stmt_execute($update_stmt);
            mysqli_stmt_close($update_stmt);

            // Kirim response
            echo json_encode(['status' => 'success', 'message' => $message]);
        } else {
            echo json_encode(['status' => 'no_new']);
        }
        mysqli_stmt_close($stmt_pengajuan);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'not_logged_in']);
}
?>
