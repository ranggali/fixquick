<?php
session_start();
header('Content-Type: application/json');
require_once 'connection_db.php';
require_once dirname(__FILE__) . '/../../vendor/autoload.php';

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-0aAWAUl8N0E9K5NbPtRcMVP0';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

$response = ['status' => '', 'message' => ''];

try {
    // Ambil dan validasi input
    $id_layanan = $_POST['id_layanan'] ?? null;
    $id_pelayanan_jasa = $_POST['id_pelayanan_jasa'] ?? null;
    $kategori_jasa = $_POST['kategori_jasa'] ?? null;
    $tanggal_pesanan = $_POST['tanggal_pesanan'] ?? null;
    $waktu = $_POST['waktu'] ?? null;
    $alamat = $_POST['alamat'] ?? null;
    $no_rumah = $_POST['no_rumah'] ?? null;
    $catatan_tambahan = $_POST['catatan_tambahan'] ?? null;
    $id_metode = $_POST['id_metode'] ?? null;
    $nomor_invoice = $_POST['nomor_invoice'] ?? null;
    $total_pembayaran = $_POST['harga'] ?? null;
    $id_warga = $_SESSION['id_warga'] ?? null;

    if (!$kategori_jasa || !$tanggal_pesanan || !$waktu || !$alamat || !$id_metode || !$id_warga || !$nomor_invoice || !$total_pembayaran) {
        throw new Exception("Semua data wajib diisi.");
    }

    // Validasi tanggal
    $dateTime = DateTime::createFromFormat('Y-m-d', $tanggal_pesanan);
    if (!$dateTime || $dateTime->format('Y-m-d') !== $tanggal_pesanan) {
        throw new Exception("Format tanggal tidak valid.");
    }

    // Ambil nomor telepon warga
    $stmt = $conn->prepare("SELECT no_telepon FROM warga WHERE id_warga = ?");
    $stmt->bind_param("i", $id_warga);
    $stmt->execute();
    $stmt->bind_result($no_telepon);
    $stmt->fetch();
    $stmt->close();

    if (!$no_telepon) {
        throw new Exception("Nomor telepon warga tidak ditemukan.");
    }

    // Sanitasi input
    $kategori_jasa = htmlspecialchars($kategori_jasa);
    $alamat = htmlspecialchars($alamat);
    $no_rumah = htmlspecialchars($no_rumah);
    $catatan_tambahan = htmlspecialchars($catatan_tambahan);
    $id_metode = (int)$id_metode;

    // Buat order ID Midtrans
    $orderId = 'FXQ-' . $nomor_invoice;
    $paymentType = $id_metode;

    $params = [
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => $total_pembayaran,
        ]
    ];

    $no_pembayaran = null;
    $qris_url = null;
    $midtrans_token = null;

    // Midtrans charge hanya jika metode selain tunai
    if ($paymentType == 1) {
        $params['payment_type'] = 'qris';
        $charge = \Midtrans\CoreApi::charge($params);
        $qris_url = $charge->actions[0]->url;
        $midtrans_token = $charge->transaction_id;
    } elseif ($paymentType == 2) {
        $params['payment_type'] = 'cstore';
        $params['cstore'] = [
            'store' => 'alfamart',
            'message' => 'Pembayaran FixQuick'
        ];
        $charge = \Midtrans\CoreApi::charge($params);
        $no_pembayaran = $charge->payment_code;
        $midtrans_token = $charge->transaction_id;
    } elseif ($paymentType == 3) {
        $params['payment_type'] = 'bank_transfer';
        $params['bank_transfer'] = ['bank' => 'bca'];
        $charge = \Midtrans\CoreApi::charge($params);
        $no_pembayaran = $charge->va_numbers[0]->va_number;
        $midtrans_token = $charge->transaction_id;
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO pesanan_layanan 
        (kategori_jasa, tanggal_pesanan, waktu, alamat, no_rumah, catatan_tambahan, id_metode, id_warga, nomor_invoice, id_layanan, id_pelayanan_jasa, total_pembayaran, no_pembayaran, midtrans_order_id, midtrans_token, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");

    $stmt->bind_param("ssssssiisiiisss",
        $kategori_jasa,
        $tanggal_pesanan,
        $waktu,
        $alamat,
        $no_rumah,
        $catatan_tambahan,
        $id_metode,
        $id_warga,
        $nomor_invoice,
        $id_layanan,
        $id_pelayanan_jasa,
        $total_pembayaran,
        $no_pembayaran,
        $orderId,
        $midtrans_token
    );

    if (!$stmt->execute()) {
        throw new Exception("Gagal menyimpan pesanan: " . $stmt->error);
    }

    $stmt->close();

    echo json_encode([
        'status' => 'success',
        'message' => 'Pesanan berhasil dibuat.',
        'payment' => [
            'method' => $paymentType,
            'no_pembayaran' => $no_pembayaran,
            'qris_url' => $qris_url
        ]
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    exit;
}

$conn->close();
