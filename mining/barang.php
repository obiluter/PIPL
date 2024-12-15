<?php
header('Content-Type: application/json');

// Konfigurasi koneksi database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'db_kampus';

// Koneksi ke database
$connection = new mysqli($host, $user, $password, $database);

// Periksa koneksi
if ($connection->connect_error) {
    die(json_encode(['error' => 'Gagal terhubung ke database: ' . $connection->connect_error]));
}

// Query data barang
$query = "SELECT id, nama, jenis AS kategori, jumlah, kondisi AS status FROM barang";
$result = $connection->query($query);

// Periksa hasil query
if ($result->num_rows > 0) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode([]);
}

// Tutup koneksi
$connection->close();
?>
