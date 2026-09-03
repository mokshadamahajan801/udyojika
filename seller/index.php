<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
|--------------------------------------------------------------------------
| Seller Authentication
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/includes/auth.php';

/*
|--------------------------------------------------------------------------
| Seller Information
|--------------------------------------------------------------------------
*/

$seller_id = (int)($seller_profile['id'] ?? $current_user['id'] ?? 0);

if ($seller_id <= 0) {
    die("Seller ID not found.");
}

/*
|--------------------------------------------------------------------------
| Page Header
|--------------------------------------------------------------------------
*/

$page_title = "Maker Dashboard - Udyojika";

$page_header = "Namaste, " . htmlspecialchars(
    $seller_profile['owner_name'] ?? $current_user['name'] ?? 'Maker'
);

$page_subheader = "Here is an overview of your orders, sales and patron reviews";

require_once __DIR__ . '/includes/header.php';

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

$stats = get_seller_dashboard_stats($seller_id);

$all_orders = get_all_orders();

/*
|--------------------------------------------------------------------------
| Filter Orders For This Seller
|--------------------------------------------------------------------------
*/

$seller_orders = [];

foreach ($all_orders as $ord) {

    $seller_items = [];

    foreach ($ord['items'] as $item) {

        if ((int)$item['seller_id'] === $seller_id) {
            $seller_items[] = $item;
        }
    }

    if (!empty($seller_items)) {

        $ord['seller_items'] = $seller_items;

        $seller_orders[] = $ord;
    }
}

/*
|--------------------------------------------------------------------------
| Seller Reviews
|--------------------------------------------------------------------------
*/

$all_reviews = get_all_reviews();

$seller_reviews = array_filter(
    $all_reviews,
    fn($r) => (int)$r['seller_id'] === $seller_id
);

/*
|--------------------------------------------------------------------------
| Seller Enquiries
|--------------------------------------------------------------------------
*/

$all_enquiries = get_all_enquiries();

$seller_enquiries = array_filter(
    $all_enquiries,
    fn($e) => (int)$e['seller_id'] === $seller_id
);

?>

<!-- 10 Summary Metric Cards Grid -->
<div class="row g-3 mb-4">

    <!-- Total Products -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-title">My Products</div>

            <div class="stat-value">
                <?php echo $stats['total_products']; ?>
            </div>

            <div class="stat-trend text-success">
                <i class="fa-solid fa-check"></i>
                <?php echo $stats['active_products']; ?> Active in store
            </div>
        </div>
    </div>


    <!-- Total Sales -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">

            <div class="stat-title">Total Sales</div>

            <div class="stat-value text-maroon-900">
                ₹<?php echo number_format($stats['total_sales']); ?>
            </div>

            <div class="stat-trend text-success">
                <i class="fa-solid fa-indian-rupee-sign"></i>
                100% Payout
            </div>

        </div>
    </div>


    <!-- Pending Orders -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card border-warning">

            <div class="stat-title">New Orders</div>

            <div class="stat-value text-warning">
                <?php echo $stats['pending_orders']; ?>
            </div>

            <div class="stat-trend text-warning">
                <a href="orders.php"
                   class="text-decoration-none small text-warning fw-bold">
                    Pack & Dispatch →
                </a>
            </div>

        </div>
    </div>


    <!-- Processing Orders -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">

            <div class="stat-title">In Kitchen / Craft</div>

            <div class="stat-value text-primary">
                <?php echo $stats['processing_orders']; ?>
            </div>

            <div class="stat-trend text-primary">
                In preparation
            </div>

        </div>
    </div>


    <!-- Completed Orders -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">

            <div class="stat-title">Delivered</div>

            <div class="stat-value text-success">
                <?php echo $stats['completed_orders']; ?>
            </div>

            <div class="stat-trend text-success">
                <i class="fa-solid fa-circle-check"></i>
                Customer verified
            </div>

        </div>
    </div>


    <!-- Average Rating -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">

            <div class="stat-title">Customer Rating</div>

            <div class="stat-value text-warning">
                <?php echo $stats['average_rating']; ?> ★
            </div>

            <div class="stat-trend text-muted">
                <?php echo $stats['total_reviews']; ?> Verified Reviews
            </div>

        </div>
    </div>

