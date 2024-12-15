<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_kampus"; // Ganti dengan nama database Anda

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$nama = $_POST['nama'];
$nim = $_POST['nim'];
$jurusan = $_POST['jurusan'];
$fakultas = $_POST['fakultas'];
$kontak = $_POST['kontak'];
$alamat = $_POST['alamat'];
$barang_id = $_POST['id_barang'];  // Ambil ID Barang dari form
$tanggal_peminjaman = $_POST['tanggal_peminjaman'];
$tanggal_pengembalian = $_POST['tanggal_pengembalian'];
$keterangan = $_POST['keterangan'];

// Cek apakah ID barang kosong
if (empty($barang_id)) {
    echo "<script>alert('ID Barang tidak boleh kosong.'); window.history.back();</script>";
    exit; // Hentikan eksekusi jika ID barang kosong
}

// Menyimpan data peminjam ke tabel peminjam
$sql_peminjam = "INSERT INTO peminjam (nama, nim, jurusan, fakultas, kontak, alamat) 
                 VALUES ('$nama', '$nim', '$jurusan', '$fakultas', '$kontak', '$alamat')";

if ($conn->query($sql_peminjam) === TRUE) {
    // Ambil ID peminjam yang baru saja dimasukkan
    $peminjam_id = $conn->insert_id;

    // Menyimpan data peminjaman ke tabel peminjaman
    $sql_peminjaman = "INSERT INTO peminjaman (peminjam_id, barang_id, tanggal_peminjaman, tanggal_pengembalian, keterangan) 
                       VALUES ('$peminjam_id', '$barang_id', '$tanggal_peminjaman', '$tanggal_pengembalian', '$keterangan')";

    if ($conn->query($sql_peminjaman) === TRUE) {
        echo "<script>
                alert('Formulir peminjaman berhasil disimpan dan tunggu verifikasinya!');
                window.location.href = 'peminjaman.html'; // Kembali ke halaman peminjaman
              </script>";
    } else {
        echo "Error: " . $sql_peminjaman . "<br>" . $conn->error;
    }
} else {
    echo "Error: " . $sql_peminjam . "<br>" . $conn->error;
}

$conn->close();
?>
