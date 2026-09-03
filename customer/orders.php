<?php
$page_title = "My Orders - Udyojika";
$page_header = "My Purchase History";
$page_subheader = "View status, receipts and tracking updates for all your orders";
require_once __DIR__ . '/includes/header.php';

$all_orders = get_all_orders($pdo);
$my_orders = array_filter($all_orders, fn($o) => (int)$o['customer_id'] === (int)$customer_id);
?>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-box-open text-maroon-800"></i> All Orders (<?php echo count($my_orders); ?>)</h5>
        <a href="../products.php" class="btn btn-maroon btn-sm"><i class="fa-solid fa-plus me-1"></i> Continue Shopping</a>
    </div>
    <div class="p-3">
        <div class="d-flex flex-column gap-3">
            <?php if (empty($my_orders)): ?>
                <div class="text-center py-5">
                    <i class="fa-solid fa-bag-shopping fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="font-serif fw-bold text-maroon-900">No Orders Found</h5>
                    <p class="text-muted small">Explore our women entrepreneurs' freshly made products today.</p>
                    <a href="../products.php" class="btn btn-maroon btn-sm">Explore Products</a>
                </div>
            <?php else: ?>
                <?php foreach ($my_orders as $ord): ?>
                    <div class="p-3 p-md-4 border rounded-4 bg-white shadow-sm">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 pb-3 mb-3 border-bottom">
                            <div>
                                <strong class="fs-6 text-maroon-900 d-block">Order #<?php echo $ord['order_number']; ?></strong>
                                <small class="text-muted">Placed on <?php echo date('d M Y, h:i A', strtotime($ord['created_at'])); ?></small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge-status-<?php echo $ord['order_status']; ?>"><?php echo ucfirst($ord['order_status']); ?></span>
                                <span class="badge bg-light text-dark border"><?php echo $ord['payment_method']; ?> (<?php echo $ord['payment_status']; ?>)</span>
                            </div>
                        </div>

                        <div class="row align-items-center g-3">
                            <div class="col-md-7">
                                <div class="d-flex flex-column gap-2">
                                    <?php foreach ($ord['items'] as $it): ?>
                                        <div class="d-flex justify-content-between align-items-center small">
                                            <div>
                                                <strong class="text-dark"><?php echo $it['quantity']; ?>x</strong> <?php echo htmlspecialchars($it['product_name']); ?>
                                                <span class="text-muted">by <?php echo htmlspecialchars($it['seller_name']); ?></span>
                                            </div>
                                            <span class="fw-bold text-maroon-800">₹<?php echo number_format($it['subtotal']); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="col-md-5 text-md-end pt-3 pt-md-0 border-top border-md-top-0">
                                <div class="mb-2">
                                    <small class="text-muted d-block">Order Total:</small>
                                    <strong class="fs-5 text-maroon-900">₹<?php echo number_format($ord['total_amount']); ?></strong>
                                </div>
                                <div class="d-flex justify-content-md-end gap-2">
                                    <a href="order-details.php?id=<?php echo $ord['id']; ?>" class="btn btn-sm btn-outline-maroon">
                                        <i class="fa-solid fa-file-invoice me-1"></i> View Receipt
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
