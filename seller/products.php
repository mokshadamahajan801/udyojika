<?php

$page_title = "My Products - Maker Portal";
$page_header = "My Homemade Products";
$page_subheader = "Manage item descriptions, prices, stock availability and ingredients";

require_once __DIR__ . '/includes/header.php';


/* ---------------------------------
   GET LOGGED-IN SELLER
--------------------------------- */

$current_user = get_logged_in_user();

$seller_id = 0;

if (!empty($current_user['id'])) {

    $seller_stmt = $pdo->prepare("
        SELECT id
        FROM sellers
        WHERE user_id = ?
        AND status = 'active'
        LIMIT 1
    ");

    $seller_stmt->execute([
        (int) $current_user['id']
    ]);

    $seller = $seller_stmt->fetch(PDO::FETCH_ASSOC);

    if ($seller) {
        $seller_id = (int) $seller['id'];
    }
}


/* ---------------------------------
   GET ALL PRODUCTS
--------------------------------- */

$all_products = get_all_products($pdo);


/* ---------------------------------
   FILTER ONLY CURRENT SELLER PRODUCTS
--------------------------------- */

$my_products = array_filter(
    $all_products,
    function ($p) use ($seller_id) {
        return (int)($p['seller_id'] ?? 0) === $seller_id;
    }
);

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <span class="badge bg-cream-200 text-maroon-900 border px-3 py-2">

            Active Products in Store:

            <strong>
                <?php echo count($my_products); ?>
            </strong>

        </span>

    </div>


    <a href="add-product.php" class="btn btn-maroon btn-sm">

        <i class="fa-solid fa-circle-plus me-1"></i>

        Add New Product

    </a>

</div>


<div class="dashboard-card">

    <div class="dashboard-card-header">

        <h5 class="dashboard-card-title">

            <i class="fa-solid fa-box-open text-maroon-800"></i>

            Products Catalog

        </h5>


        <div class="input-group input-group-sm" style="max-width: 250px;">

            <span class="input-group-text bg-white">

                <i class="fa-solid fa-magnifying-glass text-muted"></i>

            </span>

            <input
                type="text"
                id="dashboardTableSearch"
                class="form-control"
                placeholder="Search my products..."
            >

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

                <?php if (!empty($my_products)): ?>


                    <?php foreach ($my_products as $p): ?>

                        <tr>

                            <!-- Product -->

                            <td>

                                <div class="d-flex align-items-center gap-3">

                                    <?php
                                    $product_image = '';

                                    if (!empty($p['images']) && is_array($p['images'])) {
                                        $product_image = $p['images'][0] ?? '';
                                    }

                                    if (empty($product_image)) {
                                        $product_image = '../assets/images/product-placeholder.jpg';
                                    }
                                    ?>


                                    <img
                                        src="<?php echo htmlspecialchars($product_image); ?>"
                                        class="rounded-3 border"
                                        style="width: 48px; height: 48px; object-fit: cover;"
                                        alt=""
                                    >


                                    <div>

                                        <strong class="text-dark d-block">

                                            <?php
                                            echo htmlspecialchars(
                                                $p['name'] ?? 'Unnamed Product'
                                            );
                                            ?>

                                        </strong>


                                        <small class="text-muted">

                                            <?php
                                            echo htmlspecialchars(
                                                $p['unit'] ?? ''
                                            );
                                            ?>

                                        </small>

                                    </div>

                                </div>

                            </td>


                            <!-- Category -->

                            <td>

                                <span class="badge bg-light text-dark border">

                                    <?php
                                    echo htmlspecialchars(
                                        $p['category'] ?? 'Other'
                                    );
                                    ?>

                                </span>

                            </td>


                            <!-- Price -->

                            <td>

                                <strong class="text-maroon-800">

                                    ₹<?php
                                    echo number_format(
                                        (float)($p['price'] ?? 0),
                                        2
                                    );
                                    ?>

                                </strong>


                                <?php if (!empty($p['original_price'])): ?>

                                    <small class="text-muted text-decoration-line-through d-block">

                                        ₹<?php
                                        echo number_format(
                                            (float)$p['original_price'],
                                            2
                                        );
                                        ?>

                                    </small>

                                <?php endif; ?>

                            </td>


                            <!-- Stock -->

                            <td>

                                <span class="badge bg-success-subtle text-success border border-success-subtle">

                                    <?php
                                    echo (int)($p['stock'] ?? 0);
                                    ?>

                                    available

                                </span>

                            </td>


                            <!-- Rating -->

                            <td>

                                <span class="text-warning small fw-bold">

                                    <i class="fa-solid fa-star"></i>

                                    <?php
                                    echo number_format(
                                        (float)($p['rating'] ?? 0),
                                        1
                                    );
                                    ?>

                                </span>


                                <small class="text-muted">

                                    (<?php
                                    echo (int)($p['review_count'] ?? 0);
                                    ?>)

                                </small>

                            </td>


                            <!-- Status -->

                            <td>

                                <?php if (($p['status'] ?? 'active') === 'active'): ?>

                                    <span class="badge-status-active">
                                        Active
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-secondary">
                                        <?php echo htmlspecialchars($p['status']); ?>
                                    </span>

                                <?php endif; ?>

                            </td>


                            <!-- Actions -->

                            <td>

                                <div class="btn-group btn-group-sm">


                                    <!-- View -->

                                    <a
                                        href="../product-details.php?slug=<?php echo urlencode($p['slug'] ?? ''); ?>"
                                        target="_blank"
                                        class="btn btn-light border"
                                        title="View in Store"
                                    >

                                        <i class="fa-regular fa-eye"></i>

                                    </a>


                                    <!-- Edit -->

                                    <a
                                        href="edit-product.php?id=<?php echo (int)$p['id']; ?>"
                                        class="btn btn-light border"
                                        title="Edit Product"
                                    >

                                        <i class="fa-solid fa-pen"></i>

                                    </a>


                                    <!-- Delete -->

                                    <button
                                        class="btn btn-light border text-danger"
                                        title="Delete"
                                        onclick="if(confirm('Remove this product?')) alert('Delete functionality will be connected next.');"
                                    >

                                        <i class="fa-solid fa-trash-can"></i>

                                    </button>


                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>


                <?php else: ?>

                    <tr>

                        <td colspan="7" class="text-center py-5">

                            <div class="text-muted">

                                <i class="fa-solid fa-box-open fs-1 mb-3"></i>

                                <h5>No Products Found</h5>

                                <p class="mb-3">
                                    You haven't added any products yet.
                                </p>

                                <a
                                    href="add-product.php"
                                    class="btn btn-maroon btn-sm"
                                >

                                    <i class="fa-solid fa-circle-plus me-1"></i>

                                    Add Your First Product

                                </a>

                            </div>

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>


<script>

/* ---------------------------------
   SEARCH MY PRODUCTS
--------------------------------- */

const searchInput = document.getElementById('dashboardTableSearch');

if (searchInput) {

    searchInput.addEventListener('keyup', function () {

        const searchValue = this.value.toLowerCase();

        const rows = document.querySelectorAll(
            '.dashboard-table tbody tr'
        );

        rows.forEach(function (row) {

            const rowText = row.textContent.toLowerCase();

            if (rowText.includes(searchValue)) {

                row.style.display = '';

            } else {

                row.style.display = 'none';

            }

        });

    });

}

</script>


<?php require_once __DIR__ . '/includes/footer.php'; ?>