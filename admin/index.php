<?php

require_once __DIR__ . '/includes/auth.php';

require_admin();

$page_title = "Admin Dashboard - Udyojika";
$page_header = "Executive Marketplace Overview";
$page_subheader = "Real-time statistics across all sellers, buyers, products and revenue";

require_once __DIR__ . '/includes/header.php';

$stats = get_admin_dashboard_stats();
$orders = get_all_orders();
$seller_requests = get_seller_requests();
$products = get_all_products($pdo);
$reviews = get_all_reviews();
$categories = get_categories($pdo);

?>

<!-- Top 12 Summary Metrics Grid -->
<div class="row g-3 mb-4">
    <!-- Total Users -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Users</div>
                    <div class="stat-value"><?php echo $stats['total_users']; ?></div>
                </div>
                <div class="stat-icon bg-primary-subtle text-primary"><i class="fa-solid fa-users"></i></div>
            </div>
            <div class="stat-trend text-success"><i class="fa-solid fa-arrow-trend-up"></i> +12% this mo</div>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Customers</div>
                    <div class="stat-value"><?php echo $stats['total_customers']; ?></div>
                </div>
                <div class="stat-icon bg-info-subtle text-info"><i class="fa-solid fa-user-group"></i></div>
            </div>
            <div class="stat-trend text-muted"><i class="fa-solid fa-circle-check text-success"></i> Active</div>
        </div>
    </div>

    <!-- Total Sellers -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Sellers / Makers</div>
                    <div class="stat-value"><?php echo $stats['total_sellers']; ?></div>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning"><i class="fa-solid fa-store"></i></div>
            </div>
            <div class="stat-trend text-success"><i class="fa-solid fa-check-double"></i> Verified</div>
        </div>
    </div>

    <!-- Pending Seller Requests -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card border-warning">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Maker Requests</div>
                    <div class="stat-value text-danger"><?php echo $stats['pending_requests']; ?></div>
                </div>
                <div class="stat-icon bg-danger-subtle text-danger"><i class="fa-solid fa-user-clock"></i></div>
            </div>
            <div class="stat-trend text-danger"><a href="seller-requests.php" class="text-decoration-none small text-danger fw-bold">Review Now &rarr;</a></div>
        </div>
    </div>

    <!-- Total Businesses -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Home Brands</div>
                    <div class="stat-value"><?php echo $stats['total_businesses']; ?></div>
                </div>
                <div class="stat-icon bg-success-subtle text-success"><i class="fa-solid fa-shop"></i></div>
            </div>
            <div class="stat-trend text-success"><i class="fa-solid fa-award"></i> 100% Homemade</div>
        </div>
    </div>

    <!-- Total Products -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Live Products</div>
                    <div class="stat-value"><?php echo $stats['total_products']; ?></div>
                </div>
                <div class="stat-icon bg-secondary-subtle text-secondary"><i class="fa-solid fa-box-open"></i></div>
            </div>
            <div class="stat-trend text-muted">Across 6 Cats</div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total Orders</div>
                    <div class="stat-value"><?php echo $stats['total_orders']; ?></div>
                </div>
                <div class="stat-icon bg-primary-subtle text-primary"><i class="fa-solid fa-cart-shopping"></i></div>
            </div>
            <div class="stat-trend text-success"><i class="fa-solid fa-truck-fast"></i> Active</div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Pending Orders</div>
                    <div class="stat-value text-warning"><?php echo $stats['pending_orders']; ?></div>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning"><i class="fa-solid fa-clock-rotate-left"></i></div>
            </div>
            <div class="stat-trend text-warning"><a href="orders.php" class="text-decoration-none small text-warning fw-bold">View pending &rarr;</a></div>
        </div>
    </div>

    <!-- Completed Orders -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Delivered</div>
                    <div class="stat-value text-success"><?php echo $stats['completed_orders']; ?></div>
                </div>
                <div class="stat-icon bg-success-subtle text-success"><i class="fa-solid fa-circle-check"></i></div>
            </div>
            <div class="stat-trend text-success"><i class="fa-solid fa-shield-halved"></i> 100% Payout</div>
        </div>
    </div>

    <!-- Total Sales Revenue -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Total GMV</div>
                    <div class="stat-value text-maroon-900">₹<?php echo number_format($stats['total_sales']); ?></div>
                </div>
                <div class="stat-icon bg-danger-subtle text-danger"><i class="fa-solid fa-indian-rupee-sign"></i></div>
            </div>
            <div class="stat-trend text-success"><i class="fa-solid fa-arrow-trend-up"></i> +18.4%</div>
        </div>
    </div>

    <!-- Reviews -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Reviews</div>
                    <div class="stat-value"><?php echo $stats['total_reviews']; ?></div>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning"><i class="fa-solid fa-star"></i></div>
            </div>
            <div class="stat-trend text-success">Avg 4.9 ★</div>
        </div>
    </div>

    <!-- Contact Enquiries -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-title">Enquiries</div>
                    <div class="stat-value"><?php echo $stats['total_enquiries']; ?></div>
                </div>
                <div class="stat-icon bg-info-subtle text-info"><i class="fa-solid fa-envelope-open"></i></div>
            </div>
            <div class="stat-trend text-muted">Buyer & Maker</div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Monthly Sales & Orders Chart -->
    <div class="col-lg-8">
        <div class="dashboard-card h-100">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-chart-column text-maroon-800"></i> Monthly Sales & Order Velocity (2024)</h5>
                <span class="badge bg-cream-200 text-maroon-900 border">INR (₹) Revenue</span>
            </div>
            <div class="p-4">
                <div class="chart-bar-container">
                    <div class="chart-bar-col">
                        <div class="chart-bar-fill" style="height: 45%;" title="Jan: ₹24,000"></div>
                        <span class="chart-bar-label">Jan</span>
                    </div>
                    <div class="chart-bar-col">
                        <div class="chart-bar-fill" style="height: 55%;" title="Feb: ₹32,500"></div>
                        <span class="chart-bar-label">Feb</span>
                    </div>
                    <div class="chart-bar-col">
                        <div class="chart-bar-fill" style="height: 65%;" title="Mar: ₹48,000"></div>
                        <span class="chart-bar-label">Mar</span>
                    </div>
                    <div class="chart-bar-col">
                        <div class="chart-bar-fill" style="height: 60%;" title="Apr: ₹42,000"></div>
                        <span class="chart-bar-label">Apr</span>
                    </div>
                    <div class="chart-bar-col">
                        <div class="chart-bar-fill" style="height: 75%;" title="May: ₹59,000"></div>
                        <span class="chart-bar-label">May</span>
                    </div>
                    <div class="chart-bar-col">
                        <div class="chart-bar-fill" style="height: 70%;" title="Jun: ₹55,000"></div>
                        <span class="chart-bar-label">Jun</span>
                    </div>
                    <div class="chart-bar-col">
                        <div class="chart-bar-fill" style="height: 85%;" title="Jul: ₹72,000"></div>
                        <span class="chart-bar-label">Jul</span>
                    </div>
                    <div class="chart-bar-col">
                        <div class="chart-bar-fill" style="height: 100%;" title="Aug: ₹89,500"></div>
                        <span class="chart-bar-label">Aug (Current)</span>
                    </div>
                </div>
                <div class="d-flex justify-content-between pt-3 border-top mt-3 small text-muted">
                    <div><i class="fa-solid fa-circle text-terracotta me-1"></i> Peak Festive Spikes in August (Raksha Bandhan & Faral)</div>
                    <strong class="text-maroon-900">Total Direct Payout: ₹4,22,000</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Products By Category Breakdown -->
    <div class="col-lg-4">
        <div class="dashboard-card h-100">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-pie-chart text-terracotta"></i> Products by Category</h5>
            </div>
            <div class="p-3">
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($categories as $cat): ?>
                        <div>
                            <div class="d-flex justify-content-between small fw-bold mb-1">
                                <span><i class="fa-solid <?php echo $cat['icon']; ?> text-terracotta me-2"></i> <?php echo $cat['name']; ?></span>
                                <span class="text-muted"><?php echo $cat['product_count']; ?> items</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-maroon-800" role="progressbar" style="width: <?php echo min(100, $cat['product_count'] / 1.5); ?>%;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tables Row: Recent Orders & Recent Seller Requests -->
