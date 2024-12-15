<?php
// Konfigurasi database
$host = "localhost";
$user = "root"; // Ganti dengan username database Anda
$pass = "";     // Ganti dengan password database Anda
$db   = "db_kampus"; // Ganti dengan nama database Anda

// Koneksi ke database
$conn = new mysqli($host, $user, $pass, $db);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data dari tabel peminjam dan pengembalian menggunakan JOIN
$sql = "SELECT 
            peminjam.nama, 
            peminjam.nim, 
            peminjam.jurusan, 
            peminjam.fakultas,
            peminjam.kontak, 
            peminjam.alamat, 
            pengembalian.barang_id, 
            pengembalian.tanggal_pengembalian, 
            pengembalian.keterangan, 
            pengembalian.status
        FROM peminjam
        INNER JOIN pengembalian ON peminjam.id = pengembalian.peminjam_id"; // Menggunakan peminjam.id untuk JOIN dengan pengembalian.peminjam_id

// Menjalankan query
$result = $conn->query($sql);

// Kembalikan data dalam format JSON
if ($result->num_rows > 0) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data); // Mengirim data JSON ke client
} else {
    echo json_encode([]); // Jika tidak ada data, kembalikan array kosong
}

$conn->close();
?>
