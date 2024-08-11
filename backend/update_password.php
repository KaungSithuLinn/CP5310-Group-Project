<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $token = $data['token'] ?? '';
    $newPassword = $data['new_password'] ?? '';

    if (empty($token) || empty($newPassword)) {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }

    try {
        // Check if the token is valid
        $stmt = $pdo->prepare("SELECT * FROM user WHERE reset_token = :token");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Invalid token.']);
            exit;
        }

        // Update the password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE user SET password = :password, reset_token = NULL WHERE userID = :userID");
        $stmt->execute(['password' => $hashedPassword, 'userID' => $user['userID']]);

        echo json_encode(['success' => true, 'message' => 'Your password has been reset successfully.']);
    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred while updating the password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}