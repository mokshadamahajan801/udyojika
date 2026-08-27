<?php
$page_title = "Account Settings - Maker Portal";
$page_header = "Maker Profile & Security";
$page_subheader = "Manage personal contact details and password credentials";
require_once __DIR__ . '/includes/header.php';

$success_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success_msg = "Account details updated successfully!";
}
?>

<?php if (!empty($success_msg)): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="fa-solid fa-circle-check fs-4"></i>
        <div><?php echo $success_msg; ?></div>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-user-gear text-maroon-800"></i> Personal Information</h5>
            </div>
            <div class="p-4">
                <form action="profile.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name *</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($seller_profile['owner_name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address (Login ID) *</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($seller_profile['email'] ?? $current_user['email']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Mobile Phone Number *</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($current_user['phone']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Account Role</label>
                            <input type="text" class="form-control bg-light" readonly value="Verified Woman Maker / Seller">
                        </div>

                        <div class="col-12 pt-3 border-top mt-4">
                            <h6 class="font-serif fw-bold text-maroon-900 mb-3">Change Security Password</h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">New Password</label>
                            <input type="password" name="new_password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="••••••••">
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

    <!-- Security Badges -->
    <div class="col-lg-4">
        <div class="dashboard-card mb-4">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-shield-halved text-success"></i> Maker Verification</h5>
            </div>
            <div class="p-3 small text-secondary">
                <div class="p-3 bg-success-subtle rounded-3 border border-success-subtle mb-3">
                    <i class="fa-solid fa-circle-check text-success fs-5 mb-2 d-block"></i>
                    <strong class="text-success d-block">Verified Home Entrepreneur</strong>
                    <span>Government ID and Kitchen Hygiene inspected and approved.</span>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Member Since</span>
                    <strong class="text-dark">2021</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Maker Level</span>
                    <strong class="text-dark">Top Star Maker ★</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
