function confirmDelete() {
    Swal.fire({
        title: "Yakin menghapus data ..?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "Batal",
        confirmButtonText: "Hapus",
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Berhasil!",
                text: "Berhasil dihapus!.",
                icon: "success"
            });
            Swal.fire({
                position: "top",
                icon: "success",
                title: "Berhasil dihapus!",
                showConfirmButton: false,
                timer: 1000
              });
        }
    });
}
