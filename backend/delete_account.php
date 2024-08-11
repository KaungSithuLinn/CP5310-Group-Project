<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

try {
    $userId = $_SESSION['user_id'];

    $pdo->beginTransaction();

    // Delete user's reviews
    $stmt = $pdo->prepare("DELETE FROM userreview WHERE userID = :userID");
    $stmt->execute(['userID' => $userId]);

    // Delete user's bookings
    $stmt = $pdo->prepare("DELETE FROM booking_details WHERE booking_id IN (SELECT id FROM bookings WHERE userID = :userID)");
    $stmt->execute(['userID' => $userId]);

    $stmt = $pdo->prepare("DELETE FROM bookings WHERE userID = :userID");
    $stmt->execute(['userID' => $userId]);

    // Delete user's cart items
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :userID");
    $stmt->execute(['userID' => $userId]);

    // Finally, delete the user
    $stmt = $pdo->prepare("DELETE FROM user WHERE userID = :userID");
    $stmt->execute(['userID' => $userId]);

    $pdo->commit();

    // Clear session
    session_destroy();

    echo json_encode(['success' => true, 'message' => 'Your account has been successfully deleted. We\'re sad to see you go!']);
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Delete account error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while deleting the account']);
}