<?php
require_once 'backend/db_config.php';
session_start();

// Redirect if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: account.php');
    exit;
}

// Handle POST request for login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $response = [
            'success' => false,
            'message' => 'Email and password are required.'
        ];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = [
            'success' => false,
            'message' => 'Invalid email format. Please enter a valid email address.'
        ];
    } else {
        try {
            // Fetch user from database
            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['userID'];
                $_SESSION['username'] = $user['username'];

                // Update last login timestamp
                $updateStmt = $pdo->prepare("UPDATE user SET last_login = NOW() WHERE userID = :userID");
                $updateStmt->execute(['userID' => $user['userID']]);

                $response = [
                    'success' => true,
                    'username' => $user['username'],
                    'message' => 'Welcome to PureComfort!',
                    'redirect' => 'account.php'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Invalid email or password. Please try again.'
                ];
            }
        } catch (PDOException $e) {
            $response = [
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ];
            error_log('Database error: ' . $e->getMessage());
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$pageTitle = "Login - PureComfort Luxury Accommodations";
$pageDescription = "Log in to your PureComfort account to manage your bookings and enjoy exclusive benefits.";
include 'includes/header.php';
?>

<!-- Hero Section -->
<header class="hero login-hero">
    <div class="container">
        <h1 class="fade-in">Welcome Back!</h1>
        <p class="lead text-white">Log in to manage your bookings and enjoy exclusive benefits</p>
    </div>
</header>

<!-- Login Section -->
<section class="login-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form id="loginForm" class="login-form" novalidate>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        <div class="invalid-feedback">Password must be at least 8 characters long.</div>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
                <div id="loginMessage" class="mt-3 text-center" style="display: none;"></div>
                <div class="mt-3 text-center">
                    <a href="forgot_password.php" class="text-muted">Forgot your password?</a>
                </div>
                <div class="mt-3 text-center">
                    Don't have an account? <a href="register.php">Register here</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="js/login.js"></script>