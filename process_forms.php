<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Process login form
        $email = $_POST['email'];
        $password = $_POST['password'];
        // Example: Validate login credentials (replace with actual validation)
        if ($email === 'test@example.com' && $password === 'password123') {
            echo "Login successful for email: " . $email;
        } else {
            echo "Invalid email or password";
        }
    } elseif (isset($_POST['register'])) {
        // Process registration form
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        // Example: Save user to database (replace with actual database code)
        // Simulate registration success
        echo json_encode(array("success" => true, "message" => "Registration successful for: $name"));
    } elseif (isset($_POST['review'])) {
        // Process review submission
        $review = $_POST['review'];
        // Example: Save review to database (replace with actual database code)
        echo "Review submitted: " . $review;
    } else {
        // Handle invalid requests
        http_response_code(400); // Bad Request
        echo json_encode(array("success" => false, "message" => "Invalid request"));
    }
} else {
    // Handle invalid requests
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}
?>
