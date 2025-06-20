<?php
header('Content-Type: application/json');

// Direct API test - bypass routing
try {
    // Include database and functions directly
    require_once __DIR__ . '/config/database.php';
    require_once __DIR__ . '/includes/functions.php';
    
    // Test basic functionality
    $search = $_GET['search'] ?? '';
    $branch = $_GET['cabang_tujuan'] ?? '';
    
    $shipments = getShipments($search, $branch);
    
    // Return JSON response
    echo json_encode([
        'status' => 'success',
        'count' => count($shipments),
        'data' => $shipments
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'file' => __FILE__
    ]);
}
?>