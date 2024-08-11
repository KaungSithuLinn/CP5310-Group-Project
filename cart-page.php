<?php
session_start();
require_once 'backend/db_config.php';

// Authorization check (F.1)
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = "Your Cart - PureComfort Luxury Accommodations";
$pageDescription = "Review and confirm your luxury accommodations at PureComfort.";
include 'includes/header.php';
?>

<!-- Hero Section -->
<header class="hero cart-hero">
    <div class="container">
        <h1 class="fade-in">Your Cart</h1>
        <p class="lead text-white">Review and confirm your luxury accommodations</p>
    </div>
</header>

<!-- Cart Content -->
<section class="cart-content py-5">
    <div class="container">
        <div id="cart-items" class="mb-4">
            <!-- Cart items will be dynamically inserted here -->
        </div>
        <div id="cart-summary" class="text-right">
            <h3>Total: $<span id="total-price">0.00</span></h3>
            <button id="checkout-btn" class="btn btn-primary btn-lg mt-3" disabled>Proceed to Checkout</button>
            <a href="accommodations.php" class="btn btn-outline-secondary btn-lg mt-3 ml-2">Continue Shopping</a>
            <button id="clear-cart-btn" class="btn btn-danger btn-lg mt-3 ml-2">Clear Cart</button> <!-- Button to clear the cart -->
        </div>
        <div id="empty-cart-message" class="text-center" style="display:none;">
            <h2>Your cart is empty</h2>
            <p>Explore our luxurious accommodations and add your favorite to the cart.</p>
            <a href="accommodations.php" class="btn btn-primary btn-lg mt-3">View Accommodations</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="js/cart.js"></script>