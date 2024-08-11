<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$roomId = $data['id'] ?? null;
$roomName = $data['name'] ?? null;
$roomPrice = $data['price'] ?? null;

if (!$roomId || !$roomName || !$roomPrice) {
    echo json_encode(['success' => false, 'message' => 'Invalid room data']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, room_id, quantity) VALUES (:user_id, :room_id, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1");
    $stmt->execute([
        'user_id' => $_SESSION['user_id'],
        'room_id' => $roomId
    ]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while adding to cart']);
}
?>