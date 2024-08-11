<?php
$pageTitle = "Forgot Password - PureComfort Luxury Accommodations";
$pageDescription = "Reset your password for PureComfort.";
include 'includes/header.php';
?>

<!-- Hero Section -->
<header class="hero forgot-password-hero">
    <div class="container">
        <h1 class="fade-in">Forgot Your Password?</h1>
        <p class="lead text-white fade-in">Enter your email to reset your password</p>
    </div>
</header>

<!-- Reset Password Section -->
<section class="reset-password-section py-5">
    <div class="container">
        <form id="resetPasswordForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </form>
        <div id="resetMessage" class="mt-3"></div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('email').value;

    fetch('backend/process_reset_password.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: email })
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
        document.getElementById('resetMessage').innerHTML = '<p class="text-danger">An error occurred. Please try again.</p>';
    });
});
</script>