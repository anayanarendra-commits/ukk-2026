-- create_pengembalians_table

CREATE TABLE IF NOT EXISTS `pengembalian` (
    id_pengembalian       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_peminjaman         INT UNSIGNED NOT NULL UNIQUE,
    tanggal_dikembalikan  DATE NOT NULL,
    kondisi_buku          ENUM('baik', 'rusak', 'hilang') NOT NULL DEFAULT 'baik',
    denda                 DECIMAL(10,0) NOT NULL,
    catatan_admin         TEXT NULL,
    created_at            DATETIME NULL,
    updated_at            DATETIME NULL,
    CONSTRAINT fk_pengembalian_peminjaman
        FOREIGN KEY (id_peminjaman) REFERENCES peminjaman(id_peminjaman)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;