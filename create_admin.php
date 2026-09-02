<?php

require_once __DIR__ . '/includes/db.php';

$email = "admin@udyojika.com";
$password = "Admin@123";
$name = "Admin";
$role = "admin";

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO users (name, email, password, role)
    VALUES (?, ?, ?, ?)
");

$stmt->execute([
    $name,
    $email,
    $hashed_password,
    $role
]);

echo "Admin created successfully!";
?>