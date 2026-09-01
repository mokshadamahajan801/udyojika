<?php
require_once __DIR__ . '/../../includes/db.php';

require_once __DIR__ . '/../../includes/functions.php';

$current_user = require_role(['seller']);
$seller_id = $current_user['seller_id'] ?? 1;
$seller_profile = get_seller_by_id($seller_id);
