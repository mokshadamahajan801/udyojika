<?php
$page_title = "Start Your Home Business | Become a Seller on Udyojika";
require_once __DIR__ . '/includes/header.php';

$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = htmlspecialchars($_POST['full_name'] ?? '');
    $business_name = htmlspecialchars($_POST['business_name'] ?? '');
    $category = htmlspecialchars($_POST['category'] ?? '');
    $success_message = "Thank you, {$full_name}! Your application for '{$business_name}' has been received. Our Women Entrepreneurship cell will contact you within 24 hours.";
}
?>

<!-- Hero Banner -->
<div class="bg-maroon-900 text-white py-5">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 mb-3">WOMEN HOME ENTREPRENEURS PROGRAM</span>
                <h1 class="display-5 font-serif fw-bold mb-3">Turn Your Kitchen & Craft Talent into a Thriving Business</h1>
                <p class="lead text-light opacity-75 mb-4">
                    Join over 5,000+ Indian homemakers who earn between ₹25,000 to ₹1,50,000+ every month by selling homemade snacks, sweets, pickles, jewellery, and traditional handicrafts.
                </p>
                <div class="d-flex flex-wrap gap-4 pt-2">
                    <div>
                        <h3 class="fw-bold text-warning font-serif mb-0">₹0</h3>
                        <small class="text-light opacity-75">Registration Fee</small>
                    </div>
                    <div class="vr bg-light"></div>
                    <div>
                        <h3 class="fw-bold text-warning font-serif mb-0">Doorstep</h3>
                        <small class="text-light opacity-75">Courier Pickup</small>
                    </div>
                    <div class="vr bg-light"></div>
                    <div>
                        <h3 class="fw-bold text-warning font-serif mb-0">Weekly</h3>
                        <small class="text-light opacity-75">Direct Bank Payouts</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="bg-white text-dark p-4 rounded-4 shadow-lg">
                    <h4 class="font-serif fw-bold text-maroon-900 mb-1">Quick Seller Registration</h4>
                    <p class="small text-muted mb-3">Get your homemade store live in less than 24 hours.</p>

                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-check fs-4"></i>
                            <div><?php echo $success_message; ?></div>
                        </div>
                    <?php endif; ?>

                    <form action="become-seller.php" method="POST" class="needs-validation" novalidate>
                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1">Your Full Name *</label>
                            <input type="text" name="full_name" class="form-control form-control-sm" required placeholder="e.g. Radhika Sharma">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1">Home Brand / Business Name *</label>
                            <input type="text" name="business_name" class="form-control form-control-sm" required placeholder="e.g. Radhika's Kitchen Masalas">
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small fw-bold mb-1">WhatsApp Number *</label>
                                <input type="tel" name="phone" class="form-control form-control-sm" required placeholder="+91 98765 43210">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold mb-1">City / Town *</label>
                                <input type="text" name="city" class="form-control form-control-sm" required placeholder="e.g. Pune / Nagpur">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1">Primary Category *</label>
                            <select name="category" class="form-select form-select-sm" required>
                                <option value="">Select Category</option>
                                <option value="Homemade Food & Snacks">Homemade Food & Snacks</option>
                                <option value="Pickles & Spices">Traditional Pickles & Spices</option>
                                <option value="Handmade Jewellery">Handmade Jewellery & Crafts</option>
                                <option value="Clothing & Kurtis">Custom Clothing & Sarees</option>
                                <option value="Candles & Aromas">Natural Candles & Aromas</option>
                                <option value="Organic Beauty">Herbal Beauty & Soaps</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold mb-1">Tell Us What You Make *</label>
                            <textarea name="description" class="form-control form-control-sm" rows="2" required placeholder="Describe your recipes, ingredients or handmade products..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold">
                            Submit Seller Application <i class="fa-solid fa-arrow-right ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Why Sell With Udyojika -->
<div class="container py-5">
    <div class="text-center max-w-700 mx-auto mb-5">
        <span class="text-terracotta fw-bold text-uppercase small tracking-wide">Why Join Udyojika</span>
        <h2 class="font-serif fw-bold text-maroon-900 mb-2">Designed Specifically for Women Homemakers</h2>
        <p class="text-muted">We remove all friction so you can focus 100% on making high quality homemade products.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 shadow-sm border h-100">
                <div class="bg-danger-subtle text-danger rounded-circle p-3 d-inline-flex mb-3 fs-4">
                    <i class="fa-solid fa-house-chimney-user"></i>
                </div>
                <h5 class="fw-bold mb-2">Work From Your Home</h5>
                <p class="text-secondary small mb-0">No need for expensive retail space. Prepare orders from your existing home kitchen or studio at your own flexible pace.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 shadow-sm border h-100">
                <div class="bg-warning-subtle text-warning rounded-circle p-3 d-inline-flex mb-3 fs-4">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
                <h5 class="fw-bold mb-2">Doorstep Logistics</h5>
                <p class="text-secondary small mb-0">Our courier partners pick up freshly packed items directly from your home and deliver safely across all Indian pin codes.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 shadow-sm border h-100">
                <div class="bg-success-subtle text-success rounded-circle p-3 d-inline-flex mb-3 fs-4">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
                <h5 class="fw-bold mb-2">Weekly Bank Settlements</h5>
                <p class="text-secondary small mb-0">Direct deposits to your bank account every Tuesday. Transparent order records and zero hidden transaction cuts.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
