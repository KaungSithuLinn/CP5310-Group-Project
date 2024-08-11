<?php
session_start();
require_once 'db_config.php';

error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');

// Authorization check
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

try {
    // Fetch cart items
    $stmt = $pdo->prepare("SELECT c.room_id, c.quantity, r.price, r.name 
                            FROM cart c 
                            JOIN rooms r ON c.room_id = r.id
                            WHERE c.user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Validate cart contents
    if (empty($cartItems)) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty']);
        exit;
    }

    // Create booking
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO bookings (userID) VALUES (:user_id)");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $bookingId = $pdo->lastInsertId();

    // Add booking details
    $stmt = $pdo->prepare("INSERT INTO booking_details (booking_id, room_id, room_name, quantity, price, booking_date) 
                            VALUES (:booking_id, :room_id, :room_name, :quantity, :price, NOW())");
    $totalPrice = 0;
    foreach ($cartItems as $item) {
        $roomPrice = $item['price'] * $item['quantity'];
        $stmt->execute([
            'booking_id' => $bookingId,
            'room_id' => $item['room_id'],
            'room_name' => $item['name'],
            'quantity' => $item['quantity'],
            'price' => $roomPrice
        ]);
        $totalPrice += $roomPrice;
    }

    // Update total price in bookings table
    $stmt = $pdo->prepare("UPDATE bookings SET total_price = :total_price WHERE id = :booking_id");
    $stmt->execute([
        'total_price' => $totalPrice,
        'booking_id' => $bookingId
    ]);

    // Clear cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);

    $pdo->commit();

    // Log successful booking
    error_log("Booking created successfully. Booking ID: " . $bookingId . ", User ID: " . $_SESSION['user_id']);

    echo json_encode(['success' => true, 'bookingId' => $bookingId]);
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log('Database error in create_booking.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while creating the booking: ' . $e->getMessage()]);
}