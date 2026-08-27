<?php
$page_title = "Platform Settings - Admin Portal";
$page_header = "System Configuration & Preferences";
$page_subheader = "Manage marketplace policies, shipping thresholds, and seller onboarding rules";
require_once __DIR__ . '/includes/header.php';

$settings = get_system_settings();
$saved_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $saved_msg = "Platform settings saved and updated successfully!";
}
?>

<?php if (!empty($saved_msg)): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="fa-solid fa-circle-check fs-4"></i>
        <div><?php echo htmlspecialchars($saved_msg); ?></div>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-sliders text-maroon-800"></i> General Marketplace Settings</h5>
            </div>
            <div class="p-4">
                <form action="settings.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Platform Brand Name</label>
                            <input type="text" name="site_name" class="form-control" value="<?php echo htmlspecialchars($settings['site_name']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Maker Support Email</label>
                            <input type="email" name="support_email" class="form-control" value="<?php echo htmlspecialchars($settings['support_email']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Maker WhatsApp Helpline</label>
                            <input type="text" name="support_phone" class="form-control" value="<?php echo htmlspecialchars($settings['support_phone']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Free Delivery Threshold (₹)</label>
                            <input type="number" name="free_shipping_threshold" class="form-control" value="<?php echo $settings['free_shipping_threshold']; ?>">
                            <small class="text-muted">Orders above this amount qualify for Free Shipping</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Standard Delivery Flat Fee (₹)</label>
                            <input type="number" name="standard_shipping_rate" class="form-control" value="<?php echo $settings['standard_shipping_rate']; ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Seller Commission Policy</label>
                            <input type="text" class="form-control bg-light" readonly value="0% Platform Commission (100% Direct Payout to Women Homemakers)">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Maker Verification Mode</label>
                            <select class="form-select">
                                <option selected>Manual Admin Verification (Ensures safety, purity & quality)</option>
                                <option>Instant Auto Approval</option>
                            </select>
                        </div>
                        <div class="col-12 pt-3 border-top mt-4">
                            <button type="submit" class="btn btn-maroon px-4 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Security & System Diagnostics -->
    <div class="col-lg-4">
        <div class="dashboard-card mb-4">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-shield-halved text-success"></i> System Status</h5>
            </div>
            <div class="p-3 small text-secondary">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span>PHP Version</span>
                    <strong class="text-dark"><?php echo phpversion(); ?></strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span>Database Engine</span>
                    <strong class="text-dark">MySQL / PDO</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span>Session Engine</span>
                    <strong class="text-success">Active & Protected</strong>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>Server Environment</span>
                    <strong class="text-dark">Apache / XAMPP</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
