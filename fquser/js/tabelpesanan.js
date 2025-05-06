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
