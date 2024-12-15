if __name__ == "__main__":
    # Path file Excel
    file_path = 'C:/xampp/htdocs/mining/data/BMN.xlsx'  # Sesuaikan dengan lokasi file Excel Anda
    
    # Buat tabel
    create_tables()
    
    # Import data
    insert_data(file_path)
