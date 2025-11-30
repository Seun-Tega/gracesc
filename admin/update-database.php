<?php
// admin/update-database.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../config/database.php';
$database = new Database();
$db = $database->getConnection();

$success = '';
$error = '';

if ($_POST && isset($_POST['update_database'])) {
    try {
        // Check if columns already exist
        $check_columns = $db->query("SHOW COLUMNS FROM services LIKE 'is_active'");
        if ($check_columns->rowCount() == 0) {
            // Add missing columns
            $db->exec("ALTER TABLE services 
                      ADD COLUMN is_active TINYINT(1) DEFAULT 1");
            $success .= "Added is_active column. ";
        }
        
        $check_columns = $db->query("SHOW COLUMNS FROM services LIKE 'created_at'");
        if ($check_columns->rowCount() == 0) {
            $db->exec("ALTER TABLE services 
                      ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
            $success .= "Added created_at column. ";
        }
        
        $check_columns = $db->query("SHOW COLUMNS FROM services LIKE 'updated_at'");
        if ($check_columns->rowCount() == 0) {
            $db->exec("ALTER TABLE services 
                      ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
            $success .= "Added updated_at column. ";
        }
        
        if (empty($success)) {
            $success = "All columns already exist. Database is up to date!";
        } else {
            $success = "Database updated successfully! " . $success;
        }
        
    } catch (PDOException $e) {
        $error = "Error updating database: " . $e->getMessage();
    }
}

// Check current database structure
$columns = [];
try {
    $stmt = $db->query("SHOW COLUMNS FROM services");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $error = "Error checking database structure: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Database - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Update Database</h2>
                        <a href="manage-services.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Services
                        </a>
                    </div>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        </div>
                        <div class="text-center mt-4">
                            <a href="manage-services.php" class="btn btn-primary btn-lg">
                                <i class="fas fa-cog me-2"></i>Manage Services
                            </a>
                        </div>
                    <?php else: ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Database Update Required</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>Your services table needs additional columns for full functionality. This update will add:</p>
                                        
                                        <div class="mb-4">
                                            <h6>Required Columns:</h6>
                                            <ul class="list-group">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <code>is_active</code>
                                                        <small class="text-muted d-block">Controls whether service is visible on website</small>
                                                    </div>
                                                    <span class="badge <?php echo in_array('is_active', $columns) ? 'bg-success' : 'bg-warning'; ?>">
                                                        <?php echo in_array('is_active', $columns) ? 'Exists' : 'Missing'; ?>
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <code>created_at</code>
                                                        <small class="text-muted d-block">Tracks when service was created</small>
                                                    </div>
                                                    <span class="badge <?php echo in_array('created_at', $columns) ? 'bg-success' : 'bg-warning'; ?>">
                                                        <?php echo in_array('created_at', $columns) ? 'Exists' : 'Missing'; ?>
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <code>updated_at</code>
                                                        <small class="text-muted d-block">Tracks when service was last updated</small>
                                                    </div>
                                                    <span class="badge <?php echo in_array('updated_at', $columns) ? 'bg-success' : 'bg-warning'; ?>">
                                                        <?php echo in_array('updated_at', $columns) ? 'Exists' : 'Missing'; ?>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Note:</strong> This update is safe and will not affect your existing services. 
                                            All existing services will be set to active by default.
                                        </div>

                                        <form method="POST">
                                            <button type="submit" name="update_database" class="btn btn-primary btn-lg">
                                                <i class="fas fa-database me-2"></i>Update Database Now
                                            </button>
                                            <a href="manage-services.php" class="btn btn-outline-secondary btn-lg">
                                                Skip for Now
                                            </a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Benefits of This Update</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="fas fa-eye text-primary me-2"></i>
                                            <strong>Control Visibility</strong>
                                            <p class="small mb-2">Show/hide services on your website without deleting them</p>
                                        </div>
                                        <div class="mb-3">
                                            <i class="fas fa-clock text-primary me-2"></i>
                                            <strong>Track Changes</strong>
                                            <p class="small mb-2">See when services were created and last updated</p>
                                        </div>
                                        <div class="mb-3">
                                            <i class="fas fa-cog text-primary me-2"></i>
                                            <strong>Better Management</strong>
                                            <p class="small mb-2">Organize seasonal or temporary services easily</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>