<div class="row g-4 mb-4">
    <!-- Recent Orders Table -->
    <div class="col-lg-8">
        <div class="dashboard-card mb-0">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-bag-shopping text-maroon-800"></i> Recent Orders</h5>
                <a href="orders.php" class="btn btn-outline-maroon btn-sm">View All Orders</a>
            </div>
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $ord): ?>
                            <tr>
                                <td class="fw-bold text-maroon-900"><?php echo $ord['order_number']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($ord['customer_name']); ?></strong>
                                    <small class="text-muted d-block"><?php echo htmlspecialchars($ord['customer_phone']); ?></small>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?php echo count($ord['items']); ?> item(s)</span></td>
                                <td class="fw-bold text-maroon-800">₹<?php echo number_format($ord['total_amount']); ?></td>
                                <td>
                                    <span class="badge-status-<?php echo strtolower($ord['order_status']); ?>">
                                        <?php echo ucfirst($ord['order_status']); ?>
                                    </span>
                                </td>
                                <td class="small text-muted"><?php echo date('d M, h:i A', strtotime($ord['created_at'])); ?></td>
                                <td>
                                    <a href="orders.php?id=<?php echo $ord['id']; ?>" class="btn btn-light btn-sm border" title="View Details"><i class="fa-regular fa-eye"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Seller Requests -->
    <div class="col-lg-4">
        <div class="dashboard-card mb-0">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-user-plus text-warning"></i> Pending Maker Requests</h5>
                <a href="seller-requests.php" class="btn btn-outline-maroon btn-sm">Manage</a>
            </div>
            <div class="p-3">
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($seller_requests as $req): ?>
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="fw-bold text-maroon-900 mb-0"><?php echo htmlspecialchars($req['full_name']); ?></h6>
                                <span class="badge-status-<?php echo $req['status']; ?>"><?php echo ucfirst($req['status']); ?></span>
                            </div>
                            <small class="text-muted d-block mb-1"><strong>Brand:</strong> <?php echo htmlspecialchars($req['business_name']); ?> (<?php echo htmlspecialchars($req['city']); ?>)</small>
                            <small class="text-secondary d-block mb-2"><?php echo htmlspecialchars(substr($req['description'], 0, 70)); ?>...</small>
                            <div class="d-flex gap-2">
                                <a href="seller-requests.php" class="btn btn-sm btn-success py-0 px-2 fw-bold" style="font-size: 0.75rem;"><i class="fa-solid fa-check me-1"></i> Approve</a>
                                <a href="seller-requests.php" class="btn btn-sm btn-outline-danger py-0 px-2 fw-bold" style="font-size: 0.75rem;"><i class="fa-solid fa-xmark me-1"></i> Reject</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row: Recent Live Products & Verified Reviews -->