</div>


<!-- Sales Chart & Quick Actions Row -->
<div class="row g-4 mb-4">

    <div class="col-lg-8">

        <div class="dashboard-card h-100">

            <div class="dashboard-card-header">

                <h5 class="dashboard-card-title">
                    <i class="fa-solid fa-chart-line text-maroon-800"></i>
                    My Weekly Sales Velocity
                </h5>

                <span class="badge bg-cream-200 text-maroon-900 border">
                    August 2024
                </span>

            </div>


            <div class="p-4">

                <div class="chart-bar-container">

                    <div class="chart-bar-col">
                        <div class="chart-bar-fill"
                             style="height: 35%;"
                             title="Mon: ₹1,200"></div>
                        <span class="chart-bar-label">Mon</span>
                    </div>

                    <div class="chart-bar-col">
                        <div class="chart-bar-fill"
                             style="height: 50%;"
                             title="Tue: ₹2,400"></div>
                        <span class="chart-bar-label">Tue</span>
                    </div>

                    <div class="chart-bar-col">
                        <div class="chart-bar-fill"
                             style="height: 40%;"
                             title="Wed: ₹1,800"></div>
                        <span class="chart-bar-label">Wed</span>
                    </div>

                    <div class="chart-bar-col">
                        <div class="chart-bar-fill"
                             style="height: 65%;"
                             title="Thu: ₹3,200"></div>
                        <span class="chart-bar-label">Thu</span>
                    </div>

                    <div class="chart-bar-col">
                        <div class="chart-bar-fill"
                             style="height: 80%;"
                             title="Fri: ₹4,100"></div>
                        <span class="chart-bar-label">Fri</span>
                    </div>

                    <div class="chart-bar-col">
                        <div class="chart-bar-fill"
                             style="height: 95%;"
                             title="Sat: ₹5,600"></div>
                        <span class="chart-bar-label">Sat</span>
                    </div>

                    <div class="chart-bar-col">
                        <div class="chart-bar-fill"
                             style="height: 100%;"
                             title="Sun: ₹6,200"></div>
                        <span class="chart-bar-label">Sun</span>
                    </div>

                </div>


                <div class="d-flex justify-content-between pt-3 border-top mt-3 small text-muted">

                    <span>
                        <i class="fa-solid fa-truck text-terracotta me-1"></i>
                        Courier pickup happens daily at 4:00 PM
                    </span>

                    <strong class="text-maroon-900">
                        Next Bank Settlement: Tuesday
                    </strong>

                </div>

            </div>

        </div>

    </div>


    <!-- Quick Maker Actions -->
    <div class="col-lg-4">

        <div class="dashboard-card h-100">

            <div class="dashboard-card-header">

                <h5 class="dashboard-card-title">
                    <i class="fa-solid fa-bolt text-warning"></i>
                    Quick Maker Actions
                </h5>

            </div>


            <div class="p-3 d-flex flex-column gap-2">

                <a href="add-product.php"
                   class="btn btn-maroon w-100 py-2 d-flex align-items-center justify-content-between">

                    <span>
                        <i class="fa-solid fa-circle-plus me-2"></i>
                        Add New Product
                    </span>

                    <i class="fa-solid fa-chevron-right small"></i>

                </a>


                <a href="products.php"
                   class="btn btn-outline-maroon w-100 py-2 d-flex align-items-center justify-content-between">

                    <span>
                        <i class="fa-solid fa-boxes-stacked me-2"></i>
                        Update Stock Counts
                    </span>

                    <i class="fa-solid fa-chevron-right small"></i>

                </a>


                <a href="business.php"
                   class="btn btn-light border w-100 py-2 d-flex align-items-center justify-content-between">

                    <span>
                        <i class="fa-solid fa-store me-2"></i>
                        Edit Home Kitchen Story
                    </span>

                    <i class="fa-solid fa-chevron-right small"></i>

                </a>


                <a href="earnings.php"
                   class="btn btn-light border w-100 py-2 d-flex align-items-center justify-content-between">

                    <span>
                        <i class="fa-solid fa-building-columns me-2"></i>
                        View Bank Payouts
                    </span>

                    <i class="fa-solid fa-chevron-right small"></i>

                </a>

            </div>

        </div>

    </div>

