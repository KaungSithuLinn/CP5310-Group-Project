<?php
require_once 'backend/db_config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fName = filter_input(INPUT_POST, 'fName', FILTER_SANITIZE_STRING);
    $lName = filter_input(INPUT_POST, 'lName', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $date_of_birth = filter_input(INPUT_POST, 'date_of_birth', FILTER_SANITIZE_STRING);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
    $passport = filter_input(INPUT_POST, 'passport', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
    $postcode = filter_input(INPUT_POST, 'postcode', FILTER_SANITIZE_STRING);

    $errors = [];

    // Validate input
    if (empty($fName) || empty($lName) || empty($email) || empty($username) || empty($password) || empty($confirm_password) || empty($date_of_birth) || empty($gender) || empty($passport) || empty($address) || empty($country) || empty($postcode)) {
        $errors[] = 'All fields are required.';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }
    if (!preg_match("/^[a-zA-Z ]*$/", $fName) || !preg_match("/^[a-zA-Z ]*$/", $lName)) {
        $errors[] = 'Name should only contain letters and spaces.';
    }

    if (empty($errors)) {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT userID FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetch()) {
                $errors[] = 'Email already in use.';
            } else {
                // Check if username already exists
                $stmt = $pdo->prepare("SELECT userID FROM user WHERE username = :username");
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->execute();
                if ($stmt->fetch()) {
                    $errors[] = 'Username already in use.';
                } else {
                    // Hash password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Insert new user
                    $stmt = $pdo->prepare("INSERT INTO user (fName, lName, email, username, password, date_of_birth, gender, passport, address, country, postcode) VALUES (:fName, :lName, :email, :username, :password, :dob, :gender, :passport, :address, :country, :postcode)");
                    $stmt->execute([
                        ':fName' => $fName,
                        ':lName' => $lName,
                        ':email' => $email,
                        ':username' => $username,
                        ':password' => $hashed_password,
                        ':dob' => $date_of_birth,
                        ':gender' => $gender,
                        ':passport' => $passport,
                        ':address' => $address,
                        ':country' => $country,
                        ':postcode' => $postcode
                    ]);

                    $response = [
                        'success' => true,
                        'message' => 'Registration successful. You can now log in.',
                        'redirect' => 'login.php'
                    ];
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;
                }
            }
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            $errors[] = 'An error occurred. Please try again later.';
        }
    }

    if (!empty($errors)) {
        $response = [
            'success' => false,
            'message' => implode(' ', $errors)
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

$pageTitle = "Register - PureComfort Luxury Accommodations";
$pageDescription = "Register for a PureComfort account to book your stay and manage your reservations.";
include 'includes/header.php';
?>

<!-- Registration Form -->
<section class="register-section py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">Create Your Account</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form id="registerForm" action="register.php" method="POST" novalidate>
                    <div class="form-group">
                        <label for="fName">First Name</label>
                        <input type="text" class="form-control" id="fName" name="fName" required pattern="[A-Za-z ]+">
                        <div class="invalid-feedback">Please enter your first name (letters and spaces only).</div>
                    </div>
                    <div class="form-group">
                        <label for="lName">Last Name</label>
                        <input type="text" class="form-control" id="lName" name="lName" required pattern="[A-Za-z ]+">
                        <div class="invalid-feedback">Please enter your last name (letters and spaces only).</div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <div class="invalid-feedback">Please enter a username.</div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        <div class="invalid-feedback">Password must be at least 8 characters long.</div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <div class="invalid-feedback">Passwords do not match.</div>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="date_of_birth" required>
                        <div class="invalid-feedback">Please enter your date of birth.</div>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        <div class="invalid-feedback">Please select your gender.</div>
                    </div>
                    <div class="form-group">
                        <label for="passport">Passport</label>
                        <input type="text" class="form-control" id="passport" name="passport" required>
                        <div class="invalid-feedback">Please enter your passport number.</div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                        <div class="invalid-feedback">Please enter your address.</div>
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" class="form-control" id="country" name="country" required>
                        <div class="invalid-feedback">Please enter your country.</div>
                    </div>
                    <div class="form-group">
                        <label for="postcode">Postcode</label>
                        <input type="text" class="form-control" id="postcode" name="postcode" required>
                        <div class="invalid-feedback">Please enter your postcode.</div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </form>
                <div id="registerMessage" class="mt-3 text-center" style="display: none;"></div>
                <div class="mt-3 text-center">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registerForm');
    const registerMessage = document.getElementById('registerMessage');

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (!form.checkValidity()) {
            event.stopPropagation();
            form.classList.add('was-validated');
        } else {
            const formData = new FormData(form);
            fetch('register.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        registerMessage.textContent = data.message;
                        registerMessage.className = 'mt-3 alert alert-success';
                        registerMessage.style.display = 'block';
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    } else {
                        registerMessage.textContent = data.message || 'Registration failed. Please try again.';
                        registerMessage.className = 'mt-3 alert alert-danger';
                        registerMessage.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    registerMessage.textContent = 'An error occurred. Please try again.';
                    registerMessage.className = 'mt-3 alert alert-danger';
                    registerMessage.style.display = 'block';
                });
        }
    }, false);

    // Confirm password validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    function validatePassword(){
        if(password.value != confirmPassword.value) {
            confirmPassword.setCustomValidity("Passwords Don't Match");
        } else {
            confirmPassword.setCustomValidity('');
        }
    }
    password.onchange = validatePassword;
    confirmPassword.onkeyup = validatePassword;

    // Update cart badge
    updateCartBadge();
});
</script>