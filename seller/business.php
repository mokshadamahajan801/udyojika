<?php
$page_title = "My Business Profile - Maker Portal";
$page_header = "Home Brand & Studio Profile";
$page_subheader = "Update your maker story, kitchen specialties, badges and customer WhatsApp number";
require_once __DIR__ . '/includes/header.php';

$success_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_profile['business_name'] = htmlspecialchars($_POST['business_name'] ?? $seller_profile['business_name']);
    $seller_profile['short_bio'] = htmlspecialchars($_POST['short_bio'] ?? $seller_profile['short_bio']);
    $seller_profile['specialty'] = htmlspecialchars($_POST['specialty'] ?? $seller_profile['specialty']);
    $seller_profile['whatsapp'] = htmlspecialchars($_POST['whatsapp'] ?? $seller_profile['whatsapp']);
    $seller_profile['address'] = htmlspecialchars($_POST['address'] ?? $seller_profile['address']);
    $success_msg = "Your maker brand profile has been updated successfully!";
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
                <h5 class="dashboard-card-title"><i class="fa-solid fa-store text-maroon-800"></i> Edit Brand & Kitchen Details</h5>
            </div>
            <div class="p-4">
                <form action="business.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Business / Home Brand Name *</label>
                            <input type="text" name="business_name" class="form-control" value="<?php echo htmlspecialchars($seller_profile['business_name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Founder / Homemaker Name *</label>
                            <input type="text" class="form-control bg-light" readonly value="<?php echo htmlspecialchars($seller_profile['owner_name']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Primary Category</label>
                            <input type="text" class="form-control bg-light" readonly value="<?php echo htmlspecialchars($seller_profile['category']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Direct WhatsApp Number for Patrons *</label>
                            <input type="text" name="whatsapp" class="form-control" value="<?php echo htmlspecialchars($seller_profile['whatsapp']); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Signature Specialty *</label>
                            <input type="text" name="specialty" class="form-control" value="<?php echo htmlspecialchars($seller_profile['specialty']); ?>" required>
                            <small class="text-muted">e.g. Maharashtrian Festive Faral & Poha Chivda</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Short Bio (Shown on cards) *</label>
                            <textarea name="short_bio" class="form-control" rows="2" required><?php echo htmlspecialchars($seller_profile['short_bio']); ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Full Maker Journey & Heritage Story</label>
                            <textarea name="full_story" class="form-control" rows="4"><?php echo htmlspecialchars($seller_profile['full_story']); ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Kitchen / Workshop Address (Courier Pickup Location) *</label>
                            <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($seller_profile['address'] ?? 'Lane 4, Prabhat Road, Pune'); ?>" required>
                        </div>
                        <div class="col-12 pt-3 border-top mt-4">
                            <button type="submit" class="btn btn-maroon px-4 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Profile Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Live Preview Column -->
    <div class="col-lg-4">
        <div class="dashboard-card mb-4">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-eye text-terracotta"></i> Storefront Preview</h5>
            </div>
            <div class="p-3">
                <div class="border rounded-4 overflow-hidden shadow-sm bg-white">
                    <img src="<?php echo $seller_profile['banner_image']; ?>" class="w-100" style="height: 120px; object-fit: cover;" alt="">
                    <div class="p-3 text-center position-relative" style="margin-top: -35px;">
                        <img src="<?php echo $seller_profile['avatar']; ?>" class="rounded-circle border border-3 border-white shadow-sm mb-2" style="width: 70px; height: 70px; object-fit: cover;" alt="">
                        <h6 class="fw-bold font-serif text-maroon-900 mb-0"><?php echo htmlspecialchars($seller_profile['business_name']); ?></h6>
                        <small class="text-muted d-block mb-2">By <?php echo htmlspecialchars($seller_profile['owner_name']); ?></small>
                        <span class="badge bg-warning text-dark mb-2"><i class="fa-solid fa-star me-1"></i><?php echo $seller_profile['rating']; ?> (<?php echo $seller_profile['review_count']; ?> reviews)</span>
                        <p class="small text-secondary mb-3"><?php echo htmlspecialchars($seller_profile['short_bio']); ?></p>
                        <a href="../business-details.php?id=<?php echo $seller_id; ?>" target="_blank" class="btn btn-outline-maroon btn-sm w-100">Open Public Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
