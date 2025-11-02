<?php
include 'database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Blood Donors - Blood Bank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .donor-card {
            transition: transform 0.3s;
        }
        .donor-card:hover {
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
        <h1 class="text-center text-danger mb-4">Find Blood Donors</h1>
    </div>

    <!-- Search Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="text-center text-danger mb-4">Search Donors</h2>
                    <form method="GET" action="">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">District</label>
                                <select name="district" class="form-select">
                                    <option value="">All Districts</option>
                                    <option value="Ariyalur" <?= isset($_GET['district']) && $_GET['district'] == 'Ariyalur' ? 'selected' : '' ?>>Ariyalur</option>
                                    <option value="Chengalpattu" <?= isset($_GET['district']) && $_GET['district'] == 'Chengalpattu' ? 'selected' : '' ?>>Chengalpattu</option>
                                    <option value="Chennai" <?= isset($_GET['district']) && $_GET['district'] == 'Chennai' ? 'selected' : '' ?>>Chennai</option>
                                    <option value="Coimbatore" <?= isset($_GET['district']) && $_GET['district'] == 'Coimbatore' ? 'selected' : '' ?>>Coimbatore</option>
                                    <option value="Cuddalore" <?= isset($_GET['district']) && $_GET['district'] == 'Cuddalore' ? 'selected' : '' ?>>Cuddalore</option>
                                    <option value="Dharmapuri" <?= isset($_GET['district']) && $_GET['district'] == 'Dharmapuri' ? 'selected' : '' ?>>Dharmapuri</option>
                                    <option value="Dindigul" <?= isset($_GET['district']) && $_GET['district'] == 'Dindigul' ? 'selected' : '' ?>>Dindigul</option>
                                    <option value="Erode" <?= isset($_GET['district']) && $_GET['district'] == 'Erode' ? 'selected' : '' ?>>Erode</option>
                                    <option value="Kallakurichi" <?= isset($_GET['district']) && $_GET['district'] == 'Kallakurichi' ? 'selected' : '' ?>>Kallakurichi</option>
                                    <option value="Kanchipuram" <?= isset($_GET['district']) && $_GET['district'] == 'Kanchipuram' ? 'selected' : '' ?>>Kanchipuram</option>
                                    <option value="Kanyakumari" <?= isset($_GET['district']) && $_GET['district'] == 'Kanyakumari' ? 'selected' : '' ?>>Kanyakumari</option>
                                    <option value="Karur" <?= isset($_GET['district']) && $_GET['district'] == 'Karur' ? 'selected' : '' ?>>Karur</option>
                                    <option value="Krishnagiri" <?= isset($_GET['district']) && $_GET['district'] == 'Krishnagiri' ? 'selected' : '' ?>>Krishnagiri</option>
                                    <option value="Madurai" <?= isset($_GET['district']) && $_GET['district'] == 'Madurai' ? 'selected' : '' ?>>Madurai</option>
                                    <option value="Mayiladuthurai" <?= isset($_GET['district']) && $_GET['district'] == 'Mayiladuthurai' ? 'selected' : '' ?>>Mayiladuthurai</option>
                                    <option value="Nagapattinam" <?= isset($_GET['district']) && $_GET['district'] == 'Nagapattinam' ? 'selected' : '' ?>>Nagapattinam</option>
                                    <option value="Namakkal" <?= isset($_GET['district']) && $_GET['district'] == 'Namakkal' ? 'selected' : '' ?>>Namakkal</option>
                                    <option value="Nilgiris" <?= isset($_GET['district']) && $_GET['district'] == 'Nilgiris' ? 'selected' : '' ?>>Nilgiris</option>
                                    <option value="Perambalur" <?= isset($_GET['district']) && $_GET['district'] == 'Perambalur' ? 'selected' : '' ?>>Perambalur</option>
                                    <option value="Pudukkottai" <?= isset($_GET['district']) && $_GET['district'] == 'Pudukkottai' ? 'selected' : '' ?>>Pudukkottai</option>
                                    <option value="Ramanathapuram" <?= isset($_GET['district']) && $_GET['district'] == 'Ramanathapuram' ? 'selected' : '' ?>>Ramanathapuram</option>
                                    <option value="Ranipet" <?= isset($_GET['district']) && $_GET['district'] == 'Ranipet' ? 'selected' : '' ?>>Ranipet</option>
                                    <option value="Salem" <?= isset($_GET['district']) && $_GET['district'] == 'Salem' ? 'selected' : '' ?>>Salem</option>
                                    <option value="Sivaganga" <?= isset($_GET['district']) && $_GET['district'] == 'Sivaganga' ? 'selected' : '' ?>>Sivaganga</option>
                                    <option value="Tenkasi" <?= isset($_GET['district']) && $_GET['district'] == 'Tenkasi' ? 'selected' : '' ?>>Tenkasi</option>
                                    <option value="Thanjavur" <?= isset($_GET['district']) && $_GET['district'] == 'Thanjavur' ? 'selected' : '' ?>>Thanjavur</option>
                                    <option value="Theni" <?= isset($_GET['district']) && $_GET['district'] == 'Theni' ? 'selected' : '' ?>>Theni</option>
                                    <option value="Thoothukudi" <?= isset($_GET['district']) && $_GET['district'] == 'Thoothukudi' ? 'selected' : '' ?>>Thoothukudi</option>
                                    <option value="Tiruchirappalli" <?= isset($_GET['district']) && $_GET['district'] == 'Tiruchirappalli' ? 'selected' : '' ?>>Tiruchirappalli</option>
                                    <option value="Tirunelveli" <?= isset($_GET['district']) && $_GET['district'] == 'Tirunelveli' ? 'selected' : '' ?>>Tirunelveli</option>
                                    <option value="Tirupathur" <?= isset($_GET['district']) && $_GET['district'] == 'Tirupathur' ? 'selected' : '' ?>>Tirupathur</option>
                                    <option value="Tiruppur" <?= isset($_GET['district']) && $_GET['district'] == 'Tiruppur' ? 'selected' : '' ?>>Tiruppur</option>
                                    <option value="Tiruvallur" <?= isset($_GET['district']) && $_GET['district'] == 'Tiruvallur' ? 'selected' : '' ?>>Tiruvallur</option>
                                    <option value="Tiruvannamalai" <?= isset($_GET['district']) && $_GET['district'] == 'Tiruvannamalai' ? 'selected' : '' ?>>Tiruvannamalai</option>
                                    <option value="Tiruvarur" <?= isset($_GET['district']) && $_GET['district'] == 'Tiruvarur' ? 'selected' : '' ?>>Tiruvarur</option>
                                    <option value="Vellore" <?= isset($_GET['district']) && $_GET['district'] == 'Vellore' ? 'selected' : '' ?>>Vellore</option>
                                    <option value="Viluppuram" <?= isset($_GET['district']) && $_GET['district'] == 'Viluppuram' ? 'selected' : '' ?>>Viluppuram</option>
                                    <option value="Virudhunagar" <?= isset($_GET['district']) && $_GET['district'] == 'Virudhunagar' ? 'selected' : '' ?>>Virudhunagar</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Blood Group</label>
                                <select name="blood_group" class="form-select">
                                    <option value="">All Blood Groups</option>
                                    <option value="A+" <?= isset($_GET['blood_group']) && $_GET['blood_group'] == 'A+' ? 'selected' : '' ?>>A+</option>
                                    <option value="A-" <?= isset($_GET['blood_group']) && $_GET['blood_group'] == 'A-' ? 'selected' : '' ?>>A-</option>
                                    <option value="B+" <?= isset($_GET['blood_group']) && $_GET['blood_group'] == 'B+' ? 'selected' : '' ?>>B+</option>
                                    <option value="B-" <?= isset($_GET['blood_group']) && $_GET['blood_group'] == 'B-' ? 'selected' : '' ?>>B-</option>
                                    <option value="O+" <?= isset($_GET['blood_group']) && $_GET['blood_group'] == 'O+' ? 'selected' : '' ?>>O+</option>
                                    <option value="O-" <?= isset($_GET['blood_group']) && $_GET['blood_group'] == 'O-' ? 'selected' : '' ?>>O-</option>
                                    <option value="AB+" <?= isset($_GET['blood_group']) && $_GET['blood_group'] == 'AB+' ? 'selected' : '' ?>>AB+</option>
                                    <option value="AB-" <?= isset($_GET['blood_group']) && $_GET['blood_group'] == 'AB-' ? 'selected' : '' ?>>AB-</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Location (Area)</label>
                                <input type="text" name="location" class="form-control" placeholder="Enter area name" value="<?= $_GET['location'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-danger btn-lg">Search Donors</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php
            // Filter donors based on search criteria
            $where_conditions = [];
            $params = [];
            $types = '';

            if (!empty($_GET['district'])) {
                $where_conditions[] = "district = ?";
                $params[] = $_GET['district'];
                $types .= 's';
            }

            if (!empty($_GET['blood_group'])) {
                $where_conditions[] = "blood_group = ?";
                $params[] = $_GET['blood_group'];
                $types .= 's';
            }

            if (!empty($_GET['location'])) {
                $where_conditions[] = "location LIKE ?";
                $params[] = '%' . $_GET['location'] . '%';
                $types .= 's';
            }

            $where_sql = '';
            if (!empty($where_conditions)) {
                $where_sql = 'WHERE ' . implode(' AND ', $where_conditions);
            }

            $sql = "SELECT * FROM donors $where_sql ORDER BY name";
            $stmt = $conn->prepare($sql);
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $donors = $result->fetch_all(MYSQLI_ASSOC);
            ?>

            <div class="mt-4">
                <p class="text-muted text-center">Found <?= count($donors) ?> donor(s)</p>
            </div>

            <div class="row mt-4 g-4">
                <?php if (empty($donors)): ?>
                    <div class="col-12">
                        <div class="card text-center py-5">
                            <div class="card-body">
                                <h3>No donors found matching your criteria.</h3>
                                <p class="text-muted">Please try different search options or <a href="register.php">register as a donor</a>.</p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($donors as $donor): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card donor-card h-100 shadow">
                                <div class="card-header bg-danger text-white text-center">
                                    <h5 class="mb-0"><?= htmlspecialchars($donor['name']) ?></h5>
                                    <p class="mb-0">Blood Group: <?= $donor['blood_group'] ?></p>
                                </div>
                                <div class="card-body">
                                    <p><strong>Age:</strong> <?= $donor['age'] ?> years</p>
                                    <p><strong>Gender:</strong> <?= $donor['gender'] ?></p>
                                    <p><strong>District:</strong> <?= htmlspecialchars($donor['district']) ?></p>
                                    <p><strong>Location:</strong> <?= htmlspecialchars($donor['location']) ?></p>
                                    <p><strong>Pincode:</strong> <?= $donor['pincode'] ?></p>
                                    <p><strong>Last Donation:</strong> <?= $donor['last_donation'] ?: 'Never' ?></p>
                                    <p><strong>Contact:</strong> <?= $donor['phone'] ?></p>
                                    <?php if ($donor['email']): ?>
                                        <p><strong>Email:</strong> <?= htmlspecialchars($donor['email']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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