<?php
session_start();
header('Content-Type: application/json');
require __DIR__ . '/config.php';

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Please log in to place an order.']);
    exit;
}

$user = $_SESSION['user'];
$contactType = $_POST['contactType'] ?? '';
$contactValue = trim($_POST['contactValue'] ?? '');
$features = $_POST['features'] ?? '';
$total = floatval($_POST['total'] ?? 0);
$productId = $_POST['productId'] ?? '1';

if (!$contactType || !$contactValue || $total <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required.']);
    exit;
}

// Get user ID
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$user]);
$userRow = $stmt->fetch();
if (!$userRow) {
    http_response_code(400);
    echo json_encode(['error' => 'User not found.']);
    exit;
}
$userId = $userRow['id'];

try {
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, product_id, features, total_price, contact_type, contact_value) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $productId, $features, $total, $contactType, $contactValue]);
    echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to place order: ' . $e->getMessage()]);
}
?>