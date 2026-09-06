<?php
$current_page = basename($_SERVER['PHP_SELF']);

/* =========================
   Dynamic Customer Counts
   ========================= */

$order_count = 0;
$wishlist_count = 0;
$cart_count = 0;

if (!empty($customer_id)) {

    // Total Orders
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM orders
        WHERE customer_id = ?
    ");
    $stmt->execute([$customer_id]);
    $order_count = (int)$stmt->fetchColumn();

    // Wishlist Items
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM wishlist
        WHERE customer_id = ?
    ");
    $stmt->execute([$customer_id]);
    $wishlist_count = (int)$stmt->fetchColumn();

    // Cart Quantity
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(quantity), 0)
        FROM cart_items
        WHERE customer_id = ?
    ");
    $stmt->execute([$customer_id]);
    $cart_count = (int)$stmt->fetchColumn();
}
?>

<!-- Customer Sidebar -->
<aside class="dashboard-sidebar">

    <a href="index.php" class="sidebar-brand">
        <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center"
             style="width: 38px; height: 38px;">
            <i class="fa-solid fa-bag-shopping fs-5"></i>
        </div>

        <div class="lh-1">
            <span class="fs-5 font-serif fw-bold d-block text-white">
                Udyojika
            </span>
            <span class="badge bg-warning text-dark brand-badge">
                My Account
            </span>
        </div>
    </a>


    <ul class="sidebar-menu">

        <li class="menu-header">Buyer Hub</li>


        <!-- Dashboard -->
        <li>
            <a href="index.php"
               class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-house-chimney-window"></i>

                <span>Dashboard</span>

            </a>
        </li>


        <!-- My Orders -->
        <li>
            <a href="orders.php"
               class="nav-link <?php echo in_array($current_page, ['orders.php', 'order-details.php']) ? 'active' : ''; ?>">

                <i class="fa-solid fa-box"></i>

                <span>My Orders</span>

                <?php if ($order_count > 0): ?>

                    <span class="badge bg-warning text-dark rounded-pill ms-auto">
                        <?php echo $order_count; ?>
                    </span>

                <?php endif; ?>

            </a>
        </li>


        <!-- My Wishlist -->
        <li>
            <a href="wishlist.php"
               class="nav-link <?php echo $current_page === 'wishlist.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-heart"></i>

                <span>My Wishlist</span>

                <?php if ($wishlist_count > 0): ?>

                    <span class="badge bg-danger rounded-pill ms-auto">
                        <?php echo $wishlist_count; ?>
                    </span>

                <?php endif; ?>

            </a>
        </li>


        <!-- Shopping Basket -->
        <li>
            <a href="cart.php"
               class="nav-link <?php echo $current_page === 'cart.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-cart-shopping"></i>

                <span>Shopping Basket</span>

                <?php if ($cart_count > 0): ?>

                    <span class="badge bg-success rounded-pill ms-auto">
                        <?php echo $cart_count; ?>
                    </span>

                <?php endif; ?>

            </a>
        </li>


        <li class="menu-header">My Feedback & Chats</li>


        <!-- Reviews -->
        <li>
            <a href="reviews.php"
               class="nav-link <?php echo $current_page === 'reviews.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-star"></i>

                <span>My Reviews</span>

            </a>
        </li>


        <!-- Enquiries -->
        <li>
            <a href="enquiries.php"
               class="nav-link <?php echo $current_page === 'enquiries.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-comments"></i>

                <span>Maker Inquiries</span>

            </a>
        </li>


        <li class="menu-header">Settings & Addresses</li>


        <!-- Addresses -->
        <li>
            <a href="addresses.php"
               class="nav-link <?php echo $current_page === 'addresses.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-location-dot"></i>

                <span>Saved Addresses</span>

            </a>
        </li>


        <!-- Profile -->
        <li>
            <a href="profile.php"
               class="nav-link <?php echo $current_page === 'profile.php' ? 'active' : ''; ?>">

                <i class="fa-solid fa-user-gear"></i>

                <span>Personal Profile</span>

            </a>
        </li>

    </ul>


    <!-- Sidebar Footer -->
    <div class="sidebar-footer">

        <div class="user-sidebar-profile mb-2">

            <img
                src="<?php echo htmlspecialchars(
                    $current_user['avatar']
                    ?? 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=300&auto=format&fit=crop'
                ); ?>"
                alt="Customer">

            <div class="lh-1 text-truncate">

                <strong class="d-block text-white small text-truncate">
                    <?php echo htmlspecialchars($current_user['name']); ?>
                </strong>

                <small class="text-white-50"
                       style="font-size: 0.72rem;">
                    Customer
                </small>

            </div>

        </div>


        <!-- Sign Out -->
        <a href="../logout.php"
           class="btn btn-outline-light btn-sm w-100 py-1"
           style="font-size: 0.82rem;">

            <i class="fa-solid fa-arrow-right-from-bracket me-1"></i>

            Sign Out

        </a>

    </div>

</aside>


<div class="sidebar-backdrop" id="sidebarBackdrop"></div>