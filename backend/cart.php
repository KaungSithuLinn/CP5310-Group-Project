<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

// Enable error logging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Log incoming request
error_log("Received " . $_SERVER['REQUEST_METHOD'] . " request to cart.php");
error_log("POST data: " . print_r($_POST, true));
error_log("SESSION data: " . print_r($_SESSION, true));

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

function getCart($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT c.id, c.quantity, r.id as room_id, r.name, r.price, r.image FROM cart c JOIN rooms r ON c.room_id = r.id WHERE c.user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Fetch cart contents
        echo json_encode([
            'success' => true,
            'cart' => getCart($pdo, $_SESSION['user_id'])
        ]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        switch ($action) {
            case 'add':
                $room_id = $_POST['room_id'] ?? '';
                $quantity = intval($_POST['quantity'] ?? 1);

                if (empty($room_id)) {
                    echo json_encode(['success' => false, 'message' => 'Room ID is required']);
                    exit;
                }

                $stmt = $pdo->prepare("INSERT INTO cart (user_id, room_id, quantity) VALUES (:user_id, :room_id, :quantity) ON DUPLICATE KEY UPDATE quantity = quantity + :quantity");
                $stmt->execute([
                    'user_id' => $_SESSION['user_id'],
                    'room_id' => $room_id,
                    'quantity' => $quantity
                ]);

                echo json_encode(['success' => true, 'message' => 'Item added to cart', 'cart' => getCart($pdo, $_SESSION['user_id'])]);
                break;

            case 'remove':
                $room_id = $_POST['room_id'] ?? '';

                if (empty($room_id)) {
                    echo json_encode(['success' => false, 'message' => 'Room ID is required']);
                    exit;
                }

                $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id AND room_id = :room_id");
                $stmt->execute([
                    'user_id' => $_SESSION['user_id'],
                    'room_id' => $room_id
                ]);

                echo json_encode(['success' => true, 'message' => 'Item removed from cart', 'cart' => getCart($pdo, $_SESSION['user_id'])]);
                break;

            case 'update':
                $room_id = $_POST['room_id'] ?? '';
                $quantity = intval($_POST['quantity'] ?? 0);

                if (empty($room_id)) {
                    echo json_encode(['success' => false, 'message' => 'Room ID is required']);
                    exit;
                }

                if ($quantity > 0) {
                    $stmt = $pdo->prepare("UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND room_id = :room_id");
                    $stmt->execute([
                        'user_id' => $_SESSION['user_id'],
                        'room_id' => $room_id,
                        'quantity' => $quantity
                    ]);
                } else {
                    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id AND room_id = :room_id");
                    $stmt->execute([
                        'user_id' => $_SESSION['user_id'],
                        'room_id' => $room_id
                    ]);
                }

                echo json_encode(['success' => true, 'message' => 'Cart updated', 'cart' => getCart($pdo, $_SESSION['user_id'])]);
                break;

            case 'clear':
                $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
                $stmt->execute(['user_id' => $_SESSION['user_id']]);

                echo json_encode(['success' => true, 'message' => 'Cart cleared', 'cart' => getCart($pdo, $_SESSION['user_id'])]);
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