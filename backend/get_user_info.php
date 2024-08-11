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
    $stmt = $pdo->prepare("SELECT fName, lName, email, username, date_of_birth, gender, passport, address, country, postcode FROM user WHERE userID = :userID");
    $stmt->bindParam(':userID', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Sanitize data before sending
        $sanitizedUser = array_map('htmlspecialchars', $user);
        
        echo json_encode([
            'success' => true,
            'user' => $sanitizedUser
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} catch (PDOException $e) {
    // Log the error (consider using a more robust logging system in production)
    error_log("Database error in get_user_info.php: " . $e->getMessage());
    
    echo json_encode(['success' => false, 'message' => 'An error occurred while fetching user data']);
}
?>