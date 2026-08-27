<?php
$page_title = "Browse All Homemade & Handcrafted Products";
require_once __DIR__ . '/includes/header.php';

$all_products = get_all_products($pdo);
$categories = get_categories($pdo);

$selected_category = isset($_GET['category']) ? trim($_GET['category']) : '';
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'featured';

// Filter products
$filtered_products = array_filter($all_products, function ($item) use ($selected_category, $search_query) {
    if (!empty($selected_category) && $item['category_slug'] !== $selected_category) {
        return false;
    }
    if (!empty($search_query)) {
        $q = strtolower($search_query);
        $name_match = strpos(strtolower($item['name']), $q) !== false;
        $cat_match = strpos(strtolower($item['category']), $q) !== false;
        $seller_match = strpos(strtolower($item['seller_name']), $q) !== false;
        $desc_match = strpos(strtolower($item['description']), $q) !== false;
        if (!$name_match && !$cat_match && !$seller_match && !$desc_match) {
            return false;
        }
    }
    return true;
});

// Sort products
if ($sort === 'price-low') {
    usort($filtered_products, fn($a, $b) => $a['price'] <=> $b['price']);
} elseif ($sort === 'price-high') {
    usort($filtered_products, fn($a, $b) => $b['price'] <=> $a['price']);
} elseif ($sort === 'rating') {
    usort($filtered_products, fn($a, $b) => $b['rating'] <=> $a['rating']);
}
?>

<!-- Breadcrumb Header -->
<div class="bg-cream-100 py-4 border-bottom">

    <div class="container">

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb mb-1 small">

                <li class="breadcrumb-item">
                    <a href="index.php"
                       class="text-decoration-none text-muted">
                        Home
                    </a>
                </li>

                <li class="breadcrumb-item active text-maroon-800 fw-bold"
                    aria-current="page">
                    Shop Products
                </li>

            </ol>

        </nav>


        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>

                <?php if (!empty($search_query)): ?>

                    <h2 class="font-serif fw-bold text-maroon-900 mb-0">
                        Search Results for
                        "<?php echo htmlspecialchars($search_query); ?>"
                    </h2>

                    <small class="text-muted">
                        Found <?php echo count($filtered_products); ?> product(s)
                    </small>

                <?php elseif (!empty($selected_category)): ?>

                    <h2 class="font-serif fw-bold text-maroon-900 mb-0">
                        Homemade & Handcrafted Products
                    </h2>

                    <small class="text-muted">
                        Showing <?php echo count($filtered_products); ?>
                        products
                    </small>

                <?php else: ?>

                    <h2 class="font-serif fw-bold text-maroon-900 mb-0">
                        Homemade & Handcrafted Marketplace
                    </h2>

                    <small class="text-muted">
                        Showing <?php echo count($filtered_products); ?>
                        authentic products created by verified women makers
                    </small>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>

<div class="container py-5">
    <div class="row g-4">
        
        <!-- Left Sidebar Filter -->
        <div class="col-lg-3">
            <div class="bg-white p-4 rounded-4 shadow-sm border sticky-top" style="top: 100px; z-index: 10;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-maroon-800 mb-0 font-serif">Filter Catalog</h5>
                    <a href="products.php" class="btn btn-link btn-sm text-decoration-none text-muted p-0">Clear All</a>
                </div>

                <!-- Category Filter -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small text-uppercase">Categories</label>
                    <div class="list-group list-group-flush small">
                        <a href="products.php" class="list-group-item list-group-item-action px-2 py-2 border-0 rounded <?php echo empty($selected_category) ? 'bg-cream-200 fw-bold text-maroon-800' : ''; ?>">
                            All Categories (<?php echo count($all_products); ?>)
                        </a>
                        <?php foreach ($categories as $cat): ?>
                            <a href="products.php?category=<?php echo urlencode($cat['slug']); ?>" 
                               class="list-group-item list-group-item-action px-2 py-2 border-0 rounded d-flex justify-content-between align-items-center <?php echo $selected_category === $cat['slug'] ? 'bg-cream-200 fw-bold text-maroon-800' : ''; ?>">
                                <span><i class="fa-solid <?php echo htmlspecialchars($cat['icon']); ?> me-2 text-terracotta"></i> <?php echo htmlspecialchars($cat['name']); ?></span>
                                <span class="badge bg-light text-muted border"><?php echo $cat['product_count']; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Trust Guarantee Box -->
                <div class="p-3 bg-cream-100 rounded-3 border">
                    <h6 class="fw-bold text-maroon-800 mb-1"><i class="fa-solid fa-heart text-danger me-1"></i> Direct Support</h6>
                    <p class="small text-muted mb-0">100% of the sale value (minus minimal logistics) directly benefits the woman entrepreneur who made your item.</p>
                </div>
            </div>
        </div>

        <!-- Right Product Grid -->
        <div class="col-lg-9">
            
            <!-- Controls Bar -->
            <div class="bg-white p-3 rounded-4 shadow-sm border mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Sort By:</span>
                    <select class="form-select form-select-sm" style="width: auto;" onchange="location = this.value;">
                        <option value="products.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'featured'])); ?>" <?php echo $sort === 'featured' ? 'selected' : ''; ?>>Featured / Popular</option>
                        <option value="products.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'price-low'])); ?>" <?php echo $sort === 'price-low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="products.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'price-high'])); ?>" <?php echo $sort === 'price-high' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="products.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'rating'])); ?>" <?php echo $sort === 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                    </select>
                </div>
                <div class="text-muted small">
                    Showing <strong><?php echo count($filtered_products); ?></strong> of <?php echo count($all_products); ?> items
                </div>
            </div>

            <!-- Products Grid -->
            <?php if (empty($filtered_products)): ?>
                <div class="text-center py-5 bg-white rounded-4 border p-5">
                    <div class="text-muted display-4 mb-3"><i class="fa-solid fa-basket-shopping"></i></div>
                    <h4 class="font-serif text-maroon-800 fw-bold">No Products Found</h4>
                    <p class="text-muted">No items matched your current filter criteria. Try clearing search filters.</p>
                    <a href="products.php" class="btn btn-maroon px-4">Reset All Filters</a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($filtered_products as $product): ?>
                        <div class="col-12 col-sm-6 col-md-4">
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
                                        <span class="star-rating"><i class="fa-solid fa-star"></i> <?php echo $product['rating']; ?></span>
                                    </div>
                                    <h6 class="fw-bold mb-1">
                                        <a href="product-details.php?slug=<?php echo urlencode($product['slug']); ?>" class="text-dark text-decoration-none">
                                            <?php echo htmlspecialchars($product['name']); ?>
                                        </a>
                                    </h6>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <img src="<?php echo htmlspecialchars($product['seller_avatar']); ?>" class="rounded-circle" style="width: 20px; height: 20px; object-fit: cover;" alt="Maker">
                                        <small class="text-muted text-truncate">By <?php echo htmlspecialchars($product['seller_name']); ?></small>
                                    </div>
                                    <div class="mt-auto d-flex align-items-center justify-content-between pt-2 border-top">
                                        <div>
                                            <span class="fs-5 fw-bold text-maroon-800">₹<?php echo number_format($product['price']); ?></span>
                                            <?php if (!empty($product['original_price'])): ?>
                                                <span class="text-muted text-decoration-line-through small ms-1">₹<?php echo number_format($product['original_price']); ?></span>
                                            <?php endif; ?>
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
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
