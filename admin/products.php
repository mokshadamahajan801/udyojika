<?php

require_once __DIR__ . '/includes/auth.php';
require_admin();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$products = get_all_products($pdo);

$page_title = "Products - Admin Portal";
$page_header = "Products";
$page_subheader = "View all products listed by sellers";

require_once __DIR__ . '/includes/header.php';

?>

<!-- Search & Count -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

    <span class="badge bg-cream-200 text-maroon-900 border px-3 py-2">
        Total Products:
        <strong><?php echo count($products); ?></strong>
    </span>

    <div class="input-group input-group-sm" style="max-width: 300px;">
        <span class="input-group-text bg-white">
            <i class="fa-solid fa-magnifying-glass text-muted"></i>
        </span>

        <input
            type="text"
            id="dashboardTableSearch"
            class="form-control"
            placeholder="Search product or maker...">
    </div>

</div>


<!-- Products Card -->
<div class="dashboard-card">

    <div class="dashboard-card-header">

        <h5 class="dashboard-card-title">
            <i class="fa-solid fa-box-open text-maroon-800"></i>
            All Products
        </h5>

    </div>


    <div class="table-responsive">

        <table class="dashboard-table">

            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Seller / Maker</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>


            <tbody>

                <?php if (!empty($products)): ?>

                    <?php foreach ($products as $p): ?>

                        <tr>

                            <!-- Product -->
                            <td>

                                <div class="d-flex align-items-center gap-2">

                                    <?php
                                    $product_image = 'https://via.placeholder.com/60';

                                    if (!empty($p['images']) && is_array($p['images'])) {
                                        $product_image = $p['images'][0];
                                    }
                                    ?>

                                    <img
                                        src="<?php echo htmlspecialchars($product_image); ?>"
                                        class="rounded-3 border"
                                        style="width:44px;height:44px;object-fit:cover;"
                                        alt="Product">

                                    <div>

                                        <strong class="text-dark d-block">
                                            <?php
                                            echo htmlspecialchars(
                                                $p['name'] ?? 'Unnamed Product'
                                            );
                                            ?>
                                        </strong>

                                        <?php if (!empty($p['unit'])): ?>

                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($p['unit']); ?>
                                            </small>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </td>


                            <!-- Category -->
                            <td>

                                <span class="badge bg-light text-dark border">

                                    <?php
                                    echo htmlspecialchars(
                                        $p['category'] ?? 'N/A'
                                    );
                                    ?>

                                </span>

                            </td>


                            <!-- Seller -->
                            <td>

                                <strong class="text-maroon-900">

                                    <?php
                                    echo htmlspecialchars(
                                        $p['seller_name'] ?? 'Unknown Seller'
                                    );
                                    ?>

                                </strong>

                                <?php if (!empty($p['seller_location'])): ?>

                                    <small class="text-muted d-block">

                                        <?php
                                        echo htmlspecialchars(
                                            $p['seller_location']
                                        );
                                        ?>

                                    </small>

                                <?php endif; ?>

                            </td>


                            <!-- Price -->
                            <td>

                                <strong class="text-maroon-800">

                                    ₹<?php
                                        echo number_format(
                                            (float) ($p['price'] ?? 0),
                                            2
                                        );
                                        ?>

                                </strong>

                            </td>


                            <!-- Stock -->
                            <td>

                                <span class="badge bg-light text-dark border">

                                    <?php
                                    echo (int) ($p['stock_quantity'] ?? 0);
                                    ?>

                                    in stock

                                </span>

                            </td>


                            <!-- Rating -->
                            <td>

                                <span class="text-warning small fw-bold">

                                    <i class="fa-solid fa-star"></i>

                                    <?php
                                    echo number_format(
                                        (float) ($p['rating'] ?? 0),
                                        1
                                    );
                                    ?>

                                </span>

                                <small class="text-muted">

                                    (<?php
                                        echo (int) ($p['review_count'] ?? 0);
                                        ?>)

                                </small>

                            </td>


                            <!-- Status -->
                            <td>

                                <?php
                                $product_status = strtolower(
                                    $p['status'] ?? 'active'
                                );
                                ?>

                                <span class="badge-status-<?php
                                                            echo htmlspecialchars($product_status);
                                                            ?>">

                                    <?php
                                    echo ucfirst($product_status);
                                    ?>

                                </span>

                            </td>


                            <!-- View Only -->
                            <td>

                                <?php if (!empty($p['slug'])): ?>

                                    <a href="product-view.php?id=<?php echo $p['id']; ?>"
                                        class="btn btn-light border"
                                        title="View Product">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    
                                <?php else: ?>

                                    <span class="text-muted small">
                                        No page
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="8" class="text-center py-5">

                            <div class="text-muted">

                                <i class="fa-solid fa-box-open fs-1 mb-3"></i>

                                <h6>No Products Found</h6>

                                <p class="small mb-0">
                                    No products have been added by sellers yet.
                                </p>

                            </div>

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>


<?php require_once __DIR__ . '/includes/footer.php'; ?>