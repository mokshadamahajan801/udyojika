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

            /* Category information */
            c.slug AS category_slug,

            /* Seller information */
            s.business_name AS seller_name,
            s.owner_name AS seller_owner,
            s.avatar AS seller_avatar,
            s.location AS seller_location

        FROM products p

        LEFT JOIN categories c
            ON p.category_id = c.id

        LEFT JOIN sellers s
            ON p.seller_id = s.id

        WHERE p.status = 'active'

        ORDER BY p.created_at DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as &$product) {

        // Category
        $product['category'] =
            $product['category_name'] ?? 'Uncategorized';

        $product['category_slug'] =
            $product['category_slug'] ?? '';


        // Seller name
        $product['seller_name'] =
            $product['seller_name']
            ?? $product['seller_owner']
            ?? 'Unknown Maker';


        // Seller avatar
        $product['seller_avatar'] =
            $product['seller_avatar'] ?? '';


        // Seller location
        $product['seller_location'] =
            $product['seller_location'] ?? 'Location not available';


        // Product images
        $raw_images = $product['images'] ?? null;

        if (!empty($raw_images)) {

            $decoded_images = json_decode(
                $raw_images,
                true
            );

            if (is_array($decoded_images)) {
                $product['images'] = $decoded_images;
            } else {
                $product['images'] = [$raw_images];
            }
        } else {
            $product['images'] = [];
        }
    }

    unset($product);

    return $products;
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

    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /*
    |--------------------------------------------------------------------------
    | Get Popular Products For Each Category
    |--------------------------------------------------------------------------
    */

    foreach ($categories as &$category) {

        $category['popular_items'] = [];

        $productStmt = $pdo->prepare("
            SELECT p.name
            FROM products p
            WHERE (
                p.category_id = ?
                OR p.category_name = ?
            )
            AND p.status = 'active'
            ORDER BY p.is_featured DESC, p.created_at DESC
            LIMIT 5
        ");

        $productStmt->execute([
            $category['id'],
            $category['name']
        ]);

        $category['popular_items'] = $productStmt->fetchAll(PDO::FETCH_COLUMN);
    }

    unset($category);

    return $categories;
}


/**
 * Get all active sellers
 */
