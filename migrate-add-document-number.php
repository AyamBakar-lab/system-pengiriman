<?php
// Database migration to add document number field to existing installations
require_once 'config/database.php';

echo "<h1>Database Migration: Add Document Number Field</h1>";

try {
    // Check if column already exists
    $stmt = $pdo->query("SHOW COLUMNS FROM shipments LIKE 'no_dokumen'");
    $columnExists = $stmt->fetch();
    
    if (!$columnExists) {
        echo "<p>Adding no_dokumen column to shipments table...</p>";
        
        // Add the column
        $pdo->exec("ALTER TABLE shipments ADD COLUMN no_dokumen VARCHAR(50) AFTER id");
        
        // Generate document numbers for existing records
        $stmt = $pdo->query("SELECT id, created_at FROM shipments WHERE no_dokumen IS NULL OR no_dokumen = ''");
        $existingShipments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $updateStmt = $pdo->prepare("UPDATE shipments SET no_dokumen = ? WHERE id = ?");
        
        foreach ($existingShipments as $shipment) {
            $date = new DateTime($shipment['created_at']);
            $dateStr = $date->format('Ymd');
            $docNumber = "DOK-{$dateStr}-" . str_pad($shipment['id'], 4, '0', STR_PAD_LEFT);
            
            $updateStmt->execute([$docNumber, $shipment['id']]);
            echo "<p>Generated document number {$docNumber} for shipment ID {$shipment['id']}</p>";
        }
        
        // Make the column unique and not null
        $pdo->exec("ALTER TABLE shipments MODIFY COLUMN no_dokumen VARCHAR(50) UNIQUE NOT NULL");
        
        echo "<p><strong>✅ Migration completed successfully!</strong></p>";
        echo "<p>Added no_dokumen field and generated document numbers for " . count($existingShipments) . " existing records.</p>";
        
    } else {
        echo "<p><strong>ℹ️ Column no_dokumen already exists.</strong></p>";
        echo "<p>No migration needed.</p>";
    }
    
    // Verify the structure
    echo "<h2>Current Table Structure</h2>";
    $stmt = $pdo->query("DESCRIBE shipments");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<p><strong>❌ Migration failed:</strong> " . $e->getMessage() . "</p>";
}

echo "<p><a href='index.php'>← Back to Application</a></p>";
?>