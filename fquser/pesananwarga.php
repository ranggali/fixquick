<?php
session_start();
require_once 'php/connection_db.php';

// Validasi login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'resident' || !isset($_SESSION['id_warga'])) {
    header('Location: ../FixQuickWebsite/login.php');
    exit;
}

$id_warga = $_SESSION['id_warga'] ?? null;
$nama_warga = $_SESSION['nama_warga'] ?? 'User 123';

// Ambil data dari database
if ($id_warga) {
    $query = $conn->prepare("SELECT p.nama_perumahan, w.id_perumahan FROM warga w JOIN perumahan p ON w.id_perumahan = p.id_perumahan WHERE w.id_warga = ?");
    $query->bind_param("i", $id_warga);
    $query->execute();
    $result = $query->get_result();
    $data = $result->fetch_assoc();

    $namaPerumahan = $data['nama_perumahan'] ?? 'Perumahan tidak ditemukan';
    $id_perumahan = $data['id_perumahan'] ?? null;

    $queryLayanan = $conn->prepare("SELECT kategori_jasa FROM layanan WHERE id_perumahan = ?");
    $queryLayanan->bind_param("i", $id_perumahan);
    $queryLayanan->execute();
    $resultLayanan = $queryLayanan->get_result();

    $kategoriJasa = [];
    while ($row = $resultLayanan->fetch_assoc()) {
        $kategoriJasa[] = $row['kategori_jasa'];
    }

    $query->close();
    $queryLayanan->close();
} else {
    $namaPerumahan = 'Perumahan tidak ditemukan';
    $kategoriJasa = [];
}

// Cek apakah no_telepon sudah ada
$queryTelepon = $conn->prepare("SELECT no_telepon FROM warga WHERE id_warga = ?");
$queryTelepon->bind_param("i", $id_warga);
$queryTelepon->execute();
$resultTelepon = $queryTelepon->get_result();
$dataTelepon = $resultTelepon->fetch_assoc();

$no_telepon = $dataTelepon['no_telepon'] ?? null;

if (is_null($no_telepon)) {
    echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <style>
                /* Menambahkan font DM Sans */
                @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap');
                .swal2-popup {
                    font-family: 'DM Sans', sans-serif !important;
                }
            </style>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'Lengkapi Data Diri',
                        confirmButtonColor: '#009688',
                        text: 'Lengkapi Data diri anda terlebih dahulu sebelum melakukan pemesanan layanan',
                    }).then(() => {
                        window.location.href = 'profilwarga.php';
                    });
                });
            </script>";
    exit; // Hentikan eksekusi lebih lanjut
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" href="assets/img/logo1.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan FixQuick</title>
    <link rel="stylesheet" href="css/pesanan/style.css">
    <link rel="stylesheet" href="css/pesanan/pilihlayanan.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- SweetAlert2 dan Animate.css (jika belum ditambahkan) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <!-- jQuery and jQuery UI CSS and JS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- Timepicker Addon CSS and JS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>

    <!-- Localization for Indonesian -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/i18n/datepicker-id.js"></script>

    <!-- css -->
    <link rel="stylesheet" href="css/pesanan/pilihlayanan.css">
    <link rel="stylesheet" href="css/pesanan/detail.css">
    <link rel="stylesheet" href="css/pesanan/alamat.css">
    <link rel="stylesheet" href="css/pesanan/pembayaran.css">
    <link rel="stylesheet" href="css/pesanan/invoice.css">
    <link rel="stylesheet" href="css/pesanan/pesananwarga.css">
    <!-- end css -->

</head>

