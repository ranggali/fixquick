// Menampilkan loader saat halaman mulai dimuat
window.addEventListener('beforeunload', function() {
    document.getElementById('loader').style.display = 'block';  // Menampilkan loader
});

// Menyembunyikan loader setelah halaman dimuat sepenuhnya
window.addEventListener('load', function() {
    setTimeout(function() {
        document.getElementById('loader').style.display = 'none';  // Menyembunyikan loader
    }, 3000); 
});
