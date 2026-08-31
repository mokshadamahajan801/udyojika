<?php

require_once __DIR__ . '/includes/db.php';

$email = 'admin@udyojika.com';
$new_password = 'Admin@123';

$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    UPDATE users
    SET password = ?,
        role = 'admin',
        status = 'active'
    WHERE email = ?
");

$stmt->execute([
    $hashed_password,
    $email
]);

if ($stmt->rowCount() > 0) {
    echo "Admin password updated successfully!";
} else {
    echo "Admin user found, but no changes were made.";
}