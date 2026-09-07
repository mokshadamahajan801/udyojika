<?php
$page_title = "Product Reviews - Admin Portal";
$page_header = "Customer Ratings & Reviews";
$page_subheader = "Approve, moderate or delete customer feedback for homemade items";
require_once __DIR__ . '/includes/header.php';

$reviews = get_all_reviews();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $review_id = (int)($_POST['review_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($review_id > 0 && $action === 'approve') {

        $stmt = $pdo->prepare("
            UPDATE reviews
            SET status = 'approved'
            WHERE id = ?
        ");

        $stmt->execute([$review_id]);
    }
    if ($review_id > 0 && $action === 'delete') {

    $stmt = $pdo->prepare("
        DELETE FROM reviews
        WHERE id = ?
    ");

    $stmt->execute([$review_id]);
}
}
?>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-star text-warning"></i> Customer Feedback (<?php echo count($reviews); ?>)</h5>
    </div>
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Product & Maker</th>
                    <th>Rating</th>
                    <th>Review Comment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $rev): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($rev['customer_name']); ?></strong></td>
                        <td>
                            <strong class="text-maroon-900 d-block"><?php echo htmlspecialchars($rev['product_name']); ?></strong>
                            <small class="text-muted">By <?php echo htmlspecialchars($rev['seller_name']); ?></small>
                        </td>
                        <td>
                            <span class="text-warning fw-bold"><?php echo str_repeat('★', $rev['rating']); ?></span>
                        </td>
                        <td class="small text-secondary" style="max-width: 320px;">
                            "<?php echo htmlspecialchars($rev['review_text']); ?>"
                        </td>
                        <td><span class="badge-status-approved"><?php echo ucfirst($rev['status']); ?></span></td>
                        <td class="small text-muted"><?php echo date('d M, Y', strtotime($rev['created_at'])); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
<form method="POST" style="display:inline;">
    <input type="hidden" name="review_id" value="<?php echo $rev['id']; ?>">
    <input type="hidden" name="action" value="approve">
    <button type="submit" class="btn btn-light border text-success" title="Approve">
        <i class="fa-solid fa-check"></i>
    </button>
<form method="POST" style="display:inline;" onsubmit="return confirm('Delete this review?');">
    <input type="hidden" name="review_id" value="<?php echo $rev['id']; ?>">
    <input type="hidden" name="action" value="delete">
    <button type="submit" class="btn btn-light border text-danger" title="Delete Review">
        <i class="fa-solid fa-trash-can"></i>
    </button>
</form>                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
