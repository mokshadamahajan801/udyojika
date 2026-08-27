<?php
$page_title = "Manage Products - Admin Portal";
$page_header = "Marketplace Products Catalog";
$page_subheader = "Approve, moderate, edit prices or toggle featured homemade products";
require_once __DIR__ . '/includes/header.php';

$products = get_all_products();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-cream-200 text-maroon-900 border px-3 py-2">
            Total Live Products: <strong><?php echo count($products); ?></strong>
        </span>
    </div>
    <div class="input-group input-group-sm" style="max-width: 300px;">
        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
        <input type="text" id="dashboardTableSearch" class="form-control" placeholder="Search product or maker...">
    </div>
</div>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-box-open text-maroon-800"></i> All Homemade Products</h5>
    </div>
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Product & Image</th>
                    <th>Category</th>
                    <th>Seller / Maker</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Rating</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?php echo $p['images'][0]; ?>" class="rounded-3 border" style="width: 44px; height: 44px; object-fit: cover;" alt="">
                                <div>
                                    <strong class="text-dark d-block"><?php echo htmlspecialchars($p['name']); ?></strong>
                                    <small class="text-muted"><?php echo htmlspecialchars($p['unit']); ?></small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($p['category']); ?></span></td>
                        <td>
                            <strong class="text-maroon-900"><?php echo htmlspecialchars($p['seller_name']); ?></strong>
                            <small class="text-muted d-block"><?php echo htmlspecialchars($p['seller_location']); ?></small>
                        </td>
                        <td>
                            <strong class="text-maroon-800">₹<?php echo $p['price']; ?></strong>
                            <?php if (!empty($p['original_price'])): ?>
                                <small class="text-muted text-decoration-line-through d-block">₹<?php echo $p['original_price']; ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border"><?php echo $p['stock_quantity']; ?> in stock</span>
                        </td>
                        <td>
                            <span class="text-warning small fw-bold"><i class="fa-solid fa-star"></i> <?php echo $p['rating']; ?></span>
                            <small class="text-muted">(<?php echo $p['review_count']; ?>)</small>
                        </td>
                        <td>
                            <?php if ($p['is_featured']): ?>
                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-star me-1"></i> Featured</span>
                            <?php else: ?>
                                <span class="text-muted small">Standard</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge-status-active">Active</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="../product-details.php?slug=<?php echo $p['slug']; ?>" target="_blank" class="btn btn-light border" title="View Public Page"><i class="fa-regular fa-eye"></i></a>
                                <button class="btn btn-light border" title="Edit Product" onclick="alert('Edit Product #<?php echo $p['id']; ?>');"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-light border text-danger" title="Delete/Hide Product" onclick="if(confirm('Hide product from marketplace?')) alert('Product status updated.');"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
