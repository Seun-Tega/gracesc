v<?php
// admin/manage-bookings.php
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

// Update booking status
if ($_POST && isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'] ?? '';
    $status = $_POST['status'] ?? '';

    if (!empty($booking_id)) {
        try {
            $query = "UPDATE bookings SET status = :status WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":id", $booking_id);

            if ($stmt->execute()) {
                $success = "Booking status updated successfully!";
            }
        } catch (PDOException $e) {
            $error = "Error updating booking: " . $e->getMessage();
        }
    }
}

// Delete booking
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    try {
        $query = "DELETE FROM bookings WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $delete_id);
        
        if ($stmt->execute()) {
            $success = "Booking deleted successfully!";
        }
    } catch (PDOException $e) {
        $error = "Error deleting booking: " . $e->getMessage();
    }
}

// Get all bookings with service names
$query = "SELECT b.*, s.name as service_name 
          FROM bookings b 
          LEFT JOIN services s ON b.service_id = s.id 
          ORDER BY b.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
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
                        <h2>Manage Bookings</h2>
                        <span class="badge bg-primary">Total: <?php echo count($bookings); ?></span>
                    </div>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">All Bookings</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Customer</th>
                                            <th>Service</th>
                                            <th>Contact</th>
                                            <th>Date & Time</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bookings as $booking): 
                                            $status_class = $booking['status'] == 'confirmed' ? 'success' : 
                                                           ($booking['status'] == 'pending' ? 'warning' : 
                                                           ($booking['status'] == 'cancelled' ? 'danger' : 'secondary'));
                                        ?>
                                        <tr>
                                            <td>#<?php echo $booking['id']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($booking['customer_name']); ?></strong>
                                                <?php if (!empty($booking['customer_address'])): ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($booking['customer_address']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($booking['customer_email']); ?><br>
                                                <?php echo htmlspecialchars($booking['customer_phone']); ?>
                                            </td>
                                            <td>
                                                <?php echo date('M j, Y', strtotime($booking['booking_date'])); ?><br>
                                                <small class="text-muted"><?php echo $booking['booking_time']; ?></small>
                                            </td>
                                            <td>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                        <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                        <option value="completed" <?php echo $booking['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                        <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                    </select>
                                                    <input type="hidden" name="update_status" value="1">
                                                </form>
                                            </td>
                                            <td><?php echo date('M j, Y g:i A', strtotime($booking['created_at'])); ?></td>
                                            <td>
                                                <?php if (!empty($booking['special_notes'])): ?>
                                                    <button class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($booking['special_notes']); ?>">
                                                        <i class="fas fa-sticky-note"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <a href="?delete_id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this booking?')">
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

                    <!-- Statistics -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo count(array_filter($bookings, fn($b) => $b['status'] == 'pending')); ?></h3>
                                    <p class="mb-0">Pending</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo count(array_filter($bookings, fn($b) => $b['status'] == 'confirmed')); ?></h3>
                                    <p class="mb-0">Confirmed</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo count(array_filter($bookings, fn($b) => $b['status'] == 'completed')); ?></h3>
                                    <p class="mb-0">Completed</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h3><?php echo count(array_filter($bookings, fn($b) => $b['status'] == 'cancelled')); ?></h3>
                                    <p class="mb-0">Cancelled</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
</body>
</html>