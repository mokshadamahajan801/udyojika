<?php
$page_title = "Shopping Cart - Udyojika";
require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-cream-100 py-4 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-maroon-800 fw-bold" aria-current="page">Your Cart</li>
            </ol>
        </nav>
        <h2 class="font-serif fw-bold text-maroon-900 mb-0">Your Homemade Shopping Cart</h2>
    </div>
</div>

<div class="container py-5">
    <div id="cart-empty-view" style="display: none;" class="text-center py-5 bg-white rounded-4 border p-5">
        <div class="display-3 text-muted mb-3"><i class="fa-solid fa-basket-shopping"></i></div>
        <h3 class="font-serif fw-bold text-maroon-900 mb-2">Your Cart is Currently Empty</h3>
        <p class="text-muted mb-4">Discover authentic homemade delicacies and artisanal crafts crafted by women makers.</p>
        <a href="products.php" class="btn btn-maroon btn-lg px-4">Start Shopping</a>
    </div>

    <div id="cart-content-view" class="row g-4">
        <!-- Cart Items List -->
        <div class="col-lg-8">
            <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="font-serif fw-bold text-maroon-800 mb-0">Cart Items (<span id="cart-item-count-label">0</span>)</h5>
                    <button type="button" class="btn btn-link text-danger text-decoration-none btn-sm p-0" onclick="clearFullCart()">Clear Cart</button>
                </div>

                <div id="cart-items-table" class="d-flex flex-column gap-3">
                    <!-- Injected by JavaScript -->
                </div>
            </div>

            <!-- Custom Maker Note -->
            <div class="bg-white p-4 rounded-4 shadow-sm border">
                <h6 class="fw-bold text-maroon-800 mb-2"><i class="fa-regular fa-message me-2"></i> Note for the Home Maker / Custom Request</h6>
                <p class="small text-muted mb-2">Have a preference for less spice, sugar-free, or a handwritten gift note? Let the maker know:</p>
                <textarea class="form-control" rows="2" placeholder="e.g. Please pack in festive boxes with less chilli powder for elder parents..."></textarea>
            </div>
        </div>

        <!-- Order Summary Column -->
        <div class="col-lg-4">
            <div class="bg-white p-4 rounded-4 shadow-sm border sticky-top" style="top: 100px;">
                <h5 class="font-serif fw-bold text-maroon-900 mb-3 pb-2 border-bottom">Order Summary</h5>
                
                <!-- Coupon Box -->
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Promo / Discount Code</label>
                    <div class="input-group">
                        <input type="text" id="couponCodeInput" class="form-control text-uppercase" placeholder="Try: UDYOJIKA10">
                        <button class="btn btn-outline-maroon" type="button" onclick="applyDiscountCoupon()">Apply</button>
                    </div>
                    <small id="couponMessage" class="d-block mt-1"></small>
                </div>

                <div class="d-flex flex-column gap-2 mb-3 pt-2 border-top">
                    <div class="d-flex justify-content-between text-muted">
                        <span>Items Subtotal</span>
                        <span class="fw-semibold text-dark">₹<span id="summary-subtotal">0</span></span>
                    </div>
                    <div class="d-flex justify-content-between text-muted">
                        <span>Estimated Shipping</span>
                        <span class="text-success fw-semibold" id="summary-shipping">FREE</span>
                    </div>
                    <div class="d-flex justify-content-between text-success" id="discount-row" style="display: none !important;">
                        <span>Coupon Discount</span>
                        <span class="fw-bold">-₹<span id="summary-discount">0</span></span>
                    </div>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>Direct to Maker Support</span>
                        <span class="badge bg-cream-200 text-maroon-800 border">100% Payout</span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top mb-4">
                    <span class="fs-5 fw-bold text-maroon-900 font-serif">Total Amount</span>
                    <span class="fs-4 fw-bold text-maroon-900 font-serif">₹<span id="summary-total">0</span></span>
                </div>

                <button type="button" class="btn btn-maroon btn-lg w-100 py-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                    Proceed to Secure Checkout <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>

                <div class="text-center mt-3 small text-muted">
                    <i class="fa-solid fa-lock me-1 text-success"></i> 256-Bit Encrypted & Safe Payments
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-cream-100 border-bottom py-3">
                <h5 class="modal-title font-serif fw-bold text-maroon-900" id="checkoutModalLabel">
                    <i class="fa-solid fa-shield-halved text-success me-2"></i> Complete Your Homemade Order
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="checkoutForm" onsubmit="handlePlaceOrder(event)">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name *</label>
                            <input type="text" class="form-control" required value="Aditi Sharma">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Mobile Number (for delivery SMS) *</label>
                            <input type="tel" class="form-control" required value="+91 98765 43210">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Delivery Address *</label>
                            <textarea class="form-control" rows="2" required>Flat 402, Sai Heritage, Prabhat Road, Pune, Maharashtra - 411004</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Choose Payment Method *</label>
                            <div class="d-flex flex-column gap-2">
                                <label class="p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer bg-light">
                                    <input type="radio" name="payment_mode" value="upi" checked>
                                    <div>
                                        <strong class="d-block text-dark"><i class="fa-solid fa-qrcode text-primary me-2"></i> Instant UPI / GPay / PhonePe</strong>
                                        <small class="text-muted">Pay directly using any UPI App</small>
                                    </div>
                                </label>
                                <label class="p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer">
                                    <input type="radio" name="payment_mode" value="cod">
                                    <div>
                                        <strong class="d-block text-dark"><i class="fa-solid fa-money-bill-wave text-success me-2"></i> Cash on Delivery (COD)</strong>
                                        <small class="text-muted">Pay cash when fresh order arrives</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-maroon px-4 fw-bold">Confirm & Place Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let discountPercent = 0;

