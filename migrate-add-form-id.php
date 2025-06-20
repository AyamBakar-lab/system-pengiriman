<?php
// Database migration to add ID form field to existing installations
require_once 'config/database.php';

echo "<h1>Database Migration: Add ID Form Field</h1>";

try {
    // Check if column already exists
    $stmt = $pdo->query("SHOW COLUMNS FROM shipments LIKE 'id_form'");
    $columnExists = $stmt->fetch();
    
    if (!$columnExists) {
        echo "<p>Adding id_form column to shipments table...</p>";
        
        // Add the column
        $pdo->exec("ALTER TABLE shipments ADD COLUMN id_form VARCHAR(50) AFTER id");
        
        // Generate form IDs for existing records
        $stmt = $pdo->query("SELECT id, created_at FROM shipments WHERE id_form IS NULL OR id_form = ''");
        $existingShipments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $updateStmt = $pdo->prepare("UPDATE shipments SET id_form = ? WHERE id = ?");
        
        foreach ($existingShipments as $shipment) {
            $date = new DateTime($shipment['created_at']);
            $dateStr = $date->format('Ymd');
            $formId = "FORM-{$dateStr}-" . str_pad($shipment['id'], 4, '0', STR_PAD_LEFT);
            
            $updateStmt->execute([$formId, $shipment['id']]);
            echo "<p>Generated form ID {$formId} for shipment ID {$shipment['id']}</p>";
        }
        
        // Make the column unique and not null
        $pdo->exec("ALTER TABLE shipments MODIFY COLUMN id_form VARCHAR(50) UNIQUE NOT NULL");
        
        echo "<p><strong>✅ Migration completed successfully!</strong></p>";
        echo "<p>Added id_form field and generated form IDs for " . count($existingShipments) . " existing records.</p>";
        
    } else {
        echo "<p><strong>ℹ️ Column id_form already exists.</strong></p>";
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
    
    echo "<h2>Test Form ID Search</h2>";
    echo "<form method='GET' action='get-form-details.php' target='_blank'>";
    echo "<input type='text' name='id_form' placeholder='Enter Form ID (e.g., FORM-20250119-0001)' style='padding: 8px; width: 300px;'>";
    echo "<button type='submit' style='padding: 8px 16px; margin-left: 10px;'>Search Form</button>";
    echo "</form>";
    
} catch (Exception $e) {
    echo "<p><strong>❌ Migration failed:</strong> " . $e->getMessage() . "</p>";
}

echo "<p><a href='index.php'>← Back to Application</a></p>";
?>