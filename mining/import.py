import pandas as pd
import mysql.connector
from mysql.connector import Error

# Function to connect to the database
def connect_to_db():
    try:
        connection = mysql.connector.connect(
            host='localhost',
            database='db_kampus',
            user='root',
            password=''  # Sesuaikan password jika ada
        )
        if connection.is_connected():
            print("Berhasil terhubung ke database")
            return connection
    except Error as e:
        print("Error saat terhubung ke database:", e)
        return None

# Function to create all necessary tables
def create_tables():
    connection = connect_to_db()
    if connection:
        cursor = connection.cursor()

        # Tabel users
        create_users_table = """
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'mahasiswa') NOT NULL
        );
        """

        # Tabel peminjam
        create_peminjam_table = """
        CREATE TABLE IF NOT EXISTS peminjam (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(255) UNIQUE,
            nim INT(10),
            jurusan VARCHAR(50),
            fakultas VARCHAR(50),
            kontak VARCHAR(50),
            alamat VARCHAR(255)
        );
        """

        # Tabel barang
        create_barang_table = """
        CREATE TABLE IF NOT EXISTS barang (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(255) UNIQUE,
            jenis VARCHAR(255),
            jumlah INT(100),
            kondisi VARCHAR(255)
        );
        """

        # Tabel peminjaman
        create_peminjaman_table = """
        CREATE TABLE IF NOT EXISTS peminjaman (
            id INT AUTO_INCREMENT PRIMARY KEY,
            peminjam_id INT,
            barang_id INT,
            tanggal_pinjam DATE,
            tanggal_kembali DATE,
            status VARCHAR(50),
            FOREIGN KEY (peminjam_id) REFERENCES peminjam(id),
            FOREIGN KEY (barang_id) REFERENCES barang(id)
        );
        """

        # Tabel pengembalian (tidak ada tanggal_pinjam)
        create_pengembalian_table = """
        CREATE TABLE IF NOT EXISTS pengembalian (
            id INT AUTO_INCREMENT PRIMARY KEY,
            peminjam_id INT,
            barang_id INT,
            tanggal_kembali DATE,
            status VARCHAR(50),
            FOREIGN KEY (peminjam_id) REFERENCES peminjam(id),
            FOREIGN KEY (barang_id) REFERENCES barang(id)
        );
        """

        try:
            cursor.execute(create_users_table)
            print("Tabel 'users' berhasil dibuat.")
            cursor.execute(create_peminjam_table)
            print("Tabel 'peminjam' berhasil dibuat.")
            cursor.execute(create_barang_table)
            print("Tabel 'barang' berhasil dibuat.")
            cursor.execute(create_peminjaman_table)
            print("Tabel 'peminjaman' berhasil dibuat.")
            cursor.execute(create_pengembalian_table)
            print("Tabel 'pengembalian' berhasil dibuat.")
            connection.commit()
        except Error as e:
            print(f"Error saat membuat tabel: {e}")
        finally:
            cursor.close()
            connection.close()

# Function to insert data from an Excel file into the database
def insert_data(file_path):
    try:
        # Baca file Excel
        df = pd.read_excel(file_path)

        connection = connect_to_db()
        if connection:
            cursor = connection.cursor()

            # Insert data users
            df_users = df[['Username', 'Email', 'Password', 'Role']].drop_duplicates()
            for _, row in df_users.iterrows():
                cursor.execute(
                    "INSERT INTO users (username, email, password, role) VALUES (%s, %s, %s, %s) "
                    "ON DUPLICATE KEY UPDATE id=id",
                    (row['Username'], row['Email'], row['Password'], row['Role'])
                )

            # Insert data peminjam
            df_peminjam = df[['Nama Peminjam', 'Kontak', 'Alamat']].drop_duplicates()
            for _, row in df_peminjam.iterrows():
                cursor.execute(
                    "INSERT INTO peminjam (nama, kontak, alamat) VALUES (%s, %s, %s) "
                    "ON DUPLICATE KEY UPDATE id=id",
                    (row['Nama Peminjam'], row['Kontak'], row['Alamat'])
                )

            # Insert data barang
            df_barang = df[['Nama Barang', 'Jenis Barang', 'Kondisi Barang']].drop_duplicates()
            for _, row in df_barang.iterrows():
                cursor.execute(
                    "INSERT INTO barang (nama, jenis, jumlah, kondisi) VALUES (%s, %s, %s, %s) "
                    "ON DUPLICATE KEY UPDATE id=id",
                    (row['Nama Barang'], row['Jenis Barang'], row['Jumlah Barang'], row['Kondisi Barang'])
                )

            # Insert data peminjaman
            for _, row in df.iterrows():
                cursor.execute(
                    """
                    INSERT INTO peminjaman (peminjam_id, barang_id, tanggal_pinjam, tanggal_kembali, status)
                    SELECT p.id, b.id, %s, %s, %s
                    FROM peminjam p, barang b
                    WHERE p.nama = %s AND p.kontak = %s AND b.nama = %s
                    """,
                    (
                        row['Tanggal Pinjam'], row['Tanggal Kembali'], row['Status'],
                        row['Nama Peminjam'], row['Kontak'], row['Nama Barang']
                    )
                )

            # Insert data pengembalian (jika ada)
            for _, row in df.iterrows():
                cursor.execute(
                    """
                    INSERT INTO pengembalian (peminjam_id, barang_id, tanggal_kembali, status)
                    SELECT p.id, b.id, %s, %s
                    FROM peminjam p, barang b
                    WHERE p.nama = %s AND p.kontak = %s AND b.nama = %s
                    """,
                    (
                        row['Tanggal Kembali'], row['Status'],
                        row['Nama Peminjam'], row['Kontak'], row['Nama Barang']
                    )
                )

            connection.commit()
            print("Data berhasil dimasukkan ke dalam database")

            cursor.close()
            connection.close()
    except Exception as e:
        print(f"Error saat membaca file Excel: {e}")

# Main function
if __name__ == "__main__":
    # Path file Excel
    file_path = 'C:/xampp/htdocs/mining/data/BMN.xlsx'  # Sesuaikan dengan lokasi file Excel Anda
    
    # Buat tabel
    create_tables()
    
    # Import data
    insert_data(file_path)
