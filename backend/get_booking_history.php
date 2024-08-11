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
    $stmt = $pdo->prepare("SELECT b.id, b.total_price, b.booking_date, bd.room_name, bd.quantity 
                            FROM bookings b
                            JOIN booking_details bd ON b.id = bd.booking_id
                            WHERE b.userID = :userID
                            ORDER BY b.booking_date DESC");
    $stmt->bindParam(':userID', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'bookings' => $bookings]);
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}