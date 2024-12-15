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
$tanggal_pengembalian = $_POST['tanggal_pengembalian'];
$keterangan = $_POST['keterangan'];

// Cek apakah ID barang kosong
if (empty($barang_id)) {
    echo "<script>alert('ID Barang tidak boleh kosong.'); window.history.back();</script>";
    exit; // Hentikan eksekusi jika ID barang kosong
}

// Periksa apakah peminjam sudah ada berdasarkan NIM atau nama
$sql_check_peminjam = "SELECT id, nama, nim, jurusan, fakultas, kontak, alamat FROM peminjam WHERE nim = '$nim' OR nama = '$nama' LIMIT 1";
$result_check = $conn->query($sql_check_peminjam);

// Jika peminjam sudah ada, ambil ID peminjam, jika tidak, tambahkan peminjam baru
if ($result_check->num_rows > 0) {
    // Jika peminjam ditemukan, ambil ID peminjam
    $row = $result_check->fetch_assoc();
    $peminjam_id = $row['id'];
} else {
    // Jika peminjam belum ada, tambahkan peminjam baru
    $sql_peminjam = "INSERT INTO peminjam (nama, nim, jurusan, fakultas, kontak, alamat) 
                     VALUES ('$nama', '$nim', '$jurusan', '$fakultas', '$kontak', '$alamat')";

    if ($conn->query($sql_peminjam) === TRUE) {
        // Ambil ID peminjam yang baru saja dimasukkan
        $peminjam_id = $conn->insert_id;
    } else {
        echo "Error: " . $sql_peminjam . "<br>" . $conn->error;
        exit; // Jika terjadi kesalahan saat memasukkan data peminjam baru
    }
}

// Menyimpan data pengembalian ke tabel pengembalian
// Status pengembalian akan diset 'Menunggu' atau sesuai dengan status yang diinginkan
$status = 'Menunggu'; // Status pengembalian sementara

$sql_pengembalian = "INSERT INTO pengembalian (peminjam_id, barang_id, tanggal_pengembalian, keterangan, status) 
                     VALUES ('$peminjam_id', '$barang_id', '$tanggal_pengembalian', '$keterangan', '$status')";

if ($conn->query($sql_pengembalian) === TRUE) {
    echo "<script>
            alert('Formulir pengembalian berhasil disimpan dan tunggu verifikasinya!');
            window.location.href = 'pengembalian.html'; // Kembali ke halaman pengembalian
          </script>";
} else {
    echo "Error: " . $sql_pengembalian . "<br>" . $conn->error;
}

$conn->close();
?>
