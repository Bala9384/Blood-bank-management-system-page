<?php
include 'database.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = false;
}

/// Simple login check
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Direct check without database
    if ($username === 'Balamurugan' && $password === 'Bala1234') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
    } else {
        $login_error = 'Invalid username or password.';
    }
}
// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Handle donor deletion
if (isset($_GET['delete_donor'])) {
    if ($_SESSION['admin_logged_in']) {
        $donor_id = $_GET['delete_donor'];
        $sql = "DELETE FROM donors WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $donor_id);
        $stmt->execute();
        header('Location: admin.php?tab=donors');
        exit;
    }
}

// Handle message deletion
if (isset($_GET['delete_message'])) {
    if ($_SESSION['admin_logged_in']) {
        $message_id = $_GET['delete_message'];
        $sql = "DELETE FROM messages WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $message_id);
        $stmt->execute();
        header('Location: admin.php?tab=messages');
        exit;
    }
}

// Handle credential update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_username'])) {
    if ($_SESSION['admin_logged_in']) {
        $new_username = $_POST['new_username'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        
        // Verify current password
        $sql = "SELECT password FROM admin_credentials WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $_SESSION['admin_username']);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        
        if (password_verify($current_password, $admin['password'])) {
            if ($new_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE admin_credentials SET username = ?, password = ? WHERE username = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param('sss', $new_username, $hashed_password, $_SESSION['admin_username']);
            } else {
                $update_sql = "UPDATE admin_credentials SET username = ? WHERE username = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param('ss', $new_username, $_SESSION['admin_username']);
            }
            
            if ($update_stmt->execute()) {
                $_SESSION['admin_username'] = $new_username;
                $update_success = 'Credentials updated successfully!';
            } else {
                $update_error = 'Error updating credentials.';
            }
        } else {
            $update_error = 'Current password is incorrect.';
        }
    }
}

// Get statistics
$total_donors = $conn->query("SELECT COUNT(*) as count FROM donors")->fetch_assoc()['count'];
$new_messages = $conn->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'];

