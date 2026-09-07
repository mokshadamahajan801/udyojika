<?php

require_once __DIR__ . '/includes/auth.php';

$page_title = "My Cart - Customer Portal";
$page_header = "Your Shopping Basket";
$page_subheader = "Review handmade items, select delivery address and proceed to checkout";

/*
|--------------------------------------------------------------------------
| Update Cart Quantity
|--------------------------------------------------------------------------
*/
if (isset($_GET['update_id'], $_GET['quantity'])) {

    $update_id = (int)$_GET['update_id'];
    $quantity = max(1, (int)$_GET['quantity']);

    $stmt = $pdo->prepare("
        UPDATE cart_items
        SET quantity = ?
        WHERE id = ?
          AND customer_id = ?
    ");

    $stmt->execute([
        $quantity,
        $update_id,
        $customer_id
    ]);

    header("Location: cart.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Remove Cart Item
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_cart_id'])) {

    $remove_id = (int)$_POST['remove_cart_id'];

    $stmt = $pdo->prepare("
        DELETE FROM cart_items
        WHERE id = ?
          AND customer_id = ?
    ");

    $stmt->execute([
        $remove_id,
        $customer_id
    ]);

    header("Location: cart.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Get Customer Cart Directly From Database
|--------------------------------------------------------------------------
*/
$stmt = $pdo->prepare("
    SELECT
        ci.id AS cart_id,
        ci.customer_id,
        ci.product_id,
        ci.quantity,

        p.name,
        p.price,
        p.images,
        p.unit,

        s.id AS seller_id,
        s.business_name AS seller_name

    FROM cart_items ci

    INNER JOIN products p
        ON p.id = ci.product_id

    LEFT JOIN sellers s
        ON s.id = p.seller_id

    WHERE ci.customer_id = ?

    ORDER BY ci.created_at DESC
");

$stmt->execute([$customer_id]);

$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Prepare Product Images + Quantity
|--------------------------------------------------------------------------
*/
foreach ($cart_items as &$item) {

    $images = json_decode($item['images'] ?? '', true);

    if (is_array($images) && !empty($images)) {
        $item['image'] = $images[0];
    } else {
        $item['image'] = '';
    }

    $item['qty'] = (int)$item['quantity'];
    $item['price'] = (float)$item['price'];

    if (empty($item['seller_name'])) {
        $item['seller_name'] = 'Home Maker';
    }
}

unset($item);

/*
|--------------------------------------------------------------------------
| Calculate Totals
|--------------------------------------------------------------------------
*/
$subtotal = 0;

foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['qty'];
}

$discount = 0;

$shipping = ($subtotal >= 499 || $subtotal == 0)
    ? 0
    : 50;

$total = $subtotal - $discount + $shipping;

require_once __DIR__ . '/includes/header.php';

?>

<div class="row g-4">

    <!-- Items in Cart -->
    <div class="col-lg-8">

        <div class="dashboard-card">

            <div class="dashboard-card-header">

                <h5 class="dashboard-card-title">
                    <i class="fa-solid fa-cart-shopping text-maroon-800"></i>
                    Items in Basket
                    (<?php echo count($cart_items); ?>)
                </h5>

                <a href="../products.php"
                   class="btn btn-outline-maroon btn-sm">
                    + Add More Items
                </a>

            </div>

            <div class="p-3">

                <?php if (empty($cart_items)): ?>

                    <div class="text-center py-5">

                        <i class="fa-solid fa-cart-shopping display-4 text-muted mb-3"></i>

                        <h5 class="fw-bold text-maroon-900">
                            Your cart is empty
                        </h5>

                        <p class="text-muted mb-3">
                            Add some handmade products to your cart.
                        </p>

                        <a href="../products.php"
                           class="btn btn-maroon">
                            Start Shopping
                        </a>

                    </div>

                <?php else: ?>

                    <div class="d-flex flex-column gap-3">

                        <?php foreach ($cart_items as $item): ?>

                            <div class="p-3 border rounded-3 bg-white shadow-sm
                                        d-flex flex-column flex-sm-row
                                        justify-content-between
                                        align-items-sm-center gap-3">

                                <!-- Product -->
                                <div class="d-flex align-items-center gap-3">

                                    <?php
                                    $cart_image = $item['image'] ?? '';

                                    if (!empty($cart_image)) {

                                        if (
                                            strpos($cart_image, 'http://') === 0 ||
                                            strpos($cart_image, 'https://') === 0 ||
                                            strpos($cart_image, '../') === 0
                                        ) {
                                            $cart_image_path = $cart_image;
                                        } else {
                                            $cart_image_path = '../' . ltrim($cart_image, '/');
                                        }

                                    } else {
                                        $cart_image_path = '../images/default-product.jpg';
                                    }
                                    ?>

                                    <img
                                        src="<?php echo htmlspecialchars($cart_image_path); ?>"
                                        class="rounded-3 border"
                                        style="width: 64px; height: 64px; object-fit: cover;"
                                        alt="<?php echo htmlspecialchars($item['name']); ?>"
                                        onerror="this.src='../images/default-product.jpg';"
                                    >

                                    <div>
                                        <strong class="text-dark d-block">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </strong>

                                        <small class="text-muted d-block">
                                            <?php echo htmlspecialchars($item['unit']); ?>
                                            &bull;
                                            <span class="text-terracotta">
                                                <?php echo htmlspecialchars($item['seller_name']); ?>
                                            </span>
                                        </small>

                                        <span class="fw-bold text-maroon-800">
                                            ₹<?php echo $item['price']; ?> each
                                        </span>
                                    </div>

                                </div>

                                <!-- Quantity + Total + Remove -->
                                <div class="d-flex align-items-center gap-3">

                                    <div class="input-group input-group-sm"
                                         style="width:100px;">

                                        <button
                                            class="btn btn-outline-secondary"
                                            type="button"
                                            onclick="updateCartQuantity(
                                                <?php echo $item['cart_id']; ?>,
                                                <?php echo max(1, $item['qty'] - 1); ?>
                                            )"
                                        >
                                            -
                                        </button>

                                        <input
                                            type="text"
                                            class="form-control text-center"
                                            value="<?php echo $item['qty']; ?>"
                                            readonly
                                        >

                                        <button
                                            class="btn btn-outline-secondary"
                                            type="button"
                                            onclick="updateCartQuantity(
                                                <?php echo $item['cart_id']; ?>,
                                                <?php echo $item['qty'] + 1; ?>
                                            )"
                                        >
                                            +
                                        </button>

                                    </div>

                                    <div
                                        class="text-end"
                                        style="min-width:80px;"
                                    >

                                        <strong class="text-maroon-900 fs-6">

                                            ₹<?php
                                            echo number_format(
                                                $item['price'] * $item['qty'],
                                                2
                                            );
                                            ?>

                                        </strong>

                                    </div>

                                    <form
                                        method="POST"
                                        action="cart.php"
                                        class="d-inline"
                                    >

                                        <input
                                            type="hidden"
                                            name="remove_cart_id"
                                            value="<?php echo $item['cart_id']; ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-light btn-sm text-danger border"
                                            title="Remove"
                                        >
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>

                                    </form>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>


    <!-- Order Summary & Checkout -->
<div class="col-lg-4">

    <div class="dashboard-card">

        <div class="dashboard-card-header">
            <h5 class="dashboard-card-title">
                <i class="fa-solid fa-receipt text-maroon-800"></i>
                Order Summary
            </h5>
        </div>

        <div class="p-4">

            <!-- Bill Details -->
            <div class="small text-secondary">

                <div class="d-flex justify-content-between py-2">
                    <span>
                        Items (<?php echo count($cart_items); ?>)
                    </span>

                    <strong class="text-dark">
                        ₹<?php echo number_format($subtotal, 2); ?>
                    </strong>
                </div>


                <div class="d-flex justify-content-between py-2">
                    <span>Subtotal</span>

                    <strong class="text-dark">
                        ₹<?php echo number_format($subtotal, 2); ?>
                    </strong>
                </div>


                <div class="d-flex justify-content-between py-2">
                    <span>Discount</span>

                    <?php if ($discount > 0): ?>

                        <strong class="text-success">
                            -₹<?php echo number_format($discount, 2); ?>
                        </strong>

                    <?php else: ?>

                        <strong class="text-success">
                            ₹0.00
                        </strong>

                    <?php endif; ?>

                </div>


                <div class="d-flex justify-content-between py-2">
                    <span>Shipping Charges</span>

                    <?php if ($shipping == 0): ?>

                        <strong class="text-success">
                            FREE
                        </strong>

                    <?php else: ?>

                        <strong class="text-dark">
                            ₹<?php echo number_format($shipping, 2); ?>
                        </strong>

                    <?php endif; ?>

                </div>


                <!-- Total -->
                <div class="d-flex justify-content-between align-items-center py-3 border-top mt-2">

                    <strong class="text-maroon-900 fs-5">
                        Total Payable
                    </strong>

                    <strong class="text-maroon-900 fs-4">
                        ₹<?php echo number_format($total, 2); ?>
                    </strong>

                </div>

            </div>

            <!-- Proceed to Pay -->
            <?php if (!empty($cart_items)): ?>

                <a
                    href="checkout.php"
                    class="btn btn-maroon w-100 py-2 fw-bold shadow-sm"
                >

                    Proceed to Pay

                    <i class="fa-solid fa-credit-card ms-1"></i>

                </a>

            <?php else: ?>

                <button
                    type="button"
                    class="btn btn-secondary w-100 py-2"
                    disabled
                >

                    Cart is Empty

                </button>

            <?php endif; ?>


            <!-- Continue Shopping -->
            <a
                href="../products.php"
                class="btn btn-outline-maroon w-100 mt-2"
            >

                Continue Shopping

            </a>

        </div>

    </div>

</div>
<script>

function updateCartQuantity(cartId, quantity) {

    if (quantity < 1) {
        quantity = 1;
    }

    window.location.href =
        "cart.php?update_id=" +
        cartId +
        "&quantity=" +
        quantity;
}

</script>


<?php require_once __DIR__ . '/includes/footer.php'; ?>