<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/auth.php';

$page_title = "My Account - Udyojika";
$page_header = "Welcome back, " . htmlspecialchars($current_user['name'] ?? '');
$page_subheader = "Track your handmade orders, wishlist items and personalized maker updates";

require_once __DIR__ . '/includes/header.php';

$stats = get_customer_dashboard_stats($customer_id, $pdo);

$stmt = $pdo->prepare("
    SELECT *
    FROM orders
    WHERE customer_id = ?
    ORDER BY created_at DESC
    LIMIT 5
");

$stmt->execute([$customer_id]);

$my_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($my_orders as &$order) {

    $itemStmt = $pdo->prepare("
        SELECT *
        FROM order_items
        WHERE order_id = ?
    ");

    $itemStmt->execute([$order['id']]);

    $order['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
}

unset($order);

$products = get_all_products($pdo);

$stmt = $pdo->prepare("
    SELECT
        p.*
    FROM wishlist w
    INNER JOIN products p ON p.id = w.product_id
    WHERE w.customer_id = ?
    ORDER BY w.created_at DESC
    LIMIT 3
");

$stmt->execute([$customer_id]);

$wishlist_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- 4 Key Customer Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-title">Total Orders</div>
            <div class="stat-value text-maroon-900"><?php echo $stats['total_orders']; ?></div>
            <div class="stat-trend text-muted"><a href="orders.php" class="text-maroon-800 text-decoration-none small fw-bold">View history &rarr;</a></div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card border-warning">
            <div class="stat-title">Active Orders</div>
            <div class="stat-value text-warning"><?php echo $stats['pending_orders']; ?></div>
            <div class="stat-trend text-warning"><i class="fa-solid fa-truck-fast"></i> In dispatch</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-title">Delivered Orders</div>
            <div class="stat-value text-success"><?php echo $stats['completed_orders']; ?></div>
            <div class="stat-trend text-success"><i class="fa-solid fa-circle-check"></i> Delivered</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-title">Total Spent on Makers</div>
            <div class="stat-value text-terracotta">₹<?php echo number_format($stats['total_spent']); ?></div>
            <div class="stat-trend text-success"><i class="fa-solid fa-heart text-danger"></i> 100% to Women</div>
        </div>
    </div>
</div>

<!-- Active Orders Tracking & Wishlist Quick Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="dashboard-card h-100 mb-0">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-truck-ramp-box text-maroon-800"></i> Active & Recent Orders</h5>
                <a href="orders.php" class="btn btn-outline-maroon btn-sm">All Orders</a>
            </div>
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Items & Maker</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Placed On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($my_orders)): ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">You haven't placed any orders yet. <a href="../products.php">Shop Now</a></td></tr>
                        <?php else: ?>
                            <?php foreach ($my_orders as $ord): ?>
                                <tr>
                                    <td class="fw-bold text-maroon-900"><?php echo $ord['order_number']; ?></td>
                                    <td>
                                        <div class="small">
                                            <?php foreach ($ord['items'] as $it): ?>
                                                <div><strong><?php echo $it['quantity']; ?>x</strong> <?php echo htmlspecialchars($it['product_name']); ?></div>
                                            <?php endforeach; ?>
                                        </div>
                                    </td>
                                    <td><strong class="text-maroon-800">₹<?php echo number_format($ord['total_amount']); ?></strong></td>
                                    <td>
                                        <span class="badge-status-<?php echo $ord['order_status']; ?>"><?php echo ucfirst($ord['order_status']); ?></span>
                                    </td>
                                    <td class="small text-muted"><?php echo date('d M, Y', strtotime($ord['created_at'])); ?></td>
                                    <td>
                                        <a href="order-details.php?id=<?php echo $ord['id']; ?>" class="btn btn-sm btn-light border" title="Track Order"><i class="fa-regular fa-eye"></i> View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Wishlist Side Widget -->
    <div class="col-lg-4">
        <div class="dashboard-card h-100 mb-0">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-heart text-danger"></i> Saved in Wishlist</h5>
                <a href="wishlist.php" class="btn btn-outline-maroon btn-sm">View All</a>
            </div>
            <div class="p-3">
                <div class="d-flex flex-column gap-3">
                    <?php if (empty($wishlist_products)): ?>
                        <p class="text-muted text-center py-3">
                            Your wishlist is empty.
                            <a href="wishlist.php">View Wishlist</a>
                        </p>
                    <?php else: ?>

                        <?php foreach ($wishlist_products as $p): ?>
                            <div class="d-flex align-items-center gap-3 p-2 rounded-3 border bg-light">
                                <img src="<?php echo htmlspecialchars($p['images'][0] ?? '../images/default-product.jpg'); ?>"
                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;"
                                    alt="">

                                <div class="flex-grow-1 lh-1">
                                    <strong class="small text-dark d-block text-truncate mb-1" style="max-width: 150px;">
                                        <?php echo htmlspecialchars($p['name']); ?>
                                    </strong>

                                    <span class="fw-bold text-maroon-800 small">
                                        ₹<?php echo $p['price']; ?>
                                    </span>
                                </div>

                                <a href="cart.php" class="btn btn-sm btn-maroon py-1 px-2" title="Move to Cart">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recommended Fresh Homemade Delicacies & Handicrafts -->
<div class="dashboard-card mb-0">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-sparkles text-warning"></i> Handcrafted Just for You</h5>
        <a href="../products.php" class="btn btn-outline-maroon btn-sm">Browse Marketplace</a>
    </div>
    <div class="p-3">
        <div class="row g-3">
            <?php foreach (array_slice($products, 0, 4) as $p): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border rounded-3 overflow-hidden shadow-none hover-lift">
                        <img src="<?php echo htmlspecialchars($p['images'][0] ?? '../images/default-product.jpg'); ?>" style="height: 140px; object-fit: cover;" alt="">
                        <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                            <div>
                                <small class="text-muted d-block mb-1"><?php echo htmlspecialchars($p['category']); ?></small>
                                <strong class="small text-dark d-block text-truncate mb-1"><?php echo htmlspecialchars($p['name']); ?></strong>
                                <small class="text-terracotta d-block mb-2">By <?php echo htmlspecialchars($p['seller_name']); ?></small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <span class="fw-bold text-maroon-900">₹<?php echo $p['price']; ?></span>
                                <a href="../product-details.php?slug=<?php echo $p['slug']; ?>" class="btn btn-sm btn-maroon py-0 px-2">Order</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
