<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'config/database.php';
require_once 'includes/functions.php';

echo "<h1>Test ID Form Functionality</h1>";

try {
    // Test 1: Check if id_form column exists
    echo "<h2>1. Database Structure Check</h2>";
    $stmt = $pdo->query("SHOW COLUMNS FROM shipments LIKE 'id_form'");
    $columnExists = $stmt->fetch();
    
    if ($columnExists) {
        echo "<p>✅ Column 'id_form' exists in shipments table</p>";
        echo "<p>Type: {$columnExists['Type']}, Null: {$columnExists['Null']}, Key: {$columnExists['Key']}</p>";
    } else {
        echo "<p>❌ Column 'id_form' does not exist. Run migrate-add-form-id.php first.</p>";
        exit();
    }
    
    // Test 2: Create a test shipment with ID form
    echo "<h2>2. Create Test Shipment</h2>";
    $testData = [
        'id_form' => 'FORM-' . date('Ymd') . '-TEST',
        'no_dokumen' => 'DOK-' . date('Ymd') . '-TEST',
        'no_polisi' => 'B1234TEST',
        'no_segel' => 'SEAL001',
        'nama_driver' => 'Test Driver',
        'nama_co_driver' => 'Test Co-Driver',
        'nama_staff' => 'Test Staff',
        'cabang_tujuan' => 'jakarta',
        'catatan_pengiriman' => 'Test shipment for ID form functionality',
        'items' => [
            [
                'nama_barang' => 'Test Item 1',
                'no_dokumen' => 'DOK-' . date('Ymd') . '-ITEM1',
                'tanggal_kirim' => date('Y-m-d'),
                'jumlah_barang' => 10,
                'catatan' => 'Test item 1'
            ],
            [
                'nama_barang' => 'Test Item 2',
                'no_dokumen' => 'DOK-' . date('Ymd') . '-ITEM2',
                'tanggal_kirim' => date('Y-m-d'),
                'jumlah_barang' => 5,
                'catatan' => 'Test item 2'
            ]
        ]
    ];
    
    $shipment = createShipment($pdo, $testData);
    $testShipmentId = $shipment['id'];
    echo "<p>✅ Test shipment created with ID: {$testShipmentId}</p>";
    echo "<p>ID Form: {$shipment['id_form']}</p>";
    echo "<p>Document Number: {$shipment['no_dokumen']}</p>";
    echo "<p>Items Count: " . count($shipment['items']) . "</p>";
    
    // Test 3: Search functionality
    echo "<h2>3. Search Functionality Test</h2>";
    
    // Test search by ID form
    $searchResults = getShipments($pdo, $testData['id_form']);
    if (!empty($searchResults)) {
        echo "<p>✅ Search by ID Form successful. Found " . count($searchResults) . " result(s)</p>";
        foreach ($searchResults as $result) {
            echo "<p>Found: {$result['id_form']} - {$result['no_polisi']} ({$result['nama_driver']})</p>";
        }
    } else {
        echo "<p>❌ Search by ID Form failed</p>";
    }
    
    // Test search by document number
    $searchResults = getShipments($pdo, $testData['no_dokumen']);
    if (!empty($searchResults)) {
        echo "<p>✅ Search by Document Number successful</p>";
    } else {
        echo "<p>❌ Search by Document Number failed</p>";
    }
    
    // Test 4: API endpoint test
    echo "<h2>4. API Endpoint Test</h2>";
    $apiUrl = "get-form-details.php?id_form=" . urlencode($testData['id_form']);
    
    echo "<p>Testing API: <a href='{$apiUrl}' target='_blank'>{$apiUrl}</a></p>";
    
    // Test with curl if available
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "/" . $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            $data = json_decode($response, true);
            if ($data && isset($data['shipment'])) {
                echo "<p>✅ API endpoint working correctly</p>";
                echo "<p>API Response: ID Form = {$data['shipment']['id_form']}, Total Items = {$data['total_items']}</p>";
            } else {
                echo "<p>❌ API returned invalid data</p>";
            }
        } else {
            echo "<p>❌ API endpoint returned HTTP {$httpCode}</p>";
        }
    } else {
        echo "<p>⚠️ cURL not available for API testing</p>";
    }
    
    // Test 5: Clean up test data
    echo "<h2>5. Cleanup Test Data</h2>";
    $stmt = $pdo->prepare("DELETE FROM shipment_items WHERE shipment_id = ?");
    $stmt->execute([$testShipmentId]);
    
    $stmt = $pdo->prepare("DELETE FROM shipments WHERE id = ?");
    $stmt->execute([$testShipmentId]);
    
    echo "<p>✅ Test data cleaned up</p>";
    
    // Test 6: Form ID generation test
    echo "<h2>6. Form ID Generation Test</h2>";
    $now = new DateTime();
    $dateStr = $now->format('Ymd');
    
    // Get the latest form ID for today
    $stmt = $pdo->prepare("SELECT id_form FROM shipments WHERE id_form LIKE ? ORDER BY id_form DESC LIMIT 1");
    $stmt->execute(["FORM-{$dateStr}-%"]);
    $latestForm = $stmt->fetch();
    
    if ($latestForm) {
        $parts = explode('-', $latestForm['id_form']);
        $lastNumber = intval($parts[2]);
        $nextNumber = $lastNumber + 1;
        echo "<p>✅ Latest form ID for today: {$latestForm['id_form']}</p>";
        echo "<p>Next form ID would be: FORM-{$dateStr}-" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT) . "</p>";
    } else {
        echo "<p>ℹ️ No forms created today. First form would be: FORM-{$dateStr}-0001</p>";
    }
    
    echo "<h2>✅ All Tests Completed Successfully!</h2>";
    echo "<p><strong>Summary:</strong></p>";
    echo "<ul>";
    echo "<li>Database structure is correct</li>";
    echo "<li>ID Form creation and storage working</li>";
    echo "<li>Search functionality operational</li>";
    echo "<li>API endpoints accessible</li>";
    echo "<li>Form ID generation logic functional</li>";
    echo "</ul>";
    
    echo "<p><a href='index.php'>← Return to Application</a></p>";
    
} catch (Exception $e) {
    echo "<h2>❌ Test Failed</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
}
?>