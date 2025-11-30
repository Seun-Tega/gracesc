<?php
// admin/login.php

// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/database.php';

// Debug: Check if we have POST data
error_log("POST data: " . print_r($_POST, true));

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($username) && !empty($password)) {
        try {
            $database = new Database();
            $db = $database->getConnection();
            
            // Debug: Check database connection
            if (!$db) {
                throw new Exception("Database connection failed");
            }
            
            $query = "SELECT * FROM admin_users WHERE username = :username";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":username", $username);
            
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Debug logging
                    error_log("Found user: " . $admin['username']);
                    error_log("Password verify result: " . (password_verify($password, $admin['password_hash']) ? 'true' : 'false'));
                    
                    if (password_verify($password, $admin['password_hash'])) {
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_username'] = $admin['username'];
                        $_SESSION['admin_id'] = $admin['id'];
                        
                        // Debug: Check session
                        error_log("Session set - admin_logged_in: true");
                        error_log("Redirecting to dashboard...");
                        
                        header("Location: dashboard.php");
                        exit;
                    } else {
                        $error = "Invalid password. Please try again.";
                    }
                } else {
                    $error = "Username not found.";
                }
            } else {
                $error = "Database query failed.";
            }
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Please enter both username and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - GRACE SENIOR CARE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow" style="width: 400px;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h3 class="card-title">
                        <i class="fas fa-hands-helping me-2 text-primary"></i>
                        Admin Login
                    </h3>
                    <p class="text-muted">GRACE SENIOR CARE</p>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                        <!-- Temporary debug info -->
                        <hr>
                        <small class="text-muted">
                            Debug Info:<br>
                            Session: <?php echo session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive'; ?><br>
                            Username provided: <?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : 'None'; ?>
                        </small>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                
                <div class="text-center mt-3">
                    <small class="text-muted">Default credentials: admin / admin123</small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>