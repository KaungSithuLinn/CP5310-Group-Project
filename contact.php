<?php
$pageTitle = "Contact Us - PureComfort Luxury Accommodations";
$pageDescription = "Get in touch with PureComfort for inquiries about our luxury accommodations in Singapore.";
include 'includes/header.php';
?>

<!-- Hero Section -->
<header class="hero contact-hero">
    <div class="container">
        <h1 class="fade-in">Contact Us</h1>
        <p class="lead text-white">We're here to assist you with any inquiries</p>
    </div>
</header>

<!-- Contact Section -->
<section class="contact-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Get in Touch</h2>
                <form id="contactForm" novalidate>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback">Please enter your name.</div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                        <div class="invalid-feedback">Please enter a subject.</div>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        <div class="invalid-feedback">Please enter your message.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
                <div id="contactMessage" class="mt-3" style="display: none;"></div>
            </div>
            <div class="col-md-6">
                <h2>Contact Information</h2>
                <p><strong>Address:</strong> 123 Orchard Road, Singapore 123456</p>
                <p><strong>Phone:</strong> +65 6123 4567</p>
                <p><strong>Email:</strong> info@purecomfort.com</p>
                <h3 class="mt-4">Business Hours</h3>
                <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
                <p>Saturday - Sunday: 10:00 AM - 4:00 PM</p>
                <!-- Add a map here if desired -->
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('contactForm');
    const contactMessage = document.getElementById('contactMessage');

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (!form.checkValidity()) {
            event.stopPropagation();
            form.classList.add('was-validated');
        } else {
            const formData = new FormData(form);
            handleContact(formData);
        }
    }, false);

    function handleContact(formData) {
        fetch('backend/contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                contactMessage.textContent = 'Message sent successfully!';
                contactMessage.className = 'mt-3 alert alert-success';
                form.reset();
            } else {
                contactMessage.textContent = data.message || 'Failed to send message. Please try again.';
                contactMessage.className = 'mt-3 alert alert-danger';
            }
            contactMessage.style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            contactMessage.textContent = 'An error occurred. Please try again.';
            contactMessage.className = 'mt-3 alert alert-danger';
            contactMessage.style.display = 'block';
        });
    }

    // Update cart badge
    updateCartBadge();
});
</script>
</body>
</html>