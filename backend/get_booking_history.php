<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY check_in_date DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'bookings' => $bookings]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>