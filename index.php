<?php

$page_title = "Homemade with Love | Empowering Women Home Entrepreneurs";

require_once __DIR__ . '/includes/header.php';

$products = get_all_products($pdo);
$categories = $categories_nav;
$sellers = get_sellers($pdo);

?>

<!-- Hero Banner Section -->
<section class="hero-section py-5">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-badge mb-3">
                    <i class="fa-solid fa-sparkles text-warning"></i>
                    <span>India's #1 Women Maker Marketplace</span>
                </div>
                <h1 class="display-4 fw-bold text-maroon-900 mb-3 lh-sm">
                    Homemade with Love, <br>
                    <span class="text-terracotta">Crafted by Women.</span>
                </h1>
                <p class="lead text-muted mb-4 pe-lg-4">
                    Discover authentic festive snacks, pure desi ghee sweets, hand-sculpted terracotta jewellery, organic handlooms and artisan candles made by skilled women right from their home kitchens & studios.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="products.php" class="btn btn-maroon btn-lg px-4 d-inline-flex align-items-center gap-2">
                        <span>Explore Homemade Shop</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="become-seller.php" class="btn btn-outline-maroon btn-lg px-4 d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-store text-terracotta"></i>
                        <span>Start Selling</span>
                    </a>
                </div>
                <div class="d-flex align-items-center gap-4 pt-2 border-top">
                    <div>
                        <h4 class="fw-bold text-maroon-800 mb-0 font-serif">5,000+</h4>
                        <small class="text-muted">Home Makers</small>
                    </div>
                    <div class="vr"></div>
                    <div>
                        <h4 class="fw-bold text-maroon-800 mb-0 font-serif">100%</h4>
                        <small class="text-muted">Pure & Authentic</small>
                    </div>
                    <div class="vr"></div>
                    <div>
                        <h4 class="fw-bold text-maroon-800 mb-0 font-serif">₹2.4 Cr+</h4>
                        <small class="text-muted">Directly Earned</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 position-relative">
                <div class="position-relative p-2">
                    <img src="https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?q=80&w=800&auto=format&fit=crop" 
                         alt="Fresh Homemade Snacks" 
                         class="img-fluid rounded-4 shadow-lg w-100" 
                         style="max-height: 480px; object-fit: cover;">
                    
                    <!-- Floating Seller Badge -->
                    <div class="position-absolute bottom-0 start-0 translate-middle-y bg-white p-3 rounded-4 shadow-lg d-flex align-items-center gap-3 ms-3 mb-2 border border-warning" style="max-width: 280px;">
                        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=150&auto=format&fit=crop" class="rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover;" alt="Sunita">
                        <div>
                            <h6 class="mb-0 fw-bold text-maroon-800">Sunita Kulkarni</h6>
                            <small class="text-muted d-block">Annapurna Swaad, Pune</small>
                            <span class="badge bg-success py-1 px-2"><i class="fa-solid fa-star text-warning me-1"></i> 4.9 (184 reviews)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Feature Pillars -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="pillar-card h-100">
                    <div class="pillar-icon bg-danger-subtle text-danger">
                        <i class="fa-solid fa-mortar-pestle"></i>
                    </div>
                    <h5 class="fw-bold mb-2">100% Homemade</h5>
                    <p class="text-muted small mb-0">Crafted in small home batches with authentic family recipes and zero artificial chemicals.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="pillar-card h-100">
                    <div class="pillar-icon bg-warning-subtle text-warning">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Direct Income to Women</h5>
                    <p class="text-muted small mb-0">Every purchase puts direct financial independence into the hands of talented homemakers.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="pillar-card h-100">
                    <div class="pillar-icon bg-success-subtle text-success">
                        <i class="fa-solid fa-certificate"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Quality & Hygiene Verified</h5>
                    <p class="text-muted small mb-0">Each home maker is vetted for pure raw ingredients, clean preparation, and taste standards.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="pillar-card h-100">
                    <div class="pillar-icon bg-primary-subtle text-primary">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Pan-India Fresh Delivery</h5>
                    <p class="text-muted small mb-0">Special food-grade and protective craft packaging for safe transit straight to your doorstep.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Categories Section -->
