<?php
require_once __DIR__ . '/includes/db.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : 'authentic-crunchy-bhajani-chakli';
$product = get_product_by_slug($slug, $pdo);
$seller = get_seller_by_id($product['seller_id'], $pdo);
$page_title = $product['name'];

require_once __DIR__ . '/includes/header.php';
?>

<!-- Breadcrumb -->
<div class="bg-cream-100 py-3 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item"><a href="products.php" class="text-decoration-none text-muted">Shop</a></li>
                <li class="breadcrumb-item"><a href="products.php?category=<?php echo urlencode($product['category_slug']); ?>" class="text-decoration-none text-muted"><?php echo htmlspecialchars($product['category']); ?></a></li>
                <li class="breadcrumb-item active text-maroon-800 fw-bold" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        
        <!-- Product Image Gallery -->
        <div class="col-lg-6">
            <div class="sticky-top" style="top: 100px;">
                <div class="bg-white rounded-4 overflow-hidden shadow-sm border mb-3 p-2 text-center" style="max-height: 480px;">
                    <img id="main-product-gallery-img" src="<?php echo htmlspecialchars($product['images'][0]); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid rounded-3" style="max-height: 460px; width: 100%; object-fit: cover;">
                </div>
                <?php if (count($product['images']) > 1): ?>
                    <div class="d-flex gap-2">
                        <?php foreach ($product['images'] as $idx => $img): ?>
                            <button type="button" class="btn p-1 gallery-thumb-btn border rounded-3 <?php echo $idx === 0 ? 'active border-maroon-800' : ''; ?>" data-img-src="<?php echo htmlspecialchars($img); ?>" style="width: 80px; height: 80px;">
                                <img src="<?php echo htmlspecialchars($img); ?>" class="w-100 h-100 object-fit-cover rounded" alt="Thumb">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Purchase & Info Column -->
        <div class="col-lg-6">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="badge bg-cream-200 text-maroon-800 fw-bold px-3 py-2 border">
                    <i class="fa-solid fa-tag me-1"></i> <?php echo htmlspecialchars($product['category']); ?>
                </span>
                <?php if (!empty($product['badge'])): ?>
                    <span class="badge bg-danger text-white px-3 py-2 fw-bold text-uppercase"><?php echo htmlspecialchars($product['badge']); ?></span>
                <?php endif; ?>
            </div>

            <h1 class="font-serif fw-bold text-maroon-900 mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>

            <!-- Ratings & Reviews Count -->
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div class="star-rating fs-5">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star-half-stroke"></i>
                    <span class="fw-bold text-dark ms-2"><?php echo $product['rating']; ?></span>
                </div>
                <span class="text-muted small">(<?php echo $product['review_count']; ?> verified buyer reviews)</span>
                <span class="badge bg-success-subtle text-success border border-success"><i class="fa-solid fa-circle-check me-1"></i> In Stock (<?php echo $product['stock_quantity']; ?> left)</span>
            </div>

            <!-- Price -->
            <div class="mb-4">
                <div class="d-flex align-items-baseline gap-3">
                    <span class="display-6 fw-bold text-maroon-900 font-serif">₹<?php echo number_format($product['price']); ?></span>
                    <?php if (!empty($product['original_price'])): ?>
                        <span class="fs-5 text-muted text-decoration-line-through">₹<?php echo number_format($product['original_price']); ?></span>
                        <span class="badge bg-warning text-dark fw-bold">SAVE <?php echo round((($product['original_price'] - $product['price']) / $product['original_price']) * 100); ?>%</span>
                    <?php endif; ?>
                </div>
                <small class="text-muted">Unit: <strong><?php echo htmlspecialchars($product['unit']); ?></strong> | Inclusive of all domestic taxes</small>
            </div>

            <!-- Quick Description -->
            <p class="text-secondary leading-relaxed mb-4">
                <?php echo htmlspecialchars($product['description']); ?>
            </p>

            <!-- Quantity and Action Buttons -->
            <div class="bg-light p-4 rounded-4 border mb-4">
                <div class="row g-3 align-items-center mb-3">
                    <div class="col-sm-4">
                        <label class="form-label small fw-bold text-muted mb-1">Select Quantity:</label>
                        <div class="input-group qty-control-group">
                            <button class="btn btn-outline-secondary qty-btn-minus" type="button"><i class="fa-solid fa-minus"></i></button>
                            <input type="number" class="form-control text-center qty-input fw-bold" id="productDetailQty" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                            <button class="btn btn-outline-secondary qty-btn-plus" type="button"><i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="col-sm-8 d-flex gap-2 align-self-end">
                        <button type="button" class="btn btn-maroon w-100 py-2 fw-bold d-flex align-items-center justify-content-center gap-2" 
                                onclick="window.addToCart('<?php echo $product['id']; ?>', '<?php echo addslashes($product['name']); ?>', <?php echo $product['price']; ?>, '<?php echo $product['images'][0]; ?>', '<?php echo addslashes($product['seller_name']); ?>', '<?php echo addslashes($product['unit']); ?>', document.getElementById('productDetailQty').value)">
                            <i class="fa-solid fa-bag-shopping"></i> Add to Cart
                        </button>
                        <button type="button" class="btn btn-outline-danger p-2 px-3 rounded-pill" onclick="window.toggleWishlist('<?php echo $product['id']; ?>', '<?php echo addslashes($product['name']); ?>')">
                            <i class="fa-regular fa-heart fs-5"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 small text-muted">
                    <i class="fa-solid fa-clock text-terracotta"></i>
                    <span><strong>Preparation:</strong> <?php echo htmlspecialchars($product['prep_time']); ?></span>
                </div>
            </div>

            <!-- Meet The Maker Card -->
            <div class="p-4 bg-cream-100 rounded-4 border mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="<?php echo htmlspecialchars($seller['avatar']); ?>" class="rounded-circle shadow-sm" style="width: 56px; height: 56px; object-fit: cover;" alt="<?php echo htmlspecialchars($seller['owner_name']); ?>">
                    <div>
                        <span class="badge bg-success-subtle text-success border border-success mb-1 small"><i class="fa-solid fa-shield-halved me-1"></i> Verified Maker</span>
                        <h5 class="fw-bold mb-0 text-maroon-900"><?php echo htmlspecialchars($seller['business_name']); ?></h5>
                        <small class="text-muted">By <strong><?php echo htmlspecialchars($seller['owner_name']); ?></strong> &bull; <?php echo htmlspecialchars($seller['location']); ?></small>
                    </div>
                </div>
                <p class="small text-secondary mb-3"><?php echo htmlspecialchars($seller['short_bio']); ?></p>
                <div class="d-flex gap-2">
                    <a href="business-details.php?id=<?php echo $seller['id']; ?>" class="btn btn-outline-maroon btn-sm">View Seller Profile & Other Items</a>
                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $seller['whatsapp']); ?>?text=Hello%20<?php echo urlencode($seller['owner_name']); ?>,%20I%20am%20interested%20in%20<?php echo urlencode($product['name']); ?>" target="_blank" class="btn btn-success btn-sm">
                        <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp Maker
                    </a>
                </div>
            </div>

            <!-- Ingredients & Features -->
            <?php if (!empty($product['ingredients'])): ?>
                <div class="mb-4">
                    <h5 class="fw-bold text-maroon-800 font-serif mb-2">Pure Ingredients Used</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($product['ingredients'] as $ing): ?>
                            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill"><i class="fa-solid fa-leaf text-success me-1"></i> <?php echo htmlspecialchars($ing); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
