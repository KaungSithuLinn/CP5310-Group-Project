<?php
require 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to manage your cart.";
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO cart (user_id, room_id, quantity) VALUES (:user_id, :room_id, :quantity) ON DUPLICATE KEY UPDATE quantity = quantity + :quantity");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':room_id', $room_id);
    $stmt->bindParam(':quantity', $quantity);

    if ($stmt->execute()) {
        echo "Room added to cart!";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
?>