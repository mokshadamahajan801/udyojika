<?php

require_once __DIR__ . '/includes/auth.php';
require_admin();

require_once __DIR__ . '/../includes/db.php';


// =====================================================
// GET PRODUCT ID
// =====================================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: products.php?error=invalid_product');
    exit;
}

$product_id = (int) $_GET['id'];


// =====================================================
// FETCH PRODUCT + SELLER
// =====================================================

$stmt = $pdo->prepare("
    SELECT
        p.id,
        p.name,
        p.slug,
        p.category_id,
        p.category_name,
        p.seller_id,
        p.price,
        p.original_price,
        p.unit,
        p.rating,
        p.review_count,
        p.badge,
        p.in_stock,
        p.stock_quantity,
        p.images,
        p.description,
        p.features,
        p.ingredients,
        p.prep_time,
        p.is_featured,
        p.status,
        p.created_at,

        s.business_name,
        s.owner_name,
        s.location AS seller_location,
        s.email AS seller_email,
        s.status AS seller_status

    FROM products p
    LEFT JOIN sellers s ON p.seller_id = s.id
    WHERE p.id = ?
    LIMIT 1
");

$stmt->execute([$product_id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);


// =====================================================
// PRODUCT NOT FOUND
// =====================================================

if (!$product) {
    header('Location: products.php?error=product_not_found');
    exit;
}


// =====================================================
// PAGE INFORMATION
// =====================================================

$page_title = "View Product - Udyojika";
$page_header = "Product Details";
$page_subheader = "View complete product and seller information";

require_once __DIR__ . '/includes/header.php';


// =====================================================
// PRODUCT IMAGES
// =====================================================

$images = [];

if (!empty($product['images'])) {

    $decoded = json_decode($product['images'], true);

    if (is_array($decoded)) {
        $images = $decoded;
    } else {
        $images = array_filter(
            array_map('trim', explode(',', $product['images']))
        );
    }
}

$main_image = !empty($images[0])
    ? $images[0]
    : 'https://via.placeholder.com/500x400?text=No+Image';


// =====================================================
// STATUS
// =====================================================

$status = strtolower($product['status'] ?? 'inactive');


// =====================================================
// FEATURED
// =====================================================

$is_featured = !empty($product['is_featured']);

?>

<div class="container-fluid">

    <!-- BACK BUTTON -->
    <div class="mb-4">
        <a href="products.php" class="btn btn-light border">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back to Products
        </a>
    </div>


    <!-- =================================================
         PRODUCT MAIN CARD
    ================================================== -->

    <div class="dashboard-card mb-4">

        <div class="dashboard-card-header">

            <div>
                <h5 class="dashboard-card-title mb-1">
                    <i class="fa-solid fa-box-open text-maroon-800"></i>
                    Product Information
                </h5>

                <small class="text-muted">
                    Product ID #<?php echo $product['id']; ?>
                </small>
            </div>

            <div class="d-flex gap-2">

                <?php if ($is_featured): ?>
                    <span class="badge bg-warning text-dark px-3 py-2">
                        <i class="fa-solid fa-star me-1"></i>
                        Featured
                    </span>
                <?php endif; ?>

                <span class="badge-status-<?php echo htmlspecialchars($status); ?>">
                    <?php echo ucfirst($status); ?>
                </span>

            </div>

        </div>


        <div class="p-4">

            <div class="row g-4">

                <!-- PRODUCT IMAGE -->

                <div class="col-lg-5">

                    <div class="text-center">

                        <img
                            src="<?php echo htmlspecialchars($main_image); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>"
                            class="img-fluid rounded-4 border shadow-sm"
                            style="width:100%; max-height:420px; object-fit:cover;"
                        >

                    </div>


                    <?php if (count($images) > 1): ?>

                        <div class="d-flex gap-2 mt-3 flex-wrap">

                            <?php foreach ($images as $image): ?>

                                <img
                                    src="<?php echo htmlspecialchars($image); ?>"
                                    class="rounded-3 border"
                                    style="width:70px;height:70px;object-fit:cover;"
                                    alt=""
                                >

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- PRODUCT BASIC DETAILS -->

                <div class="col-lg-7">

                    <h2 class="fw-bold text-maroon-900 mb-2">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </h2>


                    <?php if (!empty($product['badge'])): ?>

                        <span class="badge bg-warning text-dark mb-3">
                            <?php echo htmlspecialchars($product['badge']); ?>
                        </span>

                    <?php endif; ?>


                    <div class="row g-3 mb-4">

                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Product ID
                            </small>

                            <strong>
                                #<?php echo $product['id']; ?>
                            </strong>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Category
                            </small>

                            <strong>
                                <?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?>
                            </strong>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Price
                            </small>

                            <strong class="text-maroon-800 fs-5">
                                ₹<?php echo number_format((float)$product['price'], 2); ?>
                            </strong>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Original Price
                            </small>

                            <?php if (!empty($product['original_price'])): ?>

                                <span class="text-muted text-decoration-line-through">
                                    ₹<?php echo number_format((float)$product['original_price'], 2); ?>
                                </span>

                            <?php else: ?>

                                <span class="text-muted">
                                    Not specified
                                </span>

                            <?php endif; ?>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Unit
                            </small>

                            <strong>
                                <?php echo htmlspecialchars($product['unit'] ?? 'piece'); ?>
                            </strong>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Stock
                            </small>

                            <?php if ($product['in_stock']): ?>

                                <span class="badge bg-success">
                                    Available
                                </span>

                            <?php else: ?>

                                <span class="badge bg-danger">
                                    Out of Stock
                                </span>

                            <?php endif; ?>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Stock Quantity
                            </small>

                            <strong>
                                <?php echo (int)$product['stock_quantity']; ?>
                            </strong>

                        </div>


                        <div class="col-md-6">

                            <small class="text-muted d-block">
                                Rating
                            </small>

                            <strong class="text-warning">
                                <i class="fa-solid fa-star"></i>
                                <?php echo number_format((float)$product['rating'], 1); ?>
                            </strong>

                            <small class="text-muted">
                                (<?php echo (int)$product['review_count']; ?> reviews)
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- =================================================
         DESCRIPTION
    ================================================== -->

    <div class="dashboard-card mb-4">

        <div class="dashboard-card-header">

            <h5 class="dashboard-card-title">
                <i class="fa-solid fa-align-left text-maroon-800"></i>
                Product Description
            </h5>

        </div>

        <div class="p-4">

            <?php if (!empty($product['description'])): ?>

                <p class="text-secondary mb-0">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </p>

            <?php else: ?>

                <p class="text-muted mb-0">
                    No description provided.
                </p>

            <?php endif; ?>

        </div>

    </div>



    <!-- =================================================
         FEATURES / INGREDIENTS
    ================================================== -->

    <div class="row g-4 mb-4">

        <div class="col-lg-6">

            <div class="dashboard-card h-100">

                <div class="dashboard-card-header">

                    <h5 class="dashboard-card-title">
                        <i class="fa-solid fa-list-check text-maroon-800"></i>
                        Features
                    </h5>

                </div>

                <div class="p-4">

                    <?php if (!empty($product['features'])): ?>

                        <p class="text-secondary mb-0">
                            <?php echo nl2br(htmlspecialchars($product['features'])); ?>
                        </p>

                    <?php else: ?>

                        <p class="text-muted mb-0">
                            No features provided.
                        </p>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <div class="col-lg-6">

            <div class="dashboard-card h-100">

                <div class="dashboard-card-header">

                    <h5 class="dashboard-card-title">
                        <i class="fa-solid fa-leaf text-success"></i>
                        Ingredients
                    </h5>

                </div>

                <div class="p-4">

                    <?php if (!empty($product['ingredients'])): ?>

                        <p class="text-secondary mb-0">
                            <?php echo nl2br(htmlspecialchars($product['ingredients'])); ?>
                        </p>

                    <?php else: ?>

                        <p class="text-muted mb-0">
                            No ingredients information provided.
                        </p>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>



    <!-- =================================================
         PREPARATION
    ================================================== -->

    <div class="dashboard-card mb-4">

        <div class="p-4">

            <div class="row g-4">

                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Preparation Time
                    </small>

                    <strong>
                        <?php echo htmlspecialchars($product['prep_time'] ?? 'Not specified'); ?>
                    </strong>

                </div>


                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Added On
                    </small>

                    <strong>
                        <?php
                        echo !empty($product['created_at'])
                            ? date('d M Y, h:i A', strtotime($product['created_at']))
                            : 'N/A';
                        ?>
                    </strong>

                </div>

            </div>

        </div>

    </div>



    <!-- =================================================
         SELLER INFORMATION
    ================================================== -->

    <div class="dashboard-card mb-4">

        <div class="dashboard-card-header">

            <h5 class="dashboard-card-title">
                <i class="fa-solid fa-store text-maroon-800"></i>
                Seller / Maker Information
            </h5>

        </div>

        <div class="p-4">

            <?php if (!empty($product['seller_id'])): ?>

                <div class="row g-4">

                    <div class="col-md-6">

                        <small class="text-muted d-block">
                            Seller / Owner
                        </small>

                        <strong>
                            <?php echo htmlspecialchars($product['owner_name'] ?? 'N/A'); ?>
                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block">
                            Business Name
                        </small>

                        <strong>
                            <?php echo htmlspecialchars($product['business_name'] ?? 'N/A'); ?>
                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block">
                            Location
                        </small>

                        <strong>
                            <?php echo htmlspecialchars($product['seller_location'] ?? 'N/A'); ?>
                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block">
                            Email
                        </small>

                        <?php if (!empty($product['seller_email'])): ?>

                            <a
                                href="mailto:<?php echo htmlspecialchars($product['seller_email']); ?>"
                                class="text-decoration-none"
                            >
                                <?php echo htmlspecialchars($product['seller_email']); ?>
                            </a>

                        <?php else: ?>

                            <span class="text-muted">
                                N/A
                            </span>

                        <?php endif; ?>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block">
                            Seller Status
                        </small>

                        <span class="badge-status-<?php echo htmlspecialchars(strtolower($product['seller_status'] ?? 'inactive')); ?>">
                            <?php echo ucfirst($product['seller_status'] ?? 'Unknown'); ?>
                        </span>

                    </div>

                </div>

            <?php else: ?>

                <div class="alert alert-warning mb-0">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                    Seller information is not available for this product.
                </div>

            <?php endif; ?>

        </div>

    </div>



    <!-- =================================================
         FINAL BACK
    ================================================== -->

    <div class="d-flex justify-content-end mb-4">

        <a href="products.php" class="btn btn-light border">

            <i class="fa-solid fa-arrow-left me-1"></i>

            Back to Products

        </a>

    </div>

</div>


<?php require_once __DIR__ . '/includes/footer.php'; ?>