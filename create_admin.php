<?php
// create_admin.php - Run this once to create a proper admin user
include '../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $username = "admin";
    $password = "Admin@123"; // Change this to your desired password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // First, let's check if admin table exists and has the right structure
    $query = "CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $db->exec($query);
    
    // Insert or update admin user
    $query = "INSERT INTO admin_users (username, password_hash) 
              VALUES (:username, :password_hash)
              ON DUPLICATE KEY UPDATE password_hash = :password_hash";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password_hash", $password_hash);
    
    if ($stmt->execute()) {
        echo "Admin user created/updated successfully!<br>";
        echo "Username: " . $username . "<br>";
        echo "Password: " . $password . "<br>";
        echo "Hash: " . $password_hash . "<br>";
    } else {
        echo "Error creating admin user.";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>