<?php
session_start();
require_once 'backend/db_config.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    echo "Invalid token.";
    exit;
}

// Check if the token is valid
$stmt = $pdo->prepare("SELECT * FROM user WHERE reset_token = :token");
$stmt->bindParam(':token', $token, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Invalid token.";
    exit;
}

$pageTitle = "Reset Password - PureComfort Luxury Accommodations";
$pageDescription = "Reset your password for PureComfort.";
include 'includes/header.php';
?>

<!-- Reset Password Section -->
<section class="reset-password-section py-5">
    <div class="container">
        <h1>Reset Your Password</h1>
        <form id="resetPasswordForm">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
        <div id="resetMessage" class="mt-3"></div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if (newPassword !== confirmPassword) {
        document.getElementById('resetMessage').innerHTML = '<p class="text-danger">Passwords do not match.</p>';
        return;
    }

    fetch('backend/process_reset_password.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ token: '<?= htmlspecialchars($token) ?>', new_password: newPassword })
    })
    .then(response => response.json())
    .then(data => {
        const messageDiv = document.getElementById('resetMessage');
        if (data.success) {
            messageDiv.innerHTML = '<p class="text-success">' + data.message + '</p>';
        } else {
            messageDiv.innerHTML = '<p class="text-danger">' + data.message + '</p>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('resetMessage').innerHTML = '<p class="text-danger">An error occurred while resetting the password.</p>';
    });
});
</script>