$common_blood = $conn->query("
    SELECT blood_group, COUNT(*) as count 
    FROM donors 
    GROUP BY blood_group 
    ORDER BY count DESC 
    LIMIT 1
")->fetch_assoc();
$common_blood_type = $common_blood ? $common_blood['blood_group'] : '-';

$active_districts = $conn->query("SELECT COUNT(DISTINCT district) as count FROM donors")->fetch_assoc()['count'];

// Get donors and messages
$donors = $conn->query("SELECT * FROM donors ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
$messages = $conn->query("SELECT * FROM messages ORDER BY date DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Blood Bank</title>
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
        <h1 class="text-center text-danger mb-4">Admin Panel</h1>
    </div>

    <?php if (!$_SESSION['admin_logged_in']): ?>
        <!-- Admin Login -->
        <section class="py-4">
            <div class="container">
                <div class="card shadow mx-auto" style="max-width: 500px;">
                    <div class="card-body p-4">
                        <h2 class="text-center text-danger mb-4">Admin Login</h2>
                        
                        <?php if (isset($login_error)): ?>
                            <div class="alert alert-danger"><?= $login_error ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">Login</button>
                        </form>
                        <div class="text-center mt-3">
                            <small class="text-muted"></small>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php else: ?>
        <!-- Admin Panel -->
        <section class="py-4">
            <div class="container">
                <h2 class="text-danger mb-4">Admin Dashboard - Welcome <?= $_SESSION['admin_username'] ?></h2>
                
                <!-- Statistics Cards -->
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-danger"><?= $total_donors ?></h3>
                                <p class="text-muted">Total Donors</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-danger"><?= $new_messages ?></h3>
                                <p class="text-muted">New Messages</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-danger"><?= $common_blood_type ?></h3>
                                <p class="text-muted">Most Common Blood Type</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-danger"><?= $active_districts ?></h3>
                                <p class="text-muted">Active Districts</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link <?= (!isset($_GET['tab']) || $_GET['tab'] == 'donors') ? 'active' : '' ?>" 
                           href="?tab=donors">Donor Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['tab']) && $_GET['tab'] == 'messages') ? 'active' : '' ?>" 
                           href="?tab=messages">Contact Messages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['tab']) && $_GET['tab'] == 'settings') ? 'active' : '' ?>" 
                           href="?tab=settings">Admin Settings</a>
                    </li>
                </ul>

                <!-- Donors Tab -->
                <?php if (!isset($_GET['tab']) || $_GET['tab'] == 'donors'): ?>
                    <div class="d-flex gap-2 mb-3">
                        <a href="admin.php?export=donors" class="btn btn-danger">
                            <i class="fas fa-download me-2"></i>Export Data
                        </a>
                        <a href="admin.php?tab=donors" class="btn btn-outline-danger">
                            <i class="fas fa-sync-alt me-2"></i>Refresh
                        </a>
                        <a href="admin.php?logout=1" class="btn btn-secondary">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>District</th>
                                            <th>Blood Group</th>
                                            <th>Location</th>
                                            <th>Phone</th>
                                            <th>Last Donation</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($donors as $donor): ?>
                                            <tr>
                                                <td><?= $donor['id'] ?></td>
                                                <td><?= htmlspecialchars($donor['name']) ?></td>
                                                <td><?= $donor['age'] ?></td>
                                                <td><?= $donor['gender'] ?></td>
                                                <td><?= htmlspecialchars($donor['district']) ?></td>
                                                <td><?= $donor['blood_group'] ?></td>
                                                <td><?= htmlspecialchars($donor['location']) ?></td>
                                                <td><?= $donor['phone'] ?></td>
                                                <td><?= $donor['last_donation'] ?: 'Never' ?></td>
                                                <td>
                                                    <a href="admin.php?delete_donor=<?= $donor['id'] ?>" 
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Are you sure you want to delete this donor?')">
                                                        Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Messages Tab -->
                <?php if (isset($_GET['tab']) && $_GET['tab'] == 'messages'): ?>
                    <div class="d-flex gap-2 mb-3">
                        <a href="admin.php?tab=messages" class="btn btn-outline-danger">
                            <i class="fas fa-sync-alt me-2"></i>Refresh Messages
                        </a>
                        <a href="admin.php?logout=1" class="btn btn-secondary">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </div>

                    <div class="row g-4">
                        <?php foreach ($messages as $message): ?>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h5 class="text-danger"><?= htmlspecialchars($message['subject']) ?></h5>
                                                <p class="text-muted mb-1">From: <?= htmlspecialchars($message['name']) ?> (<?= htmlspecialchars($message['email']) ?>)</p>
                                                <small class="text-muted">Date: <?= date('M j, Y g:i A', strtotime($message['date'])) ?></small>
                                            </div>
                                            <a href="admin.php?delete_message=<?= $message['id'] ?>&tab=messages" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this message?')">
                                                Delete
                                            </a>
                                        </div>
                                        <p class="mb-0"><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Settings Tab -->
                <?php if (isset($_GET['tab']) && $_GET['tab'] == 'settings'): ?>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-danger mb-4">
                                <i class="fas fa-cog me-2"></i>Admin Settings
                            </h4>
                            
                            <?php if (isset($update_success)): ?>
                                <div class="alert alert-success"><?= $update_success ?></div>
                            <?php endif; ?>
                            
                            <?php if (isset($update_error)): ?>
                                <div class="alert alert-danger"><?= $update_error ?></div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">New Username</label>
                                        <input type="text" name="new_username" class="form-control" value="<?= $_SESSION['admin_username'] ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" name="current_password" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label">New Password (leave blank to keep current)</label>
                                        <input type="password" name="new_password" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" name="confirm_password" class="form-control">
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-2"></i>Update Credentials
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="admin.php?logout=1" class="btn btn-secondary">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

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