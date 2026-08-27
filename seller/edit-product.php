<?php
$page_title = "Edit Product - Maker Portal";
$page_header = "Update Homemade Product";
$page_subheader = "Modify details, price or stock availability";
require_once __DIR__ . '/includes/header.php';

$product_id = $_GET['id'] ?? 1;
$all_products = get_all_products();
$product = $all_products[0];
foreach ($all_products as $p) {
    if ((int)$p['id'] === (int)$product_id) {
        $product = $p;
        break;
    }
}

$categories = get_categories();
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success_msg = "Product updated successfully!";
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
                <h5 class="dashboard-card-title"><i class="fa-solid fa-pen-to-square text-maroon-800"></i> Edit Product #<?php echo $product['id']; ?></h5>
            </div>
            <div class="p-4">
                <form action="edit-product.php?id=<?php echo $product['id']; ?>" method="POST">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Product Title *</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Category *</label>
                            <select name="category" class="form-select" required>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?php echo htmlspecialchars($c['name']); ?>" <?php echo $product['category'] === $c['name'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Packaging / Unit *</label>
                            <input type="text" name="unit" class="form-control" value="<?php echo htmlspecialchars($product['unit']); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Selling Price (₹) *</label>
                            <input type="number" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Original MRP (₹)</label>
                            <input type="number" name="original_price" class="form-control" value="<?php echo $product['original_price'] ?? ''; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Available Stock *</label>
                            <input type="number" name="stock" class="form-control" value="<?php echo $product['stock_quantity']; ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Description *</label>
                            <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>
                        <div class="col-12 pt-3 border-top mt-4">
                            <button type="submit" class="btn btn-maroon px-4 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Updates
                            </button>
                            <a href="products.php" class="btn btn-light border ms-2">Back to Catalog</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Product Image Preview -->
    <div class="col-lg-4">
        <div class="dashboard-card mb-4">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-image text-terracotta"></i> Product Image</h5>
            </div>
            <div class="p-3 text-center">
                <img src="<?php echo $product['images'][0]; ?>" class="img-fluid rounded-4 shadow-sm border mb-3" style="max-height: 220px; object-fit: cover;" alt="">
                <button class="btn btn-sm btn-outline-secondary w-100" onclick="alert('Image upload dialog');"><i class="fa-solid fa-camera me-1"></i> Replace Photo</button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
