<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Logout CUSTOMER only
|--------------------------------------------------------------------------
|
| IMPORTANT:
| Do NOT use session_destroy().
| Admin session must remain untouched.
|--------------------------------------------------------------------------
*/

unset($_SESSION['customer_id']);
unset($_SESSION['customer_name']);
unset($_SESSION['customer_email']);
unset($_SESSION['customer_user']);

/*
|--------------------------------------------------------------------------
| Redirect to Customer Login
|--------------------------------------------------------------------------
*/

header("Location: login.php?msg=logged_out");
exit;
