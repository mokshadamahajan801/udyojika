<?php

require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

if (empty($_POST['product_id'])) {
    header("Location: index.php");
    exit;
}

$product_id = (int)$_POST['product_id'];

/*
 * Check that this product is actually in
 * the current customer's wishlist.
 */
$stmt = $pdo->prepare("
    SELECT id
    FROM wishlist
    WHERE customer_id = ?
      AND product_id = ?
    LIMIT 1
");

$stmt->execute([
    $customer_id,
    $product_id
]);

$wishlist_item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$wishlist_item) {
    header("Location: index.php");
    exit;
}

/*
 * Check that the product exists and is active.
 */
$stmt = $pdo->prepare("
    SELECT id
    FROM products
    WHERE id = ?
      AND status = 'active'
    LIMIT 1
");

$stmt->execute([$product_id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: index.php");
    exit;
}

/*
 * Check whether the product is already in cart.
 */
$stmt = $pdo->prepare("
    SELECT id
    FROM cart_items
    WHERE customer_id = ?
      AND product_id = ?
    LIMIT 1
");

$stmt->execute([
    $customer_id,
    $product_id
]);

$cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cart_item) {

    // Product already in cart → increase quantity
    $stmt = $pdo->prepare("
        UPDATE cart_items
        SET quantity = quantity + 1
        WHERE id = ?
          AND customer_id = ?
    ");

    $stmt->execute([
        $cart_item['id'],
        $customer_id
    ]);

} else {

    // Product not in cart → add new item
    $stmt = $pdo->prepare("
        INSERT INTO cart_items
        (
            customer_id,
            product_id,
            quantity
        )
        VALUES
        (
            ?,
            ?,
            1
        )
    ");

    $stmt->execute([
        $customer_id,
        $product_id
    ]);
}

/*
 * Go to Cart after adding.
 */
header("Location: cart.php");
exit;