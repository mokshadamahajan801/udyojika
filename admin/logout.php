<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Logout ONLY ADMIN
|--------------------------------------------------------------------------
|
| Customer session ला touch करायचे नाही.
|--------------------------------------------------------------------------
*/

unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_user']);

/*
|--------------------------------------------------------------------------
| Redirect to Admin Login
|--------------------------------------------------------------------------
*/

header("Location: /udyojika/admin/login.php");
exit;