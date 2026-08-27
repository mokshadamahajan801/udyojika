<?php
$page_title = "Order Details - Maker Portal";
$page_header = "Order Fulfillment & Dispatch";
$page_subheader = "View customer delivery address and pack instructions";
require_once __DIR__ . '/includes/header.php';

$order_id = $_GET['id'] ?? 1;
$order = get_order_by_number($order_id);
if (!$order) {
    echo "<div class='alert alert-danger'>Order not found.</div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$status_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['new_status'] ?? '';
    $order['order_status'] = $new_status;
    $status_msg = "Order status updated to " . strtoupper($new_status) . " successfully! Courier tracking updated.";
}
?>

<?php if (!empty($status_msg)): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="fa-solid fa-circle-check fs-4"></i>
        <div><?php echo $status_msg; ?></div>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="orders.php" class="btn btn-outline-secondary btn-sm mb-2"><i class="fa-solid fa-arrow-left me-1"></i> Back to Orders</a>
        <h4 class="font-serif fw-bold text-maroon-900 mb-0">Order <?php echo $order['order_number']; ?></h4>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-maroon btn-sm" onclick="window.print();"><i class="fa-solid fa-print me-1"></i> Print Packing Slip</button>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Order Items Card -->
        <div class="dashboard-card mb-4">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-boxes-packing text-maroon-800"></i> Items to Pack & Ship</h5>
            </div>
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Item Description</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $it): ?>
                            <tr>
                                <td>
                                    <strong class="text-dark d-block"><?php echo htmlspecialchars($it['product_name']); ?></strong>
                                    <small class="text-muted">Sold by: <?php echo htmlspecialchars($it['seller_name']); ?></small>
                                </td>
                                <td>₹<?php echo $it['price']; ?></td>
                                <td><span class="badge bg-light text-dark border px-2 py-1"><?php echo $it['quantity']; ?></span></td>
                                <td class="fw-bold text-maroon-900">₹<?php echo number_format($it['subtotal']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small">Payment Mode: <strong><?php echo $order['payment_method']; ?></strong> (<?php echo $order['payment_status']; ?>)</span>
                <div class="text-end">
                    <span class="text-muted small d-block">Grand Total</span>
                    <strong class="fs-5 text-maroon-900">₹<?php echo number_format($order['total_amount']); ?></strong>
                </div>
            </div>
        </div>

        <?php if (!empty($order['notes'])): ?>
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5 class="dashboard-card-title"><i class="fa-regular fa-note-sticky text-warning"></i> Customer Special Note</h5>
                </div>
                <div class="p-3">
                    <p class="small text-secondary mb-0">"<?php echo htmlspecialchars($order['notes']); ?>"</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Customer & Status Side -->
    <div class="col-lg-4">
        <!-- Customer & Shipping Info -->
        <div class="dashboard-card mb-4">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-location-dot text-danger"></i> Shipping Address</h5>
            </div>
            <div class="p-3 small text-secondary">
                <strong class="text-dark d-block mb-1 fs-6"><?php echo htmlspecialchars($order['customer_name']); ?></strong>
                <p class="mb-2"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fa-solid fa-phone text-muted"></i>
                    <a href="tel:<?php echo $order['customer_phone']; ?>" class="text-decoration-none text-dark fw-bold"><?php echo htmlspecialchars($order['customer_phone']); ?></a>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-regular fa-envelope text-muted"></i>
                    <span><?php echo htmlspecialchars($order['customer_email']); ?></span>
                </div>
            </div>
        </div>

        <!-- Fulfillment Status Update Box -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-truck-fast text-terracotta"></i> Dispatch Progress</h5>
            </div>
            <div class="p-3">
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Current State</span>
                    <span class="badge-status-<?php echo $order['order_status']; ?> fs-6"><?php echo ucfirst($order['order_status']); ?></span>
                </div>

                <form action="order-details.php?id=<?php echo $order['id']; ?>" method="POST">
                    <label class="form-label small fw-bold">Update Order Status</label>
                    <select name="new_status" class="form-select form-select-sm mb-3">
                        <option value="pending" <?php echo $order['order_status'] === 'pending' ? 'selected' : ''; ?>>Pending (Order Placed)</option>
                        <option value="processing" <?php echo $order['order_status'] === 'processing' ? 'selected' : ''; ?>>Processing / In Kitchen</option>
                        <option value="completed" <?php echo $order['order_status'] === 'completed' ? 'selected' : ''; ?>>Dispatched / Delivered</option>
                        <option value="cancelled" <?php echo $order['order_status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>

                    <button type="submit" class="btn btn-maroon btn-sm w-100 fw-bold">
                        <i class="fa-solid fa-check me-1"></i> Update Status & Notify Buyer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
