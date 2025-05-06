function searchTable() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    const table = document.querySelector(".striped-table tbody");
    const rows = table.getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName("td");
        let rowContainsFilter = false;

        for (let j = 0; j < cells.length; j++) {
            const cellContent = cells[j].textContent || cells[j].innerText;
            if (cellContent.toLowerCase().indexOf(filter) > -1) {
                rowContainsFilter = true;
                break;
            }
        }

        rows[i].style.display = rowContainsFilter ? "" : "none";
    }
}
// Search Pengajuan Jasa
function searchTablePengajuan() {
    const input = document.getElementById("searchInputPengajuan");
    const filter = input.value.toLowerCase();
    const table = document.querySelector("#dataTablePengajuan tbody");
    const rows = table.getElementsByTagName("tr");

    // Loop untuk setiap baris
    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName("td");
        let rowContainsFilter = false;

        // Loop untuk setiap cell dalam baris
        for (let j = 0; j < cells.length; j++) {
            const cell = cells[j];

            // Jika cell adalah gambar (untuk status)
            if (cell.getElementsByTagName("img").length > 0) {
                const imgAlt = cell.getElementsByTagName("img")[0].alt.toLowerCase();
                if (imgAlt.indexOf(filter) > -1) {
                    rowContainsFilter = true;
                    break;
                }
            } else {
                // Jika cell biasa (selain gambar)
                const cellContent = cell.textContent || cell.innerText;
                if (cellContent.toLowerCase().indexOf(filter) > -1) {
                    rowContainsFilter = true;
                    break;
                }
            }
        }

        // Tampilkan atau sembunyikan baris berdasarkan pencarian
        rows[i].style.display = rowContainsFilter ? "" : "none";
    }
}

