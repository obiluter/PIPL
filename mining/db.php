<?php
$host = 'localhost';  // atau sesuai dengan konfigurasi Anda
$username = 'root';    // atau sesuai dengan konfigurasi Anda
$password = '';        // atau sesuai dengan konfigurasi Anda
$dbname = 'db_kampus'; // ganti dengan nama database Anda

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
