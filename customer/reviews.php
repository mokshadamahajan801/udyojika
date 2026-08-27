<?php
$page_title = "My Reviews - Customer Portal";
$page_header = "My Product Feedback";
$page_subheader = "Ratings and appreciations you have shared with women makers";
require_once __DIR__ . '/includes/header.php';

$all_reviews = get_all_reviews();
$my_reviews = array_filter($all_reviews, fn($r) => (int)$r['customer_id'] === (int)$customer_id);
?>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-star text-warning"></i> My Published Reviews (<?php echo count($my_reviews); ?>)</h5>
    </div>
    <div class="p-3">
        <div class="d-flex flex-column gap-3">
            <?php if (empty($my_reviews)): ?>
                <p class="text-muted text-center py-4">You haven't written any reviews yet. Share your experience after receiving your items!</p>
            <?php else: ?>
                <?php foreach ($my_reviews as $rev): ?>
                    <div class="p-3 border rounded-3 bg-white shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong class="text-maroon-900 fs-6"><?php echo htmlspecialchars($rev['product_name']); ?></strong>
                                <small class="text-muted d-block">Handcrafted by <?php echo htmlspecialchars($rev['seller_name']); ?></small>
                            </div>
                            <span class="text-warning fw-bold"><?php echo str_repeat('★', $rev['rating']); ?></span>
                        </div>
                        <p class="small text-secondary mb-2">"<?php echo htmlspecialchars($rev['review_text']); ?>"</p>
                        <small class="text-muted"><i class="fa-regular fa-clock me-1"></i> Posted on <?php echo date('d M Y', strtotime($rev['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
