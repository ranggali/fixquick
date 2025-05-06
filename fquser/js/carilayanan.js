document.querySelector('.search-box input').addEventListener('input', function () {
    const searchTerm = this.value.toLowerCase(); // Ambil teks pencarian dan ubah ke huruf kecil
    const serviceCards = document.querySelectorAll('.card-container .service-card');
    const serviceOptions = document.querySelector('.service-options'); // Elemen service-options
    const cardContainer = document.querySelector('.card-container');
    let hasMatch = false;

    // Iterasi melalui setiap kartu layanan
    serviceCards.forEach(card => {
        const title = card.querySelector('.card-title').textContent.toLowerCase();
        const description = card.querySelector('.card-footer .card-price')?.textContent.toLowerCase() || '';
        const combinedText = title + ' ' + description;

        // Periksa kecocokan teks pencarian
        if (combinedText.includes(searchTerm)) {
            card.style.display = 'block'; // Tampilkan kartu
            hasMatch = true;
        } else {
            card.style.display = 'none'; // Sembunyikan kartu
        }
    });

    // Logika untuk menangani "Maaf layanan belum tersedia"
    const noMatchMessage = document.querySelector('.no-match-container');
    if (!hasMatch) {
        if (!noMatchMessage) {
            // Buat elemen container untuk pesan dan gambar
            const containerElement = document.createElement('div');
            containerElement.className = 'no-match-container';
            containerElement.style.display = 'flex'; // Menggunakan flexbox
            containerElement.style.flexDirection = 'column'; // Mengatur arah kolom
            containerElement.style.alignItems = 'center'; // Memusatkan secara vertikal
            containerElement.style.justifyContent = 'center'; // Memusatkan secara horizontal
            containerElement.style.marginTop = '20px';
            containerElement.style.minHeight = '200px'; // Atur tinggi minimal jika diperlukan
        
            // Tambahkan gambar
            const imageElement = document.createElement('img');
            imageElement.src = 'assets/img/404.png';
            imageElement.alt = 'Data not found';
            imageElement.style.width = '250px'; // Atur ukuran gambar sesuai kebutuhan
            imageElement.style.marginBottom = '10px';
        
            // Tambahkan teks pesan
            const messageElement = document.createElement('p');
            messageElement.textContent = 'Maaf, layanan belum tersedia';
            messageElement.style.fontSize = '16px';
            messageElement.style.color = '#555';
        
            // Masukkan gambar dan teks ke dalam container
            containerElement.appendChild(imageElement);
            containerElement.appendChild(messageElement);
        
            // Tambahkan container ke dalam card-container
            cardContainer.appendChild(containerElement);
        }

        // Sembunyikan elemen service-options
        if (serviceOptions) {
            serviceOptions.style.display = 'none';
        }
    } else {
        // Hapus pesan jika ada hasil pencarian
        if (noMatchMessage) {
            noMatchMessage.remove();
        }
    
        // Tampilkan elemen service-options kembali
        if (serviceOptions) {
            serviceOptions.style.display = 'flex'; // Atau 'block' sesuai kebutuhan
        }
    }
});