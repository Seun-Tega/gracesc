<?php
// admin/header.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$page_title = $page_title ?? 'Admin Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - GRACE SENIOR CARE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/admin-styles.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h4>
                    <i class="fas fa-hands-helping me-2"></i>
                    Admin Panel
                </h4>
                <small>Grace Senior Care</small>
            </div>
            
            <nav class="admin-nav">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage-applications.php' ? 'active' : ''; ?>" href="manage-applications.php">
                    <i class="fas fa-file-alt"></i> Applications
                    <?php
                    include '../config/database.php';
                    $database = new Database();
                    $db = $database->getConnection();
                    $pending_count = $db->query("SELECT COUNT(*) FROM caregiver_applications WHERE status = 'pending'")->fetchColumn();
                    if ($pending_count > 0): ?>
                    <span class="badge bg-warning"><?php echo $pending_count; ?></span>
                    <?php endif; ?>
                </a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage-services.php' ? 'active' : ''; ?>" href="manage-services.php">
                    <i class="fas fa-concierge-bell"></i> Services
                </a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage-team.php' ? 'active' : ''; ?>" href="manage-team.php">
                    <i class="fas fa-users"></i> Team Members
                </a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage-bookings.php' ? 'active' : ''; ?>" href="manage-bookings.php">
                    <i class="fas fa-calendar-check"></i> Bookings
                    <?php
                    $pending_bookings = $db->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();
                    if ($pending_bookings > 0): ?>
                    <span class="badge bg-warning"><?php echo $pending_bookings; ?></span>
                    <?php endif; ?>
                </a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage-blog.php' ? 'active' : ''; ?>" href="manage-blog.php">
                    <i class="fas fa-blog"></i> Blog
                </a>
                <a class="nav-link" href="../index.php" target="_blank">
                    <i class="fas fa-external-link-alt"></i> View Site
                </a>
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="admin-header-content">
                    <div class="admin-title">
                        <h2><?php echo htmlspecialchars($page_title); ?></h2>
                    </div>
                    <div class="admin-user">
                        <div class="user-info">
                            <div class="user-name"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></div>
                            <div class="user-role">Administrator</div>
                        </div>
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($_SESSION['admin_username'], 0, 1)); ?>
                        </div>
                    </div>
                </div>
            </header>