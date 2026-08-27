<?php

require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

$current_user = require_role(['customer', 'admin', 'seller']);

$customer_id = $current_user['id'] ?? null;