<div class="row g-4">
    <!-- Products -->
    <div class="col-lg-7">
        <div class="dashboard-card mb-0">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-box text-terracotta"></i> Featured Homemade Products</h5>
                <a href="products.php" class="btn btn-outline-maroon btn-sm">View All</a>
            </div>
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Maker / Brand</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($products, 0, 4) as $p): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?php echo $p['images'][0]; ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;" alt="">
                                        <span class="fw-bold text-dark"><?php echo htmlspecialchars($p['name']); ?></span>
                                    </div>
                                </td>
                                <td class="small text-muted"><?php echo htmlspecialchars($p['seller_name']); ?></td>
                                <td class="fw-bold text-maroon-800">₹<?php echo $p['price']; ?></td>
                                <td><span class="badge bg-light text-dark border"><?php echo $p['stock_quantity']; ?> in stock</span></td>
                                <td><span class="badge-status-active">Active</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Reviews -->
    <div class="col-lg-5">
        <div class="dashboard-card mb-0">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-star text-warning"></i> Recent Customer Reviews</h5>
                <a href="reviews.php" class="btn btn-outline-maroon btn-sm">Moderate</a>
            </div>
            <div class="p-3">
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($reviews as $rev): ?>
                        <div class="p-3 border rounded-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="small text-dark"><?php echo htmlspecialchars($rev['customer_name']); ?></strong>
                                <span class="text-warning small"><?php echo str_repeat('★', $rev['rating']); ?></span>
                            </div>
                            <small class="text-maroon-800 fw-semibold d-block mb-1">On "<?php echo htmlspecialchars($rev['product_name']); ?>"</small>
                            <p class="small text-muted mb-0">"<?php echo htmlspecialchars($rev['review_text']); ?>"</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>



