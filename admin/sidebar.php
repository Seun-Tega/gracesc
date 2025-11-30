<?php
// admin/sidebar.php
?>
<!-- Sidebar -->
<div class="col-md-3 col-lg-2 admin-sidebar bg-dark text-white">
    <div class="p-3">
        <h4 class="text-white">
            <i class="fas fa-hands-helping me-2"></i>
            Admin Panel
        </h4>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
        <a class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'manage-services.php' ? 'active' : ''; ?>" href="manage-services.php">
            <i class="fas fa-concierge-bell me-2"></i> Services
        </a>
        <a class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'manage-team.php' ? 'active' : ''; ?>" href="manage-team.php">
            <i class="fas fa-users me-2"></i> Team Members
        </a>
        <a class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'manage-bookings.php' ? 'active' : ''; ?>" href="manage-bookings.php">
            <i class="fas fa-calendar-check me-2"></i> Bookings
        </a>
        <a class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'manage-blog.php' ? 'active' : ''; ?>" href="manage-blog.php">
            <i class="fas fa-blog me-2"></i> Blog
        </a>
        <a class="nav-link text-white" href="../index.php" target="_blank">
            <i class="fas fa-external-link-alt me-2"></i> View Site
        </a>
        <a class="nav-link text-white" href="logout.php">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
    </nav>
</div>