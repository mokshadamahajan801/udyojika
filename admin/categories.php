<?php
$page_title = "Manage Categories - Admin Portal";
$page_header = "Marketplace Categories";
$page_subheader = "Add, edit, or delete categories and browse departmental listings";
require_once __DIR__ . '/includes/header.php';

$categories = get_categories();
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_name = trim($_POST['category_name'] ?? '');
    if (!empty($cat_name)) {
        $success_msg = "Category '{$cat_name}' added successfully!";
    }
}
?>

<?php if (!empty($success_msg)): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="fa-solid fa-circle-check fs-4"></i>
        <div><?php echo htmlspecialchars($success_msg); ?></div>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Add Category Form Column -->
    <div class="col-lg-4">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-plus text-terracotta"></i> Add New Category</h5>
            </div>
            <div class="p-3">
                <form action="categories.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Category Title *</label>
                        <input type="text" name="category_name" class="form-control" required placeholder="e.g. Handmade Pottery & Decor">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">URL Slug *</label>
                        <input type="text" name="slug" class="form-control" required placeholder="e.g. handmade-pottery-decor">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Font Awesome Icon Class</label>
                        <input type="text" name="icon" class="form-control" placeholder="fa-utensils" value="fa-palette">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Category Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Brief description for marketplace department..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-maroon w-100 fw-bold">
                        <i class="fa-solid fa-plus me-1"></i> Save Category
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Category Listing Table -->
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-layer-group text-maroon-800"></i> Active Departments (<?php echo count($categories); ?>)</h5>
            </div>
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Icon</th>
                            <th>Products</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $c): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?php echo $c['image']; ?>" class="rounded-3" style="width: 44px; height: 44px; object-fit: cover;" alt="">
                                        <strong class="text-maroon-900"><?php echo htmlspecialchars($c['name']); ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-cream-200 text-maroon-800 p-2"><i class="fa-solid <?php echo $c['icon']; ?> fs-6"></i></span>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?php echo $c['product_count']; ?> items</span></td>
                                <td class="small text-muted" style="max-width: 250px;"><?php echo htmlspecialchars(substr($c['description'], 0, 70)); ?>...</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-light border" title="Edit" onclick="alert('Edit <?php echo htmlspecialchars($c['name']); ?>');"><i class="fa-solid fa-pen"></i></button>
                                        <button class="btn btn-light border text-danger" title="Delete" onclick="if(confirm('Delete this category?')) alert('Category removed.');"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
