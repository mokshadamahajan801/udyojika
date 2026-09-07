<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/includes/auth.php';

/*
|--------------------------------------------------------------------------
| Load Cart
|--------------------------------------------------------------------------
*/
$cart_items = get_customer_cart($pdo, $customer_id);

if (empty($cart_items)) {
    header("Location: cart.php");
    exit;
}

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
$shipping = ($subtotal >= 499) ? 0 : 50;
$total = $subtotal - $discount + $shipping;

/*
|--------------------------------------------------------------------------
| Load Customer Addresses
|--------------------------------------------------------------------------
*/
$stmt = $pdo->prepare("
    SELECT *
    FROM addresses
    WHERE customer_id = ?
    ORDER BY is_default DESC, created_at DESC
");

$stmt->execute([$customer_id]);

$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Place Order
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {

    $address_id = (int)($_POST['address_id'] ?? 0);
    $payment_method = trim($_POST['payment_method'] ?? 'UPI');

    if ($address_id <= 0) {
        $error_message = "Please select a delivery address.";
    } else {

        /*
        | Get selected address
        */
        $stmt = $pdo->prepare("
            SELECT *
            FROM addresses
            WHERE id = ? AND customer_id = ?
            LIMIT 1
        ");

        $stmt->execute([$address_id, $customer_id]);

        $address = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$address) {

            $error_message = "Invalid delivery address.";

        } else {

            /*
            | Create readable shipping address
            */
            $shipping_address = $address['full_name'] . "\n"
                . $address['phone'] . "\n"
                . $address['address_line1'] . "\n";

            if (!empty($address['address_line2'])) {
                $shipping_address .= $address['address_line2'] . "\n";
            }

            $shipping_address .=
                $address['city'] . ", "
                . $address['state'] . " - "
                . $address['pincode'];

            /*
            | Generate order number
            */
            $order_number = 'UDY' . date('YmdHis') . rand(100, 999);

            try {

                $pdo->beginTransaction();

                /*
                | Insert Order
                */
                $stmt = $pdo->prepare("
                    INSERT INTO orders (
                        order_number,
                        customer_id,
                        customer_name,
                        customer_email,
                        customer_phone,
                        shipping_address,
                        payment_method,
                        payment_status,
                        order_status,
                        subtotal,
                        shipping_fee,
                        discount,
                        total_amount
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', 'pending', ?, ?, ?, ?)
                ");

                $stmt->execute([
                    $order_number,
                    $customer_id,
                    $current_user['name'] ?? '',
                    $current_user['email'] ?? '',
                    $current_user['phone'] ?? '',
                    $shipping_address,
                    $payment_method,
                    $subtotal,
                    $shipping,
                    $discount,
                    $total
                ]);

                $order_id = $pdo->lastInsertId();

                /*
                | Insert Order Items
                */
                $itemStmt = $pdo->prepare("
                    INSERT INTO order_items (
                        order_id,
                        product_id,
                        product_name,
                        seller_id,
                        seller_name,
                        price,
                        quantity,
                        subtotal
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");

                foreach ($cart_items as $item) {

                    $item_subtotal = $item['price'] * $item['qty'];

                    $itemStmt->execute([
                        $order_id,
                        $item['product_id'],
                        $item['name'],
                        $item['seller_id'] ?? 0,
                        $item['seller_name'] ?? '',
                        $item['price'],
                        $item['qty'],
                        $item_subtotal
                    ]);
                }

                /*
                | Clear Cart
                */
                $deleteStmt = $pdo->prepare("
                    DELETE FROM cart_items
                    WHERE customer_id = ?
                ");

                $deleteStmt->execute([$customer_id]);

                $pdo->commit();

                /*
                | Go to Order Details
                */
                header("Location: order-details.php?id=" . $order_id);
                exit;

                        } catch (PDOException $e) {

                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }

                $error_message = "Something went wrong while placing your order.";
            }
        }
    }
}

$page_title = "Checkout - Udyojika";
$page_header = "Checkout";
$page_subheader = "Confirm your delivery details and order";

require_once __DIR__ . '/includes/header.php';

?>

<div class="container-fluid py-4">

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger rounded-3">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="fa-solid fa-location-dot text-maroon-800 me-2"></i>
                                Delivery Address
                            </h5>
                            <p class="text-muted small mb-0">
                                Select where you want your order delivered.
                            </p>
                        </div>

                        <a href="addresses.php" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-plus me-1"></i>
                            Add Address
                        </a>
                    </div>

                    <?php if (empty($addresses)): ?>

                        <div class="alert alert-light border text-center">
                            <p class="mb-2 fw-semibold">
                                No delivery address found.
                            </p>

                            <a href="addresses.php" class="btn btn-maroon btn-sm">
                                Add Delivery Address
                            </a>
                        </div>

                    <?php else: ?>

                        <?php foreach ($addresses as $address): ?>

                            <label class="d-block border rounded-3 p-3 mb-3"
                                   style="cursor:pointer;">

                                <div class="d-flex gap-3">

                                    <input
                                        type="radio"
                                        name="address_id"
                                        value="<?php echo $address['id']; ?>"
                                        form="checkoutForm"
                                        class="form-check-input mt-1"
                                        <?php echo !empty($address['is_default']) ? 'checked' : ''; ?>
                                    >

                                    <div>

                                        <div class="fw-bold">
                                            <?php echo htmlspecialchars($address['title']); ?>

                                            <?php if (!empty($address['is_default'])): ?>
                                                <span class="badge bg-success ms-2">
                                                    Default
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="small mt-1">
                                            <?php echo htmlspecialchars($address['full_name']); ?>
                                        </div>

                                        <div class="small text-muted">
                                            <?php echo htmlspecialchars($address['phone']); ?>
                                        </div>

                                        <div class="small text-muted mt-1">
                                            <?php echo htmlspecialchars($address['address_line1']); ?>

                                            <?php if (!empty($address['address_line2'])): ?>
                                                , <?php echo htmlspecialchars($address['address_line2']); ?>
                                            <?php endif; ?>

                                            <br>

                                            <?php echo htmlspecialchars($address['city']); ?>,
                                            <?php echo htmlspecialchars($address['state']); ?> -
                                            <?php echo htmlspecialchars($address['pincode']); ?>
                                        </div>

                                    </div>

                                </div>

                            </label>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>
            </div>


            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-1">
                        <i class="fa-solid fa-credit-card text-maroon-800 me-2"></i>
                        Payment Method
                    </h5>

                    <p class="text-muted small mb-3">
                        Select a payment method for this demo checkout.
                    </p>

                    <div class="border rounded-3 p-3">

                        <label class="d-flex align-items-center gap-3 mb-0"
                               style="cursor:pointer;">

                            <input
                                type="radio"
                                name="payment_method"
                                value="UPI"
                                form="checkoutForm"
                                checked
                                class="form-check-input"
                            >

                            <div>
                                <div class="fw-bold">UPI</div>
                                <div class="small text-muted">
                                    Demo payment option
                                </div>
                            </div>

                        </label>

                    </div>

                </div>
            </div>


            <form method="POST" id="checkoutForm">

                <input type="hidden" name="place_order" value="1">

                <button
                    type="submit"
                    class="btn btn-maroon w-100 py-3 fw-bold rounded-3"
                    <?php echo empty($addresses) ? 'disabled' : ''; ?>
                >
                    <i class="fa-solid fa-check me-2"></i>
                    Place Order
                </button>

            </form>

        </div>


        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">
                        Order Summary
                    </h5>

                    <?php foreach ($cart_items as $item): ?>

                        <div class="d-flex justify-content-between gap-3 mb-3">

                            <div class="flex-grow-1">

                                <div class="fw-semibold small">
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </div>

                                <div class="text-muted small">
                                    Qty: <?php echo $item['qty']; ?>
                                </div>

                            </div>

                            <div class="fw-bold small">
                                ₹<?php echo number_format($item['price'] * $item['qty'], 2); ?>
                            </div>

                        </div>

                    <?php endforeach; ?>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>₹<?php echo number_format($subtotal, 2); ?></span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>

                        <span>
                            <?php if ($shipping == 0): ?>
                                <span class="text-success">FREE</span>
                            <?php else: ?>
                                ₹<?php echo number_format($shipping, 2); ?>
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Discount</span>
                        <span>₹<?php echo number_format($discount, 2); ?></span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">

                        <span class="fw-bold">
                            Total
                        </span>

                        <span class="fw-bold fs-5 text-maroon-800">
                            ₹<?php echo number_format($total, 2); ?>
                        </span>

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>