<?php

/*
|--------------------------------------------------------------------------
| UDYOJIKA - Common Database Functions
|--------------------------------------------------------------------------
*/

/**
 * Get all active products
 */
function get_all_products(PDO $pdo)
{
    $sql = "
        SELECT 
            p.*,
            s.business_name,
            s.owner_name
        FROM products p
        LEFT JOIN sellers s ON p.seller_id = s.id
        WHERE p.status = 'active'
        ORDER BY p.created_at DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Get all active categories
 */
function get_categories(PDO $pdo)
{
    $sql = "
        SELECT *
        FROM categories
        ORDER BY name ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Get all active sellers
 */
function get_sellers(PDO $pdo)
{
    $sql = "
        SELECT *
        FROM sellers
        WHERE status = 'active'
        ORDER BY created_at DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| Authentication Functions
|--------------------------------------------------------------------------
*/

/**
 * Login user
 */
function login_user($email, $password, $remember_me = false)
{
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE email = ?
        LIMIT 1
    ");

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Verify Password
    |--------------------------------------------------------------------------
    */
    if (!password_verify($password, $user['password'])) {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Start Session
    |--------------------------------------------------------------------------
    */
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    /*
    |--------------------------------------------------------------------------
    | Store User Session
    |--------------------------------------------------------------------------
    */
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['user_role'] = $user['role'];

    /*
    |--------------------------------------------------------------------------
    | Remember Me
    |--------------------------------------------------------------------------
    */
    if ($remember_me) {

        $token = bin2hex(random_bytes(32));

        $expires = date(
            'Y-m-d H:i:s',
            time() + (30 * 24 * 60 * 60)
        );

        $stmt = $pdo->prepare("
            UPDATE users
            SET remember_token = ?, remember_expires = ?
            WHERE id = ?
        ");

        $stmt->execute([
            hash('sha256', $token),
            $expires,
            $user['id']
        ]);

        setcookie(
            'udyojika_remember',
            $user['id'] . ':' . $token,
            [
                'expires' => time() + (30 * 24 * 60 * 60),
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );
    }

    return $user;
}


/**
 * Require user to have one of the allowed roles
 */
function require_role($roles)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    /*
    |--------------------------------------------------------------------------
    | Check Normal Session
    |--------------------------------------------------------------------------
    */
    if (empty($_SESSION['user_id'])) {

        /*
        |--------------------------------------------------------------------------
        | Try Remember Me Login
        |--------------------------------------------------------------------------
        */
        auto_login_from_cookie();
    }

    /*
    |--------------------------------------------------------------------------
    | User Still Not Logged In
    |--------------------------------------------------------------------------
    */
    if (empty($_SESSION['user_id'])) {
        header("Location: ../login.php?msg=auth_required");
        exit;
    }

    $current_user = get_logged_in_user();

    if (!$current_user) {
        header("Location: ../login.php");
        exit;
    }

    if (!is_array($roles)) {
        $roles = [$roles];
    }

    if (!in_array($current_user['role'], $roles, true)) {
        header("Location: ../login.php");
        exit;
    }

    return $current_user;
}


/**
 * Automatically login user using Remember Me cookie
 */
function auto_login_from_cookie()
{
    global $pdo;

    if (empty($_COOKIE['udyojika_remember'])) {
        return false;
    }

    $cookie_parts = explode(':', $_COOKIE['udyojika_remember'], 2);

    if (count($cookie_parts) !== 2) {
        return false;
    }

    [$user_id, $token] = $cookie_parts;

    if (!ctype_digit($user_id) || empty($token)) {
        return false;
    }

    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE id = ?
        AND remember_token = ?
        AND remember_expires > NOW()
        LIMIT 1
    ");

    $stmt->execute([
        (int) $user_id,
        hash('sha256', $token)
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {

        setcookie(
            'udyojika_remember',
            '',
            time() - 3600,
            '/'
        );

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Restore Session
    |--------------------------------------------------------------------------
    */
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['user_role'] = $user['role'];

    return true;
}


/**
 * Get currently logged-in user
 */
function get_logged_in_user()
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    global $pdo;

    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([$_SESSION['user_id']]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
}


/**
 * Logout current user
 */
function logout_user()
{
    global $pdo;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Remember Me Token From Database
    |--------------------------------------------------------------------------
    */
    if (!empty($_SESSION['user_id'])) {

        $stmt = $pdo->prepare("
            UPDATE users
            SET remember_token = NULL,
                remember_expires = NULL
            WHERE id = ?
        ");

        $stmt->execute([
            $_SESSION['user_id']
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Remember Me Cookie
    |--------------------------------------------------------------------------
    */
    setcookie(
        'udyojika_remember',
        '',
        time() - 3600,
        '/'
    );

    /*
    |--------------------------------------------------------------------------
    | Destroy Session
    |--------------------------------------------------------------------------
    */
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}