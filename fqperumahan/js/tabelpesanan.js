// Warga
// function searchTableWarga() {
//     const input = document.getElementById('searchInputWarga');
//     const filter = input.value.toLowerCase();
//     const table = document.getElementById('dataTableWarga');
//     const tr = table.getElementsByTagName('tr');

//     for (let i = 1; i < tr.length; i++) {
//         const td = tr[i].getElementsByTagName('td');
//         let rowVisible = false;

//         for (let j = 1; j < td.length; j++) {
//             if (td[j].textContent.toLowerCase().includes(filter)) {
//                 rowVisible = true;
//                 break;
//             }
//         }

//         tr[i].style.display = rowVisible ? '' : 'none';
//     }
// }

function openModalWarga() {
    document.getElementById('addModal').style.display = 'block';
}

function closeModalWarga() {
    document.getElementById('addModal').style.display = 'none';
}

function openDetailModalWarga(name, serviceProvider, serviceType, orderDate) {
    document.getElementById('detailName').textContent = name;
    document.getElementById('detailServiceProvider').textContent = serviceProvider;
    document.getElementById('detailServiceType').textContent = serviceType;
    document.getElementById('detailOrderDate').textContent = orderDate;
    document.getElementById('detailModalWarga').style.display = 'block';
}

function closeDetailModalWarga() {
    document.getElementById('detailModalWarga').style.display = 'none';
}
function addResident() {
    const table = document.getElementById('dataTableWarga').getElementsByTagName('tbody')[0];
    const name = document.getElementById('name').value;
    const serviceProvider = document.getElementById('serviceProvider').value;
    const serviceType = document.getElementById('serviceType').value;
    const orderDate = document.getElementById('orderDate').value;

    const newRow = table.insertRow();
    newRow.innerHTML = `
                <td>${table.rows.length + 1}</td>
                <td>${name}</td>
                <td>${serviceProvider}</td>
                <td>${serviceType}</td>
                <td>${orderDate}</td>
                <td><span class="status-in-process">Dalam Proses</span></td>
            `;

    closeModal();
    document.getElementById('addResidentForm').reset();
}
// Layanan
function searchTableLayanan() {
    const input = document.getElementById('searchInputLayanan');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('dataTableLayanan');
    const tr = table.getElementsByTagName('tr');
    const rowsPerPage = parseInt(document.getElementById("rowsPerPageLayanan").value, 10);

    let visibleRows = 0;

    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName('td');
        let rowVisible = false;

        for (let j = 1; j < td.length; j++) {
            if (td[j].textContent.toLowerCase().includes(filter)) {
                rowVisible = true;
                break;
            }
        }

        if (rowVisible && visibleRows < rowsPerPage) {
            tr[i].style.display = '';
            visibleRows++;
        } else {
            tr[i].style.display = 'none';
        }
    }
}


function openModalLayanan() {
    document.getElementById('addModal').style.display = 'block';
}

function closeModalLayanan() {
    document.getElementById('addModal').style.display = 'none';
}

function openDetailModalLayanan(name, email, phone, izinUsaha, alamat, kategori, tanggal, deskripsi, status) {
    document.getElementById('detailNamaPenyedia').textContent = name;
    document.getElementById('detailEmailPenyedia').textContent = email;
    document.getElementById('detailNohpPenyedia').textContent = phone;
    document.getElementById('detailNoIzinUsaha').textContent = izinUsaha;
    document.getElementById('detailAlamatPenyedia').textContent = alamat;
    document.getElementById('detailKategoriPenyedia').textContent = kategori;
    document.getElementById('detailTanggalPenyedia').textContent = tanggal;
    document.getElementById('detailDeskripsiPenyedia').textContent = deskripsi;
    document.getElementById('detailStatusPenyedia').textContent = status;

    document.getElementById('detailModalLayanan').style.display = 'block';
}

function closeDetailModalLayanan() {
    document.getElementById('detailModalLayanan').style.display = 'none';
}
// PERSETUJUAN LAYANAN 
function approveService() {
    // Logika untuk menyetujui layanan
    document.getElementById('detailStatusPenyedia').innerText = 'Disetujui';
    alert('Layanan telah disetujui.');
    closeDetailModalLayanan();
    // Lakukan update pada tabel atau database jika diperlukan
}

function rejectService() {
    // Logika untuk menolak layanan
    document.getElementById('detailStatusPenyedia').innerText = 'Ditolak';
    alert('Layanan telah ditolak.');
    closeDetailModalLayanan();
    // Lakukan update pada tabel atau database jika diperlukan
}
