<?php
echo "<h2>🔧 Test Koneksi Database XAMPP</h2>";

// Test basic PHP info
echo "<h3>1. PHP Configuration</h3>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "PDO Available: " . (extension_loaded('pdo') ? '✅ Yes' : '❌ No') . "<br>";
echo "MySQL PDO Available: " . (extension_loaded('pdo_mysql') ? '✅ Yes' : '❌ No') . "<br>";

// Test MySQL connection
echo "<h3>2. MySQL Connection Test</h3>";
$host = 'localhost';
$username = 'root';
$password = '';

try {
    echo "Trying to connect to MySQL server...<br>";
    $pdo_temp = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ MySQL server connection: SUCCESS<br>";
    
    // Test database creation
    echo "Creating database 'delivery_tracking'...<br>";
    $pdo_temp->exec("CREATE DATABASE IF NOT EXISTS `delivery_tracking` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database creation: SUCCESS<br>";
    
    // Test database connection
    echo "Connecting to delivery_tracking database...<br>";
    $pdo = new PDO("mysql:host=$host;dbname=delivery_tracking;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connection: SUCCESS<br>";
    
    // Test tables creation
    echo "<h3>3. Creating Tables</h3>";
    
    // Shipments table
    $sql_shipments = "CREATE TABLE IF NOT EXISTS shipments (
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
    echo "✅ Table 'shipments' created<br>";
    
    // Shipment items table
    $sql_items = "CREATE TABLE IF NOT EXISTS shipment_items (
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
    echo "✅ Table 'shipment_items' created<br>";
    
    // Insert sample data if tables are empty
    echo "<h3>4. Sample Data</h3>";
    $stmt = $pdo->query("SELECT COUNT(*) FROM shipments");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        echo "Inserting sample data...<br>";
        
        // Sample shipments
        $stmt = $pdo->prepare("INSERT INTO shipments (no_polisi, no_segel, nama_driver, nama_co_driver, nama_staff, cabang_tujuan, catatan_pengiriman) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute(['B 1234 ABC', 'SG001', 'Budi Santoso', 'Ahmad Rahman', 'Siti Aminah', 'jakarta', 'Pengiriman reguler']);
        $shipment1_id = $pdo->lastInsertId();
        
        $stmt->execute(['D 5678 XYZ', 'SG002', 'Joko Susilo', '', 'Rina Dewi', 'bandung', 'Pengiriman ekspres']);
        $shipment2_id = $pdo->lastInsertId();
        
        // Sample items
        $stmt = $pdo->prepare("INSERT INTO shipment_items (shipment_id, nama_barang, no_dokumen, tanggal_kirim, jumlah_barang, catatan) VALUES (?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([$shipment1_id, 'Elektronik Komputer', 'DOC-2025-001', '2025-01-18', 5, 'Hati-hati fragile']);
        $stmt->execute([$shipment1_id, 'Spare Part Motor', 'DOC-2025-002', '2025-01-18', 10, '']);
        $stmt->execute([$shipment2_id, 'Bahan Kimia', 'DOC-2025-003', '2025-01-19', 2, 'Berbahaya - hati-hati']);
        
        echo "✅ Sample data inserted<br>";
    } else {
        echo "✅ Database already contains $count shipments<br>";
    }
    
    echo "<h3>5. Database Test Complete</h3>";
    echo "<p style='background: #4CAF50; color: white; padding: 15px; border-radius: 5px;'>";
    echo "🎉 <strong>SEMUA TEST BERHASIL!</strong><br>";
    echo "Database dan tabel sudah siap digunakan.<br>";
    echo "<a href='index.php' style='color: white; text-decoration: underline;'>► Klik di sini untuk membuka aplikasi</a>";
    echo "</p>";
    
} catch(PDOException $e) {
    echo "<div style='background: #f44336; color: white; padding: 15px; border-radius: 5px;'>";
    echo "<h3>❌ ERROR</h3>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<h4>Solusi:</h4>";
    echo "<ol>";
    echo "<li>Buka XAMPP Control Panel</li>";
    echo "<li>Klik 'Start' pada Apache dan MySQL</li>";
    echo "<li>Tunggu hingga kedua service menyala (warna hijau)</li>";
    echo "<li>Refresh halaman ini</li>";
    echo "</ol>";
    echo "</div>";
}
?>