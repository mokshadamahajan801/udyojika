<?php

require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: wishlist.php");
    exit;
}

$product_id = (int)($_POST['product_id'] ?? 0);

if ($product_id <= 0) {
    header("Location: wishlist.php");
    exit;
}

$stmt = $pdo->prepare("
    DELETE FROM wishlist
    WHERE customer_id = ?
      AND product_id = ?
");

$stmt->execute([
    $customer_id,
    $product_id
]);

header("Location: wishlist.php");
exit;