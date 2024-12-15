<?php
session_start();  // Memulai sesi

// Koneksi ke database
$host = 'localhost';
$db = 'db_kampus';  // Sesuaikan dengan nama database Anda
$user = 'root';  // Sesuaikan dengan username database Anda
$pass = '';  // Sesuaikan dengan password database Anda

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses pendaftaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['new-username'];
    $password = $_POST['new-password'];
    $email = $_POST['new-email'];
    $role = $_POST['role'];

    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah username sudah ada
    $check_username = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_username);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p>Username sudah terdaftar, silakan pilih username lain.</p>";
    } else {
        // Query untuk menambah data pengguna
        $insert_user = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_user);
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            // Redirect ke halaman login setelah berhasil mendaftar
            header("Location: login.html");
            exit();
        } else {
            echo "<p>Terjadi kesalahan saat mendaftar, silakan coba lagi.</p>";
        }
    }
    $stmt->close();
}

$conn->close();
?>