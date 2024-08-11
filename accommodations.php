<?php
require_once 'backend/db_config.php';
session_start(); // Start session for user authentication

$pageTitle = "Accommodations - PureComfort Luxury Accommodations";
$pageDescription = "Explore our luxurious accommodations at PureComfort. Book your perfect stay in Singapore.";

// Fetch rooms from the database
try {
    $stmt = $pdo->query("SELECT * FROM rooms");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    $rooms = [];
}

include 'includes/header.php';
?>

<!-- Hero Section -->
<header class="hero accommodations-hero">
    <div class="container">
        <h1 class="fade-in">Our Accommodations</h1>
        <p class="lead text-white">Discover the perfect room for your stay</p>
    </div>
</header>

<!-- Accommodations Section -->
<section class="accommodations-section py-5">
    <div class="container">
        <div class="row">
            <?php if (!empty($rooms)): ?>
                <?php foreach ($rooms as $room): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <?php if (!empty($room['image'])): ?>
                                <img src="img/<?= htmlspecialchars($room['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($room['name']) ?>">
                            <?php else: ?>
                                <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">No Image Available</div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($room['name']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($room['description']) ?></p>
                                <p class="card-text"><strong>$<?= number_format($room['price'], 2) ?> per night</strong></p>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <button class="btn btn-primary add-to-cart" 
                                        data-room-id="<?= htmlspecialchars($room['id']) ?>" 
                                        data-room-name="<?= htmlspecialchars($room['name']) ?>"
                                        data-room-price="<?= htmlspecialchars($room['price']) ?>">
                                        Add to Cart
                                    </button>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-primary">Login to Book</a>
                                <?php endif; ?>
                                <a href="room_details.php?id=<?= htmlspecialchars($room['id']) ?>" class="btn btn-secondary mt-2">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p>No rooms available at the moment. Please check back later.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="js/accommodations.js"></script>