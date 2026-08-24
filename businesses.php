<?php
$page_title = "Meet Our Women Home Entrepreneurs & Artisans";
require_once __DIR__ . '/includes/header.php';

$sellers = get_sellers($pdo);
$categories = get_categories($pdo);
?>

<div class="bg-cream-100 py-4 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-maroon-800 fw-bold" aria-current="page">Women Entrepreneurs</li>
            </ol>
        </nav>
        <h2 class="font-serif fw-bold text-maroon-900 mb-1">Meet Our Women Makers & Home Chefs</h2>
        <p class="text-muted mb-0">Discover the passionate home entrepreneurs bringing authentic heritage crafts and foods to life.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($sellers as $seller): ?>
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden">
                    <div style="height: 160px; background-image: url('<?php echo htmlspecialchars($seller['banner_image']); ?>'); background-size: cover; background-position: center;"></div>
                    <div class="card-body p-4 pt-0 position-relative">
                        <div class="d-flex justify-content-between align-items-end mb-3" style="margin-top: -45px;">
                            <img src="<?php echo htmlspecialchars($seller['avatar']); ?>" class="rounded-circle border border-4 border-white shadow" style="width: 84px; height: 84px; object-fit: cover;" alt="<?php echo htmlspecialchars($seller['owner_name']); ?>">
                            <span class="badge bg-success-subtle text-success border border-success px-3 py-2"><i class="fa-solid fa-circle-check me-1"></i> Verified Maker</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h4 class="fw-bold font-serif text-maroon-900 mb-0"><?php echo htmlspecialchars($seller['business_name']); ?></h4>
                                <small class="text-muted">Led by <strong><?php echo htmlspecialchars($seller['owner_name']); ?></strong> &bull; <i class="fa-solid fa-location-dot text-danger me-1"></i> <?php echo htmlspecialchars($seller['location']); ?></small>
                            </div>
                            <span class="star-rating fs-6"><i class="fa-solid fa-star"></i> <?php echo $seller['rating']; ?> (<?php echo $seller['review_count']; ?>)</span>
                        </div>

                        <p class="text-secondary small mb-3">
                            <?php echo htmlspecialchars($seller['short_bio']); ?>
                        </p>

                        <div class="p-3 bg-cream-50 rounded-3 mb-4 border">
                            <span class="text-terracotta small fw-bold text-uppercase d-block mb-1">Specialty:</span>
                            <span class="fw-semibold text-dark small"><i class="fa-solid fa-award text-warning me-1"></i> <?php echo htmlspecialchars($seller['specialty']); ?></span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="business-details.php?id=<?php echo $seller['id']; ?>" class="btn btn-maroon btn-sm px-4">Visit Store & Products</a>
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $seller['whatsapp']); ?>" target="_blank" class="btn btn-outline-success btn-sm px-3">
                                <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
