<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Sanitize and validate input
$fName = filter_input(INPUT_POST, 'fName', FILTER_SANITIZE_STRING);
$lName = filter_input(INPUT_POST, 'lName', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$date_of_birth = filter_input(INPUT_POST, 'date_of_birth', FILTER_SANITIZE_STRING);
$gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
$passport = filter_input(INPUT_POST, 'passport', FILTER_SANITIZE_STRING);
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
$country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
$postcode = filter_input(INPUT_POST, 'postcode', FILTER_SANITIZE_STRING);

$errors = [];

// Validate input
if (empty($fName) || empty($lName) || empty($email) || empty($username) || empty($date_of_birth) || empty($gender) || empty($passport) || empty($address) || empty($country) || empty($postcode)) {
    $errors[] = 'All fields are required.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
}
if (!preg_match("/^[a-zA-Z ]*$/", $fName) || !preg_match("/^[a-zA-Z ]*$/", $lName)) {
    $errors[] = 'Name should only contain letters and spaces.';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE user SET fName = :fName, lName = :lName, email = :email, username = :username, date_of_birth = :dob, gender = :gender, passport = :passport, address = :address, country = :country, postcode = :postcode WHERE userID = :userID");
    $stmt->bindParam(':fName', $fName, PDO::PARAM_STR);
    $stmt->bindParam(':lName', $lName, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':dob', $date_of_birth, PDO::PARAM_STR);
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
    $stmt->bindParam(':passport', $passport, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':country', $country, PDO::PARAM_STR);
    $stmt->bindParam(':postcode', $postcode, PDO::PARAM_STR);
    $stmt->bindParam(':userID', $_SESSION['user_id'], PDO::PARAM_INT);
    $result = $stmt->execute();

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
    }
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
