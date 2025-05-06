// Fungsi untuk mengunduh laporan sebagai CSV
function downloadReportAsCSV() {
    const table = document.getElementById("datatablesSimple");
    let csv = [];
    const rows = table.querySelectorAll("tr");

    rows.forEach(row => {
        const cols = row.querySelectorAll("td, th");
        let rowData = [];
        cols.forEach(col => rowData.push(col.innerText));
        csv.push(rowData.join(","));
    });

    // Membuat file blob
    const csvFile = new Blob([csv.join("\n")], { type: "text/csv" });

    // Membuat link download
    const downloadLink = document.createElement("a");
    downloadLink.download = "laporan_pembayaran.csv";
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";

    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Fungsi untuk mengunduh laporan sebagai PDF
function downloadReportAsPDF() {
    const { jsPDF } = window.jspdf; // Pastikan jsPDF sudah diimport
    const doc = new jsPDF();

    const table = document.getElementById("datatablesSimple");
    const rows = table.querySelectorAll("tr");

    let y = 10; // Margin awal pada PDF
    rows.forEach((row, rowIndex) => {
        const cols = row.querySelectorAll("td, th");
        let x = 10; // Margin kiri pada PDF

        cols.forEach(col => {
            doc.text(col.innerText, x, y);
            x += 40; // Spasi antar kolom
        });
        y += 10; // Spasi antar baris
    });

    doc.save("laporan_pembayaran.pdf");
}

// Event Listener untuk tombol
document.getElementById("downloadCSV").addEventListener("click", downloadReportAsCSV);
document.getElementById("downloadPDF").addEventListener("click", downloadReportAsPDF);
