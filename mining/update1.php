<?php
// Ambil data dari AJAX
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$status = $data['status'];

// Konfigurasi database
$host = "localhost";
$user = "root"; 
$pass = "";
$db = "db_kampus";

// Koneksi ke database
$conn = new mysqli($host, $user, $pass, $db);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk memperbarui status pengembalian
$sql = "UPDATE pengembalian SET status = ? WHERE id = ?";

// Persiapkan statement
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $status, $id);

// Eksekusi query
if ($stmt->execute()) {
    echo "Status berhasil diperbarui!";
} else {
    echo "Terjadi kesalahan saat memperbarui status: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