function get_sellers(PDO $pdo)
{
    $sql = "
        SELECT *
FROM sellers
ORDER BY id DESC
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
    $_SESSION['user'] = $user;

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

function get_product_by_slug($slug, PDO $pdo)
{
    $stmt = $pdo->prepare("
    SELECT 
        p.*,
        c.name AS category,
        c.slug AS category_slug,
        b.business_name AS seller_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN sellers b ON p.seller_id = b.id
    WHERE p.slug = ?
    LIMIT 1
");

    $stmt->execute([$slug]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        return null;
    }

    // Convert image data into an array
    if (!empty($product['images'])) {
        $decoded = json_decode($product['images'], true);

        if (is_array($decoded)) {
            $product['images'] = $decoded;
        } else {
            $product['images'] = [$product['images']];
        }
    } else {
        $product['images'] = ['assets/images/default-product.jpg'];
    }

    // Convert ingredients into an array
    if (!empty($product['ingredients'])) {
        $decoded = json_decode($product['ingredients'], true);

        if (is_array($decoded)) {
            $product['ingredients'] = $decoded;
        } else {
            $product['ingredients'] = array_map(
                'trim',
                explode(',', $product['ingredients'])
            );
        }
    } else {
        $product['ingredients'] = [];
    }

    return $product;
}

function get_seller_by_id($user_id, PDO $pdo)
{
    $stmt = $pdo->prepare("
        SELECT *
        FROM sellers
        WHERE user_id = ?
        AND status = 'approved'
        LIMIT 1
    ");

    $stmt->execute([$user_id]);

    $seller = $stmt->fetch(PDO::FETCH_ASSOC);

    return $seller ?: null;
}

/**
 * Get all orders with their items
 */
function get_all_orders(PDO $pdo)
{
    $stmt = $pdo->prepare("
        SELECT *
        FROM orders
        ORDER BY created_at DESC
    ");

    $stmt->execute();

    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($orders as &$order) {

        $itemStmt = $pdo->prepare("
            SELECT *
            FROM order_items
            WHERE order_id = ?
        ");

        $itemStmt->execute([$order['id']]);

        $order['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    unset($order);

    return $orders;
}


/**
 * Get customer dashboard statistics
 */
function get_customer_dashboard_stats($customer_id, PDO $pdo)
{
    $stmt = $pdo->prepare("
        SELECT
            COUNT(*) AS total_orders,

            SUM(
                CASE
                    WHEN order_status IN ('pending', 'processing')
                    THEN 1
                    ELSE 0
                END
            ) AS pending_orders,

            SUM(
                CASE
                    WHEN order_status = 'completed'
                    THEN 1
                    ELSE 0
                END
            ) AS completed_orders,

            COALESCE(
                SUM(
                    CASE
                        WHEN payment_status = 'Paid'
                        THEN total_amount
                        ELSE 0
                    END
                ),
                0
            ) AS total_spent

        FROM orders
        WHERE customer_id = ?
    ");

    $stmt->execute([$customer_id]);

    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
        'total_orders' => (int)($stats['total_orders'] ?? 0),
        'pending_orders' => (int)($stats['pending_orders'] ?? 0),
        'completed_orders' => (int)($stats['completed_orders'] ?? 0),
        'total_spent' => (float)($stats['total_spent'] ?? 0)
    ];
}

function get_admin_dashboard_stats()
{
    global $pdo;

    $stats = [
        'total_users'       => 0,
        'total_customers'   => 0,
        'total_sellers'     => 0,
        'pending_requests'  => 0,
        'total_businesses'  => 0,
        'total_products'    => 0,
        'total_orders'      => 0,
        'pending_orders'    => 0,
        'completed_orders'  => 0,
        'total_sales'       => 0,
        'total_reviews'     => 0,
        'total_enquiries'   => 0
    ];

    /* Total Users */
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM users
    ");
    $stats['total_users'] = (int) $stmt->fetchColumn();


    /* Total Customers */
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM users
        WHERE role = 'customer'
    ");
    $stats['total_customers'] = (int) $stmt->fetchColumn();


    /* Total Sellers */
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM users
        WHERE role = 'seller'
    ");
    $stats['total_sellers'] = (int) $stmt->fetchColumn();


    /* Pending Seller / Maker Requests */
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM seller_requests
        WHERE status = 'pending'
    ");
    $stats['pending_requests'] = (int) $stmt->fetchColumn();


    /* Total Home Brands / Businesses */
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM sellers
    ");
    $stats['total_businesses'] = (int) $stmt->fetchColumn();


    /* Total Products */
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM products
    ");
    $stats['total_products'] = (int) $stmt->fetchColumn();


    /* Total Orders */
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM orders
    ");
    $stats['total_orders'] = (int) $stmt->fetchColumn();


    /* Pending Orders */
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM orders
        WHERE order_status = 'pending'
    ");
    $stats['pending_orders'] = (int) $stmt->fetchColumn();


    /* Completed / Delivered Orders */
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM orders
        WHERE order_status IN ('completed', 'delivered')
    ");
    $stats['completed_orders'] = (int) $stmt->fetchColumn();


    /* Total Sales / GMV */
    $stmt = $pdo->query("
        SELECT COALESCE(SUM(total_amount), 0)
        FROM orders
        WHERE order_status IN ('completed', 'delivered')
    ");
    $stats['total_sales'] = (float) $stmt->fetchColumn();


    /* Total Reviews */
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM reviews
    ");
    $stats['total_reviews'] = (int) $stmt->fetchColumn();


    /* Total Enquiries */
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM enquiries
    ");
    $stats['total_enquiries'] = (int) $stmt->fetchColumn();


    return $stats;
}

function get_seller_requests()
{
    global $pdo;

    try {
        $stmt = $pdo->query("
            SELECT *
            FROM seller_requests
            WHERE status = 'pending'
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Seller Requests Error: " . $e->getMessage());
        return [];
    }
}

function get_all_reviews()
{
    global $pdo;

    try {
        $stmt = $pdo->query("
            SELECT 
                r.*,
                u.name AS customer_name,
                p.name AS product_name
            FROM reviews r
            LEFT JOIN users u ON r.user_id = u.id
            LEFT JOIN products p ON r.product_id = p.id
            ORDER BY r.id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Reviews Error: " . $e->getMessage());
        return [];
    }
}

function get_all_users()
{
    global $pdo;

    $stmt = $pdo->query("
        SELECT 
            id,
            name,
            email,
            phone,
            role,
            status,
            avatar,
            created_at
        FROM users
        ORDER BY created_at DESC
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_enquiries(PDO $pdo)
{
    $stmt = $pdo->prepare("
        SELECT *
        FROM enquiries
        ORDER BY created_at DESC
    ");

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
