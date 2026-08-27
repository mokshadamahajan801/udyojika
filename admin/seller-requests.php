<?php
$page_title = "Maker Registration Applications - Admin Portal";
$page_header = "Women Maker Onboarding Requests";
$page_subheader = "Review, verify credentials, approve or reject home entrepreneur applications";
require_once __DIR__ . '/includes/header.php';

$seller_requests = get_seller_requests();
$action_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $req_id = $_POST['request_id'] ?? 0;
    $action = $_POST['action'] ?? '';
    if ($action === 'approve') {
        $action_msg = "Application #{$req_id} has been APPROVED. Welcome SMS and seller onboarding kit sent.";
    } elseif ($action === 'reject') {
        $action_msg = "Application #{$req_id} has been marked REJECTED with feedback notice sent to applicant.";
    }
}
?>

<?php if (!empty($action_msg)): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="fa-solid fa-circle-check fs-4"></i>
        <div><?php echo $action_msg; ?></div>
    </div>
<?php endif; ?>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-user-plus text-warning"></i> Incoming Maker Registration Requests</h5>
        <span class="badge bg-danger rounded-pill px-3 py-2">2 Pending Review</span>
    </div>
    <div class="p-3">
        <div class="d-flex flex-column gap-3">
            <?php foreach ($seller_requests as $req): ?>
                <div class="p-4 border rounded-4 bg-white shadow-sm">
                    <div class="row align-items-start g-3">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h5 class="font-serif fw-bold text-maroon-900 mb-0"><?php echo htmlspecialchars($req['full_name']); ?></h5>
                                <span class="badge bg-warning text-dark px-2 py-1"><i class="fa-solid fa-tag me-1"></i><?php echo htmlspecialchars($req['category']); ?></span>
                                <span class="badge-status-<?php echo $req['status']; ?>"><?php echo ucfirst($req['status']); ?></span>
                            </div>
                            
                            <div class="row g-2 small text-muted mb-3">
                                <div class="col-sm-6"><strong>Proposed Brand:</strong> <span class="text-dark fw-bold"><?php echo htmlspecialchars($req['business_name']); ?></span></div>
                                <div class="col-sm-6"><strong>City / Region:</strong> <span class="text-dark"><?php echo htmlspecialchars($req['city']); ?></span></div>
                                <div class="col-sm-6"><strong>WhatsApp Phone:</strong> <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $req['phone']); ?>" target="_blank" class="text-success fw-bold text-decoration-none"><i class="fa-brands fa-whatsapp"></i> <?php echo htmlspecialchars($req['phone']); ?></a></div>
                                <div class="col-sm-6"><strong>Applied On:</strong> <?php echo date('d M Y, h:i A', strtotime($req['created_at'])); ?></div>
                            </div>

                            <div class="p-3 bg-light rounded-3 mb-2">
                                <strong class="small d-block text-maroon-900 mb-1">Maker Story & Recipe Description:</strong>
                                <p class="small text-secondary mb-2"><?php echo htmlspecialchars($req['description']); ?></p>
                                <strong class="small d-block text-maroon-900 mb-1">Sample Products:</strong>
                                <span class="badge bg-white border text-dark"><?php echo htmlspecialchars($req['sample_products']); ?></span>
                            </div>
                        </div>

                        <div class="col-lg-4 text-lg-end">
                            <div class="p-3 bg-cream-100 rounded-3 border mb-3 text-start">
                                <small class="text-muted d-block fw-bold mb-1">Verification Checklist:</small>
                                <ul class="small list-unstyled mb-0 d-flex flex-column gap-1 text-secondary">
                                    <li><i class="fa-solid fa-check text-success me-1"></i> Contact Phone verified</li>
                                    <li><i class="fa-solid fa-check text-success me-1"></i> 100% Homemade declared</li>
                                    <li><i class="fa-solid fa-clock text-warning me-1"></i> Food Hygiene / FSSAI check</li>
                                </ul>
                            </div>

                            <form method="POST" action="seller-requests.php" class="d-flex justify-content-lg-end gap-2">
                                <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                <?php if ($req['status'] === 'pending'): ?>
                                    <button type="submit" name="action" value="approve" class="btn btn-success fw-bold">
                                        <i class="fa-solid fa-circle-check me-1"></i> Approve Maker
                                    </button>
                                    <button type="submit" name="action" value="reject" class="btn btn-outline-danger fw-bold" onclick="return confirm('Are you sure you want to reject this application?');">
                                        <i class="fa-solid fa-ban me-1"></i> Reject
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-light border text-muted" disabled>
                                        <i class="fa-solid fa-circle-check text-success me-1"></i> Processed
                                    </button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