</div>


<!-- Recent Orders & Reviews Row -->
<div class="row g-4">

    <!-- Orders -->
    <div class="col-lg-8">

        <div class="dashboard-card mb-0">

            <div class="dashboard-card-header">

                <h5 class="dashboard-card-title">
                    <i class="fa-solid fa-bag-shopping text-maroon-800"></i>
                    My Recent Orders
                </h5>

                <a href="orders.php"
                   class="btn btn-outline-maroon btn-sm">
                    View All
                </a>

            </div>


            <div class="table-responsive">

                <table class="dashboard-table">

                    <thead>

                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>My Items</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>

                    </thead>


                    <tbody>

                        <?php if (empty($seller_orders)): ?>

                            <tr>
                                <td colspan="6"
                                    class="text-center py-4 text-muted">
                                    No orders received yet.
                                </td>
                            </tr>

                        <?php else: ?>

                            <?php foreach ($seller_orders as $ord): ?>

                                <tr>

                                    <td class="fw-bold text-maroon-900">
                                        <?php echo htmlspecialchars($ord['order_number']); ?>
                                    </td>


                                    <td>

                                        <strong>
                                            <?php echo htmlspecialchars($ord['customer_name']); ?>
                                        </strong>

                                        <small class="text-muted d-block">
                                            <?php echo htmlspecialchars($ord['customer_phone']); ?>
                                        </small>

                                    </td>


                                    <td>

                                        <?php foreach ($ord['seller_items'] as $it): ?>

                                            <div class="small">

                                                <strong>
                                                    <?php echo $it['quantity']; ?>x
                                                </strong>

                                                <?php echo htmlspecialchars($it['product_name']); ?>

                                            </div>

                                        <?php endforeach; ?>

                                    </td>


                                    <td>

                                        <span class="badge-status-<?php echo htmlspecialchars($ord['order_status']); ?>">
                                            <?php echo ucfirst($ord['order_status']); ?>
                                        </span>

                                    </td>


                                    <td class="small text-muted">

                                        <?php
                                        echo date(
                                            'd M, h:i A',
                                            strtotime($ord['created_at'])
                                        );
                                        ?>

                                    </td>


                                    <td>

                                        <a href="order-details.php?id=<?php echo (int)$ord['id']; ?>"
                                           class="btn btn-sm btn-maroon">
                                            View / Pack
                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- Reviews -->
    <div class="col-lg-4">

        <div class="dashboard-card mb-0">

            <div class="dashboard-card-header">

                <h5 class="dashboard-card-title">
                    <i class="fa-solid fa-star text-warning"></i>
                    Patron Reviews
                </h5>

                <a href="reviews.php"
                   class="btn btn-outline-maroon btn-sm">
                    All
                </a>

            </div>


            <div class="p-3">

                <div class="d-flex flex-column gap-3">

                    <?php if (empty($seller_reviews)): ?>

                        <p class="text-muted small mb-0">
                            No customer reviews yet.
                        </p>

                    <?php else: ?>

                        <?php foreach ($seller_reviews as $rev): ?>

                            <div class="p-3 border rounded-3 bg-light">

                                <div class="d-flex justify-content-between align-items-center mb-1">

                                    <strong class="small text-dark">
                                        <?php echo htmlspecialchars($rev['customer_name']); ?>
                                    </strong>

                                    <span class="text-warning small">
                                        <?php echo str_repeat('★', (int)$rev['rating']); ?>
                                    </span>

                                </div>


                                <small class="text-maroon-800 fw-bold d-block mb-1">
                                    <?php echo htmlspecialchars($rev['product_name']); ?>
                                </small>


                                <p class="small text-muted mb-0">
                                    "<?php echo htmlspecialchars($rev['review_text']); ?>"
                                </p>

                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>


<?php require_once __DIR__ . '/includes/footer.php'; ?>