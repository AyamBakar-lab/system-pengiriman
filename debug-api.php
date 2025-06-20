<?php
// Simple API debug script
echo "=== API Debug Information ===\n";
echo "Current Directory: " . __DIR__ . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "Query String: " . ($_SERVER['QUERY_STRING'] ?? 'none') . "\n";
echo "Method: " . $_SERVER['REQUEST_METHOD'] . "\n";

// Test if API file exists
$api_file = __DIR__ . '/api/shipments.php';
echo "API File Exists: " . (file_exists($api_file) ? 'YES' : 'NO') . "\n";
echo "API File Path: " . $api_file . "\n";

// Test database connection
try {
    require_once __DIR__ . '/config/database.php';
    echo "Database Connection: SUCCESS\n";
    
    // Test a simple query
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM shipments");
    $result = $stmt->fetch();
    echo "Shipments Count: " . $result['count'] . "\n";
    
} catch (Exception $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}

// Test direct API access
echo "\n=== Testing Direct API Access ===\n";
if (file_exists($api_file)) {
    // Simulate GET request
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_GET['debug'] = '1';
    
    ob_start();
    try {
        include $api_file;
        $output = ob_get_contents();
        echo "API Output: " . substr($output, 0, 200) . "...\n";
    } catch (Exception $e) {
        echo "API Error: " . $e->getMessage() . "\n";
    }
    ob_end_clean();
}
?>