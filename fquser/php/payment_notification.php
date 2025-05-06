<?php
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
require_once 'connection_db.php';

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-0aAWAUl8N0E9K5NbPtRcMVP0';
\Midtrans\Config::$isProduction = false;

$notif = new \Midtrans\Notification();

$orderId = $notif->order_id; // Format: FXQ-INV12345
$transactionStatus = $notif->transaction_status;
$paymentType = $notif->payment_type;
$settlementTime = $notif->settlement_time ?? date('Y-m-d H:i:s');

// Update hanya jika status pembayaran berhasil
if (in_array($transactionStatus, ['settlement', 'capture'])) {
    $stmt = $conn->prepare("UPDATE pesanan_layanan SET status_pembayaran = ?, tanggal_pembayaran = ? WHERE midtrans_order_id = ?");
    $status = 'Berhasil Bayar';
    $stmt->bind_param("sss", $status, $settlementTime, $orderId);
    $stmt->execute();
    $stmt->close();
}

http_response_code(200);
echo "Notification received and handled.";
