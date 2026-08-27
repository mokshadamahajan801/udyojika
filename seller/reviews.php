<?php
$page_title = "Product Reviews - Maker Portal";
$page_header = "Customer Ratings & Reviews";
$page_subheader = "Read feedback from verified buyers of your homemade recipes & crafts";
require_once __DIR__ . '/includes/header.php';

$all_reviews = get_all_reviews();
$my_reviews = array_filter($all_reviews, fn($r) => (int)$r['seller_id'] === (int)$seller_id);
?>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-title">Overall Store Rating</div>
            <div class="stat-value text-warning"><?php echo $seller_profile['rating']; ?> ★</div>
            <p class="small text-muted mb-0 mt-1">Based on <?php echo $seller_profile['review_count']; ?> verified reviews</p>
        </div>
    </div>
    <div class="col-md-8">
        <div class="stat-card">
            <div class="stat-title">Patron Satisfaction</div>
            <div class="d-flex align-items-center gap-3 mt-2">
                <div class="progress flex-grow-1" style="height: 12px;">
                    <div class="progress-bar bg-success" style="width: 98%;"></div>
                </div>
                <strong class="text-success">98% Positive</strong>
            </div>
            <p class="small text-muted mb-0 mt-2">"Freshness, Pure Ghee aroma and authentic crispy texture are top compliments."</p>
        </div>
    </div>
</div>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-star text-warning"></i> Customer Feedback</h5>
    </div>
    <div class="p-3">
        <div class="d-flex flex-column gap-3">
            <?php foreach ($my_reviews as $rev): ?>
                <div class="p-3 border rounded-3 bg-white shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong class="text-dark"><?php echo htmlspecialchars($rev['customer_name']); ?></strong>
                            <span class="badge bg-success-subtle text-success border border-success-subtle ms-2"><i class="fa-solid fa-circle-check me-1"></i> Verified Buyer</span>
                        </div>
                        <span class="text-warning small"><?php echo str_repeat('★', $rev['rating']); ?></span>
                    </div>
                    <strong class="small text-maroon-900 d-block mb-1">Reviewed Product: <?php echo htmlspecialchars($rev['product_name']); ?></strong>
                    <p class="small text-secondary mb-2">"<?php echo htmlspecialchars($rev['review_text']); ?>"</p>
                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i> <?php echo date('d M Y, h:i A', strtotime($rev['created_at'])); ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
