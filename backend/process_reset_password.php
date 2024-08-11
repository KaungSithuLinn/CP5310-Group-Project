<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

// Check if the request is a POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'] ?? '';

    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email address is required.']);
        exit;
    }

    // Check if the email exists in the database
    try {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'No account found with that email address.']);
            exit;
        }

        // Generate a password reset token
        $token = bin2hex(random_bytes(50));
        $stmt = $pdo->prepare("UPDATE user SET reset_token = :token WHERE email = :email");
        $stmt->execute(['token' => $token, 'email' => $email]);

        // Send email with the reset link
        $resetLink = "http://yourdomain.com/reset_password.php?token=" . $token; // Update with your domain
        $subject = "Password Reset Request";
        $message = "Click the link below to reset your password:\n\n" . $resetLink;

        // Uncomment the line below to send the email
        // mail($email, $subject, $message);

        echo json_encode(['success' => true, 'message' => 'A password reset link has been sent to your email.']);
    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred while processing your request.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}