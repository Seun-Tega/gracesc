<?php
// admin/manage-services.php
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

// Check if is_active column exists
$column_exists = false;
try {
    $stmt = $db->query("SHOW COLUMNS FROM services LIKE 'is_active'");
    $column_exists = $stmt->rowCount() > 0;
} catch (PDOException $e) {
    // Column doesn't exist or other error
    $column_exists = false;
}

// Add new service
if ($_POST && isset($_POST['add_service'])) {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $features = $_POST['features'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 1; // Default to active if column doesn't exist

    if (!empty($name) && !empty($description) && !empty($price)) {
        try {
            if ($column_exists) {
                $query = "INSERT INTO services (name, description, price, duration, features, is_active, created_at) 
                          VALUES (:name, :description, :price, :duration, :features, :is_active, NOW())";
            } else {
                $query = "INSERT INTO services (name, description, price, duration, features) 
                          VALUES (:name, :description, :price, :duration, :features)";
            }
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":duration", $duration);
            $stmt->bindParam(":features", $features);
            
            if ($column_exists) {
                $stmt->bindParam(":is_active", $is_active);
            }

            if ($stmt->execute()) {
                $success = "Service added successfully!";
            }
        } catch (PDOException $e) {
            $error = "Error adding service: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}

// Update service
if ($_POST && isset($_POST['update_service'])) {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $features = $_POST['features'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 1;

    if (!empty($id) && !empty($name)) {
        try {
            if ($column_exists) {
                $query = "UPDATE services SET name = :name, description = :description, 
                          price = :price, duration = :duration, features = :features, 
                          is_active = :is_active WHERE id = :id";
            } else {
                $query = "UPDATE services SET name = :name, description = :description, 
                          price = :price, duration = :duration, features = :features 
                          WHERE id = :id";
            }
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":duration", $duration);
            $stmt->bindParam(":features", $features);
            
            if ($column_exists) {
                $stmt->bindParam(":is_active", $is_active);
            }

            if ($stmt->execute()) {
                $success = "Service updated successfully!";
            }
        } catch (PDOException $e) {
            $error = "Error updating service: " . $e->getMessage();
        }
    }
}

// Delete service
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    try {
        $query = "DELETE FROM services WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $delete_id);
        
        if ($stmt->execute()) {
            $success = "Service deleted successfully!";
        }
    } catch (PDOException $e) {
        $error = "Error deleting service: " . $e->getMessage();
    }
}

// Toggle service status (only if column exists)
if (isset($_GET['toggle_id']) && $column_exists) {
    $toggle_id = $_GET['toggle_id'];
    try {
        // Get current status
        $stmt = $db->prepare("SELECT is_active FROM services WHERE id = :id");
        $stmt->bindParam(":id", $toggle_id);
        $stmt->execute();
        $service = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($service) {
            $new_status = $service['is_active'] ? 0 : 1;
            $query = "UPDATE services SET is_active = :is_active WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":is_active", $new_status);
            $stmt->bindParam(":id", $toggle_id);
            
            if ($stmt->execute()) {
                $success = "Service status updated successfully!";
            }
        }
    } catch (PDOException $e) {
        $error = "Error updating service status: " . $e->getMessage();
    }
}

// Get all services
$services = $db->query("SELECT * FROM services ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Get service for editing
$edit_service = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $db->prepare("SELECT * FROM services WHERE id = :id");
    $stmt->bindParam(":id", $edit_id);
    $stmt->execute();
    $edit_service = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        .status-badge {
            cursor: pointer;
        }
        .status-active {
            background-color: #28a745;
        }
        .status-inactive {
            background-color: #dc3545;
        }
        .status-na {
            background-color: #6c757d;
        }
    </style>
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
                        <h2>Manage Services</h2>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                            <i class="fas fa-plus me-2"></i>Add New Service
                        </button>
                    </div>

                    <?php if (!$column_exists): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            The <code>is_active</code> column is missing in your database. 
                            <a href="update-database.php" class="alert-link">Click here to update your database</a> or 
                            all services will be visible on the website.
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">All Services</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($services as $service): 
                                            $is_active = $column_exists ? ($service['is_active'] ?? 1) : 1;
                                            $status_class = $column_exists ? 
                                                ($is_active ? 'status-active' : 'status-inactive') : 
                                                'status-na';
                                            $status_text = $column_exists ? 
                                                ($is_active ? 'Active' : 'Inactive') : 
                                                'Always Active';
                                        ?>
                                        <tr>
                                            <td><?php echo $service['id']; ?></td>
                                            <td><?php echo htmlspecialchars($service['name']); ?></td>
                                            <td><?php echo substr(htmlspecialchars($service['description']), 0, 100) . '...'; ?></td>
                                            <td>₦<?php echo number_format($service['price'], 2); ?></td>
                                            <td><?php echo htmlspecialchars($service['duration']); ?></td>
                                            <td>
                                                <span class="badge <?php echo $status_class; ?>" 
                                                      <?php if ($column_exists): ?>onclick="toggleStatus(<?php echo $service['id']; ?>)"<?php endif; ?>>
                                                    <?php echo $status_text; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="?edit_id=<?php echo $service['id']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?delete_id=<?php echo $service['id']; ?>" class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this service?')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Service Name *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price (₦) *</label>
                                <input type="number" class="form-control" name="price" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Duration</label>
                                <input type="text" class="form-control" name="duration" placeholder="e.g., 1 hour, 2-3 hours">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Features (one per line)</label>
                            <textarea class="form-control" name="features" rows="4" placeholder="Enter each feature on a new line"></textarea>
                        </div>
                        <?php if ($column_exists): ?>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="addIsActive" checked>
                                <label class="form-check-label" for="addIsActive">
                                    Active Service (visible on website)
                                </label>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_service" class="btn btn-primary">Add Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <?php if ($edit_service): ?>
    <div class="modal fade show" id="editServiceModal" style="display: block; background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Service</h5>
                    <a href="manage-services.php" class="btn-close"></a>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $edit_service['id']; ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Service Name *</label>
                            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($edit_service['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($edit_service['description']); ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price (₦) *</label>
                                <input type="number" class="form-control" name="price" value="<?php echo $edit_service['price']; ?>" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Duration</label>
                                <input type="text" class="form-control" name="duration" value="<?php echo htmlspecialchars($edit_service['duration']); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Features (one per line)</label>
                            <textarea class="form-control" name="features" rows="4"><?php echo htmlspecialchars($edit_service['features']); ?></textarea>
                        </div>
                        <?php if ($column_exists): ?>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive" 
                                    <?php echo ($edit_service['is_active'] ?? 1) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="editIsActive">
                                    Active Service (visible on website)
                                </label>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <a href="manage-services.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="update_service" class="btn btn-primary">Update Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleStatus(serviceId) {
            if (confirm('Are you sure you want to change the status of this service?')) {
                window.location.href = 'manage-services.php?toggle_id=' + serviceId;
            }
        }
        
        // Close edit modal when clicking outside
        document.addEventListener('click', function(e) {
            const editModal = document.getElementById('editServiceModal');
            if (editModal && e.target === editModal) {
                window.location.href = 'manage-services.php';
            }
        });
    </script>
</body>
</html>