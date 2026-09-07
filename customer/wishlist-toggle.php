<?php

require_once __DIR__ . '/includes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.'
    ]);
    exit;
}

$product_id = (int)($_POST['product_id'] ?? 0);

if ($product_id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid product.'
    ]);
    exit;
}

/*
 * Check whether product exists
 */
$stmt = $pdo->prepare("
    SELECT id, name
    FROM products
    WHERE id = ?
      AND status = 'active'
    LIMIT 1
");

$stmt->execute([$product_id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo json_encode([
        'success' => false,
        'message' => 'Product not found.'
    ]);
    exit;
}

/*
 * Check existing wishlist item
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

$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {

    // Remove from wishlist
    $stmt = $pdo->prepare("
        DELETE FROM wishlist
        WHERE id = ?
          AND customer_id = ?
    ");

    $stmt->execute([
        $existing['id'],
        $customer_id
    ]);

    $action = 'removed';

} else {

    // Add to wishlist
    $stmt = $pdo->prepare("
        INSERT INTO wishlist
        (
            customer_id,
            product_id
        )
        VALUES
        (
            ?,
            ?
        )
    ");

    $stmt->execute([
        $customer_id,
        $product_id
    ]);

    $action = 'added';
}

/*
 * Get updated wishlist count
 */
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM wishlist
    WHERE customer_id = ?
");

$stmt->execute([$customer_id]);

$count = (int)$stmt->fetchColumn();

echo json_encode([
    'success' => true,
    'action' => $action,
    'count' => $count,
    'name' => $product['name']
]);

exit;