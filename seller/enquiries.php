<?php
$page_title = "Custom Enquiries - Maker Portal";
$page_header = "Custom & Bulk Orders";
$page_subheader = "Respond to personalized requests and festive gift hamper inquiries from buyers";
require_once __DIR__ . '/includes/header.php';

$all_enquiries = get_all_enquiries($pdo);

$my_enquiries = array_filter($all_enquiries, fn($e) => (int)$e['seller_id'] === (int)$seller_id);
$reply_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply_msg = "Your response has been delivered to the customer via Email & WhatsApp!";
}
?>

<?php if (!empty($reply_msg)): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="fa-solid fa-circle-check fs-4"></i>
        <div><?php echo $reply_msg; ?></div>
    </div>
<?php endif; ?>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-comments text-maroon-800"></i> Buyer Messages & Custom Quotation Requests</h5>
    </div>
    <div class="p-3">
        <div class="d-flex flex-column gap-4">
            <?php foreach ($my_enquiries as $enq): ?>
                <div class="p-4 border rounded-4 bg-white shadow-sm">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="font-serif fw-bold text-maroon-900 mb-1"><?php echo htmlspecialchars($enq['subject']); ?></h5>
                            <small class="text-muted">
                                From: <strong><?php echo htmlspecialchars($enq['sender_name']); ?></strong> (<?php echo htmlspecialchars($enq['sender_email']); ?> | <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $enq['sender_phone']); ?>" class="text-success fw-bold text-decoration-none"><i class="fa-brands fa-whatsapp"></i> <?php echo htmlspecialchars($enq['sender_phone']); ?></a>)
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
                        <div class="p-3 bg-cream-100 rounded-3 border-start border-3 border-success small text-dark mt-2 mb-3">
                            <strong class="d-block text-success mb-1"><i class="fa-solid fa-check me-1"></i> Your Sent Reply:</strong>
                            <?php echo htmlspecialchars($enq['reply']); ?>
                        </div>
                    <?php else: ?>
                        <!-- Quick Reply Box -->
                        <form action="enquiries.php" method="POST" class="mt-3">
                            <label class="form-label small fw-bold">Send Quick Quotation / Reply to Customer:</label>
                            <textarea name="reply_text" class="form-control form-control-sm mb-2" rows="2" placeholder="Write your response, sample pricing, or delivery timeline..." required></textarea>
                            <button type="submit" class="btn btn-sm btn-maroon fw-bold">
                                <i class="fa-solid fa-paper-plane me-1"></i> Send Reply
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
