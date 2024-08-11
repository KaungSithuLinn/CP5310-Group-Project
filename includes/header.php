<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'PureComfort Luxury Accommodations'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription ?? 'Experience luxury accommodations in Singapore with PureComfort.'); ?>">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <!-- Add any additional meta tags or stylesheets here -->
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">PureComfort</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="accommodations.php">Accommodations</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="account.php">My Account</a>
                                <a class="dropdown-item" href="booking_history.php">Booking History</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="backend/logout.php">Logout</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="cart-page.php" aria-label="Shopping Cart"> <!-- Updated to cart-page.php -->
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge">0</span> <!-- This will be updated dynamically -->
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Welcome Message -->
    <?php
    if (isset($_SESSION['welcome_message'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
        echo htmlspecialchars($_SESSION['welcome_message']);
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        echo '<span aria-hidden="true">&times;</span></button></div>';
        unset($_SESSION['welcome_message']);
    }
    ?>

    <!-- Main Content Container -->
    <div class="container mt-5 pt-5">
        <!-- Page content will be inserted here -->