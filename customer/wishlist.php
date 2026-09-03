<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$page_title = "My Wishlist - Customer Portal";
$page_header = "Saved Handmade Creations";
$page_subheader = "Items you are planning to order from our women home makers";
require_once __DIR__ . '/includes/header.php';

$products = get_all_products($pdo);

$stmt = $pdo->prepare("
    SELECT product_id
    FROM wishlist
    WHERE customer_id = ?
    ORDER BY created_at DESC
");

$stmt->execute([$customer_id]);

$wishlist_product_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

$wishlist_items = array_values(
    array_filter($products, function ($product) use ($wishlist_product_ids) {
        return in_array((int)$product['id'], array_map('intval', $wishlist_product_ids), true);
    })
);

?>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-heart text-danger"></i> Wishlist Items (<?php echo count($wishlist_items); ?>)</h5>
        <a href="../products.php" class="btn btn-outline-maroon btn-sm">Explore More</a>
    </div>
    <div class="p-3">
        <div class="row g-4">
            <?php foreach ($wishlist_items as $p): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border rounded-4 overflow-hidden shadow-sm">
                        <div class="position-relative">
                            <img src="<?php echo $p['images'][0]; ?>" class="w-100" style="height: 180px; object-fit: cover;" alt="">
                            <span class="position-absolute top-0 start-0 m-2 badge bg-warning text-dark"><?php echo htmlspecialchars($p['badge']); ?></span>
                        </div>
                        <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                            <div>
                                <small class="text-muted d-block mb-1"><?php echo htmlspecialchars($p['category']); ?></small>
                                <h6 class="fw-bold text-maroon-900 mb-1"><?php echo htmlspecialchars($p['name']); ?></h6>
                                <small class="text-terracotta d-block mb-2"><i class="fa-solid fa-store me-1"></i><?php echo htmlspecialchars($p['seller_name']); ?></small>
                            </div>
                            <div class="pt-3 border-top mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <strong class="fs-5 text-maroon-900">₹<?php echo $p['price']; ?></strong>
                                        <?php if (!empty($p['original_price'])): ?>
                                            <small class="text-muted text-decoration-line-through">₹<?php echo $p['original_price']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-warning small"><i class="fa-solid fa-star"></i> <?php echo $p['rating']; ?></span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="cart.php" class="btn btn-maroon btn-sm flex-grow-1 fw-bold"><i class="fa-solid fa-cart-plus me-1"></i> Move to Cart</a>
                                    <button class="btn btn-light btn-sm border text-danger" title="Remove" onclick="alert('Removed from wishlist');"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
