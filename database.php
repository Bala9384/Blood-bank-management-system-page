<?php
// database.php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'blood_bank';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create tables if they don't exist
function createTables($conn) {
    // Donors table
    $donors_table = "CREATE TABLE IF NOT EXISTS donors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        age INT NOT NULL,
        gender ENUM('Male', 'Female', 'Other') NOT NULL,
        blood_group ENUM('A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-') NOT NULL,
        district VARCHAR(50) NOT NULL,
        location VARCHAR(100) NOT NULL,
        pincode VARCHAR(10) NOT NULL,
        phone VARCHAR(15) NOT NULL UNIQUE,
        email VARCHAR(100),
        last_donation DATE,
        registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    // Messages table
    $messages_table = "CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(15),
        subject VARCHAR(200) NOT NULL,
        message TEXT NOT NULL,
        date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    // Admin credentials table
    $admin_table = "CREATE TABLE IF NOT EXISTS admin_credentials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )";
    
    $conn->query($donors_table);
    $conn->query($messages_table);
    $conn->query($admin_table);
    
    // Insert default admin if not exists
    $check_admin = "SELECT * FROM admin_credentials WHERE username='Balamurugan'";
    $result = $conn->query($check_admin);
    if ($result->num_rows == 0) {
        $default_password = password_hash('Balamurugan1234', PASSWORD_DEFAULT);
        $insert_admin = "INSERT INTO admin_credentials (username, password) VALUES ('admin', '$default_password')";
        $conn->query($insert_admin);
    }
}

createTables($conn);
?>