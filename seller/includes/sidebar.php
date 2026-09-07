<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Seller Sidebar -->
<aside class="dashboard-sidebar">
    <a href="index.php" class="sidebar-brand">
        <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
            <i class="fa-solid fa-store fs-5"></i>
        </div>
        <div class="lh-1">
            <span class="fs-5 font-serif fw-bold d-block text-white">Udyojika</span>
            <span class="badge bg-warning text-dark brand-badge">Maker Studio</span>
        </div>
    </a>

    <ul class="sidebar-menu">
        <li class="menu-header">Maker Hub</li>
        <li>
            <a href="index.php" class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="business.php" class="nav-link <?php echo $current_page === 'business.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-store"></i>
                <span>My Business Profile</span>
            </a>
        </li>

        <li class="menu-header">Catalog & Stock</li>
        <li>
            <a href="products.php" class="nav-link <?php echo $current_page === 'products.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-box-open"></i>
                <span>My Products</span>
            </a>
        </li>
        <li>
            <a href="add-product.php" class="nav-link <?php echo $current_page === 'add-product.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-circle-plus"></i>
                <span>Add Product</span>
            </a>
        </li>

        <li class="menu-header">Orders & Patrons</li>
        <li>
            <a href="orders.php" class="nav-link <?php echo in_array($current_page, ['orders.php', 'order-details.php']) ? 'active' : ''; ?>">
                <i class="fa-solid fa-bag-shopping"></i>
                <span>Orders</span>
                <span class="badge bg-warning text-dark rounded-pill ms-auto">2</span>
            </a>
        </li>
        <li>
            <a href="customers.php" class="nav-link <?php echo $current_page === 'customers.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-group"></i>
                <span>My Customers</span>
            </a>
        </li>
        <li>
            <a href="reviews.php" class="nav-link <?php echo $current_page === 'reviews.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-star"></i>
                <span>Reviews & Ratings</span>
            </a>
        </li>
        <li>
            <a href="enquiries.php" class="nav-link <?php echo $current_page === 'enquiries.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-message"></i>
                <span>Custom Enquiries</span>
            </a>
        </li>

        <li class="menu-header">Finance & Account</li>
        <li>
            <a href="earnings.php" class="nav-link <?php echo $current_page === 'earnings.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-wallet"></i>
                <span>Earnings & Payouts</span>
            </a>
        </li>
        <li>
            <a href="profile.php" class="nav-link <?php echo $current_page === 'profile.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-user"></i>
                <span>Profile</span>
            </a>
        </li>
        <li>
            <a href="settings.php" class="nav-link <?php echo $current_page === 'settings.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-gear"></i>
                <span>Settings</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <div class="user-sidebar-profile mb-2">
            <img src="<?php echo htmlspecialchars($seller_profile['avatar'] ?? $current_user['avatar']); ?>" alt="Seller">
            <div class="lh-1 text-truncate">
                <strong class="d-block text-white small text-truncate"><?php echo htmlspecialchars($seller_profile['owner_name']); ?></strong>
                <small class="text-white-50 text-truncate d-block" style="font-size: 0.72rem;"><?php echo htmlspecialchars($seller_profile['business_name']); ?></small>
            </div>
        </div>
        <a href="../logout.php" class="btn btn-outline-light btn-sm w-100 py-1" style="font-size: 0.82rem;">
            <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Sign Out
        </a>
    </div>
</aside>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
