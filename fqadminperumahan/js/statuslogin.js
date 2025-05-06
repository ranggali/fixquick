function toggleLogin(button) {
    const row = button.closest("tr");
    const statusCell = row.querySelector("td:nth-child(4) span");
    
    if (statusCell.classList.contains("bg-success")) {
        statusCell.classList.remove("bg-success");
        statusCell.classList.add("bg-danger");
        statusCell.innerText = "Nonaktif";
        button.classList.remove("btn-warning");
        button.classList.add("btn-success");
        button.innerText = "Aktifkan";
    } else {
        statusCell.classList.remove("bg-danger");
        statusCell.classList.add("bg-success");
        statusCell.innerText = "Aktif";
        button.classList.remove("btn-success");
        button.classList.add("btn-warning");
        button.innerText = "Nonaktifkan";
    }
}
