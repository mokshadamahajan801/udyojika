<?php

require_once __DIR__ . '/includes/auth.php';

require_admin();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';


/*
|--------------------------------------------------------------------------
| Validate User ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: users.php');
    exit;
}

$user_id = (int) $_GET['id'];


/*
|--------------------------------------------------------------------------
| Get User
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT id, name, role, status
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| User Not Found
|--------------------------------------------------------------------------
*/

if (!$user) {
    header('Location: users.php?error=user_not_found');
    exit;
}


/*
|--------------------------------------------------------------------------
| Admin Cannot Be Suspended
|--------------------------------------------------------------------------
*/

if ($user['role'] === 'admin') {
    header('Location: users.php?error=admin');
    exit;
}


/*
|--------------------------------------------------------------------------
| Toggle User Status
|--------------------------------------------------------------------------
*/

$new_status = ($user['status'] === 'active')
    ? 'inactive'
    : 'active';


$stmt = $pdo->prepare("
    UPDATE users
    SET status = ?, updated_at = NOW()
    WHERE id = ?
");

$stmt->execute([
    $new_status,
    $user_id
]);


/*
|--------------------------------------------------------------------------
| Redirect After Update
|--------------------------------------------------------------------------
*/

header('Location: users.php');
exit;