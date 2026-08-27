<?php
$page_title = "Saved Addresses - Customer Portal";
$page_header = "Delivery Addresses";
$page_subheader = "Manage your home and office delivery destinations";
require_once __DIR__ . '/includes/header.php';

$addresses = get_customer_addresses($customer_id);
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success_msg = "New delivery address added successfully!";
}
?>

<?php if (!empty($success_msg)): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="fa-solid fa-circle-check fs-4"></i>
        <div><?php echo $success_msg; ?></div>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Existing Addresses -->
    <div class="col-lg-7">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-location-dot text-danger"></i> Saved Shipping Addresses</h5>
            </div>
            <div class="p-3">
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($addresses as $addr): ?>
                        <div class="p-3 border rounded-3 bg-white shadow-sm position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-maroon-900 fs-6"><?php echo htmlspecialchars($addr['title']); ?></strong>
                                <?php if ($addr['is_default']): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Default Address</span>
                                <?php endif; ?>
                            </div>
                            <strong class="text-dark d-block"><?php echo htmlspecialchars($addr['full_name']); ?></strong>
                            <p class="small text-secondary mb-2">
                                <?php echo htmlspecialchars($addr['address_line1']); ?>,<br>
                                <?php echo htmlspecialchars($addr['address_line2']); ?>,<br>
                                <?php echo htmlspecialchars($addr['city']); ?>, <?php echo htmlspecialchars($addr['state']); ?> - <strong><?php echo htmlspecialchars($addr['pincode']); ?></strong>
                            </p>
                            <small class="text-muted d-block mb-3"><i class="fa-solid fa-phone me-1"></i> <?php echo htmlspecialchars($addr['phone']); ?></small>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-light border" onclick="alert('Edit address dialog');"><i class="fa-solid fa-pen me-1"></i> Edit</button>
                                <?php if (!$addr['is_default']): ?>
                                    <button class="btn btn-sm btn-outline-danger" onclick="if(confirm('Delete address?')) alert('Address removed');"><i class="fa-solid fa-trash-can me-1"></i> Delete</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Address Form -->
    <div class="col-lg-5">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-plus text-maroon-800"></i> Add New Address</h5>
            </div>
            <div class="p-4">
                <form action="addresses.php" method="POST">
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Address Label (e.g. Home, Office, Mom's House) *</label>
                        <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. Home" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Recipient Full Name *</label>
                        <input type="text" name="full_name" class="form-control form-control-sm" value="<?php echo htmlspecialchars($current_user['name']); ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Contact Phone Number *</label>
                        <input type="text" name="phone" class="form-control form-control-sm" value="<?php echo htmlspecialchars($current_user['phone']); ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Flat / House No., Building Name *</label>
                        <input type="text" name="address_line1" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Street, Area, Landmark</label>
                        <input type="text" name="address_line2" class="form-control form-control-sm">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">City *</label>
                            <input type="text" name="city" class="form-control form-control-sm" value="Pune" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Pincode *</label>
                            <input type="text" name="pincode" class="form-control form-control-sm" placeholder="411001" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-maroon btn-sm w-100 fw-bold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Address
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