<body class="font-DM Sans">

    <div class="loader" id="loader">
        <img src="assets/load/loader.gif" alt="Loading...">
    </div>

    <a href="homepagewarga.php" class="back-button" aria-label="Kembali ke halaman utama">
        <i class='bx bx-arrow-back'></i> Kembali
    </a>

    <nav class="navbar">
        <div class="navbar-logo">
            <img src="assets/img/logo1.png" alt="FixQuick logo" class="logo">
            <span class="logo-text">FixQuick</span>
        </div>
        <!-- <div class="navbar-menu">
            <a href="#" class="menu-link">Hubungi kami</a>
            <button class="logout-button desktop-only" onclick="redirectToWebsite()">Keluar</button>
            <i class="fas fa-user-circle menu-icon desktop-only" id="profil_warga"></i>
            <i class="fas fa-cog menu-icon desktop-only"></i>
        </div> -->
        <!-- <button class="hamburger-button" id="hamburger">
            <i class="fas fa-bars"></i>
        </button> -->
    </nav>

    <!-- Mobile Menu -->
    <!-- <div class="mobile-menu hidden" id="mobileMenu">
        <a href="#" class="menu-link mobile-only">Hubungi kami</a>
        <button class="profile-button mobile-only" id="profil_button">Profil</button>
        <button class="setting-button mobile-only">Pengaturan</button>
        <button class="logout-button mobile-only" onclick="redirectToHomePageWarga()">Kembali</button>
    </div> -->

    <main class="main-contentpesananwarga">
        <a href="homepagewarga.php" class="tombolkembali">Kembali</a>
        <div class="progress-container" style="padding: 20px;">
            <div class="progress-bar" id="progress-bar"></div>

            <div class="step" data-step="1" onclick="goToStep(1)">
                <p class="subtitle">Pilih Layanan</p>
            </div>
            <div class="step" data-step="2" onclick="goToStep(2)">
                <p class="subtitle">Waktu Pemesanan</p>
            </div>
            <div class="step" data-step="3" onclick="goToStep(3)">
                <p class="subtitle">Informasi Lokasi</p>
            </div>
            <div class="step" data-step="4" onclick="goToStep(4)">
                <p class="subtitle">Metode Pembayaran</p>
            </div>
            <div class="step" data-step="5" onclick="goToStep(5)">
                <p class="subtitle">Konfirmasi Pesanan</p>
            </div>
            <div class="step" data-step="6" onclick="goToStep(6)">
                <p class="subtitle">Pembayaran Berhasil</p>
            </div>
        </div>

        <div class="content" id="content">
            <!-- Konten akan di-update berdasarkan langkah -->
        </div>
    </main><br>

    <footer class="py-8" style="border-top: 2px solid #bdbebf;">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-between items-center">
                <!-- Social media icons: will stack on small screens -->
                <div class="flex space-x-4 mb-4 md:mb-0 w-full md:w-auto">
                    <a href="#" class="text-gray-800"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-800"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-800"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-800"><i class="fab fa-linkedin-in"></i></a>
                </div>

                <!-- Company, Services, and Support sections: will stack on small screens -->
                <div class="flex space-x-16 w-full md:w-auto">
                    <div class="mb-4 md:mb-0 w-full md:w-auto">
                        <h5 class="font-bold text-gray-800 mb-2">COMPANY</h5>
                        <ul class="space-y-1">
                            <li><a href="#" class="text-gray-800">About Us</a></li>
                            <li><a href="#" class="text-gray-800">Career</a></li>
                            <li><a href="#" class="text-gray-800">Press</a></li>
                            <li><a href="#" class="text-gray-800">Blog</a></li>
                        </ul>
                    </div>
                    <div class="mb-4 md:mb-0 w-full md:w-auto">
                        <h5 class="font-bold text-gray-800 mb-2">SERVICES</h5>
                        <ul class="space-y-1">
                            <li><a href="#" class="text-gray-800">Residential</a></li>
                            <li><a href="#" class="text-gray-800">Office Cleaning</a></li>
                            <li><a href="#" class="text-gray-800">Commercial Cleaning</a></li>
                        </ul>
                    </div>
                    <!-- Support section will move to the bottom on small screens -->
                    <div class="w-full md:w-auto">
                        <h5 class="font-bold text-gray-800 mb-2">SUPPORT</h5>
                        <ul class="space-y-1">
                            <li><a href="#" class="text-gray-800">Contact Us</a></li>
                            <li><a href="#" class="text-gray-800">FAQ's</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mt-8 text-left text-gray-500 text-sm">
                © FixQuick 2024-<span id="year"></span>. All rights reserved. · <a href="#" class="text-gray-500">Terms of Service</a> · <a href="#" class="text-gray-500">Privacy Policy</a>
            </div>
        </div>
    </footer>

    <!-- script pesanan warga -->
    <script>
        // Tambahkan fungsi untuk mendapatkan nama perumahan
        function getNamaPerumahan() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: 'getdata/get_nama_perumahan.php',
                    method: 'GET',
                    success: function(data) {
                        resolve(JSON.parse(data));
                    },
                    error: function(err) {
                        reject(err);
                    }
                });
            });
        }

        function getKategoriJasa() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: 'getdata/get_kategori_jasa.php',
                    method: 'GET',
                    success: function(data) {
                        resolve(JSON.parse(data));
                    },
                    error: function(err) {
                        reject(err);
                    }
                });
            });
        }

        // Dibawah sudah kode js
        // Declare variables to store user input data
        let selectedService = '';
        let selectedIdLayanan = '';
        let selectedIdPelayananJasa = '';
        let selectedDate = '';
        let selectedTime = '';
        let address = '';
        let houseNumber = '';
        let additionalNotes = '';
        let paymentMethod = '';
        let invoiceNumber = generateInvoiceNumber();

        function handleServiceSelect(selectElement) {
            selectedService = selectElement.value;
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            selectedIdLayanan = selectedOption.getAttribute('data-id-layanan');
            selectedIdPelayananJasa = selectedOption.getAttribute('data-id-pelayanan-jasa');
            const selectedHarga = selectedOption.getAttribute('data-harga');
            totalHarga = parseInt(selectedHarga); // Simpan harga sebagai angka
        }

        function goToStep(step) {
            // Hapus kelas aktif dari semua langkah
            document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));

            // Tambah kelas aktif pada langkah yang dipilih
            const activeStep = document.querySelector(`.step[data-step="${step}"]`);
            activeStep.classList.add('active');

            // Hitung lebar progress bar
            const progressBar = document.getElementById('progress-bar');
            const totalSteps = document.querySelectorAll('.step').length;
            const progressWidth = ((step - 1) / (totalSteps - 1)) * 100;
            progressBar.style.width = `${progressWidth}%`;

            // Update konten berdasarkan langkah
            const content = document.getElementById('content');
            switch (step) {
                case 1:
                    getNamaPerumahan().then(namaPerumahan => {
                        return getKategoriJasa().then(kategoriJasa => {
                            let optionsHTML = '<option value="">--Pilih Layanan--</option>';
                            kategoriJasa.forEach(item => {
                                optionsHTML += `
                                    <option value="${item.kategori_jasa}" 
                                            data-id-layanan="${item.id_layanan}" 
                                            data-id-pelayanan-jasa="${item.id_pelayanan_jasa}" 
                                            data-harga="${item.harga}">
                                        ${item.kategori_jasa}
                                    </option>`;
                            });

                            content.innerHTML = `
                                <div class="containerlayanan">
                                    <h1>Pilih Layanan</h1>
                                    <p>Perumahan Anda: ${namaPerumahan}</p>
                                    <p>Silahkan Pilih Layanan yang Anda Butuhkan Dari Daftar Layanan Berikut:</p>
                                    <select id="service-select" onchange="handleServiceSelect(this)">
                                        ${optionsHTML}
                                    </select>
                                    <br>
                                    <button onclick="goToStep(2)">Selanjutnya</button>
                                </div>`;
                        });
                    }).catch(err => {
                        console.error('Error fetching data:', err);
                        content.innerHTML = '<p>Maaf, terjadi kesalahan saat memuat data. Silakan coba lagi nanti.</p>';
                    });
                    break;
                case 2:
                    content.innerHTML = '<div class="tanggaltengah">' +
                        '<h1>Detail Pemesanan</h1>' +
                        '<p>Isi detail pemesanan untuk layanan yang Anda pilih.</p>' +
                        '<div class="input-wrapper date-wrapper">' +
                        '<input type="text" id="date-picker" class="datetime-input" placeholder="Pilih Tanggal" onchange="convertDate(this.value)">' +
                        '</div>' +
                        '<div class="input-wrapper time-wrapper">' +
                        '<input type="text" id="time-picker" class="datetime-input" placeholder="Pilih Waktu" onchange="selectedTime = this.value">' +
                        '</div><br>' +
                        '<button onclick="goToStep(3)">Selanjutnya</button>' +
                        '</div>';
                    break;
                case 3:
                    content.innerHTML = '<div class="containeralamat">' +
                        '<h1>Tambahkan Alamat Lengkap Anda</h1>' +
                        '<p>Berikan rincian tambahan yang mungkin kami perlukan dari anda secara spesifik</p>' +
                        '<div class="form-group">' +
                        '<div>' +
                        '<label for="alamat">ALAMAT LENGKAP</label>' +
                        '<input type="text" id="alamat" placeholder="Masukkan alamat" onchange="address = this.value">' +
                        '</div>' +
                        '<div>' +
                        '<label for="nomor-rumah">NOMOR RUMAH</label>' +
                        '<input type="text" id="nomor-rumah" class="short" placeholder="No Rumah" onchange="houseNumber = this.value">' +
                        '</div>' +
                        '</div>' +
                        '<p>Catatan Tambahan</p>' +
                        '<textarea id="notes" class="textarea" placeholder="Contoh: Ada kandang ayam." onchange="additionalNotes = this.value"></textarea><br>' +
                        '<button onclick="goToStep(4)">Selanjutnya</button>' +
                        '</div>';
                    break;
                case 4:
                    content.innerHTML = '<h1 class="payment-title">Pilih Pembayaran</h1>' +
                        '<p class="payment-description">Pilih metode pembayaran yang Anda inginkan.</p>' +
                        '<div class="payment">' +
                        '<div class="payment-method" onclick="selectPaymentMethod(this, \'QRIS OVO DANA GOPAY SHOPEPAY, DLL\')">' +
                        '<h3>QRIS OVO DANA GOPAY SHOPEPAY, DLL</h3>' +
                        '<img src="assets/img/qris.png" alt="QRIS Payment">' +
                        '</div>' +
                        '<div class="payment-method" onclick="selectPaymentMethod(this, \'Convenience Store\')">' +
                        '<h3>Convenience Store</h3>' +
                        '<img src="assets/img/csok.png" alt="Convenience Store Payment">' +
                        '</div>' +
                        '<div class="payment-method" onclick="selectPaymentMethod(this, \'Virtual Account\')">' +
                        '<h3>Virtual Account</h3>' +
                        '<img src="assets/img/var.png" alt="Virtual Account">' +
                        '</div>' +
                        '<div class="payment-method" onclick="selectPaymentMethod(this, \'Tunai\')">' +
                        '<h3>Tunai</h3>' +
                        '<img src="assets/img/tunai.png" alt="Cash Payment">' +
                        '</div>' +
                        '</div>' +
                        '<div class="button-containerpmb">' +
                        '<button onclick="goToStep(5)">Selanjutnya</button>' +
                        '</div>';
                    break;
                case 5:
                    content.innerHTML = generateInvoice();
                    break;
                case 6:
                    let paymentDetail = '';
                    if (window.paymentData) {
                        const method = window.paymentData.method;
                        if (method === 1 && window.paymentData.qris_url) {
                            paymentDetail = `<img src="${window.paymentData.qris_url}" alt="QRIS Payment" style="width: 200px; height: 200px;">`;
                        } else if ((method === 2 || method === 3) && window.paymentData.no_pembayaran) {
                            paymentDetail = `Nomor Pembayaran: ${window.paymentData.no_pembayaran}`;
                        } else if (method === 4) {
                            paymentDetail = 'Silakan siapkan uang tunai sebesar total harga di bawah ini.';
                        } else {
                            paymentDetail = 'Informasi pembayaran tidak tersedia.';
                        }
                    } else {
                        paymentDetail = 'Informasi pembayaran tidak tersedia.';
                    }

                    content.innerHTML = `
        <h2>Pembayaran Berhasil</h2>
        <p>Terima kasih atas pembayaran Anda. Pesanan Anda telah berhasil diproses.</p>
        <div class="order-container">
            <div class="order-header">
                <h1>Hi ${namaWarga}!</h1>
                <p>Berikut adalah rincian pesanan Anda:</p>
                <p style="font-size:10px">Mohon untuk mengingat nomor invoice Anda!</p>
            </div>
            <table class="order-summary">
                <tr>
                    <th>Nomor Invoice #</th>
                    <th>${invoiceNumber}</th>
                </tr>
                <tr>
                    <td>Layanan</td>
                    <td>${selectedService}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>${selectedDate}</td>
                </tr>
                <tr>
                    <td>Waktu</td>
                    <td>${selectedTime}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>${address}, No. ${houseNumber}</td>
                </tr>
                <tr>
                    <td>Metode Pembayaran</td>
                    <td>${paymentMethod}</td>
                </tr>
                <tr>
                    <td>Detail Pembayaran</td>
                    <td>${paymentDetail}</td>
                </tr>
                <tr class="total-row">
                    <th>TOTAL</th>
                    <th>Rp${totalHarga.toLocaleString()}</th>
                </tr>
            </table>
        </div>
    `;
                    break;

                default:
                    content.innerHTML = "<p>Langkah tidak valid.</p>";
                    break;
            }

            // Inisialisasi datepicker dan timepicker setelah konten diperbarui
            $('#date-picker').datepicker($.datepicker.regional['id']);
            $('#time-picker').timepicker({
                timeFormat: 'HH:mm',
                controlType: 'select',
                showButtonPanel: true,
                closeText: 'Tutup',
                currentText: 'Sekarang',
                hourText: 'Jam',
                minuteText: 'Menit'
            });
        }

        function convertDate(dateString) {
            // Mengharapkan format dd/mm/yyyy
            const parts = dateString.split('/');
            if (parts.length === 3) {
                // Mengonversi ke format yyyy-mm-dd
                selectedDate = `${parts[2]}-${parts[0]}-${parts[1]}`;
            } else {
                console.error('Format tanggal tidak valid');
            }
        }

        function generateInvoiceNumber() {
            // Fungsi untuk menghasilkan 6 karakter acak
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = '';
            for (let i = 0; i < 6; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                result += characters[randomIndex];
            }

            // Ambil tahun saat ini dan ambil dua digit terakhir
            const currentYear = new Date().getFullYear();
            const yearSuffix = currentYear.toString().slice(-2); // Ambil dua digit terakhir

            // Gabungkan semua bagian
            return `FQ${result}${yearSuffix}`;
        }

        // Contoh penggunaan
        console.log(invoiceNumber); // Output: FQ-4B8D1X-24 (contoh)
        // Function to select payment method and change its style
        function selectPaymentMethod(element, method) {
            // Remove active class from all payment methods
            document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('active'));

            // Add active class to the selected payment method
            element.classList.add('active');

            // Store the selected payment method
            paymentMethod = method;
        }

        // Function to generate the invoice content
        function generateInvoice() {
            return `
                <div class="order-container">
                    <div class="order-header">
                        <h1>Hi ${namaWarga}!</h1>
                        <p>Berikut adalah rincian pesanan Anda:</p>
                        <p style="font-size:10px">mohon untuk mengingat nomor invoice anda!</p>
                    </div>
                    <table class="order-summary">
                        <tr>
                            <th>Nomor Invoice #</th>
                            <th>${invoiceNumber}</th>
                        </tr>
                        <tr>
                            <td>Layanan</td>
                            <td>${selectedService}</td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>${selectedDate}</td>
                        </tr>
                        <tr>
                            <td>Waktu</td>
                            <td>${selectedTime}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>${address}, No. ${houseNumber}</td>
                        </tr>
                        <tr>
                            <td>Metode Pembayaran</td>
                            <td>${paymentMethod}</td>
                        </tr>
                        <tr class="total-row">
                            <th>TOTAL</th>
                            <th>Rp${totalHarga.toLocaleString()}</th>
                        </tr>
                    </table>
                    <button onclick="konfirmasiPesanan()">Konfirmasi Pesanan</button>
                </div>`;
        }

        function konfirmasiPesanan() {
            console.log("Tanggal yang dikirim:", selectedDate);
            console.log("Nomor Invoice yang dikirim:", invoiceNumber); // Tambahkan log ini

            const id_metode = paymentMethod === 'QRIS OVO DANA GOPAY SHOPEPAY, DLL' ? 1 :
                paymentMethod === 'Convenience Store' ? 2 :
                paymentMethod === 'Tunai' ? 4 : 3;

            $.ajax({
                url: 'php/submit_order.php',
                method: 'POST',
                data: {
                    id_layanan: selectedIdLayanan, // Tambahkan id_layanan
                    id_pelayanan_jasa: selectedIdPelayananJasa,
                    kategori_jasa: selectedService,
                    tanggal_pesanan: selectedDate, // Pastikan ini sudah dalam format YYYY-MM-DD
                    waktu: selectedTime,
                    alamat: address,
                    no_rumah: houseNumber,
                    catatan_tambahan: additionalNotes,
                    id_metode: id_metode,
                    nomor_invoice: invoiceNumber,
                    harga: totalHarga
                },
                success: function(response) {
                    try {
                        if (typeof response === 'string') {
                            response = JSON.parse(response); // Parsing jika respons berupa string
                        }
                        if (response.status === 'success') {
                            alert(response.message);
                            // Simpan data pembayaran ke window biar bisa dipakai di step 6
                            window.paymentData = response.payment;
                            goToStep(6);
                        } else {
                            console.error('Server Error:', response.message);
                            alert('Terjadi kesalahan: ' + response.message);
                        }
                    } catch (error) {
                        console.error('JSON Parse Error:', error, response);
                        alert("Terjadi kesalahan pada server.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", xhr.responseText);
                    alert("Terjadi kesalahan saat mengirim data. Silakan coba lagi.");
                }
            });
        }

        // Set langkah awal ke langkah pertama
        goToStep(1);
    </script>
    <script>
        fetch('php/submit_order.php', {
                method: 'POST',
                body: new FormData(formElement), // formElement adalah elemen form HTML Anda
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        html: `Pesanan berhasil dibuat.<br>Invoice: <b>${data.invoice_number}</b><br>No Pembayaran: <b>${data.no_pembayaran}</b>`,
                        // text: data.message, // Pesan dari server
                    }).then(() => {
                        // Tambahkan logika jika perlu, seperti redirect
                        console.log("Invoice Number:", data.invoice_number);
                        console.log("No Pembayaran:", data.no_pembayaran);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message, // Pesan error dari server
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan pada server.',
                });
                console.error('Error:', error);
            });
    </script>
    <script>
        function redirectToHomePageWarga() {
            window.location.href = 'homepagewarga.php';
        }
    </script>
    <script>
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');

        // Toggle mobile menu visibility
        hamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
    <!-- script tahunan -->
    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
    <script>
        const namaPerumahan = "<?php echo htmlspecialchars($namaPerumahan); ?>";
    </script>
    <script>
        var namaWarga = "<?php echo htmlspecialchars($nama_warga); ?>";
    </script>
    <script src="js/spinner.js"></script>
    <!-- <script src="js/pesananwarga.js"></script> -->
</body>

</html>