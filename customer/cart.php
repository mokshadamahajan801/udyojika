<?php
$page_title = "My Cart - Customer Portal";
$page_header = "Your Shopping Basket";
$page_subheader = "Review handmade items, select delivery address and proceed to checkout";
require_once __DIR__ . '/includes/header.php';

$cart_items = [
    [
        'id' => 1,
        'name' => 'Authentic Bhajanichi Chakli (Traditional Recipe)',
        'seller_name' => 'Annapurna Swaad',
        'unit' => '500g box',
        'price' => 320,
        'qty' => 2,
        'image' => 'https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?q=80&w=800&auto=format&fit=crop'
    ],
    [
        'id' => 4,
        'name' => 'Hand-Poured Mogra & Sandalwood Soy Candle',
        'seller_name' => 'Sugandham Fragrance',
        'unit' => 'jar (220g - 45 hrs burn)',
        'price' => 499,
        'qty' => 1,
        'image' => 'https://images.unsplash.com/photo-1603006905003-be475563bc59?q=80&w=800&auto=format&fit=crop'
    ]
];

$subtotal = (320 * 2) + (499 * 1); // 640 + 499 = 1139
$discount = 100;
$shipping = 0; // free above 499
$total = $subtotal - $discount + $shipping;
?>

<div class="row g-4">
    <!-- Items in Cart -->
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-cart-shopping text-maroon-800"></i> Items in Basket (2)</h5>
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
                                    <button class="btn btn-outline-secondary" type="button">-</button>
                                    <input type="text" class="form-control text-center" value="<?php echo $item['qty']; ?>" readonly>
                                    <button class="btn btn-outline-secondary" type="button">+</button>
                                </div>
                                <div class="text-end" style="min-width: 80px;">
                                    <strong class="text-maroon-900 fs-6">₹<?php echo $item['price'] * $item['qty']; ?></strong>
                                </div>
                                <button class="btn btn-light btn-sm text-danger border" title="Remove" onclick="alert('Item removed from cart');"><i class="fa-solid fa-trash-can"></i></button>
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

                <button class="btn btn-maroon w-100 py-2 fw-bold shadow-sm" onclick="alert('Proceeding to Razorpay / UPI Secure Payment Gateway for ₹<?php echo $total; ?>');">
                    Proceed to Checkout <i class="fa-solid fa-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
