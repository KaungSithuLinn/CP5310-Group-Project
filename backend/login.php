<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Email and password are required.'
        ]);
        exit;
    }

    try {
        // Fetch user from database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Check if this is the user's first login
            $isNewUser = !isset($user['last_login']) || is_null($user['last_login']);

            // Update last login time
            $updateStmt = $pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
            $updateStmt->execute([$user['id']]);

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];

            echo json_encode([
                'success' => true,
                'username' => $user['name'],
                'isNewUser' => $isNewUser,
                'message' => $isNewUser ? 'Welcome to PureComfort!' : 'Welcome back!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid email or password.'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}