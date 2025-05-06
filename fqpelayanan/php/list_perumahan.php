<?php
include "connection_db.php";

// Query untuk mendapatkan semua data perumahan
$sql = "
    SELECT
        id_perumahan,
        nama_perumahan,
        alamat,
        email
    FROM perumahan
";

$result = mysqli_query($conn, $sql);

if ($result) {
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);

    // Kembalikan data dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo json_encode(['error' => mysqli_error($conn)]);
}
?>
