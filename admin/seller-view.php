<?php

require_once __DIR__ . '/includes/auth.php';
require_admin();

require_once __DIR__ . '/../includes/db.php';


// =====================================================
// GET SELLER ID
// =====================================================
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: sellers.php?error=invalid_seller');
    exit;
}

$seller_id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT
        id,
        user_id,
        business_name,
        owner_name,
        category,
        location,
        product_count,
        avatar,
        short_bio,
        full_story,
        whatsapp,
        email,
        status,
        created_at
    FROM sellers
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$seller_id]);

$seller = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$seller) {
    header('Location: sellers.php?error=seller_not_found');
    exit;
}

// =====================================================
// PAGE INFORMATION
// =====================================================

$page_title = "View Seller - Udyojika";
$page_header = "Seller Details";
$page_subheader = "View complete maker and business information";

require_once __DIR__ . '/includes/header.php';


// =====================================================
// DEFAULT AVATAR
// =====================================================

$avatar = !empty($seller['avatar'])
    ? $seller['avatar']
    : 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300';


// =====================================================
// STATUS
// =====================================================

$status = strtolower($seller['status'] ?? 'inactive');

?>

<div class="container-fluid">

    <!-- =================================================
         BACK BUTTON
    ================================================== -->

    <div class="mb-4">

        <a href="sellers.php" class="btn btn-light border btn-sm">

            <i class="fa-solid fa-arrow-left me-1"></i>

            Back to Sellers

        </a>

    </div>


    <!-- =================================================
         PROFILE HEADER
    ================================================== -->

    <div class="dashboard-card mb-4">

        <div class="p-4">

            <div class="row align-items-center g-4">

                <!-- PROFILE PHOTO -->

                <div class="col-auto">

                    <img
                        src="<?php echo htmlspecialchars($avatar); ?>"
                        alt="Seller Profile"
                        class="rounded-circle border"
                        style="
                            width: 110px;
                            height: 110px;
                            object-fit: cover;
                        ">

                </div>


                <!-- BASIC INFO -->

                <div class="col">

                    <div class="d-flex align-items-center gap-2 flex-wrap">

                        <h3 class="fw-bold text-maroon-900 mb-0">

                            <?php
                            echo htmlspecialchars(
                                $seller['business_name']
                            );
                            ?>

                        </h3>


                        <?php if ($status === 'active'): ?>

                            <span class="badge bg-success">

                                <i class="fa-solid fa-circle-check me-1"></i>

                                Active

                            </span>

                        <?php else: ?>

                            <span class="badge bg-danger">

                                <i class="fa-solid fa-circle-xmark me-1"></i>

                                Inactive

                            </span>

                        <?php endif; ?>

                    </div>


                    <p class="text-muted mb-2 mt-1">

                        <i class="fa-solid fa-user me-1"></i>

                        Owner:
                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $seller['owner_name']
                            );
                            ?>
                        </strong>

                    </p>


                    <div class="d-flex flex-wrap gap-3 small text-muted">

                        <span>

                            <i class="fa-solid fa-tag text-terracotta me-1"></i>

                            <?php
                            echo htmlspecialchars(
                                $seller['category']
                            );
                            ?>

                        </span>


                        <span>

                            <i class="fa-solid fa-location-dot text-terracotta me-1"></i>

                            <?php
                            echo htmlspecialchars(
                                $seller['location']
                            );
                            ?>

                        </span>


                        <span>

                            <i class="fa-solid fa-box text-terracotta me-1"></i>

                            <?php
                            echo (int) $seller['product_count'];
                            ?>
                            Products

                        </span>

                    </div>

                </div>


                <!-- ACTION BUTTONS -->

                <div class="col-auto">

                    <div class="d-flex gap-2">

                        <a
                            href="seller-edit.php?id=<?php echo $seller['id']; ?>"
                            class="btn btn-primary">

                            <i class="fa-solid fa-pen me-1"></i>

                            Edit

                        </a>


                        <?php if ($status === 'active'): ?>

                            <a
                                href="seller-suspend.php?id=<?php echo $seller['id']; ?>"
                                class="btn btn-outline-danger"
                                onclick="return confirm('Suspend this seller?');">

                                <i class="fa-solid fa-ban me-1"></i>

                                Suspend

                            </a>

                        <?php else: ?>

                            <a
                                href="seller-suspend.php?id=<?php echo $seller['id']; ?>"
                                class="btn btn-outline-success"
                                onclick="return confirm('Activate this seller?');">

                                <i class="fa-solid fa-check me-1"></i>

                                Activate

                            </a>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =================================================
         BUSINESS INFORMATION
    ================================================== -->

    <div class="row g-4">


        <!-- LEFT COLUMN -->

        <div class="col-lg-8">


            <!-- ABOUT BUSINESS -->

            <div class="dashboard-card mb-4">

                <div class="dashboard-card-header">

                    <h5 class="dashboard-card-title">

                        <i class="fa-solid fa-store text-maroon-800 me-2"></i>

                        Business Information

                    </h5>

                </div>


                <div class="p-4">


                    <div class="row g-4">


                        <!-- BUSINESS NAME -->

                        <div class="col-md-6">

                            <label class="small text-muted d-block">
                                Business Name
                            </label>

                            <strong class="text-dark">

                                <?php
                                echo htmlspecialchars(
                                    $seller['business_name']
                                );
                                ?>

                            </strong>

                        </div>


                        <!-- OWNER -->

                        <div class="col-md-6">

                            <label class="small text-muted d-block">
                                Owner Name
                            </label>

                            <strong class="text-dark">

                                <?php
                                echo htmlspecialchars(
                                    $seller['owner_name']
                                );
                                ?>

                            </strong>

                        </div>


                        <!-- CATEGORY -->

                        <div class="col-md-6">

                            <label class="small text-muted d-block">
                                Category
                            </label>

                            <strong class="text-dark">

                                <?php
                                echo htmlspecialchars(
                                    $seller['category']
                                );
                                ?>

                            </strong>

                        </div>


                        <!-- LOCATION -->

                        <div class="col-md-6">

                            <label class="small text-muted d-block">
                                Location
                            </label>

                            <strong class="text-dark">

                                <i class="fa-solid fa-location-dot text-danger me-1"></i>

                                <?php
                                echo htmlspecialchars(
                                    $seller['location']
                                );
                                ?>

                            </strong>

                        </div>


                        <!-- PRODUCTS -->

                        <div class="col-md-6">

                            <label class="small text-muted d-block">
                                Total Products
                            </label>

                            <strong class="text-dark">

                                <?php
                                echo (int) $seller['product_count'];
                                ?>

                            </strong>

                        </div>


                        <!-- STATUS -->

                        <div class="col-md-6">

                            <label class="small text-muted d-block">
                                Account Status
                            </label>


                            <?php if ($status === 'active'): ?>

                                <span class="badge bg-success">
                                    Active
                                </span>

                            <?php else: ?>

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            <?php endif; ?>

                        </div>


                    </div>


                    <hr class="my-4">


                    <!-- SHORT BIO -->

                    <div class="mb-4">

                        <label class="small text-muted d-block mb-1">
                            Short Description
                        </label>

                        <p class="mb-0 text-secondary">

                            <?php
                            echo !empty($seller['short_bio'])
                                ? nl2br(
                                    htmlspecialchars(
                                        $seller['short_bio']
                                    )
                                )
                                : 'No short description provided.';
                            ?>

                        </p>

                    </div>


                    <!-- FULL STORY -->

                    <div>

                        <label class="small text-muted d-block mb-1">
                            Business Story
                        </label>

                        <p class="mb-0 text-secondary">

                            <?php
                            echo !empty($seller['full_story'])
                                ? nl2br(
                                    htmlspecialchars(
                                        $seller['full_story']
                                    )
                                )
                                : 'No business story provided.';
                            ?>

                        </p>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 CONTACT INFORMATION
            ================================================== -->

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <h5 class="dashboard-card-title">

                        <i class="fa-solid fa-address-card text-maroon-800 me-2"></i>

                        Contact Information

                    </h5>

                </div>


                <div class="p-4">

                    <div class="row g-4">


                        <!-- EMAIL -->

                        <div class="col-md-6">

                            <label class="small text-muted d-block mb-1">
                                Email Address
                            </label>

                            <?php if (!empty($seller['email'])): ?>

                                <a
                                    href="mailto:<?php echo htmlspecialchars($seller['email']); ?>"
                                    class="text-decoration-none">

                                    <i class="fa-solid fa-envelope me-1"></i>

                                    <?php
                                    echo htmlspecialchars(
                                        $seller['email']
                                    );
                                    ?>

                                </a>

                            <?php else: ?>

                                <span class="text-muted">
                                    Not provided
                                </span>

                            <?php endif; ?>

                        </div>


                        <!-- WHATSAPP -->

                        <div class="col-md-6">

                            <label class="small text-muted d-block mb-1">
                                WhatsApp
                            </label>

                            <?php if (!empty($seller['whatsapp'])): ?>

                                <?php
                                $whatsapp_number = preg_replace(
                                    '/[^0-9]/',
                                    '',
                                    $seller['whatsapp']
                                );
                                ?>

                                <a
                                    href="https://wa.me/<?php echo $whatsapp_number; ?>"
                                    target="_blank"
                                    class="text-success text-decoration-none fw-semibold">

                                    <i class="fa-brands fa-whatsapp me-1"></i>

                                    <?php
                                    echo htmlspecialchars(
                                        $seller['whatsapp']
                                    );
                                    ?>

                                </a>

                            <?php else: ?>

                                <span class="text-muted">
                                    Not provided
                                </span>

                            <?php endif; ?>

                        </div>


                    </div>

                </div>

            </div>

        </div>


        <!-- =================================================
             RIGHT COLUMN
        ================================================== -->

        <div class="col-lg-4">


            <!-- ACCOUNT DETAILS -->

            <div class="dashboard-card mb-4">

                <div class="dashboard-card-header">

                    <h5 class="dashboard-card-title">

                        <i class="fa-solid fa-circle-info text-maroon-800 me-2"></i>

                        Account Details

                    </h5>

                </div>


                <div class="p-4">


                    <!-- SELLER ID -->

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Seller ID
                        </small>

                        <strong>
                            #SEL-<?php echo str_pad(
                                        $seller['id'],
                                        4,
                                        '0',
                                        STR_PAD_LEFT
                                    ); ?>
                        </strong>

                    </div>


                    <!-- USER ID -->

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            User ID
                        </small>

                        <strong>

                            <?php
                            echo $seller['user_id']
                                ? '#USR-' . str_pad(
                                    $seller['user_id'],
                                    4,
                                    '0',
                                    STR_PAD_LEFT
                                )
                                : 'Not linked';
                            ?>

                        </strong>

                    </div>


                    <!-- JOINED -->

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Joined Date
                        </small>

                        <strong>

                            <?php
                            echo !empty($seller['created_at'])
                                ? date(
                                    'd M Y, h:i A',
                                    strtotime(
                                        $seller['created_at']
                                    )
                                )
                                : 'Not available';
                            ?>

                        </strong>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 PRODUCT SUMMARY
            ================================================== -->

            <div class="dashboard-card mb-4">

                <div class="dashboard-card-header">

                    <h5 class="dashboard-card-title">

                        <i class="fa-solid fa-box-open text-terracotta me-2"></i>

                        Product Summary

                    </h5>

                </div>


                <div class="p-4 text-center">

                    <div
                        class="display-5 fw-bold text-maroon-900">

                        <?php
                        echo (int) $seller['product_count'];
                        ?>

                    </div>

                    <div class="text-muted">
                        Total Products
                    </div>


                    <a
                        href="products.php?seller_id=<?php echo $seller['id']; ?>"
                        class="btn btn-outline-primary btn-sm mt-3">

                        <i class="fa-solid fa-box me-1"></i>

                        View Products

                    </a>

                </div>

            </div>


            <!-- =================================================
                 QUICK ACTION
            ================================================== -->

            <div class="dashboard-card">

                <div class="p-4">

                    <h6 class="fw-bold text-maroon-900 mb-3">

                        <i class="fa-solid fa-bolt me-1"></i>

                        Quick Actions

                    </h6>


                    <div class="d-grid gap-2">


                        <a
                            href="seller-edit.php?id=<?php echo $seller['id']; ?>"
                            class="btn btn-primary">

                            <i class="fa-solid fa-pen me-1"></i>

                            Edit Seller

                        </a>


                        <?php if ($status === 'active'): ?>

                            <a
                                href="seller-suspend.php?id=<?php echo $seller['id']; ?>"
                                class="btn btn-outline-danger"
                                onclick="return confirm('Suspend this seller?');">

                                <i class="fa-solid fa-ban me-1"></i>

                                Suspend Seller

                            </a>

                        <?php else: ?>

                            <a
                                href="seller-suspend.php?id=<?php echo $seller['id']; ?>"
                                class="btn btn-outline-success"
                                onclick="return confirm('Activate this seller?');">

                                <i class="fa-solid fa-check me-1"></i>

                                Activate Seller

                            </a>

                        <?php endif; ?>


                    </div>

                </div>

            </div>


        </div>

    </div>

</div>


<?php require_once __DIR__ . '/includes/footer.php'; ?>