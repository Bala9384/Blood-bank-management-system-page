<?php
include 'database.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['fullName'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['bloodGroup'];
    $district = $_POST['district'];
    $pincode = $_POST['pincode'];
    $location = $_POST['location'];
    $phone = $_POST['phone'];
    $email = $_POST['email'] ?? '';
    $last_donation = $_POST['lastDonation'] ?: null;

    // Check if phone already exists
    $check_sql = "SELECT id FROM donors WHERE phone = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('s', $phone);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = 'This phone number is already registered. Please use a different number.';
        $message_type = 'error';
    } else {
        // Insert new donor
        $sql = "INSERT INTO donors (name, age, gender, blood_group, district, pincode, location, phone, email, last_donation) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sissssssss', $name, $age, $gender, $blood_group, $district, $pincode, $location, $phone, $email, $last_donation);
        
        if ($stmt->execute()) {
            $message = 'Thank you for registering as a blood donor! Your information has been saved.';
            $message_type = 'success';
            $_POST = []; // Clear form
        } else {
            $message = 'Error registering donor. Please try again.';
            $message_type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Donor - Blood Bank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-tint me-2"></i>
                Blood Bank Management System
            </a>
        </div>
    </nav>

    <!-- Page Title -->
    <div class="container mt-4">
        <h1 class="text-center text-danger mb-4">Register as a Blood Donor</h1>
    </div>

    <!-- Registration Form -->
    <section class="py-4">
        <div class="container">
            <div class="card shadow mx-auto" style="max-width: 800px;">
                <div class="card-body p-4">
                    <h2 class="text-center text-danger mb-4">Donor Registration Form</h2>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-<?= $message_type == 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="fullName" class="form-control" value="<?= $_POST['fullName'] ?? '' ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Age *</label>
                                <input type="number" name="age" class="form-control" min="18" max="65" value="<?= $_POST['age'] ?? '' ?>" required>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label">Gender *</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?= isset($_POST['gender']) && $_POST['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= isset($_POST['gender']) && $_POST['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                    <option value="Other" <?= isset($_POST['gender']) && $_POST['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Blood Group *</label>
                                <select name="bloodGroup" class="form-select" required>
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" <?= isset($_POST['bloodGroup']) && $_POST['bloodGroup'] == 'A+' ? 'selected' : '' ?>>A+</option>
                                    <option value="A-" <?= isset($_POST['bloodGroup']) && $_POST['bloodGroup'] == 'A-' ? 'selected' : '' ?>>A-</option>
                                    <option value="B+" <?= isset($_POST['bloodGroup']) && $_POST['bloodGroup'] == 'B+' ? 'selected' : '' ?>>B+</option>
                                    <option value="B-" <?= isset($_POST['bloodGroup']) && $_POST['bloodGroup'] == 'B-' ? 'selected' : '' ?>>B-</option>
                                    <option value="O+" <?= isset($_POST['bloodGroup']) && $_POST['bloodGroup'] == 'O+' ? 'selected' : '' ?>>O+</option>
                                    <option value="O-" <?= isset($_POST['bloodGroup']) && $_POST['bloodGroup'] == 'O-' ? 'selected' : '' ?>>O-</option>
                                    <option value="AB+" <?= isset($_POST['bloodGroup']) && $_POST['bloodGroup'] == 'AB+' ? 'selected' : '' ?>>AB+</option>
                                    <option value="AB-" <?= isset($_POST['bloodGroup']) && $_POST['bloodGroup'] == 'AB-' ? 'selected' : '' ?>>AB-</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label">District *</label>
                                <select name="district" class="form-select" required>
                                    <option value="">Select District</option>
                                    <option value="Ariyalur" <?= isset($_POST['district']) && $_POST['district'] == 'Ariyalur' ? 'selected' : '' ?>>Ariyalur</option>
                                    <option value="Chengalpattu" <?= isset($_POST['district']) && $_POST['district'] == 'Chengalpattu' ? 'selected' : '' ?>>Chengalpattu</option>
                                    <option value="Chennai" <?= isset($_POST['district']) && $_POST['district'] == 'Chennai' ? 'selected' : '' ?>>Chennai</option>
                                    <option value="Coimbatore" <?= isset($_POST['district']) && $_POST['district'] == 'Coimbatore' ? 'selected' : '' ?>>Coimbatore</option>
                                    <option value="Cuddalore" <?= isset($_POST['district']) && $_POST['district'] == 'Cuddalore' ? 'selected' : '' ?>>Cuddalore</option>
                                    <option value="Dharmapuri" <?= isset($_POST['district']) && $_POST['district'] == 'Dharmapuri' ? 'selected' : '' ?>>Dharmapuri</option>
                                    <option value="Dindigul" <?= isset($_POST['district']) && $_POST['district'] == 'Dindigul' ? 'selected' : '' ?>>Dindigul</option>
                                    <option value="Erode" <?= isset($_POST['district']) && $_POST['district'] == 'Erode' ? 'selected' : '' ?>>Erode</option>
                                    <option value="Kallakurichi" <?= isset($_POST['district']) && $_POST['district'] == 'Kallakurichi' ? 'selected' : '' ?>>Kallakurichi</option>
                                    <option value="Kanchipuram" <?= isset($_POST['district']) && $_POST['district'] == 'Kanchipuram' ? 'selected' : '' ?>>Kanchipuram</option>
                                    <option value="Kanyakumari" <?= isset($_POST['district']) && $_POST['district'] == 'Kanyakumari' ? 'selected' : '' ?>>Kanyakumari</option>
                                    <option value="Karur" <?= isset($_POST['district']) && $_POST['district'] == 'Karur' ? 'selected' : '' ?>>Karur</option>
                                    <option value="Krishnagiri" <?= isset($_POST['district']) && $_POST['district'] == 'Krishnagiri' ? 'selected' : '' ?>>Krishnagiri</option>
                                    <option value="Madurai" <?= isset($_POST['district']) && $_POST['district'] == 'Madurai' ? 'selected' : '' ?>>Madurai</option>
                                    <option value="Mayiladuthurai" <?= isset($_POST['district']) && $_POST['district'] == 'Mayiladuthurai' ? 'selected' : '' ?>>Mayiladuthurai</option>
                                    <option value="Nagapattinam" <?= isset($_POST['district']) && $_POST['district'] == 'Nagapattinam' ? 'selected' : '' ?>>Nagapattinam</option>
                                    <option value="Namakkal" <?= isset($_POST['district']) && $_POST['district'] == 'Namakkal' ? 'selected' : '' ?>>Namakkal</option>
                                    <option value="Nilgiris" <?= isset($_POST['district']) && $_POST['district'] == 'Nilgiris' ? 'selected' : '' ?>>Nilgiris</option>
                                    <option value="Perambalur" <?= isset($_POST['district']) && $_POST['district'] == 'Perambalur' ? 'selected' : '' ?>>Perambalur</option>
                                    <option value="Pudukkottai" <?= isset($_POST['district']) && $_POST['district'] == 'Pudukkottai' ? 'selected' : '' ?>>Pudukkottai</option>
                                    <option value="Ramanathapuram" <?= isset($_POST['district']) && $_POST['district'] == 'Ramanathapuram' ? 'selected' : '' ?>>Ramanathapuram</option>
                                    <option value="Ranipet" <?= isset($_POST['district']) && $_POST['district'] == 'Ranipet' ? 'selected' : '' ?>>Ranipet</option>
                                    <option value="Salem" <?= isset($_POST['district']) && $_POST['district'] == 'Salem' ? 'selected' : '' ?>>Salem</option>
                                    <option value="Sivaganga" <?= isset($_POST['district']) && $_POST['district'] == 'Sivaganga' ? 'selected' : '' ?>>Sivaganga</option>
                                    <option value="Tenkasi" <?= isset($_POST['district']) && $_POST['district'] == 'Tenkasi' ? 'selected' : '' ?>>Tenkasi</option>
                                    <option value="Thanjavur" <?= isset($_POST['district']) && $_POST['district'] == 'Thanjavur' ? 'selected' : '' ?>>Thanjavur</option>
                                    <option value="Theni" <?= isset($_POST['district']) && $_POST['district'] == 'Theni' ? 'selected' : '' ?>>Theni</option>
                                    <option value="Thoothukudi" <?= isset($_POST['district']) && $_POST['district'] == 'Thoothukudi' ? 'selected' : '' ?>>Thoothukudi</option>
                                    <option value="Tiruchirappalli" <?= isset($_POST['district']) && $_POST['district'] == 'Tiruchirappalli' ? 'selected' : '' ?>>Tiruchirappalli</option>
                                    <option value="Tirunelveli" <?= isset($_POST['district']) && $_POST['district'] == 'Tirunelveli' ? 'selected' : '' ?>>Tirunelveli</option>
                                    <option value="Tirupathur" <?= isset($_POST['district']) && $_POST['district'] == 'Tirupathur' ? 'selected' : '' ?>>Tirupathur</option>
                                    <option value="Tiruppur" <?= isset($_POST['district']) && $_POST['district'] == 'Tiruppur' ? 'selected' : '' ?>>Tiruppur</option>
                                    <option value="Tiruvallur" <?= isset($_POST['district']) && $_POST['district'] == 'Tiruvallur' ? 'selected' : '' ?>>Tiruvallur</option>
                                    <option value="Tiruvannamalai" <?= isset($_POST['district']) && $_POST['district'] == 'Tiruvannamalai' ? 'selected' : '' ?>>Tiruvannamalai</option>
                                    <option value="Tiruvarur" <?= isset($_POST['district']) && $_POST['district'] == 'Tiruvarur' ? 'selected' : '' ?>>Tiruvarur</option>
                                    <option value="Vellore" <?= isset($_POST['district']) && $_POST['district'] == 'Vellore' ? 'selected' : '' ?>>Vellore</option>
                                    <option value="Viluppuram" <?= isset($_POST['district']) && $_POST['district'] == 'Viluppuram' ? 'selected' : '' ?>>Viluppuram</option>
                                    <option value="Virudhunagar" <?= isset($_POST['district']) && $_POST['district'] == 'Virudhunagar' ? 'selected' : '' ?>>Virudhunagar</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pincode *</label>
                                <input type="text" name="pincode" class="form-control" pattern="[0-9]{6}" value="<?= $_POST['pincode'] ?? '' ?>" required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Location / Area *</label>
                            <input type="text" name="location" class="form-control" value="<?= $_POST['location'] ?? '' ?>" required>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control" pattern="[0-9]{10}" value="<?= $_POST['phone'] ?? '' ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?= $_POST['email'] ?? '' ?>">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Last Donation Date (if any)</label>
                            <input type="date" name="lastDonation" class="form-control" value="<?= $_POST['lastDonation'] ?? '' ?>">
                        </div>

                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="agreeTerms" id="agreeTerms" required>
                            <label class="form-check-label" for="agreeTerms">
                                I agree to the terms and conditions and confirm that I'm eligible to donate blood.
                            </label>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-danger btn-lg">Register as Donor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="text-danger">About Us</h5>
                    <p>We are a community-driven blood bank management system dedicated to connecting blood donors with those in need.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-danger">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-light text-decoration-none">Home</a></li>
                        <li><a href="blood.php" class="text-light text-decoration-none">Find Donors</a></li>
                        <li><a href="register.php" class="text-light text-decoration-none">Become a Donor</a></li>
                        <li><a href="contact.php" class="text-light text-decoration-none">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-danger">Contact Info</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Health Street, Chennai</li>
                        <li><i class="fas fa-phone me-2"></i> +91 98765 43210</li>
                        <li><i class="fas fa-envelope me-2"></i> info@bloodbank.com</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p>&copy; 2023 Blood Bank Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>