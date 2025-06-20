-- Database setup for XAMPP MySQL
-- Run this script in phpMyAdmin or MySQL command line

CREATE DATABASE IF NOT EXISTS delivery_tracking;
USE delivery_tracking;

-- Table for shipments
CREATE TABLE IF NOT EXISTS shipments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_form VARCHAR(50) UNIQUE NOT NULL,
    no_dokumen VARCHAR(50) UNIQUE NOT NULL,
    no_polisi VARCHAR(20) NOT NULL,
    no_segel VARCHAR(50) NOT NULL,
    nama_driver VARCHAR(100) NOT NULL,
    nama_co_driver VARCHAR(100),
    nama_staff VARCHAR(100) NOT NULL,
    cabang_tujuan VARCHAR(50) NOT NULL,
    catatan_pengiriman TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for shipment items
CREATE TABLE IF NOT EXISTS shipment_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shipment_id INT NOT NULL,
    nama_barang VARCHAR(200) NOT NULL,
    no_dokumen VARCHAR(100) NOT NULL,
    tanggal_kirim DATE NOT NULL,
    jumlah_barang INT NOT NULL DEFAULT 1,
    catatan TEXT,
    FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE CASCADE
);

-- Sample data
INSERT INTO shipments (id_form, no_dokumen, no_polisi, no_segel, nama_driver, nama_co_driver, nama_staff, cabang_tujuan, catatan_pengiriman) VALUES
('FORM-20250119-0001', 'DOK-20250119-0001', 'B 1234 ABC', 'SG001', 'Budi Santoso', 'Ahmad Rahman', 'Siti Aminah', 'jakarta', 'Pengiriman reguler'),
('FORM-20250119-0002', 'DOK-20250119-0002', 'D 5678 XYZ', 'SG002', 'Joko Susilo', '', 'Rina Dewi', 'bandung', 'Pengiriman ekspres');

INSERT INTO shipment_items (shipment_id, nama_barang, no_dokumen, tanggal_kirim, jumlah_barang, catatan) VALUES
(1, 'Elektronik Komputer', 'DOC-2025-001', '2025-01-18', 5, 'Hati-hati fragile'),
(1, 'Spare Part Motor', 'DOC-2025-002', '2025-01-18', 10, ''),
(2, 'Bahan Kimia', 'DOC-2025-003', '2025-01-19', 2, 'Berbahaya - hati-hati');