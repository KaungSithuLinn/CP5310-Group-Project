<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log incoming request
error_log("Received " . $_SERVER['REQUEST_METHOD'] . " request to cart.php");
error_log("POST data: " . print_r($_POST, true));
error_log("SESSION data: " . print_r($_SESSION, true));

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function getCart() {
    global $pdo;
    $cart = [];
    error_log("Getting cart contents");
    foreach ($_SESSION['cart'] as $room_id => $quantity) {
        $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
        $stmt->execute([$room_id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($room) {
            $room['quantity'] = $quantity;
            $cart[] = $room;
        } else {
            error_log("Room not found for ID $room_id");
        }
    }
    error_log("Cart contents: " . print_r($cart, true));
    return $cart;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Fetch cart contents
        echo json_encode([
            'success' => true,
            'cart' => getCart()
        ]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        switch ($action) {
            case 'add':
                $room_id = $_POST['room_id'] ?? '';
                $quantity = intval($_POST['quantity'] ?? 1);

                error_log("Adding to cart: Room ID = $room_id, Quantity = $quantity");

                if (empty($room_id)) {
                    error_log("Error: Room ID is required");
                    echo json_encode(['success' => false, 'message' => 'Room ID is required']);
                    exit;
                }

                // Check if room exists
                $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
                $stmt->execute([$room_id]);
                $room = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$room) {
                    error_log("Error: Room not found for ID $room_id");
                    echo json_encode(['success' => false, 'message' => 'Room not found']);
                    exit;
                }

                error_log("Room found: " . print_r($room, true));

                // Add to cart
                if (isset($_SESSION['cart'][$room_id])) {
                    $_SESSION['cart'][$room_id] += $quantity;
                } else {
                    $_SESSION['cart'][$room_id] = $quantity;
                }

                error_log("Updated cart: " . print_r($_SESSION['cart'], true));

                echo json_encode(['success' => true, 'message' => 'Item added to cart', 'cart' => getCart()]);
                break;

            case 'remove':
                $room_id = $_POST['room_id'] ?? '';

                if (empty($room_id)) {
                    echo json_encode(['success' => false, 'message' => 'Room ID is required']);
                    exit;
                }

                if (isset($_SESSION['cart'][$room_id])) {
                    unset($_SESSION['cart'][$room_id]);
                    echo json_encode(['success' => true, 'message' => 'Item removed from cart', 'cart' => getCart()]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
                }
                break;

            case 'update':
                $room_id = $_POST['room_id'] ?? '';
                $quantity = intval($_POST['quantity'] ?? 0);

                if (empty($room_id)) {
                    echo json_encode(['success' => false, 'message' => 'Room ID is required']);
                    exit;
                }

                if ($quantity > 0) {
                    $_SESSION['cart'][$room_id] = $quantity;
                    echo json_encode(['success' => true, 'message' => 'Cart updated', 'cart' => getCart()]);
                } elseif ($quantity == 0) {
                    unset($_SESSION['cart'][$room_id]);
                    echo json_encode(['success' => true, 'message' => 'Item removed from cart', 'cart' => getCart()]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
                }
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}