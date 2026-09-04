<?php
require_once __DIR__ . '/auth.php';
$site_title = $page_title ?? 'Admin Control Center - Udyojika';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

$current_user = get_logged_in_user();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_title); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Unified Dashboard CSS -->
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body class="dashboard-body">

<div class="dashboard-wrapper">
    <!-- Sidebar Navigation -->
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <!-- Main Content Container -->
    <div class="dashboard-main">
        
        <!-- Top Navbar -->
        <header class="dashboard-topbar">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-outline-secondary d-lg-none" id="sidebarToggleBtn" aria-label="Toggle Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="d-none d-md-block">
                    <h5 class="font-serif fw-bold text-maroon-900 mb-0"><?php echo htmlspecialchars($page_header ?? 'Admin Dashboard'); ?></h5>
                    <small class="text-muted"><?php echo htmlspecialchars($page_subheader ?? 'Overview of marketplace performance & requests'); ?></small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="../index.php" target="_blank" class="btn btn-outline-maroon btn-sm d-none d-sm-inline-flex align-items-center gap-1" style="border-color: #7a1c28; color: #7a1c28;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>View Public Store</span>
                </a>

                <!-- Notification Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light rounded-circle position-relative p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 40px; height: 40px;">
                        <i class="fa-regular fa-bell text-muted"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2" style="width: 280px;">
                        <li><h6 class="dropdown-header text-maroon-900 fw-bold">Recent System Alerts</h6></li>
                        <li><a class="dropdown-item small py-2 rounded-2" href="seller-requests.php"><i class="fa-solid fa-user-plus text-warning me-2"></i> 2 New Seller Applications</a></li>
                        <li><a class="dropdown-item small py-2 rounded-2" href="orders.php"><i class="fa-solid fa-bag-shopping text-success me-2"></i> New UPI Order UDY-2024-8903</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center small text-maroon-800 fw-bold" href="orders.php">View all activity</a></li>
                    </ul>
                </div>

                <!-- Admin Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn d-flex align-items-center gap-2 p-1 text-decoration-none" type="button" data-bs-toggle="dropdown">
                        <img src="<?php echo htmlspecialchars($current_user['avatar'] ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=300&auto=format&fit=crop'); ?>" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;" alt="Profile">
                        <span class="d-none d-md-inline small fw-bold text-dark"><?php echo htmlspecialchars($current_user['name']); ?></span>
                        <i class="fa-solid fa-chevron-down text-muted small"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2">
                        <li><span class="dropdown-item-text small text-muted">Signed in as <strong>Admin</strong></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item small" href="profile.php"><i class="fa-solid fa-user me-2"></i> Profile</a></li>
                        <li><a class="dropdown-item small" href="settings.php"><i class="fa-solid fa-gear me-2"></i> Settings</a></li>
                        <li><a class="dropdown-item small text-danger" href="../logout.php"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Main Dashboard Body View -->
        <main class="dashboard-content">
