<?php

require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../products.php");
    exit;
}

if (empty($_POST['product_id'])) {
    header("Location: ../products.php");
    exit;
}

$product_id = (int)$_POST['product_id'];
$quantity = max(1, (int)($_POST['quantity'] ?? 1));

/*
 * Check product
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
    header("Location: ../products.php");
    exit;
}

/*
 * Check if product is already in cart
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

    /*
     * Already in cart → increase quantity
     */
    $stmt = $pdo->prepare("
        UPDATE cart_items
        SET quantity = quantity + ?
        WHERE id = ?
          AND customer_id = ?
    ");

    $stmt->execute([
        $quantity,
        $cart_item['id'],
        $customer_id
    ]);

} else {

    /*
     * New cart item
     */
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
            ?
        )
    ");

    $stmt->execute([
        $customer_id,
        $product_id,
        $quantity
    ]);
}

/*
 * Go to Cart
 */
header("Location: ../index.php");
exit;