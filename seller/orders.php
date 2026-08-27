<?php
$page_title = "My Orders - Maker Portal";
$page_header = "Customer Orders";
$page_subheader = "View incoming requests, prepare packages and update dispatch status";
require_once __DIR__ . '/includes/header.php';

$all_orders = get_all_orders();
$seller_orders = [];

foreach ($all_orders as $ord) {
    $seller_items = [];
    foreach ($ord['items'] as $item) {
        if ((int)$item['seller_id'] === (int)$seller_id) {
            $seller_items[] = $item;
        }
    }
    if (!empty($seller_items)) {
        $ord['seller_items'] = $seller_items;
        $seller_orders[] = $ord;
    }
}

$filter = $_GET['filter'] ?? 'all';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <!-- Filter Tabs -->
    <div class="btn-group shadow-sm bg-white p-1 rounded-3 border" role="group">
        <a href="orders.php?filter=all" class="btn btn-sm <?php echo $filter === 'all' ? 'btn-maroon' : 'btn-light'; ?>">All (<?php echo count($seller_orders); ?>)</a>
        <a href="orders.php?filter=pending" class="btn btn-sm <?php echo $filter === 'pending' ? 'btn-maroon' : 'btn-light'; ?>">Pending / New</a>
        <a href="orders.php?filter=processing" class="btn btn-sm <?php echo $filter === 'processing' ? 'btn-maroon' : 'btn-light'; ?>">In Kitchen</a>
        <a href="orders.php?filter=completed" class="btn btn-sm <?php echo $filter === 'completed' ? 'btn-maroon' : 'btn-light'; ?>">Completed</a>
    </div>

    <div class="input-group input-group-sm" style="max-width: 280px;">
        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
        <input type="text" id="dashboardTableSearch" class="form-control" placeholder="Search customer or order #...">
    </div>
</div>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-bag-shopping text-maroon-800"></i> Customer Orders for My Products</h5>
    </div>
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer Name</th>
                    <th>Ordered Items</th>
                    <th>Payment Method</th>
                    <th>My Earnings</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($seller_orders)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted">No orders found for this filter.</td></tr>
                <?php else: ?>
                    <?php foreach ($seller_orders as $ord): 
                        if ($filter !== 'all' && $ord['order_status'] !== $filter) continue;
                        $my_earnings = array_sum(array_column($ord['seller_items'], 'subtotal'));
                    ?>
                        <tr>
                            <td class="fw-bold text-maroon-900"><?php echo $ord['order_number']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($ord['customer_name']); ?></strong>
                                <small class="text-muted d-block"><?php echo htmlspecialchars($ord['customer_phone']); ?></small>
                            </td>
                            <td>
                                <?php foreach ($ord['seller_items'] as $it): ?>
                                    <div class="small">
                                        <strong><?php echo $it['quantity']; ?>x</strong> <?php echo htmlspecialchars($it['product_name']); ?>
                                        <span class="text-muted">(₹<?php echo $it['price']; ?>)</span>
                                    </div>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($ord['payment_method']); ?></span>
                            </td>
                            <td><strong class="text-maroon-900 fs-6">₹<?php echo number_format($my_earnings); ?></strong></td>
                            <td>
                                <span class="badge-status-<?php echo $ord['order_status']; ?>"><?php echo ucfirst($ord['order_status']); ?></span>
                            </td>
                            <td class="small text-muted"><?php echo date('d M, Y', strtotime($ord['created_at'])); ?></td>
                            <td>
                                <a href="order-details.php?id=<?php echo $ord['id']; ?>" class="btn btn-sm btn-maroon fw-bold">
                                    <i class="fa-solid fa-box-archive me-1"></i> Order Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
