

<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

$current_user = require_role(['seller']);

$seller_profile = null;

if (!empty($current_user['id'])) {
    $seller_profile = get_seller_by_id(
        (int) $current_user['id'],
        $pdo
    );
}

if (!$seller_profile) {
    die(
        'Seller profile not found. Please make sure the user is approved and sellers.user_id matches users.id.'
    );
}
