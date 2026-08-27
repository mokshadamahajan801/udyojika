<?php
$page_title = "My Products - Maker Portal";
$page_header = "My Homemade Products";
$page_subheader = "Manage item descriptions, prices, stock availability and ingredients";
require_once __DIR__ . '/includes/header.php';

$all_products = get_all_products();
$my_products = array_filter($all_products, fn($p) => (int)$p['seller_id'] === (int)$seller_id);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <span class="badge bg-cream-200 text-maroon-900 border px-3 py-2">
            Active Products in Store: <strong><?php echo count($my_products); ?></strong>
        </span>
    </div>
    <a href="add-product.php" class="btn btn-maroon btn-sm">
        <i class="fa-solid fa-circle-plus me-1"></i> Add New Product
    </a>
</div>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-box-open text-maroon-800"></i> Products Catalog</h5>
        <div class="input-group input-group-sm" style="max-width: 250px;">
            <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="text" id="dashboardTableSearch" class="form-control" placeholder="Search my products...">
        </div>
    </div>
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Product & Image</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock Units</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($my_products as $p): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?php echo $p['images'][0]; ?>" class="rounded-3 border" style="width: 48px; height: 48px; object-fit: cover;" alt="">
                                <div>
                                    <strong class="text-dark d-block"><?php echo htmlspecialchars($p['name']); ?></strong>
                                    <small class="text-muted"><?php echo htmlspecialchars($p['unit']); ?></small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($p['category']); ?></span></td>
                        <td>
                            <strong class="text-maroon-800">₹<?php echo $p['price']; ?></strong>
                            <?php if (!empty($p['original_price'])): ?>
                                <small class="text-muted text-decoration-line-through d-block">₹<?php echo $p['original_price']; ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <?php echo $p['stock_quantity']; ?> available
                            </span>
                        </td>
                        <td>
                            <span class="text-warning small fw-bold"><i class="fa-solid fa-star"></i> <?php echo $p['rating']; ?></span>
                            <small class="text-muted">(<?php echo $p['review_count']; ?>)</small>
                        </td>
                        <td><span class="badge-status-active">Active</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="../product-details.php?slug=<?php echo $p['slug']; ?>" target="_blank" class="btn btn-light border" title="View in Store"><i class="fa-regular fa-eye"></i></a>
                                <a href="edit-product.php?id=<?php echo $p['id']; ?>" class="btn btn-light border" title="Edit Product"><i class="fa-solid fa-pen"></i></a>
                                <button class="btn btn-light border text-danger" title="Delete" onclick="if(confirm('Remove this product?')) alert('Product removed.');"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
