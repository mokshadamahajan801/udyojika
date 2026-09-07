<?php
require_once __DIR__ . '/auth.php';
$site_title = $page_title ?? 'Maker Portal - Udyojika';

$notifications = [];
$unread_notifications = 0;

if (!empty($current_user['id'])) {

    $stmt = $pdo->prepare("
        SELECT *
        FROM notifications
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 5
    ");

    $stmt->execute([
        (int) $current_user['id']
    ]);

    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM notifications
        WHERE user_id = ?
        AND is_read = 0
    ");

    $stmt->execute([
        (int) $current_user['id']
    ]);

    $unread_notifications = (int) $stmt->fetchColumn();
}
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
                <div>
                    <h5 class="font-serif fw-bold text-maroon-900 mb-0"><?php echo htmlspecialchars($page_header ?? 'Maker Dashboard'); ?></h5>
                    <small class="text-muted d-none d-md-inline"><?php echo htmlspecialchars($page_subheader ?? 'Manage your homemade products, orders & customer requests'); ?></small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                

                <!-- Notification Dropdown -->
                <!-- Notification Dropdown -->
<div class="dropdown position-relative">

    <button
        type="button"
        class="btn btn-light rounded-circle position-relative p-2"
        id="notificationButton"
        style="width:40px;height:40px;"
    >
        <i class="fa-regular fa-bell text-muted"></i>

        <?php if ($unread_notifications > 0): ?>
            <span
                class="position-absolute top-0 start-100 translate-middle p-1 bg-warning border border-light rounded-circle">
            </span>
        <?php endif; ?>
    </button>

    <ul
        id="notificationMenu"
        class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2"
        style="width:320px;"
    >

        <li>
            <h6 class="dropdown-header fw-bold">
                Recent Notifications
            </h6>
        </li>

        <?php if (!empty($notifications)): ?>

            <?php foreach ($notifications as $notification): ?>

                <li>
                    <a
                        class="dropdown-item small py-2 rounded-2"
                        href="<?php echo htmlspecialchars($notification['link']); ?>"
                    >

                        <i class="fa-solid
                            <?php echo htmlspecialchars($notification['icon']); ?>
                            <?php echo htmlspecialchars($notification['icon_color']); ?>
                            me-2">
                        </i>

                        <?php echo htmlspecialchars($notification['title']); ?>

                        <small class="d-block text-muted ms-4">
                            <?php echo htmlspecialchars($notification['message']); ?>
                        </small>

                    </a>
                </li>

            <?php endforeach; ?>

        <?php else: ?>

            <li>
                <div class="text-center text-muted small py-3">
                    <i class="fa-regular fa-bell-slash"></i>
                    <div>No new notifications</div>
                </div>
            </li>

        <?php endif; ?>

    </ul>

</div>


                <!-- Seller Profile Dropdown -->
<div class="dropdown">

    <button
        class="btn d-flex align-items-center gap-2 p-1 text-decoration-none"
        type="button"
        data-bs-toggle="dropdown"
    >

        <!-- Dynamic Seller Profile Photo -->
        <?php if (!empty($current_user['avatar'])): ?>
            <img
                src="<?php echo htmlspecialchars($current_user['avatar']); ?>"
                class="rounded-circle"
                style="width: 36px; height: 36px; object-fit: cover;"
                alt="Profile"
            >
        <?php else: ?>
            <div
                class="rounded-circle d-flex align-items-center justify-content-center"
                style="
                    width: 36px;
                    height: 36px;
                    background: #f1f1f1;
                    color: #7b1731;
                "
            >
                <i class="fa-solid fa-user"></i>
            </div>
        <?php endif; ?>


        <!-- Dynamic Seller Name -->
        <span class="d-none d-md-inline small fw-bold text-dark">
            <?php
            echo htmlspecialchars(
                $current_user['name'] ?? 'Seller'
            );
            ?>
        </span>

        <i class="fa-solid fa-chevron-down text-muted small"></i>

    </button>


    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2">

        <li>
            <span class="dropdown-item-text small text-muted">
                Maker:
                <strong>
                    <?php
                    echo htmlspecialchars(
                        $seller_profile['business_name'] ?? 'Business'
                    );
                    ?>
                </strong>
            </span>
        </li>

        <li>
            <hr class="dropdown-divider">
        </li>

        <li>
            <a class="dropdown-item small" href="business.php">
                <i class="fa-solid fa-store me-2"></i>
                Edit Business Profile
            </a>
        </li>

        <li>
            <a class="dropdown-item small" href="profile.php">
                <i class="fa-solid fa-user me-2"></i>
                Seller Profile
            </a>
        </li>

        <li>
            <a class="dropdown-item small text-danger" href="../logout.php">
                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>
                Sign Out
            </a>
        </li>

    </ul>

</div>
            </div>
        </header>

        <!-- Main Dashboard Body View -->
        <main class="dashboard-content">
