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
// Personalized recommendations based on wishlist category
$recommended_products = [];

if (!empty($wishlist_products)) {

    $recommended_category = $wishlist_products[0]['category_id'] ?? null;

    if ($recommended_category) {

        $stmt = $pdo->prepare("
            SELECT
                p.*,
                s.business_name AS seller_name
            FROM products p
            LEFT JOIN sellers s ON s.id = p.seller_id
            WHERE p.category_id = ?
              AND p.status = 'active'
            ORDER BY p.created_at DESC
            LIMIT 4
        ");

        $stmt->execute([$recommended_category]);

        $recommended_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// If no personalized products found, show latest 4 products
if (empty($recommended_products)) {
    $recommended_products = array_slice($products, -4);
}
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
                <h5 class="dashboard-card-title">
                    <i class="fa-solid fa-heart text-danger"></i>
                    Saved in Wishlist
                </h5>

                <a href="wishlist.php"
                class="btn btn-outline-maroon btn-sm">
                    View All
                </a>
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

                            <?php
                            /*
                            * Product image
                            */
                            $wishlist_image = '';

                            if (!empty($p['images'])) {

                                if (is_array($p['images'])) {

                                    $wishlist_image = $p['images'][0] ?? '';

                                } else {

                                    $decoded_images = json_decode(
                                        $p['images'],
                                        true
                                    );

                                    if (is_array($decoded_images)) {
                                        $wishlist_image = $decoded_images[0] ?? '';
                                    }
                                }
                            }

                            if (!empty($wishlist_image)) {

                                if (
                                    strpos($wishlist_image, 'http://') === 0 ||
                                    strpos($wishlist_image, 'https://') === 0 ||
                                    strpos($wishlist_image, '../') === 0
                                ) {
                                    $wishlist_image_path = $wishlist_image;
                                } else {
                                    $wishlist_image_path = '../' . ltrim($wishlist_image, '/');
                                }

                            } else {

                                $wishlist_image_path = '../images/default-product.jpg';
                            }
                            ?>


                            <div class="d-flex align-items-center gap-3 p-2 rounded-3 border bg-light">

                                <!-- Product Image -->
                                <img
                                    src="<?php echo htmlspecialchars($wishlist_image_path); ?>"
                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;"
                                    alt="<?php echo htmlspecialchars($p['name']); ?>"
                                    onerror="this.src='../images/default-product.jpg';"
                                >


                                <!-- Product Details -->
                                <div class="flex-grow-1 lh-1">

                                    <strong
                                        class="small text-dark d-block text-truncate mb-1"
                                        style="max-width: 150px;"
                                        title="<?php echo htmlspecialchars($p['name']); ?>">

                                        <?php echo htmlspecialchars($p['name']); ?>

                                    </strong>

                                    <span class="fw-bold text-maroon-800 small">
                                        ₹<?php echo number_format((float)$p['price'], 2); ?>
                                    </span>

                                </div>


                                <!-- Add to Cart -->
                                <form method="POST" action="wishlist-to-cart.php" class="m-0">

                                    <input
                                        type="hidden"
                                        name="product_id"
                                        value="<?php echo (int)$p['id']; ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-maroon py-1 px-2"
                                        title="Add to Cart">

                                        <i class="fa-solid fa-cart-plus"></i>

                                    </button>

                                </form>

                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

            </div>

        </div>
    </div>
</div>

<?php
/* =========================================================
   PERSONALIZED RECOMMENDATIONS
   ========================================================= */

$recommended_products = [];

/*
 * First try products from the customer's wishlist category.
 */
if (!empty($wishlist_products)) {

    $wishlist_product = $wishlist_products[0];

    $wishlist_category_id = !empty($wishlist_product['category_id'])
        ? (int)$wishlist_product['category_id']
        : null;

    $wishlist_category_name = trim(
        $wishlist_product['category_name']
        ?? $wishlist_product['category']
        ?? ''
    );

    if ($wishlist_category_id) {

        $stmt = $pdo->prepare("
            SELECT
                p.*,
                COALESCE(c.name, p.category_name, 'Other') AS display_category,
                s.business_name AS seller_name
            FROM products p
            LEFT JOIN categories c ON c.id = p.category_id
            LEFT JOIN sellers s ON s.id = p.seller_id
            WHERE p.status = 'active'
              AND p.category_id = ?
            ORDER BY p.created_at DESC
            LIMIT 4
        ");

        $stmt->execute([$wishlist_category_id]);

        $recommended_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } elseif ($wishlist_category_name !== '') {

        /*
         * Useful when category_id is NULL but category_name exists.
         */
        $stmt = $pdo->prepare("
            SELECT
                p.*,
                COALESCE(c.name, p.category_name, 'Other') AS display_category,
                s.business_name AS seller_name
            FROM products p
            LEFT JOIN categories c ON c.id = p.category_id
            LEFT JOIN sellers s ON s.id = p.seller_id
            WHERE p.status = 'active'
              AND (
                    p.category_name = ?
                    OR c.name = ?
                  )
            ORDER BY p.created_at DESC
            LIMIT 4
        ");

        $stmt->execute([
            $wishlist_category_name,
            $wishlist_category_name
        ]);

        $recommended_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


/*
 * If no personalized products are found,
 * show the latest active products.
 */
if (empty($recommended_products)) {

    $stmt = $pdo->prepare("
        SELECT
            p.*,
            COALESCE(c.name, p.category_name, 'Other') AS display_category,
            s.business_name AS seller_name
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        LEFT JOIN sellers s ON s.id = p.seller_id
        WHERE p.status = 'active'
        ORDER BY p.created_at DESC
        LIMIT 4
    ");

    $stmt->execute();

    $recommended_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/*
 * Prepare image paths for customer folder.
 */
foreach ($recommended_products as &$recommended_product) {

    $image = '';

    if (isset($recommended_product['images'])) {

        if (is_array($recommended_product['images'])) {

            $image = $recommended_product['images'][0] ?? '';

        } else {

            $decoded_images = json_decode(
                $recommended_product['images'],
                true
            );

            if (is_array($decoded_images)) {
                $image = $decoded_images[0] ?? '';
            }
        }
    }

    /*
     * Convert root-relative image path:
     * images/cake.jpg
     * into:
     * ../images/cake.jpg
     */
    if ($image !== '') {

        if (
            strpos($image, 'http://') === 0 ||
            strpos($image, 'https://') === 0 ||
            strpos($image, '../') === 0
        ) {
            $recommended_product['display_image'] = $image;

        } else {

            $recommended_product['display_image'] = '../' . ltrim($image, '/');
        }

    } else {

        $recommended_product['display_image'] = '../images/default-product.jpg';
    }
}

unset($recommended_product);
?>


<!-- =========================================================
     HANDCRAFTED JUST FOR YOU
     ========================================================= -->

<div class="dashboard-card mb-0">

    <div class="dashboard-card-header">

        <h5 class="dashboard-card-title">
            <i class="fa-solid fa-sparkles text-warning"></i>
            Handcrafted Just for You
        </h5>

        <a href="../products.php"
           class="btn btn-outline-maroon btn-sm">
            Browse Marketplace
        </a>

    </div>


    <div class="p-3">

        <div class="row g-3">

            <?php if (empty($recommended_products)): ?>

                <div class="col-12">
                    <p class="text-muted text-center py-4 mb-0">
                        No recommendations available right now.
                        <a href="../products.php">Browse Products</a>
                    </p>
                </div>

            <?php else: ?>

                <?php foreach ($recommended_products as $p): ?>

                    <div class="col-md-6 col-lg-3">

                        <div class="card h-100 border rounded-3 overflow-hidden shadow-none hover-lift">

                            <!-- Product Image -->
                            <img
                                src="<?php echo htmlspecialchars($p['display_image']); ?>"
                                style="height: 140px; width: 100%; object-fit: cover;"
                                alt="<?php echo htmlspecialchars($p['name']); ?>"
                                onerror="this.src='../images/default-product.jpg';"
                            >


                            <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">

                                <div>

                                    <!-- Category -->
                                    <small class="text-muted d-block mb-1">
                                        <?php
                                        echo htmlspecialchars(
                                            $p['display_category']
                                            ?? $p['category_name']
                                            ?? $p['category']
                                            ?? 'Other'
                                        );
                                        ?>
                                    </small>


                                    <!-- Product Name -->
                                    <strong
                                        class="small text-dark d-block text-truncate mb-1"
                                        title="<?php echo htmlspecialchars($p['name']); ?>">

                                        <?php echo htmlspecialchars($p['name']); ?>

                                    </strong>


                                    <!-- Seller -->
                                    <small class="text-terracotta d-block mb-2">

                                        By
                                        <?php
                                        echo htmlspecialchars(
                                            $p['seller_name']
                                            ?? 'Home Maker'
                                        );
                                        ?>

                                    </small>

                                </div>


                                <!-- Price + Order -->
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">

                                    <span class="fw-bold text-maroon-900">
                                        ₹<?php echo number_format((float)$p['price'], 2); ?>
                                    </span>

                                    <a
                                        href="../product-details.php?slug=<?php echo urlencode($p['slug']); ?>"
                                        class="btn btn-sm btn-maroon py-0 px-2">

                                        Order

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </div>

</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
