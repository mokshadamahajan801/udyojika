<?php
/**
 * Udyojika - Seller Application
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$page_title = "Start Your Home Business | Become a Seller on Udyojika";

$success_message = '';
$error_message = '';

/*
|--------------------------------------------------------------------------
| Default Form Values
|--------------------------------------------------------------------------
*/

$full_name = '';
$business_name = '';
$phone = '';
$city = '';
$category = '';
$description = '';

/*
|--------------------------------------------------------------------------
| Check Login
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php?msg=auth_required');
    exit;
}

$current_user_id = (int) $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| Get Current User
|--------------------------------------------------------------------------
*/

$user_stmt = $pdo->prepare("
    SELECT id, name, email, role
    FROM users
    WHERE id = ?
    LIMIT 1
");

$user_stmt->execute([$current_user_id]);
$current_user = $user_stmt->fetch(PDO::FETCH_ASSOC);

if (!$current_user) {
    unset(
        $_SESSION['user'],
        $_SESSION['user_id'],
        $_SESSION['user_role']
    );

    header('Location: login.php?msg=auth_required');
    exit;
}

/*
|--------------------------------------------------------------------------
| Prevent Admin / Existing Seller From Applying Again
|--------------------------------------------------------------------------
*/

if ($current_user['role'] === 'seller') {
    header('Location: seller/index.php');
    exit;
}

if ($current_user['role'] === 'admin') {
    header('Location: admin/index.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Show Success Message After Redirect
|--------------------------------------------------------------------------
|
| This prevents the browser from submitting the same POST again
| when the page is refreshed.
|--------------------------------------------------------------------------
*/

if (!empty($_SESSION['seller_application_success'])) {
    $success_message = $_SESSION['seller_application_success'];
    unset($_SESSION['seller_application_success']);
}

/*
|--------------------------------------------------------------------------
| Seller Application Processing
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $full_name = trim($_POST['full_name'] ?? '');
    $business_name = trim($_POST['business_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if (
        $full_name === '' ||
        $business_name === '' ||
        $phone === '' ||
        $city === '' ||
        $category === '' ||
        $description === ''
    ) {

        $error_message = 'Please fill in all required fields.';

    } elseif (mb_strlen($full_name) < 2) {

        $error_message = 'Please enter a valid full name.';

    } elseif (mb_strlen($business_name) < 2) {

        $error_message = 'Please enter a valid business name.';

    } elseif (!preg_match('/^[0-9+\-\s]{10,15}$/', $phone)) {

        $error_message = 'Please enter a valid WhatsApp number.';

    } elseif (mb_strlen($description) < 10) {

        $error_message =
            'Please provide a little more information about your products.';

    } else {

        try {

            /*
            |--------------------------------------------------------------------------
            | Check Existing Application For This Logged-in User
            |--------------------------------------------------------------------------
            */

            $check_stmt = $pdo->prepare("
                SELECT id, status
                FROM seller_requests
                WHERE user_id = ?
                ORDER BY id DESC
                LIMIT 1
            ");

            $check_stmt->execute([$current_user_id]);

            $existing_request = $check_stmt->fetch(PDO::FETCH_ASSOC);

            /*
            |--------------------------------------------------------------------------
            | Existing Pending Application
            |--------------------------------------------------------------------------
            */

            if (
                $existing_request &&
                $existing_request['status'] === 'pending'
            ) {

                $error_message =
                    'Your seller application is already pending approval. Please wait for the administrator to review it.';

            /*
            |--------------------------------------------------------------------------
            | Existing Approved Application
            |--------------------------------------------------------------------------
            */

            } elseif (
                $existing_request &&
                $existing_request['status'] === 'approved'
            ) {

                $error_message =
                    'Your seller application has already been approved. Please sign in again to access your seller dashboard.';

            /*
            |--------------------------------------------------------------------------
            | New Application
            |--------------------------------------------------------------------------
            */

            } else {

                $stmt = $pdo->prepare("
                    INSERT INTO seller_requests
                    (
                        user_id,
                        full_name,
                        business_name,
                        phone,
                        city,
                        category,
                        description,
                        sample_products,
                        status,
                        created_at
                    )
                    VALUES
                    (
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        'pending',
                        NOW()
                    )
                ");

                $stmt->execute([
                    $current_user_id,
                    $full_name,
                    $business_name,
                    $phone,
                    $city,
                    $category,
                    $description,
                    ''
                ]);

                /*
                |--------------------------------------------------------------------------
                | Success Flash Message
                |--------------------------------------------------------------------------
                */

                $_SESSION['seller_application_success'] =
                    "Thank you, " .
                    htmlspecialchars(
                        $full_name,
                        ENT_QUOTES,
                        'UTF-8'
                    ) .
                    "! Your application for '" .
                    htmlspecialchars(
                        $business_name,
                        ENT_QUOTES,
                        'UTF-8'
                    ) .
                    "' has been received. Our Women Entrepreneurship cell will review your application.";

                /*
                |--------------------------------------------------------------------------
                | POST -> Redirect -> GET
                |--------------------------------------------------------------------------
                */

                header('Location: become-seller.php');
                exit;
            }

        } catch (PDOException $e) {

            /*
            |--------------------------------------------------------------------------
            | Temporary detailed database error
            |--------------------------------------------------------------------------
            |
            | This helps us find the exact database problem if INSERT fails.
            | Once everything works, this can be changed to a generic message.
            |--------------------------------------------------------------------------
            */

            $error_message =
                'DATABASE ERROR: ' . $e->getMessage();
        }
    }
}

/*
|--------------------------------------------------------------------------
| Get Categories From Database
|--------------------------------------------------------------------------
*/

$categories = get_categories($pdo);

?>
<!-- Hero Banner -->
<div class="bg-maroon-900 text-white py-5">
    <div class="container py-4">
        <div class="row align-items-center g-5">

            <div class="col-lg-7">

                <span class="badge bg-warning text-dark fw-bold px-3 py-2 mb-3">
                    WOMEN HOME ENTREPRENEURS PROGRAM
                </span>

                <h1 class="display-5 font-serif fw-bold mb-3">
                    Turn Your Kitchen & Craft Talent into a Thriving Business
                </h1>

                <p class="lead text-light opacity-75 mb-4">
                    Join over 5,000+ Indian homemakers who earn between ₹25,000 to ₹1,50,000+ every month by selling homemade snacks, sweets, pickles, jewellery, and traditional handicrafts.
                </p>

                <div class="d-flex flex-wrap gap-4 pt-2">

                    <div>
                        <h3 class="fw-bold text-warning font-serif mb-0">₹0</h3>
                        <small class="text-light opacity-75">Registration Fee</small>
                    </div>

                    <div class="vr bg-light"></div>

                    <div>
                        <h3 class="fw-bold text-warning font-serif mb-0">Doorstep</h3>
                        <small class="text-light opacity-75">Courier Pickup</small>
                    </div>

                    <div class="vr bg-light"></div>

                    <div>
                        <h3 class="fw-bold text-warning font-serif mb-0">Weekly</h3>
                        <small class="text-light opacity-75">Direct Bank Payouts</small>
                    </div>

                </div>

            </div>


            <div class="col-lg-5">

                <div class="bg-white text-dark p-4 rounded-4 shadow-lg">

                    <h4 class="font-serif fw-bold text-maroon-900 mb-1">
                        Quick Seller Registration
                    </h4>

                    <p class="small text-muted mb-3">
                        Get your homemade store live in less than 24 hours.
                    </p>


                    <!-- Success Message -->
                    <?php if (!empty($success_message)): ?>

                        <div class="alert alert-success d-flex align-items-center gap-2">

                            <i class="fa-solid fa-circle-check fs-4"></i>

                            <div>
                                <?php echo $success_message; ?>
                            </div>

                        </div>

                    <?php endif; ?>


                    <!-- Error Message -->
                    <?php if (!empty($error_message)): ?>

                        <div class="alert alert-danger d-flex align-items-center gap-2">

                            <i class="fa-solid fa-circle-exclamation fs-4"></i>

                            <div>
                                <?php echo htmlspecialchars(
                                    $error_message,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>
                            </div>

                        </div>

                    <?php endif; ?>


                    <form action="become-seller.php" method="POST" class="needs-validation" novalidate>

                        <div class="mb-2">

                            <label class="form-label small fw-bold mb-1">
                                Your Full Name *
                            </label>

                            <input
                                type="text"
                                name="full_name"
                                class="form-control form-control-sm"
                                required
                                placeholder="e.g. Radhika Sharma"
                                value="<?php echo htmlspecialchars(
                                    $full_name ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>">

                        </div>


                        <div class="mb-2">

                            <label class="form-label small fw-bold mb-1">
                                Home Brand / Business Name *
                            </label>

                            <input
                                type="text"
                                name="business_name"
                                class="form-control form-control-sm"
                                required
                                placeholder="e.g. Radhika's Kitchen Masalas"
                                value="<?php echo htmlspecialchars(
                                    $business_name ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>">

                        </div>


                        <div class="row g-2 mb-2">

                            <div class="col-6">

                                <label class="form-label small fw-bold mb-1">
                                    WhatsApp Number *
                                </label>

                                <input
                                    type="tel"
                                    name="phone"
                                    class="form-control form-control-sm"
                                    required
                                    placeholder="+91 98765 43210"
                                    value="<?php echo htmlspecialchars(
                                        $phone ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>">

                            </div>


                            <div class="col-6">

                                <label class="form-label small fw-bold mb-1">
                                    City / Town *
                                </label>

                                <input
                                    type="text"
                                    name="city"
                                    class="form-control form-control-sm"
                                    required
                                    placeholder="e.g. Pune / Nagpur"
                                    value="<?php echo htmlspecialchars(
                                        $city ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>">

                            </div>

                        </div>


                        <div class="mb-2">

                            <label class="form-label small fw-bold mb-1">
                                Primary Category *
                            </label>

                            <select
                                name="category"
                                class="form-select form-select-sm"
                                required>

                                <option value="">Select Category</option>

                                <?php foreach ($categories as $cat): ?>

                                    <option
                                        value="<?php echo htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                        <?php echo (($category ?? '') === $cat['name']) ? 'selected' : ''; ?>>

                                        <?php echo htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8'); ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <div class="mb-3">

                            <label class="form-label small fw-bold mb-1">
                                Tell Us What You Make *
                            </label>

                            <textarea
                                name="description"
                                class="form-control form-control-sm"
                                rows="2"
                                required
                                placeholder="Describe your recipes, ingredients or handmade products..."><?php echo htmlspecialchars(
                                    $description ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?></textarea>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-maroon w-100 py-2 fw-bold">
                            Submit Seller Application
                            <i class="fa-solid fa-arrow-right ms-1"></i>
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </div>
</div>


<!-- Why Sell With Udyojika -->
<div class="container py-5">

    <div class="text-center max-w-700 mx-auto mb-5">

        <span class="text-terracotta fw-bold text-uppercase small tracking-wide">
            Why Join Udyojika
        </span>

        <h2 class="font-serif fw-bold text-maroon-900 mb-2">
            Designed Specifically for Women Homemakers
        </h2>

        <p class="text-muted">
            We remove all friction so you can focus 100% on making high quality homemade products.
        </p>

    </div>


    <div class="row g-4">

        <div class="col-md-4">

            <div class="p-4 bg-white rounded-4 shadow-sm border h-100">

                <div class="bg-danger-subtle text-danger rounded-circle p-3 d-inline-flex mb-3 fs-4">
                    <i class="fa-solid fa-house-chimney-user"></i>
                </div>

                <h5 class="fw-bold mb-2">
                    Work From Your Home
                </h5>

                <p class="text-secondary small mb-0">
                    No need for expensive retail space. Prepare orders from your existing home kitchen or studio at your own flexible pace.
                </p>

            </div>

        </div>


        <div class="col-md-4">

            <div class="p-4 bg-white rounded-4 shadow-sm border h-100">

                <div class="bg-warning-subtle text-warning rounded-circle p-3 d-inline-flex mb-3 fs-4">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>

                <h5 class="fw-bold mb-2">
                    Doorstep Logistics
                </h5>

                <p class="text-secondary small mb-0">
                    Our courier partners pick up freshly packed items directly from your home and deliver safely across all Indian pin codes.
                </p>

            </div>

        </div>


        <div class="col-md-4">

            <div class="p-4 bg-white rounded-4 shadow-sm border h-100">

                <div class="bg-success-subtle text-success rounded-circle p-3 d-inline-flex mb-3 fs-4">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>

                <h5 class="fw-bold mb-2">
                    Weekly Bank Settlements
                </h5>

                <p class="text-secondary small mb-0">
                    Direct deposits to your bank account every Tuesday. Transparent order records and zero hidden transaction cuts.
                </p>

            </div>

        </div>

    </div>

</div>


<?php
require_once __DIR__ . '/includes/footer.php';
?>
