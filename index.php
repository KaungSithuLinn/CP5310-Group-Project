<?php
session_start();
$pageTitle = "PureComfort Luxury Accommodations in Singapore";
$pageDescription = "Experience luxury accommodations in the heart of Singapore with PureComfort. Book your perfect stay today.";
include 'includes/header.php';
?>

<!-- Hero Section -->
<header class="hero">
    <div class="container">
        <h1 class="fade-in">Experience Luxury in Singapore</h1>
        <div class="rotating-text">
            <p>Book your perfect stay with PureComfort</p>
            <p>
                <span class="word alizarin">comfort.</span>
                <span class="word wisteria">luxury.</span>
                <span class="word peter-river">style.</span>
                <span class="word emerald">elegance.</span>
                <span class="word sun-flower">convenience.</span>
            </p>
        </div>
        <a href="accommodations.php" class="btn btn-primary btn-lg">View Accommodations</a>
    </div>
</header>

<!-- Featured Accommodations -->
<section class="featured-accommodations py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">Featured Accommodations</h2>
        <div class="row">
            <!-- Featured Room 1 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="img/room1.jpg" class="card-img-top" alt="Luxury Suite">
                    <div class="card-body">
                        <h5 class="card-title">Luxury Suite</h5>
                        <p class="card-text">Experience ultimate comfort in our spacious Luxury Suite.</p>
                        <p class="card-text"><strong>$300 per night</strong></p>
                        <a href="accommodations.php#luxury-suite" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <!-- Featured Room 2 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="img/room2.jpg" class="card-img-top" alt="Deluxe Room">
                    <div class="card-body">
                        <h5 class="card-title">Deluxe Room</h5>
                        <p class="card-text">Enjoy a comfortable stay in our well-appointed Deluxe Room.</p>
                        <p class="card-text"><strong>$200 per night</strong></p>
                        <a href="accommodations.php#deluxe-room" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <!-- Featured Room 3 -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="img/room3.jpg" class="card-img-top" alt="Family Suite">
                    <div class="card-body">
                        <h5 class="card-title">Family Suite</h5>
                        <p class="card-text">Perfect for families, our spacious Family Suite offers comfort for all.</p>
                        <p class="card-text"><strong>$400 per night</strong></p>
                        <a href="accommodations.php#family-suite" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="section-title">About PureComfort</h2>
                <p>At PureComfort, we believe in providing our guests with the ultimate luxury experience in the heart of Singapore. Our carefully curated accommodations offer a perfect blend of comfort, style, and convenience.</p>
                <a href="about.php" class="btn btn-primary">Learn More About Us</a>
            </div>
            <div class="col-md-6">
                <img src="img/PureComfort Logo.png" alt="PureComfort Luxury Accommodations" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">What Our Guests Say</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <p class="card-text">"An amazing experience! The room was spotless, and the staff went above and beyond."</p>
                        <p class="card-text"><small class="text-muted">- John D.</small></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <p class="card-text">"Perfect location and luxurious amenities. Will definitely stay here again!"</p>
                        <p class="card-text"><small class="text-muted">- Sarah M.</small></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <p class="card-text">"The attention to detail in every aspect of our stay was impressive. Highly recommended!"</p>
                        <p class="card-text"><small class="text-muted">- David L.</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net