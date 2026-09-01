<?php

require_once __DIR__ . '/includes/auth.php';
require_admin();

require_once __DIR__ . '/../includes/db.php';


// Get User ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: users.php?error=invalid_user');
    exit;
}

$user_id = (int) $_GET['id'];


// ---------------------------------------------------------
// Get basic user information
// ---------------------------------------------------------

$stmt = $pdo->prepare("
    SELECT
        id,
        name,
        email,
        phone,
        role,
        avatar,
        status,
        created_at,
        updated_at
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: users.php?error=user_not_found');
    exit;
}


// ---------------------------------------------------------
// Customer Statistics
// ---------------------------------------------------------

$customer_stats = [
    'total_orders'     => 0,
    'completed_orders' => 0,
    'cancelled_orders' => 0,
    'total_reviews'    => 0
];

if ($user['role'] === 'customer') {

    $stmt = $pdo->prepare("
        SELECT
            COUNT(*) AS total_orders,
            SUM(CASE WHEN order_status = 'completed' THEN 1 ELSE 0 END) AS completed_orders,
            SUM(CASE WHEN order_status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_orders
        FROM orders
        WHERE customer_id = ?
    ");

    $stmt->execute([$user_id]);

    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    $customer_stats['total_orders'] = (int) ($stats['total_orders'] ?? 0);
    $customer_stats['completed_orders'] = (int) ($stats['completed_orders'] ?? 0);
    $customer_stats['cancelled_orders'] = (int) ($stats['cancelled_orders'] ?? 0);


    // Customer reviews
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM reviews
        WHERE customer_id = ?
    ");

    $stmt->execute([$user_id]);

    $customer_stats['total_reviews'] = (int) $stmt->fetchColumn();
}


// ---------------------------------------------------------
// Seller Information + Statistics
// ---------------------------------------------------------

$seller = null;

$seller_stats = [
    'total_products' => 0,
    'total_orders'   => 0,
    'total_reviews'  => 0
];

if ($user['role'] === 'seller') {

    // Seller profile
    $stmt = $pdo->prepare("
        SELECT
            id,
            user_id,
            business_name,
            owner_name,
            category,
            location,
            rating,
            review_count,
            product_count,
            avatar,
            banner_image,
            short_bio,
            full_story,
            specialty,
            joined_year,
            whatsapp,
            email,
            address,
            is_verified,
            status
        FROM sellers
        WHERE user_id = ?
        LIMIT 1
    ");

    $stmt->execute([$user_id]);

    $seller = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($seller) {

        // Total Products
        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM products
            WHERE seller_id = ?
        ");

        $stmt->execute([$seller['id']]);

        $seller_stats['total_products'] = (int) $stmt->fetchColumn();


        // Total Reviews
        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM reviews
            WHERE seller_id = ?
        ");

        $stmt->execute([$seller['id']]);

        $seller_stats['total_reviews'] = (int) $stmt->fetchColumn();


        // Total Orders
        // orders -> order_items -> products -> seller
        $stmt = $pdo->prepare("
            SELECT COUNT(DISTINCT o.id)
            FROM orders o
            INNER JOIN order_items oi ON o.id = oi.order_id
            INNER JOIN products p ON oi.product_id = p.id
            WHERE p.seller_id = ?
        ");

        $stmt->execute([$seller['id']]);

        $seller_stats['total_orders'] = (int) $stmt->fetchColumn();
    }
}


// ---------------------------------------------------------
// Page Header
// ---------------------------------------------------------

$page_title = "View User - Udyojika";
$page_header = "View User";
$page_subheader = "Complete account information";

require_once __DIR__ . '/includes/header.php';

?>

<div class="container-fluid">

    <!-- Back Button -->

    <div class="mb-3">
        <a href="users.php" class="btn btn-light border btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back to Users
        </a>
    </div>


    <!-- =====================================================
         BASIC INFORMATION
    ====================================================== -->

    <div class="dashboard-card mb-4">

        <div class="dashboard-card-header">

            <h5 class="dashboard-card-title mb-0">
                <i class="fa-solid fa-user text-maroon-800 me-2"></i>
                Basic Information
            </h5>

            <span class="badge-status-<?php echo htmlspecialchars($user['status']); ?>">
                <?php echo ucfirst(htmlspecialchars($user['status'])); ?>
            </span>

        </div>


        <div class="p-4">

            <div class="row align-items-center">

                <!-- Avatar -->

                <div class="col-md-3 text-center mb-4 mb-md-0">

                    <?php
                    $avatar = !empty($user['avatar'])
                        ? $user['avatar']
                        : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=300&auto=format&fit=crop';
                    ?>

                    <img
                        src="<?php echo htmlspecialchars($avatar); ?>"
                        class="rounded-circle shadow-sm"
                        style="width:120px;height:120px;object-fit:cover;"
                        alt="Profile Photo"
                    >

                    <h5 class="mt-3 mb-1">
                        <?php echo htmlspecialchars($user['name']); ?>
                    </h5>

                    <small class="text-muted">
                        #USR-<?php echo str_pad($user['id'], 4, '0', STR_PAD_LEFT); ?>
                    </small>

                </div>


                <!-- User Details -->

                <div class="col-md-9">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <small class="text-muted d-block">Full Name</small>
                            <strong>
                                <?php echo htmlspecialchars($user['name']); ?>
                            </strong>
                        </div>


                        <div class="col-md-6">
                            <small class="text-muted d-block">Email</small>
                            <strong>
                                <?php echo htmlspecialchars($user['email']); ?>
                            </strong>
                        </div>


                        <div class="col-md-6">
                            <small class="text-muted d-block">Phone</small>
                            <strong>
                                <?php echo !empty($user['phone'])
                                    ? htmlspecialchars($user['phone'])
                                    : 'Not provided'; ?>
                            </strong>
                        </div>


                        <div class="col-md-6">
                            <small class="text-muted d-block">Role</small>

                            <?php if ($user['role'] === 'admin'): ?>

                                <span class="badge bg-danger">
                                    Admin
                                </span>

                            <?php elseif ($user['role'] === 'seller'): ?>

                                <span class="badge bg-warning text-dark">
                                    Seller / Maker
                                </span>

                            <?php else: ?>

                                <span class="badge bg-success">
                                    Customer
                                </span>

                            <?php endif; ?>

                        </div>


                        <div class="col-md-6">
                            <small class="text-muted d-block">Joined Date</small>
                            <strong>
                                <?php echo date('d M, Y h:i A', strtotime($user['created_at'])); ?>
                            </strong>
                        </div>


                        <div class="col-md-6">
                            <small class="text-muted d-block">Last Updated</small>
                            <strong>
                                <?php echo !empty($user['updated_at'])
                                    ? date('d M, Y h:i A', strtotime($user['updated_at']))
                                    : 'Not available'; ?>
                            </strong>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



<?php if ($user['role'] === 'customer'): ?>

    <!-- =====================================================
         CUSTOMER INFORMATION
    ====================================================== -->

    <div class="dashboard-card mb-4">

        <div class="dashboard-card-header">

            <h5 class="dashboard-card-title mb-0">
                <i class="fa-solid fa-cart-shopping text-maroon-800 me-2"></i>
                Customer Information
            </h5>

        </div>


        <div class="p-4">

            <div class="row g-3">

                <div class="col-md-3">

                    <div class="border rounded-3 p-3 text-center">

                        <i class="fa-solid fa-bag-shopping fa-2x text-maroon-800 mb-2"></i>

                        <h4 class="mb-0">
                            <?php echo $customer_stats['total_orders']; ?>
                        </h4>

                        <small class="text-muted">
                            Total Orders
                        </small>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="border rounded-3 p-3 text-center">

                        <i class="fa-solid fa-circle-check fa-2x text-success mb-2"></i>

                        <h4 class="mb-0">
                            <?php echo $customer_stats['completed_orders']; ?>
                        </h4>

                        <small class="text-muted">
                            Completed Orders
                        </small>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="border rounded-3 p-3 text-center">

                        <i class="fa-solid fa-circle-xmark fa-2x text-danger mb-2"></i>

                        <h4 class="mb-0">
                            <?php echo $customer_stats['cancelled_orders']; ?>
                        </h4>

                        <small class="text-muted">
                            Cancelled Orders
                        </small>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="border rounded-3 p-3 text-center">

                        <i class="fa-solid fa-star fa-2x text-warning mb-2"></i>

                        <h4 class="mb-0">
                            <?php echo $customer_stats['total_reviews']; ?>
                        </h4>

                        <small class="text-muted">
                            Total Reviews
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>


<?php elseif ($user['role'] === 'seller'): ?>


    <!-- =====================================================
         SELLER INFORMATION
    ====================================================== -->

    <div class="dashboard-card mb-4">

        <div class="dashboard-card-header">

            <h5 class="dashboard-card-title mb-0">
                <i class="fa-solid fa-store text-maroon-800 me-2"></i>
                Seller / Maker Information
            </h5>

            <?php if ($seller && $seller['is_verified']): ?>

                <span class="badge bg-success">
                    <i class="fa-solid fa-circle-check me-1"></i>
                    Verified
                </span>

            <?php endif; ?>

        </div>


        <div class="p-4">

            <?php if (!$seller): ?>

                <div class="alert alert-warning mb-0">
                    Seller profile information is not available.
                </div>

            <?php else: ?>

                <div class="row g-4">

                    <div class="col-md-6">

                        <small class="text-muted d-block">
                            Business Name
                        </small>

                        <strong>
                            <?php echo htmlspecialchars($seller['business_name']); ?>
                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block">
                            Owner Name
                        </small>

                        <strong>
                            <?php echo htmlspecialchars($seller['owner_name']); ?>
                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block">
                            Category
                        </small>

                        <strong>
                            <?php echo htmlspecialchars($seller['category']); ?>
                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block">
                            City / Location
                        </small>

                        <strong>
                            <?php echo htmlspecialchars($seller['location']); ?>
                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block">
                            Business Email
                        </small>

                        <strong>
                            <?php echo !empty($seller['email'])
                                ? htmlspecialchars($seller['email'])
                                : 'Not provided'; ?>
                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block">
                            WhatsApp
                        </small>

                        <strong>
                            <?php echo !empty($seller['whatsapp'])
                                ? htmlspecialchars($seller['whatsapp'])
                                : 'Not provided'; ?>
                        </strong>

                    </div>


                    <div class="col-12">

                        <small class="text-muted d-block">
                            Business Description
                        </small>

                        <p class="mb-0">
                            <?php
                            echo !empty($seller['short_bio'])
                                ? nl2br(htmlspecialchars($seller['short_bio']))
                                : 'No business description provided.';
                            ?>
                        </p>

                    </div>


                    <div class="col-12">

                        <small class="text-muted d-block">
                            Address
                        </small>

                        <p class="mb-0">
                            <?php
                            echo !empty($seller['address'])
                                ? nl2br(htmlspecialchars($seller['address']))
                                : 'Not provided.';
                            ?>
                        </p>

                    </div>

                </div>


                <hr class="my-4">


                <!-- Seller Statistics -->

                <h6 class="fw-bold mb-3">
                    Seller Statistics
                </h6>


                <div class="row g-3">

                    <div class="col-md-4">

                        <div class="border rounded-3 p-3 text-center">

                            <i class="fa-solid fa-box-open fa-2x text-maroon-800 mb-2"></i>

                            <h4 class="mb-0">
                                <?php echo $seller_stats['total_products']; ?>
                            </h4>

                            <small class="text-muted">
                                Total Products
                            </small>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="border rounded-3 p-3 text-center">

                            <i class="fa-solid fa-cart-shopping fa-2x text-maroon-800 mb-2"></i>

                            <h4 class="mb-0">
                                <?php echo $seller_stats['total_orders']; ?>
                            </h4>

                            <small class="text-muted">
                                Total Orders
                            </small>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="border rounded-3 p-3 text-center">

                            <i class="fa-solid fa-star fa-2x text-warning mb-2"></i>

                            <h4 class="mb-0">
                                <?php echo $seller_stats['total_reviews']; ?>
                            </h4>

                            <small class="text-muted">
                                Total Reviews
                            </small>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>


<?php endif; ?>


    <!-- Bottom Actions -->

    <div class="d-flex gap-2 mb-4">

        <?php if ($user['role'] !== 'admin'): ?>

            <a
                href="user-edit.php?id=<?php echo $user['id']; ?>"
                class="btn btn-maroon"
            >
                <i class="fa-solid fa-pen me-1"></i>
                Edit User
            </a>

        <?php endif; ?>


        <a
            href="users.php"
            class="btn btn-light border"
        >
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back to Users
        </a>

    </div>

</div>


<?php require_once __DIR__ . '/includes/footer.php'; ?>