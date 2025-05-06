
        // Declare variables to store user input data
        let selectedService = '';
        let selectedDate = '';
        let selectedTime = '';
        let address = '';
        let houseNumber = '';
        let additionalNotes = '';
        let paymentMethod = '';

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
                    content.innerHTML = '<div class="containerlayanan">' +
                        '<h1>Pilih Layanan</h1>' +
                        '<p>Silahkan Pilih Layanan yang Anda Butuhkan Dari Daftar Layanan Berikut:</p>' +
                        '<select id="service-select" onchange="selectedService = this.value;">' +
                        '<option>--Pilih Layanan--</option>' +
                        '<option value ="Perbaikan AC">Perbaikan AC</option>' +
                        '<option value="Perbaikan Listrik">Perbaikan Listrik</option>' +
                        '<option value="Perbaikan Pipa">Perbaikan Pipa</option>' +
                        '<option value="Pembersihan Rumah">Pembersihan Rumah</option>' +
                        '</select>' +
                        '<br>' +
                        '<button onclick="goToStep(2)">Selanjutnya</button>' +
                        '</div>';
                    break;
                case 2:
                    content.innerHTML = '<div class="tanggaltengah">' +
                        '<h1>Detail Pemesanan</h1>' +
                        '<p>Isi detail pemesanan untuk layanan yang Anda pilih.</p>' +
                        '<div class="input-wrapper date-wrapper">' +
                        '<input type="text" id="date-picker" class="datetime-input" placeholder="Pilih Tanggal" onchange="selectedDate = this.value">' +
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
                        '<input type="number" id="nomor-rumah" class="short" placeholder="No Rumah" onchange="houseNumber = this.value">' +
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
                        '<img src="assets/img/uwau.png" alt="Convenience Store Payment">' +
                        '</div>' +
                        '<div class="payment-method" onclick="selectPaymentMethod(this, \'Virtual Account\')">' +
                        '<h3>Virtual Account</h3>' +
                        '<img src="assets/img/var.png" alt="Virtual Account">' +
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
                    const paymentDetail = (paymentMethod === 'QRIS OVO DANA GOPAY SHOPEPAY, DLL')
                        ? '<img src="assets/img/contohqr.jpg" alt="QRIS Payment" style="width: 200px; height: 200px;">'
                        : '8889935030294'; // Example payment number

                    content.innerHTML =
                        '<h2>Pembayaran Berhasil</h2>' +
                        '<p>Terima kasih atas pembayaran Anda. Pesanan Anda telah berhasil diproses.</p>' +
                        '<div class="order-container">' +
                        '<div class="order-header">' +
                        '<h1>Hi User123!</h1>' +
                        '<p>Berikut adalah rincian pesanan Anda:</p>' +
                        '</div>' +
                        '<table class="order-summary">' +
                        '<tr>' +
                        '<th>Nomor Invoice #</th>' +
                        '<th>FQ4B8D1X24</th>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Layanan</td>' +
                        '<td>' + selectedService + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Tanggal</td>' +
                        '<td>' + selectedDate + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Waktu</td>' +
                        '<td>' + selectedTime + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Alamat</td>' +
                        '<td>' + address + ', No. ' + houseNumber + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Metode Pembayaran</td>' +
                        '<td>' + paymentMethod + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Nomor Pembayaran:</td>' +
                        '<td>' + paymentDetail + '</td>' +
                        '</tr>' +
                        '<tr class="total-row">' +
                        '<th>TOTAL</th>' +
                        '<th>Rp10.000</th>' +
                        '</tr>' +
                        '</table>' +
                        '</div>';
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
            return '<div class="order-container">' +
                '<div class="order-header">' +
                '<h1>Hi User123!</h1>' +
                '<p>Berikut adalah rincian pesanan Anda:</p>' +
                '</div>' +
                '<table class="order-summary">' +
                '<tr>' +
                '<th>Nomor Invoice #</th>' +
                '<th>0ON4DNSLN</th>' +
                '</tr>' +
                '<tr>' +
                '<td>Layanan</td>' +
                '<td>' + selectedService + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>Tanggal</td>' +
                '<td>' + selectedDate + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>Waktu</td>' +
                '<td>' + selectedTime + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>Alamat</td>' +
                '<td>' + address + ', No. ' + houseNumber + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>Metode Pembayaran</td>' +
                '<td>' + paymentMethod + '</td>' +
                '</tr>' +
                '<tr class="total-row">' +
                '<th>TOTAL</th>' +
                '<th>Rp10.000</th>' +
                '</tr>' +
                '</table>' +
                '<div class="footer">' +
                '<div>' +
                '<span>Nama:</span><br>User123' +
                '</div>' +
                '<div>' +
                '<span></span><br>' +
                '</div>' +
                '</div>' +
                '<button onclick="goToStep(6)">Konfirmasi Pembayaran</button>' +
                '</div>';
        }

        // Set langkah awal ke langkah pertama
        goToStep(1);