<section class="py-5 bg-cream-50">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-terracotta fw-bold text-uppercase small tracking-wide">Browse by Craft</span>
                <h2 class="font-serif fw-bold text-maroon-900 mb-0">Explore Handcrafted Categories</h2>
            </div>
            <a href="categories.php" class="btn btn-outline-maroon btn-sm">View All Categories <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($categories as $cat): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="products.php?category=<?php echo urlencode($cat['slug']); ?>" class="category-card text-center p-3 h-100">
                        <div class="cat-img-wrapper rounded-3 mb-3">
                            <img src="<?php echo htmlspecialchars($cat['image']); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>">
                        </div>
                        <h6 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($cat['name']); ?></h6>
                        <small class="text-muted"><?php echo $cat['product_count']; ?>+ items</small>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-terracotta fw-bold text-uppercase small tracking-wide">From Home Kitchens & Studios</span>
                <h2 class="font-serif fw-bold text-maroon-900 mb-0">Featured Homemade Treasures</h2>
            </div>
            <a href="products.php" class="btn btn-maroon btn-sm px-3">Explore All <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($products as $product): ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="product-card">
                        <div class="img-container">
                            <?php if (!empty($product['badge'])): ?>
                                <span class="product-badge badge-<?php echo strtolower(str_replace([' ', '%'], '', $product['badge'])); ?>">
                                    <?php echo htmlspecialchars($product['badge']); ?>
                                </span>
                            <?php endif; ?>
                            <button type="button" class="btn-wishlist" data-wishlist-id="<?php echo $product['id']; ?>" onclick="window.toggleWishlist('<?php echo $product['id']; ?>', '<?php echo addslashes($product['name']); ?>')">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <a href="product-details.php?slug=<?php echo urlencode($product['slug']); ?>">
                                <img src="<?php echo htmlspecialchars($product['images'][0]); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </a>
                        </div>
                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <small class="text-terracotta fw-semibold"><?php echo htmlspecialchars($product['category']); ?></small>
                                <span class="star-rating"><i class="fa-solid fa-star"></i> <?php echo $product['rating']; ?> (<?php echo $product['review_count']; ?>)</span>
                            </div>
                            <h5 class="fw-bold mb-1">
                                <a href="product-details.php?slug=<?php echo urlencode($product['slug']); ?>" class="text-dark text-decoration-none text-truncate-2">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h5>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <img src="<?php echo htmlspecialchars($product['seller_avatar']); ?>" class="rounded-circle" style="width: 22px; height: 22px; object-fit: cover;" alt="Maker">
                                <small class="text-muted">By <strong><?php echo htmlspecialchars($product['seller_name']); ?></strong> (<?php echo htmlspecialchars($product['seller_location']); ?>)</small>
                            </div>
                            <div class="mt-auto d-flex align-items-center justify-content-between pt-2 border-top">
                                <div>
                                    <span class="fs-5 fw-bold text-maroon-800">₹<?php echo number_format($product['price']); ?></span>
                                    <?php if (!empty($product['original_price'])): ?>
                                        <span class="text-muted text-decoration-line-through small ms-1">₹<?php echo number_format($product['original_price']); ?></span>
                                    <?php endif; ?>
                                    <small class="text-muted d-block" style="font-size: 0.75rem;">/ <?php echo htmlspecialchars($product['unit']); ?></small>
                                </div>
                                <button type="button" class="btn btn-maroon btn-sm px-3" onclick="window.addToCart('<?php echo $product['id']; ?>', '<?php echo addslashes($product['name']); ?>', <?php echo $product['price']; ?>, '<?php echo $product['images'][0]; ?>', '<?php echo addslashes($product['seller_name']); ?>', '<?php echo addslashes($product['unit']); ?>', 1)">
                                    <i class="fa-solid fa-plus me-1"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Women Entrepreneurs Spotlight -->
