<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Admin Sidebar -->
<aside class="dashboard-sidebar">
    <a href="index.php" class="sidebar-brand">
        <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
            <i class="fa-solid fa-spa fs-5"></i>
        </div>
        <div class="lh-1">
            <span class="fs-5 font-serif fw-bold d-block text-white">Udyojika</span>
            <span class="badge bg-warning text-dark brand-badge">Super Admin</span>
        </div>
    </a>

    <ul class="sidebar-menu">
        <li class="menu-header">Overview</li>
        <li>
            <a href="index.php" class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="menu-header">User & Maker Management</li>
        <li>
            <a href="users.php" class="nav-link <?php echo $current_page === 'users.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i>
                <span>All Users</span>
            </a>
        </li>
        <li>
            <a href="seller-requests.php" class="nav-link <?php echo $current_page === 'seller-requests.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-plus"></i>
                <span>Seller Requests</span>
                <span class="badge bg-danger rounded-pill ms-auto">2</span>
            </a>
        </li>
        <li>
            <a href="sellers.php" class="nav-link <?php echo $current_page === 'sellers.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-id-badge"></i>
                <span>Verified Sellers</span>
            </a>
        </li>
        <li>
            <a href="businesses.php" class="nav-link <?php echo $current_page === 'businesses.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-store"></i>
                <span>Home Brands</span>
            </a>
        </li>

        <li class="menu-header">Catalog & Marketplace</li>
        <li>
            <a href="categories.php" class="nav-link <?php echo $current_page === 'categories.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-layer-group"></i>
                <span>Categories</span>
            </a>
        </li>
        <li>
            <a href="products.php" class="nav-link <?php echo $current_page === 'products.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-box-open"></i>
                <span>All Products</span>
            </a>
        </li>
        <li>
            <a href="orders.php" class="nav-link <?php echo $current_page === 'orders.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-bag-shopping"></i>
                <span>Orders</span>
                <span class="badge bg-warning text-dark rounded-pill ms-auto">4</span>
            </a>
        </li>
        <li>
            <a href="reviews.php" class="nav-link <?php echo $current_page === 'reviews.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-star"></i>
                <span>Product Reviews</span>
            </a>
        </li>

        <li class="menu-header">Communications & System</li>
        <li>
            <a href="enquiries.php" class="nav-link <?php echo $current_page === 'enquiries.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-comments"></i>
                <span>Enquiries</span>
            </a>
        </li>
        <li>
            <a href="contact-messages.php" class="nav-link <?php echo $current_page === 'contact-messages.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-envelope-open-text"></i>
                <span>Contact Messages</span>
            </a>
        </li>
        <li>
            <a href="reports.php" class="nav-link <?php echo $current_page === 'reports.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-waveform"></i>
                <span>Sales Reports</span>
            </a>
        </li>
        <li>
            <a href="settings.php" class="nav-link <?php echo $current_page === 'settings.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-sliders"></i>
                <span>Settings</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <div class="user-sidebar-profile mb-2">
            <img src="<?php echo htmlspecialchars($current_user['avatar'] ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=300&auto=format&fit=crop'); ?>" alt="Admin">
            <div class="lh-1 text-truncate">
                <strong class="d-block text-white small text-truncate"><?php echo htmlspecialchars($current_user['name']); ?></strong>
                <small class="text-white-50" style="font-size: 0.75rem;">Administrator</small>
            </div>
        </div>
        <a href="../logout.php" class="btn btn-outline-light btn-sm w-100 py-1" style="font-size: 0.82rem;">
            <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Sign Out
        </a>
    </div>
</aside>
<!-- Mobile Backdrop -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
