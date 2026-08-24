<?php
$page_title = "My Saved Wishlist - Udyojika";
require_once __DIR__ . '/includes/header.php';

$all_products = get_all_products($pdo);
?>

<div class="bg-cream-100 py-4 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-maroon-800 fw-bold" aria-current="page">Saved Items</li>
            </ol>
        </nav>
        <h2 class="font-serif fw-bold text-maroon-900 mb-0">Your Saved Homemade Favorites</h2>
    </div>
</div>

<div class="container py-5">
    <div id="wishlist-empty-view" style="display: none;" class="text-center py-5 bg-white rounded-4 border p-5">
        <div class="display-3 text-muted mb-3"><i class="fa-regular fa-heart"></i></div>
        <h3 class="font-serif fw-bold text-maroon-900 mb-2">Your Wishlist is Empty</h3>
        <p class="text-muted mb-4">Tap the heart icon on any homemade food or craft to save it for later.</p>
        <a href="products.php" class="btn btn-maroon btn-lg px-4">Explore Products</a>
    </div>

    <div id="wishlist-grid-view" class="row g-4">
        <!-- Wishlist items loaded by JavaScript -->
    </div>
</div>

<script>
const productsCatalog = <?php echo json_encode($all_products); ?>;

function renderWishlist() {
    const wishlist = window.udyojika.getWishlist();
    const emptyView = document.getElementById('wishlist-empty-view');
    const gridView = document.getElementById('wishlist-grid-view');

    if (!wishlist || wishlist.length === 0) {
        emptyView.style.display = 'block';
        gridView.innerHTML = '';
        return;
    }

    const savedProducts = productsCatalog.filter(p => wishlist.includes(String(p.id)));

    if (savedProducts.length === 0) {
        emptyView.style.display = 'block';
        gridView.innerHTML = '';
        return;
    }

    emptyView.style.display = 'none';
    let html = '';

    savedProducts.forEach(product => {
        html += `
            <div class="col-12 col-sm-6 col-md-4">
                <div class="product-card">
                    <div class="img-container">
                        <button type="button" class="btn-wishlist active text-danger" onclick="removeFromWishlist('${product.id}')">
                            <i class="fa-solid fa-heart"></i>
                        </button>
                        <a href="product-details.php?slug=${encodeURIComponent(product.slug)}">
                            <img src="${product.images[0]}" alt="${product.name}">
                        </a>
                    </div>
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <h6 class="fw-bold mb-1">
                            <a href="product-details.php?slug=${encodeURIComponent(product.slug)}" class="text-dark text-decoration-none">
                                ${product.name}
                            </a>
                        </h6>
                        <small class="text-muted mb-3">By <strong>${product.seller_name}</strong> (${product.seller_location})</small>
                        <div class="mt-auto d-flex align-items-center justify-content-between pt-2 border-top">
                            <span class="fs-5 fw-bold text-maroon-800">₹${product.price}</span>
                            <button type="button" class="btn btn-maroon btn-sm px-3" onclick="window.addToCart('${product.id}', '${product.name.replace(/'/g, "\\'")}', ${product.price}, '${product.images[0]}', '${product.seller_name.replace(/'/g, "\\'")}', '${product.unit}', 1)">
                                <i class="fa-solid fa-plus me-1"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    gridView.innerHTML = html;
}

function removeFromWishlist(id) {
    let wishlist = window.udyojika.getWishlist();
    wishlist = wishlist.filter(x => x !== String(id));
    window.udyojika.saveWishlist(wishlist);
    renderWishlist();
    window.udyojika.showToast('Item removed from wishlist.', 'info', 'Wishlist');
}

document.addEventListener('DOMContentLoaded', renderWishlist);
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