function renderCart() {
    const cart = window.udyojika.getCart();
    const emptyView = document.getElementById('cart-empty-view');
    const contentView = document.getElementById('cart-content-view');
    const table = document.getElementById('cart-items-table');
    const countLabel = document.getElementById('cart-item-count-label');

    if (!cart || cart.length === 0) {
        emptyView.style.display = 'block';
        contentView.style.display = 'none';
        return;
    }

    emptyView.style.display = 'none';
    contentView.style.display = 'flex';

    let subtotal = 0;
    let html = '';

    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        html += `
            <div class="d-flex align-items-center gap-3 p-3 border rounded-3">
                <img src="${item.image}" class="rounded-3 shadow-sm" style="width: 70px; height: 70px; object-fit: cover;" alt="${item.name}">
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-0 text-dark">${item.name}</h6>
                    <small class="text-muted d-block">By <strong>${item.sellerName}</strong> &bull; ₹${item.price} / ${item.unit}</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" onclick="updateItemQty(${index}, -1)">-</button>
                    <span class="fw-bold px-2">${item.quantity}</span>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" onclick="updateItemQty(${index}, 1)">+</button>
                </div>
                <div class="text-end" style="min-width: 80px;">
                    <span class="fw-bold text-maroon-800">₹${itemTotal}</span>
                </div>
                <button type="button" class="btn btn-link text-danger p-0" title="Remove" onclick="removeCartItem(${index})">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        `;
    });

    table.innerHTML = html;
    countLabel.textContent = cart.length;

    // Recalculate summary
    const discountAmount = Math.round((subtotal * discountPercent) / 100);
    const shipping = subtotal > 499 ? 0 : 40;
    const finalTotal = subtotal - discountAmount + shipping;

    document.getElementById('summary-subtotal').textContent = subtotal.toLocaleString('en-IN');
    document.getElementById('summary-discount').textContent = discountAmount.toLocaleString('en-IN');
    document.getElementById('summary-shipping').textContent = shipping === 0 ? 'FREE' : '₹' + shipping;
    document.getElementById('summary-total').textContent = finalTotal.toLocaleString('en-IN');

    const discountRow = document.getElementById('discount-row');
    if (discountAmount > 0) {
        discountRow.style.setProperty('display', 'flex', 'important');
    } else {
        discountRow.style.setProperty('display', 'none', 'important');
    }
}

function updateItemQty(index, change) {
    const cart = window.udyojika.getCart();
    if (cart[index]) {
        cart[index].quantity += change;
        if (cart[index].quantity <= 0) {
            cart.splice(index, 1);
        }
        window.udyojika.saveCart(cart);
        renderCart();
    }
}

function removeCartItem(index) {
    const cart = window.udyojika.getCart();
    cart.splice(index, 1);
    window.udyojika.saveCart(cart);
    renderCart();
    window.udyojika.showToast('Item removed from cart.', 'info', 'Cart Updated');
}

function clearFullCart() {
    if (confirm('Are you sure you want to clear your entire cart?')) {
        window.udyojika.saveCart([]);
        renderCart();
    }
}

function applyDiscountCoupon() {
    const code = document.getElementById('couponCodeInput').value.trim().toUpperCase();
    const msg = document.getElementById('couponMessage');
    if (code === 'UDYOJIKA10' || code === 'WOMENPOWER') {
        discountPercent = 10;
        msg.className = 'text-success small fw-bold';
        msg.textContent = '🎉 Coupon Applied! 10% Women Empowerment Discount unlocked.';
        renderCart();
    } else {
        discountPercent = 0;
        msg.className = 'text-danger small';
        msg.textContent = 'Invalid coupon code. Try: UDYOJIKA10';
        renderCart();
    }
}

function handlePlaceOrder(e) {
    e.preventDefault();
    const modalEl = document.getElementById('checkoutModal');
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) modal.hide();
    
    window.udyojika.saveCart([]);
    renderCart();
    window.udyojika.showToast('Thank you! Your order has been placed directly with the women makers. You will receive an SMS confirmation.', 'success', 'Order Confirmed! 🎉');
}

document.addEventListener('DOMContentLoaded', renderCart);
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
