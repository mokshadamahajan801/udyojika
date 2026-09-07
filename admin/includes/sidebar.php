<?php

$current_page = basename($_SERVER['PHP_SELF']);

/*
|--------------------------------------------------------------------------
| Dynamic Admin Sidebar Counts
|--------------------------------------------------------------------------
*/

global $pdo;

/* Pending Seller Requests */
$pending_seller_requests = 0;

try {
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM seller_requests
        WHERE status = 'pending'
    ");

    $pending_seller_requests = (int) $stmt->fetchColumn();

} catch (PDOException $e) {
    $pending_seller_requests = 0;
}


/* Pending Orders */
$pending_orders = 0;

try {
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM orders
        WHERE status = 'pending'
    ");

    $pending_orders = (int) $stmt->fetchColumn();

} catch (PDOException $e) {
    $pending_orders = 0;
}

?>

<!-- Admin Sidebar -->
<aside class="dashboard-sidebar">

    <!-- Brand -->
    <a href="index.php" class="sidebar-brand">

        <div class="bg-warning text-dark rounded-circle p-2
                    d-flex align-items-center justify-content-center"
             style="width:38px;height:38px;">

            <i class="fa-solid fa-spa fs-5"></i>

        </div>

        <div class="lh-1">

            <span class="fs-5 font-serif fw-bold d-block text-white">
                Udyojika
            </span>

            <span class="badge bg-warning text-dark brand-badge">
                Admin
            </span>

        </div>

    </a>


    <!-- Menu -->
    <ul class="sidebar-menu">

        <!-- ================= OVERVIEW ================= -->

        <li class="menu-header">
            Overview
        </li>

        <li>
            <a href="index.php"
               class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-chart-pie"></i>

                <span>Dashboard</span>

            </a>
        </li>


        <!-- ================= USERS ================= -->

        <li class="menu-header">
            User & Maker Management
        </li>

        <li>
            <a href="users.php"
               class="nav-link <?php echo $current_page === 'users.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-users"></i>

                <span>Users</span>

            </a>
        </li>


        <!-- Seller Requests -->

        <li>
            <a href="seller-requests.php"
               class="nav-link <?php echo $current_page === 'seller-requests.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-user-plus"></i>

                <span>Seller Requests</span>

                <?php if ($pending_seller_requests > 0): ?>

                    <span class="badge bg-danger rounded-pill ms-auto">
                        <?php echo $pending_seller_requests; ?>
                    </span>

                <?php endif; ?>

            </a>
        </li>


        <!-- Verified Sellers -->

        <li>
            <a href="sellers.php"
               class="nav-link <?php echo $current_page === 'sellers.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-id-badge"></i>

                <span>Verified Sellers</span>

            </a>
        </li>


        <!-- ================= MARKETPLACE ================= -->

        <li class="menu-header">
            Marketplace
        </li>


        <!-- Products -->

        <li>
            <a href="products.php"
               class="nav-link <?php echo $current_page === 'products.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-box-open"></i>

                <span>Products</span>

            </a>
        </li>


        <!-- Orders -->

        <li>
            <a href="orders.php"
               class="nav-link <?php echo $current_page === 'orders.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-bag-shopping"></i>

                <span>Orders</span>

                <?php if ($pending_orders > 0): ?>

                    <span class="badge bg-warning text-dark rounded-pill ms-auto">
                        <?php echo $pending_orders; ?>
                    </span>

                <?php endif; ?>

            </a>
        </li>


        <!-- Reviews -->

        <li>
            <a href="reviews.php"
               class="nav-link <?php echo $current_page === 'reviews.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-star"></i>

                <span>Reviews</span>

            </a>
        </li>


        <!-- ================= COMMUNICATION ================= -->

        <li class="menu-header">
            Communication
        </li>


        <li>
            <a href="enquiries.php"
               class="nav-link <?php echo $current_page === 'enquiries.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-comments"></i>

                <span>Enquiries</span>

            </a>
        </li>


        <li>
            <a href="contact-messages.php"
               class="nav-link <?php echo $current_page === 'contact-messages.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-envelope-open-text"></i>

                <span>Contact Messages</span>

            </a>
        </li>

    </ul>


    <!-- ================= SIDEBAR FOOTER ================= -->

    <div class="sidebar-footer">

        <!-- CLICKABLE ADMIN PROFILE -->

        <a href="profile.php"
           class="user-sidebar-profile mb-2 text-decoration-none"
           style="cursor:pointer;">

            <img
                src="<?php echo htmlspecialchars(
                    $current_user['avatar']
                    ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=300&auto=format&fit=crop'
                ); ?>"
                alt="Admin"
            >

            <div class="lh-1 text-truncate">

                <strong class="d-block text-white small text-truncate">

                    <?php
                    echo htmlspecialchars(
                        $current_user['name'] ?? 'Administrator'
                    );
                    ?>

                </strong>

                <small class="text-white-50"
                       style="font-size:0.75rem;">
                    Administrator
                </small>

            </div>

        </a>


        <!-- Sign Out -->

        <a href="../logout.php"
           class="btn btn-outline-light btn-sm w-100 py-1"
           style="font-size:0.82rem;">

            <i class="fa-solid fa-arrow-right-from-bracket me-1"></i>

            Sign Out

        </a>

    </div>

</aside>


<!-- Mobile Backdrop -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>