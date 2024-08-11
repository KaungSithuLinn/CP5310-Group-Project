<?php
require_once 'backend/db_config.php';
session_start();

// Authorization check
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = "My Account - PureComfort Luxury Accommodations";
$pageDescription = "Manage your PureComfort account and view your booking history.";
include 'includes/header.php';

// Fetch user data
try {
    $stmt = $pdo->prepare("SELECT * FROM user WHERE userID = :userID");
    $stmt->bindParam(':userID', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    $user = null;
}

// Fetch booking history
try {
    $stmt = $pdo->prepare("SELECT b.id, b.total_price, b.booking_date, bd.room_name, bd.quantity 
                            FROM bookings b
                            JOIN booking_details bd ON b.id = bd.booking_id
                            WHERE b.userID = :userID
                            ORDER BY b.booking_date DESC");
    $stmt->bindParam(':userID', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    $bookings = [];
}
?>

<!-- Hero Section -->
<header class="hero account-hero">
    <div class="container">
        <h1 class="fade-in">My Account</h1>
        <p class="lead text-white">Manage your profile and bookings</p>
    </div>
</header>

<!-- Account Section -->
<section class="account-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Account Information</h5>
                        <div id="user-info">
                            <?php if ($user): ?>
                                <p><strong>First Name:</strong> <span id="user-fname"><?= htmlspecialchars($user['fName']) ?></span></p>
                                <p><strong>Last Name:</strong> <span id="user-lname"><?= htmlspecialchars($user['lName']) ?></span></p>
                                <p><strong>Email:</strong> <span id="user-email"><?= htmlspecialchars($user['email']) ?></span></p>
                                <p><strong>Username:</strong> <span id="user-username"><?= htmlspecialchars($user['username']) ?></span></p>
                                <p><strong>Date of Birth:</strong> <span id="user-date_of_birth"><?= htmlspecialchars($user['date_of_birth']) ?></span></p>
                                <p><strong>Gender:</strong> <span id="user-gender"><?= htmlspecialchars($user['gender']) ?></span></p>
                                <p><strong>Passport:</strong> <span id="user-passport"><?= htmlspecialchars($user['passport']) ?></span></p>
                                <p><strong>Address:</strong> <span id="user-address"><?= htmlspecialchars($user['address']) ?></span></p>
                                <p><strong>Country:</strong> <span id="user-country"><?= htmlspecialchars($user['country']) ?></span></p>
                                <p><strong>Postcode:</strong> <span id="user-postcode"><?= htmlspecialchars($user['postcode']) ?></span></p>
                            <?php else: ?>
                                <p>Error loading user data. Please try again later.</p>
                            <?php endif; ?>
                        </div>
                        <button class="btn btn-primary" id="edit-profile-btn">Edit Profile</button>
                        <button class="btn btn-danger mt-2" id="delete-account-btn">Delete Account</button>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <h2>Booking History</h2>
                <?php if (!empty($bookings)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Room</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Booking Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><?= htmlspecialchars($booking['id']) ?></td>
                                    <td><?= htmlspecialchars($booking['room_name']) ?></td>
                                    <td><?= htmlspecialchars($booking['quantity']) ?></td>
                                    <td>$<?= htmlspecialchars(number_format($booking['total_price'], 2)) ?></td>
                                    <td><?= htmlspecialchars($booking['booking_date']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No booking history available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm">
                    <div class="form-group">
                        <label for="edit-fName">First Name</label>
                        <input type="text" class="form-control" id="edit-fName" name="fName" required pattern="[A-Za-z ]+">
                    </div>
                    <div class="form-group">
                        <label for="edit-lName">Last Name</label>
                        <input type="text" class="form-control" id="edit-lName" name="lName" required pattern="[A-Za-z ]+">
                    </div>
                    <div class="form-group">
                        <label for="edit-email">Email</label>
                        <input type="email" class="form-control" id="edit-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-username">Username</label>
                        <input type="text" class="form-control" id="edit-username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-date_of_birth">Date of Birth</label>
                        <input type="date" class="form-control" id="edit-date_of_birth" name="date_of_birth" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-gender">Gender</label>
                        <select class="form-control" id="edit-gender" name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-passport">Passport</label>
                        <input type="text" class="form-control" id="edit-passport" name="passport" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-address">Address</label>
                        <input type="text" class="form-control" id="edit-address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-country">Country</label>
                        <input type="text" class="form-control" id="edit-country" name="country" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-postcode">Postcode</label>
                        <input type="text" class="form-control" id="edit-postcode" name="postcode" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="js/account.js"></script>