<?php
$page_title = "Order Details & Receipt - Customer Portal";
$page_header = "Order Receipt";
$page_subheader = "View detailed invoice and shipping updates";
require_once __DIR__ . '/includes/header.php';

$order_id = $_GET['id'] ?? 1;
$order = get_order_by_number($order_id);
if (!$order) {
    echo "<div class='alert alert-danger'>Order not found.</div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="orders.php" class="btn btn-outline-secondary btn-sm mb-2"><i class="fa-solid fa-arrow-left me-1"></i> Back to All Orders</a>
        <h4 class="font-serif fw-bold text-maroon-900 mb-0">Receipt #<?php echo $order['order_number']; ?></h4>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-maroon btn-sm" onclick="window.print();"><i class="fa-solid fa-print me-1"></i> Print Invoice</button>
    </div>
</div>

<div class="row g-4">
    <!-- Invoice Column -->
    <div class="col-lg-8">
        <div class="dashboard-card mb-4">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-file-invoice text-maroon-800"></i> Purchased Items</h5>
                <span class="badge-status-<?php echo $order['order_status']; ?>"><?php echo ucfirst($order['order_status']); ?></span>
            </div>
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Item Description</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $it): ?>
                            <tr>
                                <td>
                                    <strong class="text-dark d-block"><?php echo htmlspecialchars($it['product_name']); ?></strong>
                                    <small class="text-terracotta">Crafted by: <?php echo htmlspecialchars($it['seller_name']); ?></small>
                                </td>
                                <td>₹<?php echo $it['price']; ?></td>
                                <td><span class="badge bg-light text-dark border"><?php echo $it['quantity']; ?></span></td>
                                <td class="fw-bold text-maroon-900">₹<?php echo number_format($it['subtotal']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Price Breakdown -->
            <div class="p-4 bg-light border-top">
                <div class="row justify-content-end">
                    <div class="col-md-6 small">
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted">Subtotal:</span>
                            <strong class="text-dark">₹<?php echo number_format($order['subtotal']); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted">Maker Festive Discount:</span>
                            <strong class="text-success">-₹<?php echo number_format($order['discount'] ?? 0); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted">Delivery Charges:</span>
                            <strong class="text-dark"><?php echo ($order['shipping_fee'] > 0) ? '₹' . $order['shipping_fee'] : 'FREE (Above ₹499)'; ?></strong>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-top mt-2 fs-6">
                            <span class="fw-bold text-maroon-900">Total Paid:</span>
                            <strong class="fw-bold text-maroon-900">₹<?php echo number_format($order['total_amount']); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shipping & Delivery Side -->
    <div class="col-lg-4">
        <!-- Delivery Details -->
        <div class="dashboard-card mb-4">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-location-dot text-danger"></i> Delivery Details</h5>
            </div>
            <div class="p-3 small text-secondary">
                <strong class="text-dark d-block mb-1 fs-6"><?php echo htmlspecialchars($order['customer_name']); ?></strong>
                <p class="mb-2"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fa-solid fa-phone text-muted"></i>
                    <span><?php echo htmlspecialchars($order['customer_phone']); ?></span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-credit-card text-muted"></i>
                    <span>Payment: <strong><?php echo $order['payment_method']; ?></strong> (<?php echo $order['payment_status']; ?>)</span>
                </div>
            </div>
        </div>

        <!-- Direct Maker Support -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-headset text-terracotta"></i> Maker Assistance</h5>
            </div>
            <div class="p-3 small text-secondary">
                <p class="mb-3">Need to customize or check on dispatch date? Connect directly with your woman maker:</p>
                <a href="../contact.php" class="btn btn-outline-maroon btn-sm w-100 mb-2"><i class="fa-solid fa-envelope me-1"></i> Send Support Message</a>
                <a href="https://wa.me/919822012345" target="_blank" class="btn btn-success btn-sm w-100"><i class="fa-brands fa-whatsapp me-1"></i> WhatsApp Helpline</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
