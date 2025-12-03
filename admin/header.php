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
    <style>
        /* Mobile Hamburger Menu Styles */
        .mobile-menu-btn {
            display: none;
            background: var(--admin-primary);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1.5rem;
            padding: 0.75rem;
            z-index: 1001;
            position: fixed;
            top: 1rem;
            left: 1rem;
            width: 50px;
            height: 50px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }
        
        .mobile-menu-btn:hover {
            background: var(--admin-secondary);
            transform: scale(1.05);
        }
        
        .mobile-menu-btn:active {
            transform: scale(0.95);
        }
        
        .mobile-close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            padding: 0.5rem;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: background 0.3s ease;
        }
        
        .mobile-close-btn:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            backdrop-filter: blur(2px);
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        .admin-sidebar.mobile-open {
            transform: translateX(0);
            box-shadow: 2px 0 20px rgba(0,0,0,0.3);
        }
        
        /* Mobile Responsive Breakpoints */
        @media (max-width: 992px) {
            .mobile-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1000;
            }
            
            .admin-main {
                margin-left: 0 !important;
                width: 100%;
            }
            
            .admin-nav .nav-link {
                padding: 1rem 1.5rem !important;
                margin: 0.25rem 1rem;
                font-size: 1rem;
            }
            
            /* Ensure header doesn't get hidden behind hamburger */
            .admin-header {
                padding-left: 5rem;
            }
        }
        
        @media (max-width: 768px) {
            .mobile-menu-btn {
                top: 0.75rem;
                left: 0.75rem;
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }
            
            .admin-header {
                padding: 1rem 1rem 1rem 4.5rem;
            }
            
            .admin-title h2 {
                font-size: 1.4rem;
            }
            
            .admin-user {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }
            
            .user-info {
                text-align: center;
            }
        }
        
        @media (max-width: 576px) {
            .mobile-menu-btn {
                top: 0.5rem;
                left: 0.5rem;
                width: 42px;
                height: 42px;
                font-size: 1.2rem;
            }
            
            .admin-header {
                padding: 0.75rem 0.75rem 0.75rem 4rem;
            }
            
            .admin-header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .admin-user {
                flex-direction: row;
                justify-content: center;
            }
            
            .user-info {
                text-align: left;
            }
            
            .admin-title h2 {
                font-size: 1.3rem;
            }
            
            .admin-nav .badge {
                font-size: 0.7rem;
                padding: 0.25rem 0.5rem;
            }
            
            /* Make sidebar full screen on very small devices */
            .admin-sidebar {
                width: 100%;
            }
        }
        
        @media (max-width: 400px) {
            .mobile-menu-btn {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }
            
            .admin-header {
                padding: 0.5rem 0.5rem 0.5rem 3.5rem;
            }
            
            .admin-title h2 {
                font-size: 1.2rem;
            }
        }
        
        /* Prevent body scroll when sidebar is open */
        body.sidebar-open {
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Mobile Menu Button -->
        <button class="mobile-menu-btn" aria-label="Toggle navigation menu">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay"></div>
        
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="admin-brand">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4>
                            <i class="fas fa-hands-helping me-2"></i>
                            Admin Panel
                        </h4>
                        <small>Grace Senior Care</small>
                    </div>
                    <button class="mobile-close-btn d-lg-none" aria-label="Close navigation menu">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
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
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage-gallery.php' ? 'active' : ''; ?>" href="manage-gallery.php">
                    <i class="fas fa-images"></i> Gallery
                </a>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>" href="settings.php">
                    <i class="fas fa-cog"></i> Settings
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
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active"><?php echo htmlspecialchars($page_title); ?></li>
                            </ol>
                        </nav>
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

            <!-- Main Content Area -->
            <main class="admin-content">

    <script>
        // Enhanced mobile sidebar functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const mobileCloseBtn = document.querySelector('.mobile-close-btn');
            const sidebar = document.querySelector('.admin-sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            const body = document.body;
            
            function openSidebar() {
                sidebar.classList.add('mobile-open');
                overlay.classList.add('active');
                body.classList.add('sidebar-open');
                
                // Add escape key listener
                document.addEventListener('keydown', handleEscapeKey);
            }
            
            function closeSidebar() {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
                body.classList.remove('sidebar-open');
                
                // Remove escape key listener
                document.removeEventListener('keydown', handleEscapeKey);
            }
            
            function handleEscapeKey(event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            }
            
            // Mobile menu button click
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    openSidebar();
                });
            }
            
            // Close button click
            if (mobileCloseBtn) {
                mobileCloseBtn.addEventListener('click', closeSidebar);
            }
            
            // Overlay click
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }
            
            // Close sidebar when clicking on nav links (mobile)
            const navLinks = document.querySelectorAll('.admin-nav .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 992) {
                        closeSidebar();
                    }
                });
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 992 && 
                    sidebar.classList.contains('mobile-open') && 
                    !sidebar.contains(event.target) && 
                    !mobileMenuBtn.contains(event.target)) {
                    closeSidebar();
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    closeSidebar();
                }
            });
            
            // Add active class based on current page
            const currentPage = window.location.pathname.split('/').pop();
            const navLinksAll = document.querySelectorAll('.admin-nav .nav-link');
            navLinksAll.forEach(link => {
                const linkHref = link.getAttribute('href');
                if (linkHref === currentPage) {
                    link.classList.add('active');
                }
            });
            
            // Prevent sidebar click from closing it
            sidebar.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>