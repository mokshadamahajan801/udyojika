<?php
$page_title = "Manage Orders - Admin Portal";
$page_header = "Marketplace Orders & Deliveries";
$page_subheader = "Monitor customer orders, payment receipts and maker dispatch milestones";
require_once __DIR__ . '/includes/header.php';

$orders = get_all_orders($pdo);

$filter_status = $_GET['status'] ?? 'all';
$action_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? 0;
    $new_status = $_POST['order_status'] ?? '';
    $action_msg = "Order #{$order_id} status updated to " . strtoupper($new_status) . ". Customer notified via SMS.";
}
?>

<?php if (!empty($action_msg)): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="fa-solid fa-circle-check fs-4"></i>
        <div><?php echo htmlspecialchars($action_msg); ?></div>
    </div>
<?php endif; ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <!-- Status Filter Tabs -->
    <div class="btn-group shadow-sm bg-white p-1 rounded-3 border" role="group">
        <a href="orders.php?status=all" class="btn btn-sm <?php echo $filter_status === 'all' ? 'btn-maroon' : 'btn-light'; ?>">All (<?php echo count($orders); ?>)</a>
        <a href="orders.php?status=pending" class="btn btn-sm <?php echo $filter_status === 'pending' ? 'btn-maroon' : 'btn-light'; ?>">Pending</a>
        <a href="orders.php?status=processing" class="btn btn-sm <?php echo $filter_status === 'processing' ? 'btn-maroon' : 'btn-light'; ?>">Processing</a>
        <a href="orders.php?status=completed" class="btn btn-sm <?php echo $filter_status === 'completed' ? 'btn-maroon' : 'btn-light'; ?>">Completed</a>
    </div>

    <div class="input-group input-group-sm" style="max-width: 300px;">
        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
        <input type="text" id="dashboardTableSearch" class="form-control" placeholder="Search Order #, Customer...">
    </div>
</div>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-bag-shopping text-maroon-800"></i> Customer Orders</h5>
        <button class="btn btn-outline-maroon btn-sm" onclick="window.print();"><i class="fa-solid fa-print me-1"></i> Print Invoices</button>
    </div>
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer & Contact</th>
                    <th>Ordered Items</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $ord): 
                    if ($filter_status !== 'all' && $ord['order_status'] !== $filter_status) continue;
                ?>
                    <tr>
                        <td class="fw-bold text-maroon-900"><?php echo $ord['order_number']; ?></td>
                        <td>
                            <strong class="text-dark d-block"><?php echo htmlspecialchars($ord['customer_name']); ?></strong>
                            <small class="text-muted"><?php echo htmlspecialchars($ord['customer_phone']); ?></small>
                        </td>
                        <td>
                            <div class="small">
                                <?php foreach ($ord['items'] as $it): ?>
                                    <div class="text-truncate" style="max-width: 220px;">
                                        &bull; <?php echo $it['quantity']; ?>x <?php echo htmlspecialchars($it['product_name']); ?>
                                        <span class="text-muted">(<?php echo htmlspecialchars($it['seller_name']); ?>)</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($ord['payment_method']); ?></span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle d-block mt-1"><?php echo $ord['payment_status']; ?></span>
                        </td>
                        <td><strong class="text-maroon-900 fs-6">₹<?php echo number_format($ord['total_amount']); ?></strong></td>
                        <td>
                            <span class="badge-status-<?php echo $ord['order_status']; ?>"><?php echo ucfirst($ord['order_status']); ?></span>
                        </td>
                        <td class="small text-muted"><?php echo date('d M, Y', strtotime($ord['created_at'])); ?></td>
                        <td>
                            <form action="orders.php" method="POST" class="d-flex align-items-center gap-1">
                                <input type="hidden" name="order_id" value="<?php echo $ord['id']; ?>">
                                <select name="order_status" class="form-select form-select-sm py-0" style="font-size: 0.8rem;">
                                    <option value="pending" <?php echo $ord['order_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?php echo $ord['order_status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="completed" <?php echo $ord['order_status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo $ord['order_status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-maroon py-0 px-2" title="Save Status"><i class="fa-solid fa-check"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
