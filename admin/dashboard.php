<?php
// admin/dashboard.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Get counts for dashboard
$services_count = $db->query("SELECT COUNT(*) FROM services")->fetchColumn();
$bookings_count = $db->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$contacts_count = $db->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
$pending_bookings = $db->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();
$team_count = $db->query("SELECT COUNT(*) FROM team_members WHERE is_active = TRUE")->fetchColumn();

// Get application counts
$applications_count = $db->query("SELECT COUNT(*) FROM caregiver_applications")->fetchColumn();
$pending_applications = $db->query("SELECT COUNT(*) FROM caregiver_applications WHERE status = 'pending'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - GRACE SENIOR CARE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        .admin-sidebar {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            min-height: 100vh;
            padding: 0;
        }
        .admin-sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s;
        }
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: #3498db;
        }
        .dashboard-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #007bff;
        }
        .dashboard-card h3 {
            margin: 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 admin-sidebar">
                <div class="p-3 border-bottom">
                    <h4 class="text-white mb-0">
                        <i class="fas fa-hands-helping me-2"></i>
                        Admin Panel
                    </h4>
                    <small class="text-muted">Grace Senior Care</small>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="manage-applications.php">
                        <i class="fas fa-file-alt me-2"></i> Applications
                        <span class="badge bg-warning float-end"><?php echo $pending_applications; ?></span>
                    </a>
                    <a class="nav-link" href="manage-gallery.php">
    <i class="fas fa-images me-2"></i> Gallery
</a>
                    <a class="nav-link" href="manage-services.php">
                        <i class="fas fa-concierge-bell me-2"></i> Services
                    </a>
                      <a class="nav-link" href="manage-contacts.php">
                        <i class="fas fa-concierge-bell me-2"></i> Contacts
                    </a>
                    <a class="nav-link" href="manage-team.php">
                        <i class="fas fa-users me-2"></i> Team Members
                    </a>
                    <a class="nav-link" href="manage-bookings.php">
                        <i class="fas fa-calendar-check me-2"></i> Bookings
                        <span class="badge bg-warning float-end"><?php echo $pending_bookings; ?></span>
                    </a>
                    <a class="nav-link" href="manage-blog.php">
                        <i class="fas fa-blog me-2"></i> Blog
                    </a>
                    <a class="nav-link" href="../index.php" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i> View Site
                    </a>
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 bg-light">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="text-primary">Dashboard Overview</h2>
                        <span class="text-muted">Welcome, <?php echo $_SESSION['admin_username']; ?></span>
                    </div>

                    <!-- Stats Cards -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-3">
                            <div class="dashboard-card" style="border-left-color: #007bff;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="text-primary"><?php echo $services_count; ?></h3>
                                        <p class="mb-0 text-muted">Total Services</p>
                                    </div>
                                    <i class="fas fa-concierge-bell fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-card" style="border-left-color: #28a745;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="text-success"><?php echo $bookings_count; ?></h3>
                                        <p class="mb-0 text-muted">Total Bookings</p>
                                    </div>
                                    <i class="fas fa-calendar-check fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-card" style="border-left-color: #ffc107;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="text-warning"><?php echo $pending_bookings; ?></h3>
                                        <p class="mb-0 text-muted">Pending Bookings</p>
                                    </div>
                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-card" style="border-left-color: #17a2b8;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="text-info"><?php echo $team_count; ?></h3>
                                        <p class="mb-0 text-muted">Team Members</p>
                                    </div>
                                    <i class="fas fa-users fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Application Stats -->
                        <div class="col-md-3">
                            <div class="dashboard-card" style="border-left-color: #6f42c1;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="text-purple"><?php echo $applications_count; ?></h3>
                                        <p class="mb-0 text-muted">Total Applications</p>
                                    </div>
                                    <i class="fas fa-file-alt fa-2x text-purple"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-card" style="border-left-color: #fd7e14;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="text-orange"><?php echo $pending_applications; ?></h3>
                                        <p class="mb-0 text-muted">Pending Reviews</p>
                                    </div>
                                    <i class="fas fa-user-clock fa-2x text-orange"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0 text-primary">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <a href="manage-applications.php" class="btn btn-purple w-100">
                                                <i class="fas fa-file-alt me-2"></i> View Applications
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="manage-services.php" class="btn btn-primary w-100">
                                                <i class="fas fa-plus me-2"></i> Add Service
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="manage-team.php" class="btn btn-success w-100">
                                                <i class="fas fa-user-plus me-2"></i> Add Team Member
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="manage-bookings.php" class="btn btn-warning w-100">
                                                <i class="fas fa-calendar me-2"></i> View Bookings
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Applications -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 text-primary">Recent Applications</h5>
                                    <a href="manage-applications.php" class="btn btn-sm btn-outline-primary">View All</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Applicant</th>
                                                    <th>Position</th>
                                                    <th>Email</th>
                                                    <th>Applied Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query = "SELECT * FROM caregiver_applications 
                                                          ORDER BY created_at DESC LIMIT 5";
                                                $stmt = $db->prepare($query);
                                                $stmt->execute();
                                                
                                                while ($app = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $status_class = $app['status'] == 'pending' ? 'warning' : 
                                                                   ($app['status'] == 'accepted' ? 'success' : 
                                                                   ($app['status'] == 'rejected' ? 'danger' : 'info'));
                                                    echo '
                                                    <tr>
                                                        <td>#' . $app['id'] . '</td>
                                                        <td>' . htmlspecialchars($app['full_name']) . '</td>
                                                        <td>' . htmlspecialchars($app['position']) . '</td>
                                                        <td>' . htmlspecialchars($app['email']) . '</td>
                                                        <td>' . date('M j, Y', strtotime($app['created_at'])) . '</td>
                                                        <td><span class="badge bg-' . $status_class . '">' . ucfirst($app['status']) . '</span></td>
                                                    </tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Bookings -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 text-primary">Recent Bookings</h5>
                                    <a href="manage-bookings.php" class="btn btn-sm btn-outline-primary">View All</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Customer</th>
                                                    <th>Service</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query = "SELECT b.*, s.name as service_name 
                                                          FROM bookings b 
                                                          LEFT JOIN services s ON b.service_id = s.id 
                                                          ORDER BY b.created_at DESC 
                                                          LIMIT 5";
                                                $stmt = $db->prepare($query);
                                                $stmt->execute();
                                                
                                                while ($booking = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $status_class = $booking['status'] == 'confirmed' ? 'success' : 
                                                                   ($booking['status'] == 'pending' ? 'warning' : 'secondary');
                                                    echo '
                                                    <tr>
                                                        <td>#' . $booking['id'] . '</td>
                                                        <td>' . htmlspecialchars($booking['customer_name']) . '</td>
                                                        <td>' . htmlspecialchars($booking['service_name']) . '</td>
                                                        <td>' . $booking['booking_date'] . '</td>
                                                        <td>' . $booking['booking_time'] . '</td>
                                                        <td><span class="badge bg-' . $status_class . '">' . ucfirst($booking['status']) . '</span></td>
                                                    </tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>