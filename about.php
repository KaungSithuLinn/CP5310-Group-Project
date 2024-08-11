<?php
$pageTitle = "About PureComfort - Luxury Accommodations in Singapore";
$pageDescription = "Learn about PureComfort's commitment to providing exceptional luxury accommodations in Singapore. Discover our story, mission, and team.";
include 'includes/header.php';
?>

<!-- Hero Section -->
<header class="hero about-hero">
    <div class="container">
        <h1 class="fade-in">About PureComfort</h1>
        <p class="lead text-white fade-in">Discover our story of luxury and comfort</p>
    </div>
</header>

<!-- Our Story -->
<section class="about-section py-5">
    <div class="container">
        <h2 class="section-title translate-scale-fade">Our Story</h2>
        <div class="row">
            <div class="col-md-6">
                <p class="translate-scale-fade">Founded in 2024, PureComfort began with a simple vision: to provide travelers with a home away from home in the heart of Singapore. Our founder, Sarah Lim, recognized the need for accommodations that combined the luxury of high-end hotels with the comfort and convenience of a private residence.</p>
                <p class="translate-scale-fade">Over the years, we've grown from a single property to a collection of premium accommodations across Singapore's most desirable locations. Our commitment to quality, comfort, and exceptional service has remained unwavering throughout our journey.</p>
            </div>
            <div class="col-md-6">
                <img src="img/PureComfort Logo.png" alt="PureComfort's journey" class="img-fluid rounded translate-scale-fade">
            </div>
        </div>
    </div>
</section>

<!-- Our Mission -->
<section class="about-section py-5 bg-light">
    <div class="container">
        <h2 class="section-title fade-in">Our Mission</h2>
        <div class="row">
            <div class="col-md-6 order-md-2">
                <p class="translate-scale-fade">At PureComfort, our mission is to redefine the concept of temporary accommodations. We strive to:</p>
                <ul class="translate-scale-fade">
                    <li>Provide a seamless blend of luxury and comfort in every stay</li>
                    <li>Offer personalized experiences that cater to the unique needs of each guest</li>
                    <li>Maintain the highest standards of cleanliness and safety</li>
                    <li>Contribute positively to the local community and environment</li>
                </ul>
            </div>
            <div class="col-md-6 order-md-1">
                <img src="img/dart-mission-goal-success-svgrepo-com.svg" alt="PureComfort's mission" class="img-fluid rounded floating" style="width: 250px; height: auto;">
            </div>
        </div>
    </div>
</section>

<!-- Our Team -->
<section class="about-section py-5">
    <div class="container">
        <h2 class="section-title fade-in">Meet Our Team</h2>
        <div class="row justify-content-center">
            <div class="col-md-3 col-sm-6 mb-4 team-member">
                <div class="team-member text-center translate-scale-fade">
                    <img src="img/YuCheng Zhang.jpg" alt="YuCheng Zhang" class="img-fluid rounded-circle mb-3">
                    <h3>YuCheng Zhang</h3>
                    <p>Founder & CEO</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4 team-member">
                <div class="team-member text-center translate-scale-fade">
                    <img src="img/KaungSithuLinn.jpg" alt="KaungSithuLinn" class="img-fluid rounded-circle mb-3">
                    <h3>KaungSithuLinn</h3>
                    <p>Operations Manager</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4 team-member">
                <div class="team-member text-center translate-scale-fade">
                    <img src="img/Worapas Pruktipinyopap.jpg" alt="Worapas Pruktipinyopap" class="img-fluid rounded-circle mb-3">
                    <h3>Worapas Pruktipinyopap</h3>
                    <p>Customer Experience Director</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials py-5 bg-light">
    <div class="container">
        <h2 class="section-title fade-in">What Our Guests Say</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 translate-scale-fade">
                    <div class="card-body">
                        <p class="card-text">"PureComfort made our family vacation in Singapore unforgettable. The accommodations were spacious, clean, and felt just like home."</p>
                        <p class="font-weight-bold mb-0">- The Johnson Family</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 translate-scale-fade">
                    <div class="card-body">
                        <p class="card-text">"As a business traveler, I appreciate the convenience and comfort PureComfort offers. It's my go-to choice whenever I'm in Singapore."</p>
                        <p class="font-weight-bold mb-0">- Mark S., Corporate Executive</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 translate-scale-fade">
                    <div class="card-body">
                        <p class="card-text">"The attention to detail and personalized service at PureComfort is unmatched. It truly feels like a luxury home away from home."</p>
                        <p class="font-weight-bold mb-0">- Lisa and Tom, Honeymooners</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Update cart badge
    updateCartBadge();

    // Newsletter form submission
    document.getElementById('newsletter-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        // Implement newsletter signup logic here
        alert(`Thank you for subscribing with email: ${email}`);
        this.reset();
    });
});
</script>
</body>
</html>