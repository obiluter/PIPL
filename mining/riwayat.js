document.addEventListener("DOMContentLoaded", function () {
  // Ambil elemen tbody dari tabel HTML
  const riwayatTable = document.getElementById("riwayat-data");

  // Fetch data dari file PHP
  fetch("riwayat.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Terjadi kesalahan saat mengambil data.");
      }
      return response.json(); // Mengubah respons ke format JSON
    })
    .then((data) => {
      // Jika data ditemukan
      if (data.length > 0) {
        data.forEach((item) => {
          // Membuat baris baru dengan data dari JSON
          const row = `
            <tr>
              <td>${item.nama}</td>
              <td>${item.nim}</td>
              <td>${item.jurusan}</td>
              <td>${item.fakultas}</td>
              <td>${item.kontak}</td>
              <td>${item.alamat}</td>
              <td>${item.id_barang}</td>
              <td>${item.tanggal_peminjaman}</td>
              <td>${item.tanggal_pengembalian}</td>
              <td>${item.keterangan}</td>
              <td>${item.status}</td>
            </tr>
          `;
          // Menambahkan baris ke dalam tbody
          riwayatTable.innerHTML += row;
        });
      } else {
        // Jika data kosong
        riwayatTable.innerHTML = `
          <tr>
            <td colspan="11" style="text-align: center;">Data tidak ditemukan</td>
          </tr>
        `;
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      riwayatTable.innerHTML = `
        <tr>
          <td colspan="11" style="text-align: center; color: red;">Gagal memuat data</td>
        </tr>
      `;
    });
});
