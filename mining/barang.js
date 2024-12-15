function loadData() {
    const tableBody = document.getElementById('data-barang');

    // Ambil data dari file PHP
    fetch('barang.php') // Pastikan path menuju file PHP benar
        .then(response => response.json())
        .then(data => {
            tableBody.innerHTML = ''; // Kosongkan tabel
            data.forEach(barang => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${barang.id}</td>
                    <td>${barang.nama}</td>
                    <td>${barang.kategori}</td>
                    <td>${barang.jumlah}</td>
                    <td>
                        <span class="status ${barang.status === 'Tersedia' ? 'available' : 'unavailable'}">
                            ${barang.status}
                        </span>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching data:', error));
}

// Panggil fungsi load data saat halaman dimuat
document.addEventListener('DOMContentLoaded', loadData);
