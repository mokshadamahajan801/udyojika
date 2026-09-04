<?php
$page_title = "Add New Product - Maker Portal";
$page_header = "List a New Homemade Creation";
$page_subheader = "Add details, photos, ingredients and pricing for your handmade product";
require_once __DIR__ . '/includes/header.php';

$categories = get_categories();
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p_name = trim($_POST['name'] ?? '');
    $success_msg = "Product '{$p_name}' created successfully and is now LIVE in the marketplace!";
}
?>

<?php if (!empty($success_msg)): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="fa-solid fa-circle-check fs-4"></i>
        <div><?php echo htmlspecialchars($success_msg); ?> <a href="products.php" class="alert-link ms-2">View in My Products &rarr;</a></div>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-circle-plus text-maroon-800"></i> Product Information</h5>
            </div>
            <div class="p-4">
                <form action="add-product.php" method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Product Title *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Authentic Poha Chivda with Roasted Peanuts & Curry Leaves" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Category *</label>
                            <select name="category" class="form-select" required>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?php echo htmlspecialchars($c['name']); ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Packaging / Unit (e.g. 500g box, set of 2, 250ml bottle) *</label>
                            <input type="text" name="unit" class="form-control" placeholder="e.g. 500g box" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Selling Price (₹) *</label>
                            <input type="number" name="price" class="form-control" placeholder="e.g. 280" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Original MRP (₹) (Optional discount display)</label>
                            <input type="number" name="original_price" class="form-control" placeholder="e.g. 320">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Available Stock Quantity *</label>
                            <input type="number" name="stock" class="form-control" value="20" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Detailed Product Description *</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Describe the taste, materials, technique or traditional recipe behind this product..." required></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Ingredients or Raw Materials (comma separated)</label>
                            <input type="text" name="ingredients" class="form-control" placeholder="e.g. Roasted Poha, Groundnut Oil, Mustard Seeds, Green Chilli, Curry Leaves, Rock Salt">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Key Homemade Highlights (One per line)</label>
                            <textarea name="features" class="form-control" rows="3" placeholder="100% Cold-pressed oil&#10;No artificial chemicals or preservatives&#10;Freshly made in micro batches"></textarea>
                        </div>

                        <!-- Image Upload Mock / Preview -->
                        <div class="col-12">
                            <label class="form-label small fw-bold">Product Photos (Upload high quality images)</label>
                            <input type="file" id="imageUploadInput" class="form-control" accept="image/*" multiple>
                            <div id="imagePreviewContainer" class="d-flex flex-wrap mt-2"></div>
                            <small class="text-muted">Tip: Clear natural lighting photos increase customer purchases by 40%.</small>
                        </div>

                        <div class="col-12 pt-3 border-top mt-4">
                            <button type="submit" class="btn btn-maroon px-4 fw-bold">
                                <i class="fa-solid fa-cloud-arrow-up me-1"></i> Publish Product to Store
                            </button>
                            <a href="products.php" class="btn btn-light border ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Maker Guidelines Sidecard -->
    <div class="col-lg-4">
        <div class="dashboard-card mb-4">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-lightbulb text-warning"></i> Listing Tips</h5>
            </div>
            <div class="p-3 small text-secondary">
                <ul class="d-flex flex-column gap-2 mb-0 ps-3">
                    <li><strong>Authentic homemade recipes:</strong> Mention ingredients clearly for food allergies.</li>
                    <li><strong>Accurate weights:</strong> Clearly list pack size (e.g. 500g, 1kg, single piece).</li>
                    <li><strong>Safe packaging:</strong> Mention double-seal or bubble wrap protection for fragile items.</li>
                    <li><strong>Zero Commission:</strong> Udyojika does not charge commission on your listed prices.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>