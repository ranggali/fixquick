function toggleStatus(button) {
    const row = button.closest("tr");
    const statusCell = row.querySelector("td:nth-child(4) span");
    
    if (statusCell.classList.contains("bg-primary")) {
        // Mengubah dari "Dalam Antrean" ke "Diproses"
        statusCell.classList.remove("bg-primary");
        statusCell.classList.add("bg-warning");
        statusCell.innerText = "Diproses";
    } else if (statusCell.classList.contains("bg-warning")) {
        // Mengubah dari "Diproses" ke "Selesai"
        statusCell.classList.remove("bg-warning");
        statusCell.classList.add("bg-success");
        statusCell.innerText = "Selesai";
    } else if (statusCell.classList.contains("bg-success")) {
        // Mengubah dari "Selesai" kembali ke "Dalam Antrean"
        statusCell.classList.remove("bg-success");
        statusCell.classList.add("bg-primary");
        statusCell.innerText = "Dalam Antrean";
    }
}
