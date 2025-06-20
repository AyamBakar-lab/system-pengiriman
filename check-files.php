<?php
echo "<h1>File System Check</h1>";

// Check if API file exists
$api_file = __DIR__ . '/api/shipments.php';
echo "<h2>API File Status</h2>";
echo "API file path: " . $api_file . "<br>";
echo "API file exists: " . (file_exists($api_file) ? 'YES' : 'NO') . "<br>";

if (file_exists($api_file)) {
    echo "File size: " . filesize($api_file) . " bytes<br>";
    echo "File permissions: " . substr(sprintf('%o', fileperms($api_file)), -4) . "<br>";
}

// Check directory structure
echo "<h2>Directory Structure</h2>";
$files = scandir(__DIR__);
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $path = __DIR__ . '/' . $file;
        echo $file . (is_dir($path) ? ' (directory)' : ' (file)') . "<br>";
    }
}

// Check if API directory exists
$api_dir = __DIR__ . '/api';
echo "<h2>API Directory</h2>";
echo "API directory exists: " . (is_dir($api_dir) ? 'YES' : 'NO') . "<br>";

if (is_dir($api_dir)) {
    echo "API directory contents:<br>";
    $api_files = scandir($api_dir);
    foreach ($api_files as $file) {
        if ($file != '.' && $file != '..') {
            echo "- " . $file . "<br>";
        }
    }
}

// Test direct API access
echo "<h2>Direct API Test</h2>";
if (file_exists($api_file)) {
    echo "Attempting to include API file...<br>";
    
    // Capture any output
    ob_start();
    $error = null;
    
    try {
        // Set up environment for API
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/api/shipments.php';
        $_SERVER['SCRIPT_NAME'] = '/api/shipments.php';
        
        include $api_file;
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    } catch (Error $e) {
        $error = $e->getMessage();
    }
    
    $output = ob_get_clean();
    
    if ($error) {
        echo "Error: " . $error . "<br>";
    }
    
    if ($output) {
        echo "API Output:<br>";
        echo "<pre>" . htmlspecialchars(substr($output, 0, 500)) . "</pre>";
    }
}

// Test database connection
echo "<h2>Database Connection Test</h2>";
try {
    include __DIR__ . '/config/database.php';
    echo "Database connection: SUCCESS<br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM shipments");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Shipments in database: " . $result['count'] . "<br>";
    
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "<br>";
}

// Check .htaccess
echo "<h2>.htaccess Check</h2>";
$htaccess_file = __DIR__ . '/.htaccess';
echo ".htaccess exists: " . (file_exists($htaccess_file) ? 'YES' : 'NO') . "<br>";

if (file_exists($htaccess_file)) {
    echo ".htaccess content:<br>";
    echo "<pre>" . htmlspecialchars(file_get_contents($htaccess_file)) . "</pre>";
}
?>