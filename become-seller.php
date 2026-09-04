<?php

/**
 * Udyojika - Become a Seller
 * Complete Seller Registration
 */



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$page_title = "Become a Seller | Udyojika";

$success_message = '';
$error_message = '';


// =====================================================
// LOGIN CHECK
// =====================================================

if (empty($_SESSION['user_id'])) {
    header('Location: login.php?msg=auth_required');
    exit;
}

$current_user_id = (int) $_SESSION['user_id'];


// =====================================================
// GET CURRENT USER
// =====================================================

$user_stmt = $pdo->prepare("
    SELECT id, name, email, phone, role
    FROM users
    WHERE id = ?
    LIMIT 1
");

$user_stmt->execute([$current_user_id]);

$current_user = $user_stmt->fetch(PDO::FETCH_ASSOC);

if (!$current_user) {
    session_destroy();
    header('Location: login.php?msg=auth_required');
    exit;
}


// =====================================================
// ADMIN / EXISTING SELLER CHECK
// =====================================================

if ($current_user['role'] === 'admin') {
    header('Location: admin/index.php');
    exit;
}

if ($current_user['role'] === 'seller') {
    header('Location: seller/index.php');
    exit;
}


// =====================================================
// DEFAULT FORM VALUES
// =====================================================

$full_name = $current_user['name'] ?? '';
$business_name = '';
$category = '';
$location = '';
$short_bio = '';
$full_story = '';
$specialty = '';
$whatsapp = $current_user['phone'] ?? '';
$email = $current_user['email'] ?? '';
$address = '';


// =====================================================
// FORM SUBMISSION
// =====================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $full_name = trim($_POST['full_name'] ?? '');
    $business_name = trim($_POST['business_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $short_bio = trim($_POST['short_bio'] ?? '');
    $full_story = trim($_POST['full_story'] ?? '');
    $specialty = trim($_POST['specialty'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');


    // =================================================
    // VALIDATION
    // =================================================

    if (
        $full_name === '' ||
        $business_name === '' ||
        $category === '' ||
        $location === '' ||
        $short_bio === '' ||
        $full_story === '' ||
        $specialty === '' ||
        $whatsapp === '' ||
        $email === '' ||
        $address === ''
    ) {

        $error_message = "Please fill in all required fields.";

    } elseif (mb_strlen($full_name) < 2) {

        $error_message = "Please enter a valid full name.";

    } elseif (mb_strlen($business_name) < 2) {

        $error_message = "Please enter a valid business name.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error_message = "Please enter a valid email address.";

    } elseif (!preg_match('/^[0-9+\-\s]{10,15}$/', $whatsapp)) {

        $error_message = "Please enter a valid WhatsApp number.";

    } elseif (mb_strlen($short_bio) < 10) {

        $error_message = "Please write a little more about your business.";

    } elseif (mb_strlen($full_story) < 20) {

        $error_message = "Please provide more details about your business.";

    } else {

        try {

            // =============================================
            // CHECK EXISTING SELLER APPLICATION
            // =============================================

            $check_stmt = $pdo->prepare("
                SELECT id, status
                FROM sellers
                WHERE user_id = ?
                LIMIT 1
            ");

            $check_stmt->execute([$current_user_id]);

            $existing_seller = $check_stmt->fetch(PDO::FETCH_ASSOC);


            if ($existing_seller) {

                if ($existing_seller['status'] === 'pending') {

                    $error_message =
                        "Your seller application is already pending approval.";

                } elseif ($existing_seller['status'] === 'active') {

                    $error_message =
                        "Your seller account is already active. Please login again.";

                } elseif ($existing_seller['status'] === 'suspended') {

                    $error_message =
                        "Your seller account is currently suspended.";

                } else {

                    $error_message =
                        "A seller application already exists for this account.";
                }


            } else {

                // =============================================
                // FILE UPLOAD DIRECTORIES
                // =============================================

                $avatar_dir = __DIR__ . '/uploads/sellers/avatar/';
                $banner_dir = __DIR__ . '/uploads/sellers/banner/';

                if (!is_dir($avatar_dir)) {
                    mkdir($avatar_dir, 0777, true);
                }

                if (!is_dir($banner_dir)) {
                    mkdir($banner_dir, 0777, true);
                }


                // =============================================
                // AVATAR UPLOAD
                // =============================================

                $avatar_path = null;

                if (
                    isset($_FILES['avatar']) &&
                    $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE
                ) {

                    if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
                        throw new Exception("There was an error uploading the profile photo.");
                    }

                    $allowed_types = [
                        'image/jpeg',
                        'image/png',
                        'image/webp'
                    ];

                    $file_type = mime_content_type(
                        $_FILES['avatar']['tmp_name']
                    );

                    if (!in_array($file_type, $allowed_types, true)) {
                        throw new Exception(
                            "Profile photo must be JPG, PNG or WEBP."
                        );
                    }

                    if ($_FILES['avatar']['size'] > 5 * 1024 * 1024) {
                        throw new Exception(
                            "Profile photo must be less than 5MB."
                        );
                    }

                    $extension = strtolower(
                        pathinfo(
                            $_FILES['avatar']['name'],
                            PATHINFO_EXTENSION
                        )
                    );

                    $file_name =
                        'avatar_' .
                        $current_user_id .
                        '_' .
                        time() .
                        '.' .
                        $extension;

                    $destination =
                        $avatar_dir . $file_name;

                    if (!move_uploaded_file(
                        $_FILES['avatar']['tmp_name'],
                        $destination
                    )) {
                        throw new Exception(
                            "Unable to save profile photo."
                        );
                    }

                    $avatar_path =
                        'uploads/sellers/avatar/' . $file_name;
                }


                // =============================================
                // BANNER UPLOAD
                // =============================================

                $banner_path = null;

                if (
                    isset($_FILES['banner_image']) &&
                    $_FILES['banner_image']['error'] !== UPLOAD_ERR_NO_FILE
                ) {

                    if ($_FILES['banner_image']['error'] !== UPLOAD_ERR_OK) {
                        throw new Exception("There was an error uploading the business banner.");
                    }

                    $allowed_types = [
                        'image/jpeg',
                        'image/png',
                        'image/webp'
                    ];

                    $file_type = mime_content_type(
                        $_FILES['banner_image']['tmp_name']
                    );

                    if (!in_array($file_type, $allowed_types, true)) {
                        throw new Exception(
                            "Business banner must be JPG, PNG or WEBP."
                        );
                    }

                    if ($_FILES['banner_image']['size'] > 8 * 1024 * 1024) {
                        throw new Exception(
                            "Business banner must be less than 8MB."
                        );
                    }

                    $extension = strtolower(
                        pathinfo(
                            $_FILES['banner_image']['name'],
                            PATHINFO_EXTENSION
                        )
                    );

                    $file_name =
                        'banner_' .
                        $current_user_id .
                        '_' .
                        time() .
                        '.' .
                        $extension;

                    $destination =
                        $banner_dir . $file_name;

                    if (!move_uploaded_file(
                        $_FILES['banner_image']['tmp_name'],
                        $destination
                    )) {
                        throw new Exception(
                            "Unable to save business banner."
                        );
                    }

                    $banner_path =
                        'uploads/sellers/banner/' . $file_name;
                }


                // =============================================
                // INSERT SELLER
                // =============================================

                $insert_stmt = $pdo->prepare("
                    INSERT INTO sellers
                    (
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
                        5.00,
                        0,
                        0,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        0,
                        'pending',
                        NOW()
                    )
                ");

                $insert_stmt->execute([
                    $current_user_id,
                    $business_name,
                    $full_name,
                    $category,
                    $location,
                    $avatar_path,
                    $banner_path,
                    $short_bio,
                    $full_story,
                    $specialty,
                    date('Y'),
                    $whatsapp,
                    $email,
                    $address
                ]);


                // =============================================
                // SUCCESS
                // =============================================

                $success_message =
                    "Thank you, " .
                    htmlspecialchars(
                        $full_name,
                        ENT_QUOTES,
                        'UTF-8'
                    ) .
                    "! Your seller application has been submitted successfully. Please wait for admin approval.";


                // Clear form
                $full_name = '';
                $business_name = '';
                $category = '';
                $location = '';
                $short_bio = '';
                $full_story = '';
                $specialty = '';
                $whatsapp = '';
                $email = '';
                $address = '';
            }

        } catch (Exception $e) {

            $error_message = $e->getMessage();

        } catch (PDOException $e) {

            $error_message =
                "We could not submit your application right now. Please try again later.";

            error_log(
                "Seller Registration Error: " .
                $e->getMessage()
            );
        }
    }
}


// =====================================================
// GET CATEGORIES
// =====================================================

$categories = get_categories($pdo);
require_once __DIR__ . '/includes/header.php';

require_once __DIR__ . '/includes/header.php';

?>

<!-- =====================================================
     SELLER REGISTRATION
===================================================== -->

<div class="bg-maroon-900 text-white py-5">

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="bg-white text-dark p-4 p-md-5 rounded-4 shadow-lg">

                    <!-- TITLE -->

                    <h1 class="font-serif fw-bold text-maroon-900 mb-2">
                        Quick Seller Registration
                    </h1>

                    <p class="text-muted mb-4">
                        Start your homemade business with Udyojika.
                    </p>


                    <!-- SUCCESS -->

                    <?php if (!empty($success_message)): ?>

                        <div class="alert alert-success">
                            <i class="fa-solid fa-circle-check me-2"></i>

                            <?php echo $success_message; ?>
                        </div>

                    <?php endif; ?>


                    <!-- ERROR -->

                    <?php if (!empty($error_message)): ?>

                        <div class="alert alert-danger">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>

                            <?php
                            echo htmlspecialchars(
                                $error_message,
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>

                        </div>

                    <?php endif; ?>


                    <!-- FORM -->

                    <form
                        action="become-seller.php"
                        method="POST"
                        enctype="multipart/form-data"
                    >


                        <!-- FULL NAME -->

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Your Full Name *
                            </label>

                            <input
                                type="text"
                                name="full_name"
                                class="form-control"
                                required
                                placeholder="e.g. Radhika Sharma"
                                value="<?php echo htmlspecialchars(
                                    $full_name,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                            >

                        </div>


                        <!-- BUSINESS NAME -->

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Home Brand / Business Name *
                            </label>

                            <input
                                type="text"
                                name="business_name"
                                class="form-control"
                                required
                                placeholder="e.g. Radhika's Kitchen"
                                value="<?php echo htmlspecialchars(
                                    $business_name,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                            >

                        </div>


                        <!-- WHATSAPP + EMAIL -->

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-bold">
                                    WhatsApp Number *
                                </label>

                                <input
                                    type="tel"
                                    name="whatsapp"
                                    class="form-control"
                                    required
                                    placeholder="+91 98765 43210"
                                    value="<?php echo htmlspecialchars(
                                        $whatsapp,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-bold">
                                    Email *
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    required
                                    placeholder="example@gmail.com"
                                    value="<?php echo htmlspecialchars(
                                        $email,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
                                >

                            </div>

                        </div>


                        <!-- LOCATION + ADDRESS -->

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-bold">
                                    City / Town *
                                </label>

                                <input
                                    type="text"
                                    name="location"
                                    class="form-control"
                                    required
                                    placeholder="e.g. Jalgaon"
                                    value="<?php echo htmlspecialchars(
                                        $location,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-bold">
                                    Full Address *
                                </label>

                                <input
                                    type="text"
                                    name="address"
                                    class="form-control"
                                    required
                                    placeholder="Your complete address"
                                    value="<?php echo htmlspecialchars(
                                        $address,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>"
                                >

                            </div>

                        </div>


                        <!-- CATEGORY -->

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Primary Category *
                            </label>

                            <select
                                name="category"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Category
                                </option>

                                <?php foreach ($categories as $cat): ?>

                                    <option
                                        value="<?php echo htmlspecialchars(
                                            $cat['name'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ); ?>"
                                        <?php
                                        echo (
                                            $category === $cat['name']
                                        )
                                        ? 'selected'
                                        : '';
                                        ?>
                                    >

                                        <?php
                                        echo htmlspecialchars(
                                            $cat['name'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- SPECIALTY -->

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Your Specialty *
                            </label>

                            <input
                                type="text"
                                name="specialty"
                                class="form-control"
                                required
                                placeholder="e.g. Pickles, Masala, Handmade Jewellery"
                                value="<?php echo htmlspecialchars(
                                    $specialty,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>"
                            >

                        </div>


                        <!-- SHORT BIO -->

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Short Bio *
                            </label>

                            <textarea
                                name="short_bio"
                                class="form-control"
                                rows="3"
                                required
                                placeholder="Tell customers briefly about your business..."
                            ><?php
                            echo htmlspecialchars(
                                $short_bio,
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?></textarea>

                        </div>


                        <!-- FULL STORY -->

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Full Business Story *
                            </label>

                            <textarea
                                name="full_story"
                                class="form-control"
                                rows="5"
                                required
                                placeholder="Tell us about your business, experience and products..."
                            ><?php
                            echo htmlspecialchars(
                                $full_story,
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?></textarea>

                        </div>


                        <!-- PROFILE PHOTO -->

                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Profile Photo
                            </label>

                            <input
                                type="file"
                                name="avatar"
                                class="form-control"
                                accept="image/jpeg,image/png,image/webp"
                            >

                            <small class="text-muted">
                                JPG, PNG or WEBP. Maximum 5MB.
                            </small>

                        </div>


                        <!-- BANNER -->

                        <div class="mb-4">

                            <label class="form-label fw-bold">
                                Business Banner
                            </label>

                            <input
                                type="file"
                                name="banner_image"
                                class="form-control"
                                accept="image/jpeg,image/png,image/webp"
                            >

                            <small class="text-muted">
                                JPG, PNG or WEBP. Maximum 8MB.
                            </small>

                        </div>


                        <!-- SUBMIT -->

                        <button
                            type="submit"
                            class="btn btn-maroon w-100 py-3 fw-bold"
                        >

                            Submit Seller Application

                            <i class="fa-solid fa-arrow-right ms-2"></i>

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<?php require_once __DIR__ . '/includes/footer.php'; ?>