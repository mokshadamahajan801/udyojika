<?php
$page_title = "Home Brands & Businesses - Admin Portal";
$page_header = "Registered Home Brands & Studios";
$page_subheader = "Manage brand stories, specialties, badges and contact details";
require_once __DIR__ . '/includes/header.php';

$sellers = get_sellers();
?>

<div class="row g-4 mb-4">
    <?php foreach ($sellers as $b): ?>
        <div class="col-md-6 col-lg-4">
            <div class="dashboard-card h-100 mb-0">
                <div class="position-relative">
                    <img src="<?php echo $b['banner_image']; ?>" class="w-100" style="height: 140px; object-fit: cover;" alt="">
                    <img src="<?php echo $b['avatar']; ?>" class="position-absolute rounded-circle border border-3 border-white shadow-sm" style="bottom: -20px; left: 20px; width: 60px; height: 60px; object-fit: cover;" alt="">
                </div>
                <div class="p-4 pt-4 mt-2">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="font-serif fw-bold text-maroon-900 mb-0"><?php echo htmlspecialchars($b['business_name']); ?></h5>
                            <small class="text-muted">By <strong><?php echo htmlspecialchars($b['owner_name']); ?></strong></small>
                        </div>
                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-star me-1"></i><?php echo $b['rating']; ?></span>
                    </div>

                    <p class="small text-secondary mb-3"><?php echo htmlspecialchars($b['short_bio']); ?></p>

                    <div class="p-2 bg-light rounded-3 small mb-3">
                        <strong class="d-block text-maroon-900 mb-1">Specialty:</strong>
                        <span class="text-muted"><?php echo htmlspecialchars($b['specialty']); ?></span>
                    </div>

                    <div class="d-flex flex-wrap gap-1 mb-3">
                        <?php foreach ($b['badges'] as $badge): ?>
                            <span class="badge bg-cream-200 text-maroon-800 border small"><?php echo htmlspecialchars($badge); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <small class="text-muted"><i class="fa-solid fa-location-dot text-danger me-1"></i><?php echo htmlspecialchars($b['location']); ?></small>
                        <a href="../business-details.php?id=<?php echo $b['id']; ?>" target="_blank" class="btn btn-outline-maroon btn-sm">View Page</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
