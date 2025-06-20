<?php
// Automatic setup script for XAMPP
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Database - Delivery Tracking</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { background: #4CAF50; color: white; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f44336; color: white; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { background: #ff9800; color: white; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #2196F3; color: white; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .btn { display: inline-block; padding: 12px 24px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px 10px 0; }
        .btn:hover { background: #1976D2; }
        .btn-success { background: #4CAF50; }
        .btn-success:hover { background: #45a049; }
        ol li { margin: 8px 0; }
        pre { background: #f0f0f0; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Setup Database Delivery Tracking</h1>
        
        <?php
        $setup_complete = false;
        $errors = [];
        
        if (isset($_GET['action']) && $_GET['action'] === 'setup') {
            echo "<h2>Memulai Setup Database...</h2>";
            
            try {
                // Database configuration
                $host = 'localhost';
                $username = 'root';
                $password = '';
                $dbname = 'delivery_tracking';
                
                // Step 1: Connect to MySQL
                echo "<p>1. Menghubungkan ke MySQL server...</p>";
                $pdo_temp = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
                $pdo_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "<div class='success'>✅ Koneksi MySQL berhasil</div>";
                
                // Step 2: Create database
                echo "<p>2. Membuat database '$dbname'...</p>";
                $pdo_temp->exec("DROP DATABASE IF EXISTS `$dbname`");
                $pdo_temp->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                echo "<div class='success'>✅ Database '$dbname' berhasil dibuat</div>";
                
                // Step 3: Connect to the new database
                echo "<p>3. Menghubungkan ke database '$dbname'...</p>";
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "<div class='success'>✅ Koneksi database berhasil</div>";
                
                // Step 4: Create tables
                echo "<p>4. Membuat tabel database...</p>";
                
                // Shipments table
                $sql_shipments = "CREATE TABLE shipments (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    no_polisi VARCHAR(20) NOT NULL,
                    no_segel VARCHAR(50) NOT NULL,
                    nama_driver VARCHAR(100) NOT NULL,
                    nama_co_driver VARCHAR(100),
                    nama_staff VARCHAR(100) NOT NULL,
                    cabang_tujuan VARCHAR(50) NOT NULL,
                    catatan_pengiriman TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )";
                $pdo->exec($sql_shipments);
                echo "<div class='success'>✅ Tabel 'shipments' berhasil dibuat</div>";
                
                // Shipment items table
                $sql_items = "CREATE TABLE shipment_items (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    shipment_id INT NOT NULL,
                    nama_barang VARCHAR(200) NOT NULL,
                    no_dokumen VARCHAR(100) NOT NULL,
                    tanggal_kirim DATE NOT NULL,
                    jumlah_barang INT NOT NULL DEFAULT 1,
                    catatan TEXT,
                    FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE CASCADE
                )";
                $pdo->exec($sql_items);
                echo "<div class='success'>✅ Tabel 'shipment_items' berhasil dibuat</div>";
                
                // Step 5: Insert sample data
                echo "<p>5. Menambahkan data contoh...</p>";
                
                // Sample shipments
                $stmt = $pdo->prepare("INSERT INTO shipments (no_polisi, no_segel, nama_driver, nama_co_driver, nama_staff, cabang_tujuan, catatan_pengiriman) VALUES (?, ?, ?, ?, ?, ?, ?)");
                
                $stmt->execute(['B 1234 ABC', 'SG001', 'Budi Santoso', 'Ahmad Rahman', 'Siti Aminah', 'jakarta', 'Pengiriman reguler']);
                $shipment1_id = $pdo->lastInsertId();
                
                $stmt->execute(['D 5678 XYZ', 'SG002', 'Joko Susilo', '', 'Rina Dewi', 'bandung', 'Pengiriman ekspres']);
                $shipment2_id = $pdo->lastInsertId();
                
                $stmt->execute(['F 9999 QWE', 'SG003', 'Sari Indah', 'Eko Pratama', 'Dedi Susanto', 'surabaya', 'Pengiriman kilat']);
                $shipment3_id = $pdo->lastInsertId();
                
                // Sample items
                $stmt = $pdo->prepare("INSERT INTO shipment_items (shipment_id, nama_barang, no_dokumen, tanggal_kirim, jumlah_barang, catatan) VALUES (?, ?, ?, ?, ?, ?)");
                
                $stmt->execute([$shipment1_id, 'Elektronik Komputer', 'DOC-2025-001', '2025-01-18', 5, 'Hati-hati fragile']);
                $stmt->execute([$shipment1_id, 'Spare Part Motor', 'DOC-2025-002', '2025-01-18', 10, 'Kemasan aman']);
                $stmt->execute([$shipment2_id, 'Bahan Kimia', 'DOC-2025-003', '2025-01-19', 2, 'Berbahaya - hati-hati']);
                $stmt->execute([$shipment2_id, 'Obat-obatan', 'DOC-2025-004', '2025-01-19', 8, 'Simpan di tempat sejuk']);
                $stmt->execute([$shipment3_id, 'Makanan Ringan', 'DOC-2025-005', '2025-01-20', 50, 'Expired date perhatikan']);
                
                echo "<div class='success'>✅ Data contoh berhasil ditambahkan</div>";
                
                // Step 6: Test API
                echo "<p>6. Melakukan test koneksi API...</p>";
                require_once 'includes/functions.php';
                $test_data = getShipments();
                if (count($test_data) > 0) {
                    echo "<div class='success'>✅ API berfungsi dengan baik - " . count($test_data) . " data pengiriman ditemukan</div>";
                } else {
                    echo "<div class='warning'>⚠️ API berfungsi tapi tidak ada data</div>";
                }
                
                $setup_complete = true;
                
            } catch (Exception $e) {
                $errors[] = "Error: " . $e->getMessage();
            }
        }
        ?>
        
        <?php if (!$setup_complete && empty($errors)): ?>
            <div class="info">
                <h3>📋 Panduan Setup Database</h3>
                <p>Sebelum menjalankan aplikasi, database perlu di-setup terlebih dahulu.</p>
                
                <h4>Persyaratan:</h4>
                <ol>
                    <li>XAMPP sudah terinstall</li>
                    <li>Apache dan MySQL service aktif di XAMPP Control Panel</li>
                    <li>File aplikasi sudah diekstrak ke htdocs/delivery-tracking/</li>
                </ol>
                
                <h4>Langkah Setup:</h4>
                <ol>
                    <li>Pastikan MySQL berjalan di XAMPP</li>
                    <li>Klik tombol "Setup Database" di bawah</li>
                    <li>Tunggu proses setup selesai</li>
                    <li>Akses aplikasi melalui index.php</li>
                </ol>
            </div>
            
            <a href="?action=setup" class="btn btn-success">🚀 Setup Database Otomatis</a>
            <a href="test-connection.php" class="btn">🔧 Test Koneksi Manual</a>
            
        <?php elseif (!empty($errors)): ?>
            <div class="error">
                <h3>❌ Setup Gagal</h3>
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
                
                <h4>Solusi:</h4>
                <ol>
                    <li>Pastikan XAMPP Control Panel terbuka</li>
                    <li>Klik "Start" pada Apache dan MySQL</li>
                    <li>Tunggu hingga keduanya berwarna hijau</li>
                    <li>Refresh halaman ini dan coba lagi</li>
                </ol>
            </div>
            
            <a href="setup.php" class="btn">🔄 Coba Lagi</a>
            
        <?php else: ?>
            <div class="success">
                <h3>🎉 Setup Database Berhasil!</h3>
                <p>Database dan tabel telah berhasil dibuat dengan data contoh.</p>
                
                <h4>Yang telah dibuat:</h4>
                <ul>
                    <li>✅ Database: delivery_tracking</li>
                    <li>✅ Tabel: shipments</li>
                    <li>✅ Tabel: shipment_items</li>
                    <li>✅ Data contoh: 3 pengiriman dengan 5 item</li>
                    <li>✅ API endpoint: berfungsi normal</li>
                </ul>
                
                <h4>Aplikasi siap digunakan!</h4>
            </div>
            
            <a href="index.php" class="btn btn-success">📱 Buka Aplikasi</a>
            <a href="test-connection.php" class="btn">🔧 Test Koneksi</a>
            
            <div class="info">
                <h4>📊 Info Database:</h4>
                <pre>Host: localhost
Database: delivery_tracking
Username: root
Password: (kosong)
Port: 3306 (default)</pre>
            </div>
        <?php endif; ?>
        
        <hr style="margin: 30px 0;">
        <p style="text-align: center; color: #666;">
            <strong>Sistem Pencatatan Pengiriman Barang v1.0</strong><br>
            Powered by PHP & MySQL
        </p>
    </div>
</body>
</html>