<section class="py-5 bg-cream-100">
    <div class="container">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="text-terracotta fw-bold text-uppercase small tracking-wide">Meet the Makers</span>
            <h2 class="font-serif fw-bold text-maroon-900 mb-2">Empowered Women, Inspiring Stories</h2>
            <p class="text-muted">Support verified home makers and artisans running flourishing small businesses from their households.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($sellers as $seller): ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="seller-card h-100 d-flex flex-column">
                        <div class="seller-banner" style="background-image: url('<?php echo htmlspecialchars($seller['banner_image']); ?>');"></div>
                        <div class="seller-avatar-wrapper d-flex justify-content-between align-items-end">
                            <img src="<?php echo htmlspecialchars($seller['avatar']); ?>" alt="<?php echo htmlspecialchars($seller['owner_name']); ?>" class="seller-avatar">
                            <span class="badge bg-success-subtle text-success border border-success mb-2 px-2 py-1"><i class="fa-solid fa-circle-check me-1"></i> Verified</span>
                        </div>
                        <div class="p-3 pt-2 d-flex flex-column flex-grow-1">
                            <h5 class="fw-bold mb-0 text-maroon-900"><?php echo htmlspecialchars($seller['business_name']); ?></h5>
                            <small class="text-muted mb-2">Founded by <strong><?php echo htmlspecialchars($seller['owner_name']); ?></strong></small>
                            <p class="small text-secondary mb-3 text-truncate-3"><?php echo htmlspecialchars($seller['short_bio']); ?></p>
                            <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                                <span class="small text-muted"><i class="fa-solid fa-location-dot me-1 text-danger"></i> <?php echo htmlspecialchars($seller['location']); ?></span>
                                <a href="business-details.php?id=<?php echo $seller['id']; ?>" class="btn btn-outline-maroon btn-sm">Visit Store</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-terracotta fw-bold text-uppercase small tracking-wide">Simple & Transparent</span>
            <h2 class="font-serif fw-bold text-maroon-900 mb-2">How Udyojika Works</h2>
            <p class="text-muted">A bridge between talented women home makers and conscious buyers across India.</p>
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-cream-50 h-100 border">
                    <div class="bg-maroon-800 text-white rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow" style="width: 64px; height: 64px; font-size: 1.5rem;">
                        1
                    </div>
                    <h5 class="fw-bold mb-2">Choose Authentic Homemade</h5>
                    <p class="text-muted small mb-0">Browse fresh snacks, spices, handloom apparel and home decor freshly made in domestic kitchens & art workshops.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-cream-50 h-100 border">
                    <div class="bg-maroon-800 text-white rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow" style="width: 64px; height: 64px; font-size: 1.5rem;">
                        2
                    </div>
                    <h5 class="fw-bold mb-2">Freshly Prepared on Order</h5>
                    <p class="text-muted small mb-0">Our women makers craft your order in small hygienic batches with traditional methods and zero chemical shortcuts.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-cream-50 h-100 border">
                    <div class="bg-maroon-800 text-white rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 shadow" style="width: 64px; height: 64px; font-size: 1.5rem;">
                        3
                    </div>
                    <h5 class="fw-bold mb-2">Delivered Direct to You</h5>
                    <p class="text-muted small mb-0">Packed in protective containers and shipped straight to your doorstep with tracking and fresh-seal assurance.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dual Call to Action Banner -->
<section class="py-5 bg-maroon-900 text-white position-relative">
    <div class="container py-3">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 mb-3">FOR WOMEN MAKERS & ARTISANS</span>
                <h2 class="display-6 font-serif fw-bold text-white mb-3">Are You Making Delicious Food or Beautiful Crafts at Home?</h2>
                <p class="lead text-light opacity-75 mb-0">Join 5,000+ women turning their domestic talent into a respected financial income. Zero technical hassle — we handle doorstep logistics and payments.</p>
            </div>
            <div class="col-lg-5 text-lg-end">
                <a href="become-seller.php" class="btn btn-warning btn-lg px-4 py-3 fw-bold text-dark rounded-pill shadow">
                    <i class="fa-solid fa-store me-2"></i> Register Your Home Business
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>