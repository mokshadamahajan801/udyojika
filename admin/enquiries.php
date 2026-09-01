<?php
$page_title = "Manage Enquiries - Admin Portal";
$page_header = "Customer & Custom Maker Enquiries";
$page_subheader = "View inquiries sent by customers to individual women makers or corporate bulk inquiries";
require_once __DIR__ . '/includes/header.php';

$enquiries = get_all_enquiries($pdo);

?>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-comments text-maroon-800"></i> Marketplace Enquiries</h5>
    </div>
    <div class="p-3">
        <div class="d-flex flex-column gap-3">
            <?php foreach ($enquiries as $enq): ?>
                <div class="p-4 border rounded-4 bg-white shadow-sm">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="font-serif fw-bold text-maroon-900 mb-1"><?php echo htmlspecialchars($enq['subject']); ?></h5>
                            <small class="text-muted">
                                From <strong><?php echo htmlspecialchars($enq['sender_name']); ?></strong> (<?php echo htmlspecialchars($enq['sender_email']); ?>, <?php echo htmlspecialchars($enq['sender_phone']); ?>)
                                &bull; Sent to: <strong class="text-maroon-800"><?php echo htmlspecialchars($enq['seller_name']); ?></strong>
                            </small>
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
                            <strong class="d-block text-success mb-1"><i class="fa-solid fa-reply me-1"></i> Maker Reply:</strong>
                            <?php echo htmlspecialchars($enq['reply']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
