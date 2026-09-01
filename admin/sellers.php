<?php
$page_title = "Verified Sellers - Admin Portal";
$page_header = "Active Women Sellers & Makers";
$page_subheader = "Manage verified maker profiles, ratings, badges and active storefront status";
require_once __DIR__ . '/includes/header.php';

require_once __DIR__ . '/includes/auth.php';
require_admin();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$sellers = get_sellers($pdo);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <span class="badge bg-cream-200 text-maroon-900 border px-3 py-2">
            <i class="fa-solid fa-certificate text-warning me-1"></i> Total Verified Makers: <strong><?php echo count($sellers); ?></strong>
        </span>
    </div>
    <a href="seller-requests.php" class="btn btn-maroon btn-sm">
        <i class="fa-solid fa-user-plus me-1"></i> Review New Applications (2)
    </a>
</div>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-id-badge text-maroon-800"></i> Verified Women Entrepreneurs Directory</h5>
    </div>
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Maker ID</th>
                    <th>Maker & Brand</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Products</th>
                    <th>Rating</th>
                    <th>Verification</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sellers as $s): ?>
                    <tr>
                        <td class="fw-bold text-muted">#MKR-<?php echo str_pad($s['id'], 3, '0', STR_PAD_LEFT); ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?php echo $s['avatar']; ?>" class="rounded-circle border" style="width: 40px; height: 40px; object-fit: cover;" alt="">
                                <div>
                                    <strong class="text-maroon-900 d-block"><?php echo htmlspecialchars($s['owner_name']); ?></strong>
                                    <small class="text-muted"><i class="fa-solid fa-store me-1"></i><?php echo htmlspecialchars($s['business_name']); ?></small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($s['category']); ?></span></td>
                        <td><i class="fa-solid fa-location-dot text-danger me-1"></i><?php echo htmlspecialchars($s['location']); ?></td>
                        <td><span class="fw-bold"><?php echo $s['product_count']; ?></span> items</td>
                        <td>
                            <span class="text-warning fw-bold"><i class="fa-solid fa-star"></i> <?php echo $s['rating']; ?></span>
                            <small class="text-muted">(<?php echo $s['review_count']; ?>)</small>
                        </td>
                        <td>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="fa-solid fa-circle-check me-1"></i> Verified
                            </span>
                        </td>
                        <td><span class="badge-status-active">Active</span></td>
                        <td>
    <div class="btn-group btn-group-sm">

        <!-- VIEW SELLER -->
        <a
            href="seller-view.php?id=<?php echo (int)$s['id']; ?>"
            class="btn btn-light border"
            title="View Seller"
        >
            <i class="fa-regular fa-eye"></i>
        </a>



    </div>
</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
