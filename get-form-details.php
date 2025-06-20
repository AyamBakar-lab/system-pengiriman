<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Include functions with proper path handling
$root_path = dirname(__DIR__);
require_once $root_path . '/config/database.php';
require_once $root_path . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$id_form = $_GET['id_form'] ?? '';

if (empty($id_form)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID Form tidak boleh kosong']);
    exit();
}

try {
    // Get shipment by ID form
    $stmt = $pdo->prepare("SELECT * FROM shipments WHERE id_form = ?");
    $stmt->execute([$id_form]);
    $shipment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$shipment) {
        http_response_code(404);
        echo json_encode(['error' => 'Data pengiriman dengan ID Form tersebut tidak ditemukan']);
        exit();
    }
    
    // Get all items for this shipment
    $stmt = $pdo->prepare("SELECT * FROM shipment_items WHERE shipment_id = ? ORDER BY id");
    $stmt->execute([$shipment['id']]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Combine shipment and items data
    $result = [
        'shipment' => $shipment,
        'items' => $items,
        'total_items' => count($items),
        'id_form' => $shipment['id_form']
    ];
    
    echo json_encode($result);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>