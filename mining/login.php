<?php
session_start();
include('db.php'); // Koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Query untuk mencari pengguna berdasarkan username, password dan role
    $query = "SELECT * FROM users WHERE username = ? AND role = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Pengguna ditemukan, cek password
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Set session untuk menyimpan informasi pengguna
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Arahkan pengguna ke dashboard berdasarkan role
            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.html");
            } else {
                header("Location: mahasiswa_dashboard.html");
            }
        } else {
            echo "Password salah!";
        }
    } else {
        echo "Username atau Role tidak ditemukan!";
    }
}
?>
