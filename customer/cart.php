<?php

require_once __DIR__ . '/includes/auth.php';
$page_title = "My Cart - Customer Portal";
$page_header = "Your Shopping Basket";
$page_subheader = "Review handmade items, select delivery address and proceed to checkout";

if (isset($_GET['update_id'], $_GET['quantity'])) {
    $update_id = (int)$_GET['update_id'];
    $quantity = max(1, (int)$_GET['quantity']);

    $stmt = $pdo->prepare("
        UPDATE cart_items
        SET quantity = ?
        WHERE id = ? AND customer_id = ?
    ");

    $stmt->execute([
        $quantity,
        $update_id,
        $customer_id
    ]);

    header("Location: cart.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_cart_id'])) {
    $remove_id = (int)$_POST['remove_cart_id'];

    $stmt = $pdo->prepare("
        DELETE FROM cart_items
        WHERE id = ? AND customer_id = ?
    ");

    $stmt->execute([
        $remove_id,
        $customer_id
    ]);

    header("Location: cart.php");
    exit;
}
require_once __DIR__ . '/includes/header.php';

$cart_items = get_customer_cart($pdo, $customer_id);

$subtotal = 0;

foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['qty'];
}

$discount = 0;

$shipping = ($subtotal >= 499 || $subtotal == 0) ? 0 : 50;

$total = $subtotal - $discount + $shipping;
?>

<div class="row g-4">
    <!-- Items in Cart -->
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-cart-shopping text-maroon-800"></i> Items in Basket (<?php echo count($cart_items); ?>)</h5>
                <a href="../products.php" class="btn btn-outline-maroon btn-sm">+ Add More Items</a>
            </div>
            <div class="p-3">
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="p-3 border rounded-3 bg-white shadow-sm d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?php echo $item['image']; ?>" class="rounded-3 border" style="width: 64px; height: 64px; object-fit: cover;" alt="">
                                <div>
                                    <strong class="text-dark d-block"><?php echo htmlspecialchars($item['name']); ?></strong>
                                    <small class="text-muted d-block"><?php echo htmlspecialchars($item['unit']); ?> &bull; <span class="text-terracotta"><?php echo htmlspecialchars($item['seller_name']); ?></span></small>
                                    <span class="fw-bold text-maroon-800">₹<?php echo $item['price']; ?> each</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <button class="btn btn-outline-secondary" type="button"
                                            onclick="updateCartQuantity(<?php echo $item['cart_id']; ?>, <?php echo max(1, $item['qty'] - 1); ?>)">
                                        -
                                    </button>
                                    <input type="text" class="form-control text-center" value="<?php echo $item['qty']; ?>" readonly>
                                    <button class="btn btn-outline-secondary" type="button"
                                            onclick="updateCartQuantity(<?php echo $item['cart_id']; ?>, <?php echo $item['qty'] + 1; ?>)">
                                        +
                                    </button>
                                </div>
                                <div class="text-end" style="min-width: 80px;">
                                    <strong class="text-maroon-900 fs-6">₹<?php echo $item['price'] * $item['qty']; ?></strong>
                                </div>
                                <form method="POST" action="cart.php" class="d-inline">
                                    <input type="hidden" name="remove_cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <button type="submit" class="btn btn-light btn-sm text-danger border" title="Remove">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Summary & Checkout -->
    <div class="col-lg-4">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-receipt text-maroon-800"></i> Order Summary</h5>
            </div>
            <div class="p-4 small text-secondary">
                <div class="d-flex justify-content-between py-1">
                    <span>Subtotal:</span>
                    <strong class="text-dark">₹<?php echo number_format($subtotal); ?></strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Maker Festive Coupon:</span>
                    <strong class="text-success">-₹<?php echo number_format($discount); ?></strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Shipping Charges:</span>
                    <strong class="text-success">FREE Delivery</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-top mt-3 fs-5">
                    <strong class="text-maroon-900">Total Payable:</strong>
                    <strong class="text-maroon-900">₹<?php echo number_format($total); ?></strong>
                </div>

                <div class="p-3 bg-cream-100 rounded-3 border my-3">
                    <small class="d-block text-maroon-900 fw-bold mb-1"><i class="fa-solid fa-shield-heart text-danger me-1"></i> Direct 100% Payout</small>
                    <span style="font-size: 0.78rem;">Your entire payment goes straight to our women home entrepreneurs. Zero commission deducted.</span>
                </div>

                <a href="checkout.php" class="btn btn-maroon w-100 py-2 fw-bold shadow-sm">
                    Proceed to Checkout <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function updateCartQuantity(cartId, quantity) {
    if (quantity < 1) {
        quantity = 1;
    }

    window.location.href = "cart.php?update_id=" + cartId + "&quantity=" + quantity;
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
