<?php
$page_title = "All Craft & Food Categories";
require_once __DIR__ . '/includes/header.php';

$categories = get_categories($pdo);
?>

<div class="bg-cream-100 py-4 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-maroon-800 fw-bold" aria-current="page">Categories</li>
            </ol>
        </nav>
        <h2 class="font-serif fw-bold text-maroon-900 mb-1">Browse All Craft & Culinary Categories</h2>
        <p class="text-muted mb-0">From secret regional recipes to intricate traditional handlooms and organic wellness.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($categories as $cat): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm overflow-hidden h-100">
                    <div style="height: 180px; overflow: hidden; position: relative;">
                        <img src="<?php echo htmlspecialchars($cat['image']); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo htmlspecialchars($cat['name']); ?>">
                        <span class="badge bg-maroon-800 text-white position-absolute top-0 end-0 m-3 px-3 py-2">
                            <?php echo $cat['product_count']; ?>+ Products
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fa-solid <?php echo htmlspecialchars($cat['icon']); ?> text-terracotta fs-5"></i>
                            <h4 class="font-serif fw-bold text-maroon-900 mb-0"><?php echo htmlspecialchars($cat['name']); ?></h4>
                        </div>
                        <p class="text-secondary small mb-3"><?php echo htmlspecialchars($cat['description']); ?></p>
                        
                        <div class="mb-4">
                            <small class="text-muted fw-bold d-block mb-1 text-uppercase" style="font-size: 0.75rem;">Popular Items:</small>
                            <div class="d-flex flex-wrap gap-1">
                                <?php foreach ($cat['popular_items'] as $item): ?>
                                    <span class="badge bg-cream-200 text-dark border small fw-normal"><?php echo htmlspecialchars($item); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <a href="products.php?category=<?php echo urlencode($cat['slug']); ?>" class="btn btn-outline-maroon mt-auto w-100">
                            Explore <?php echo htmlspecialchars($cat['name']); ?> <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
