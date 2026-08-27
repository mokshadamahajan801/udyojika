<?php
$page_title = "Maker Inquiries - Customer Portal";
$page_header = "Custom Inquiries & Bulk Requests";
$page_subheader = "Track your conversations and special requests sent to home makers";
require_once __DIR__ . '/includes/header.php';

$all_enquiries = get_all_enquiries();
$my_enquiries = array_filter($all_enquiries, fn($e) => (int)$e['sender_id'] === (int)$customer_id);
?>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-comments text-maroon-800"></i> My Direct Maker Inquiries</h5>
    </div>
    <div class="p-3">
        <div class="d-flex flex-column gap-3">
            <?php if (empty($my_enquiries)): ?>
                <p class="text-muted text-center py-4">No custom inquiries sent yet. You can message any maker directly from their product page!</p>
            <?php else: ?>
                <?php foreach ($my_enquiries as $enq): ?>
                    <div class="p-4 border rounded-4 bg-white shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="font-serif fw-bold text-maroon-900 mb-1"><?php echo htmlspecialchars($enq['subject']); ?></h5>
                                <small class="text-muted">Sent to Maker: <strong class="text-terracotta"><?php echo htmlspecialchars($enq['seller_name']); ?></strong> &bull; <?php echo date('d M Y, h:i A', strtotime($enq['created_at'])); ?></small>
                            </div>
                            <span class="badge-status-<?php echo $enq['status'] === 'replied' ? 'completed' : 'pending'; ?>">
                                <?php echo ucfirst($enq['status']); ?>
                            </span>
                        </div>

                        <div class="p-3 bg-light rounded-3 my-2 small text-secondary">
                            "<?php echo htmlspecialchars($enq['message']); ?>"
                        </div>

                        <?php if (!empty($enq['reply'])): ?>
                            <div class="p-3 bg-cream-100 rounded-3 border-start border-3 border-success small text-dark mt-2">
                                <strong class="d-block text-success mb-1"><i class="fa-solid fa-reply me-1"></i> Maker Response from <?php echo htmlspecialchars($enq['seller_name']); ?>:</strong>
                                <?php echo htmlspecialchars($enq['reply']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
