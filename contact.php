<?php
include 'database.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'] ?? '';
    $subject = $_POST['subject'];
    $message_text = $_POST['message'];

    $sql = "INSERT INTO messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $name, $email, $phone, $subject, $message_text);
    
    if ($stmt->execute()) {
        $message = 'Thank you for your message! We have received it and will get back to you soon.';
        $message_type = 'success';
        $_POST = []; // Clear form
    } else {
        $message = 'Error sending message. Please try again.';
        $message_type = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Blood Bank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .contact-card {
            transition: transform 0.3s;
        }
        .contact-card:hover {
            transform: translateY(-5px);
        }
    </style>
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
        <h1 class="text-center text-danger mb-4">Contact Us</h1>
    </div>

    <!-- Contact Form -->
    <section class="py-4">
        <div class="container">
            <div class="card shadow mx-auto" style="max-width: 800px;">
                <div class="card-body p-4">
                    <h2 class="text-center text-danger mb-4">Get In Touch</h2>
                    <p class="text-center text-muted mb-4">Have questions or need assistance? Send us a message and we'll get back to you as soon as possible.</p>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-<?= $message_type == 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Your Name *</label>
                            <input type="text" name="name" class="form-control" value="<?= $_POST['name'] ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" value="<?= $_POST['email'] ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" pattern="[0-9]{10}" value="<?= $_POST['phone'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subject *</label>
                            <input type="text" name="subject" class="form-control" value="<?= $_POST['subject'] ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message *</label>
                            <textarea name="message" class="form-control" rows="5" required><?= $_POST['message'] ?? '' ?></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-danger btn-lg">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mt-5 g-4">
                <div class="col-md-3">
                    <div class="card contact-card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-map-marker-alt fa-2x text-danger mb-3"></i>
                            <h5>Our Location</h5>
                            <p class="text-muted">123 Health Street<br>Chennai, Tamil Nadu 600001</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card contact-card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-phone fa-2x text-danger mb-3"></i>
                            <h5>Phone Number</h5>
                            <p class="text-muted">+91 98765 43210<br>+91 98765 43211</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card contact-card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-envelope fa-2x text-danger mb-3"></i>
                            <h5>Email Address</h5>
                            <p class="text-muted">info@bloodbank.com<br>support@bloodbank.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card contact-card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-clock fa-2x text-danger mb-3"></i>
                            <h5>Working Hours</h5>
                            <p class="text-muted">Monday - Saturday: 9AM - 6PM<br>Sunday: 10AM - 2PM</p>
                        </div>
                    </div>
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