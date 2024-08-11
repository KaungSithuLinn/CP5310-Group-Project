<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

// Check if the user is logged in (F.1: Authorization)
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'count' => 0, 'message' => 'User not logged in']);
    exit;
}

try {
    // Use prepared statement to prevent SQL injection (F.4)
    $stmt = $pdo->prepare("SELECT SUM(quantity) as count FROM cart WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Ensure the count is always a number, even if the cart is empty
    $count = intval($result['count'] ?? 0);

    echo json_encode(['success' => true, 'count' => $count]);
} catch (PDOException $e) {
    // Log the error and return a generic error message (D.10: Code contains sufficient comments)
    error_log('Database error in get_cart_count.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'count' => 0, 'message' => 'An error occurred while fetching cart data']);
}
?>