<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$seller_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$seller = get_seller_by_id($seller_id, $pdo);
$page_title = $seller['business_name'] . " - " . $seller['owner_name'];

// Get all products made by this seller
$all_products = get_all_products($pdo);
$seller_products = array_filter($all_products, function($p) use ($seller_id) {
    return (int)$p['seller_id'] === (int)$seller_id;
});

require_once __DIR__ . '/includes/header.php';
?>

<!-- Seller Hero Banner -->
<div class="position-relative" style="height: 280px; background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.6)), url('<?php echo htmlspecialchars($seller['banner_image']); ?>'); background-size: cover; background-position: center;">
    <div class="container h-100 d-flex align-items-end pb-4">
        <div class="d-flex align-items-center gap-3 text-white">
            <img src="<?php echo htmlspecialchars($seller['avatar']); ?>" class="rounded-circle border border-4 border-white shadow-lg" style="width: 100px; height: 100px; object-fit: cover;" alt="<?php echo htmlspecialchars($seller['owner_name']); ?>">
            <div>
                <span class="badge bg-warning text-dark fw-bold mb-1"><i class="fa-solid fa-certificate me-1"></i> Verified Udyojika Maker</span>
                <h1 class="font-serif fw-bold mb-0 text-white"><?php echo htmlspecialchars($seller['business_name']); ?></h1>
                <p class="mb-0 text-light opacity-90">By <strong><?php echo htmlspecialchars($seller['owner_name']); ?></strong> &bull; <i class="fa-solid fa-location-dot me-1"></i> <?php echo htmlspecialchars($seller['location']); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Story & Bio Sidebar -->
        <div class="col-lg-4">
            <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                <h5 class="font-serif fw-bold text-maroon-800 mb-3">About the Maker's Journey</h5>
                <p class="text-secondary small leading-relaxed mb-4">
                    <?php echo nl2br(htmlspecialchars($seller['full_story'])); ?>
                </p>

                <div class="border-top pt-3 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Member Since:</span>
                        <span class="fw-bold small text-dark"><?php echo htmlspecialchars($seller['joined_year']); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Overall Rating:</span>
                        <span class="star-rating small"><i class="fa-solid fa-star"></i> <?php echo $seller['rating']; ?> (<?php echo $seller['review_count']; ?> reviews)</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Specialty:</span>
                        <span class="fw-semibold small text-terracotta"><?php echo htmlspecialchars($seller['specialty']); ?></span>
                    </div>
                </div>

                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $seller['whatsapp']); ?>?text=Hello%20<?php echo urlencode($seller['owner_name']); ?>,%20I%20saw%20your%20store%20on%20Udyojika" target="_blank" class="btn btn-success w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-brands fa-whatsapp fs-5"></i> Chat with <?php echo htmlspecialchars($seller['owner_name']); ?>
                </a>
            </div>
        </div>

        <!-- Products List -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="font-serif fw-bold text-maroon-900 mb-0">Homemade Products by <?php echo htmlspecialchars($seller['business_name']); ?></h4>
                <span class="badge bg-cream-200 text-maroon-800 border px-3 py-2"><?php echo count($seller_products); ?> Items Available</span>
            </div>

            <div class="row g-4">
                <?php foreach ($seller_products as $product): ?>
                    <div class="col-12 col-md-6">
                        <div class="product-card">
                            <div class="img-container">
                                <a href="product-details.php?slug=<?php echo urlencode($product['slug']); ?>">
                                    <img src="<?php echo htmlspecialchars($product['images'][0]); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </a>
                            </div>
                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <h6 class="fw-bold mb-1">
                                    <a href="product-details.php?slug=<?php echo urlencode($product['slug']); ?>" class="text-dark text-decoration-none">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </a>
                                </h6>
                                <div class="mt-auto d-flex align-items-center justify-content-between pt-3 border-top">
                                    <div>
                                        <span class="fs-5 fw-bold text-maroon-800">₹<?php echo number_format($product['price']); ?></span>
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">/ <?php echo htmlspecialchars($product['unit']); ?></small>
                                    </div>
                                    <button
                                            type="button"
                                            class="btn btn-maroon btn-sm px-3"
                                            onclick="window.addToCart(
        '<?php echo $product['id']; ?>',
        '<?php echo addslashes($product['name']); ?>',
        <?php echo $product['price']; ?>,
        '<?php echo !empty($product['images'][0]) ? htmlspecialchars($product['images'][0], ENT_QUOTES) : ''; ?>',
        '<?php echo addslashes($product['seller_name']); ?>',
        '<?php echo addslashes($product['unit']); ?>',
        1
    )">
                                            <i class="fa-solid fa-plus me-1"></i> Add
                                        </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
