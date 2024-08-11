<?php
session_start();
require_once 'backend/db_config.php';

// Authorization check
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$bookingId = $_GET['id'] ?? '';

if (empty($bookingId)) {
    echo "Invalid booking ID.";
    exit;
}

$stmt = $pdo->prepare("SELECT b.id, b.total_price, bd.room_name, bd.quantity, bd.price 
                       FROM bookings b
                       JOIN booking_details bd ON b.id = bd.booking_id
                       WHERE b.id = :booking_id");
$stmt->execute(['booking_id' => $bookingId]);
$bookingDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($bookingDetails)) {
    echo "No booking details found.";
    exit;
}

$pageTitle = "Booking Confirmation - PureComfort Luxury Accommodations";
$pageDescription = "Review your booking confirmation details.";
include 'includes/header.php';
?>

<!-- Booking Confirmation Content -->
<section class="booking-confirmation py-5">
    <div class="container">
        <h1>Booking Confirmation</h1>
        <p>Thank you for your booking! Here are the details:</p>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookingDetails as $detail): ?>
                    <tr>
                        <td><?php echo $detail['room_name']; ?></td>
                        <td><?php echo $detail['quantity']; ?></td>
                        <td>$<?php echo number_format($detail['price'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <p><strong>Total Price:</strong> $<?php echo number_format($bookingDetails[0]['total_price'], 2); ?></p>
    </div>
</section>

<?php include 'includes/footer.php'; ?>