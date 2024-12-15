<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_kampus";

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil daftar peminjaman dengan status Pending
$sql = "SELECT p.id, p.tanggal_peminjaman, p.tanggal_pengembalian, p.keterangan, b.nama_barang, m.nama 
        FROM peminjaman p
        JOIN barang b ON p.barang_id = b.id
        JOIN peminjam m ON p.peminjam_id = m.id
        WHERE p.status = 'Pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
</head>
<body>
    <h1>Daftar Peminjaman</h1>
    <table border="1">
        <tr>
            <th>Nama Mahasiswa</th>
            <th>Barang</th>
            <th>Tanggal Peminjaman</th>
            <th>Tanggal Pengembalian</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['nama']; ?></td>
            <td><?php echo $row['nama_barang']; ?></td>
            <td><?php echo $row['tanggal_peminjaman']; ?></td>
            <td><?php echo $row['tanggal_pengembalian']; ?></td>
            <td><?php echo $row['keterangan']; ?></td>
            <td>
                <form method="POST" action="acc_peminjaman.php">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="status" value="Approved">ACC</button>
                    <button type="submit" name="status" value="Rejected">Tolak</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
