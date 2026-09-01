<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_admin()
{
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
        header("Location: /udyojika/admin/login.php");
        exit;
    }

    if ($_SESSION['user_role'] !== 'admin') {
        header("Location: /udyojika/admin/login.php");
        exit;
    }
}