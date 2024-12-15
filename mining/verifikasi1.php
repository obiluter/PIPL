<?php
// Konfigurasi database
$host = "localhost";
$user = "root"; // Ganti dengan username database Anda
$pass = "";     // Ganti dengan password database Anda
$db   = "db_kampus"; // Nama database Anda

// Koneksi ke database
$conn = new mysqli($host, $user, $pass, $db);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data dari tabel pengembalian dan peminjam menggunakan JOIN
$sql = "SELECT 
            pengembalian.id, 
            peminjam.nama, 
            peminjam.nim, 
            peminjam.jurusan, 
            peminjam.kontak, 
            peminjam.alamat, 
            pengembalian.barang_id, 
            pengembalian.tanggal_pengembalian, 
            pengembalian.keterangan, 
            pengembalian.status
        FROM pengembalian
        INNER JOIN peminjam ON pengembalian.peminjam_id = peminjam.id";

// Eksekusi query
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

// Menutup koneksi
$conn->close();
?>
