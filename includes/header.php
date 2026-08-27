<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Database Connection
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/db.php';

/*
|--------------------------------------------------------------------------
| Common Functions
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/functions.php';

/*
|--------------------------------------------------------------------------
| Current Page
|--------------------------------------------------------------------------
*/
$current_page = basename($_SERVER['PHP_SELF'], '.php');

/*
|--------------------------------------------------------------------------
| Navigation Categories
|--------------------------------------------------------------------------
*/
$categories_nav = get_categories($pdo);

/*
|--------------------------------------------------------------------------
| Logged-in User
|--------------------------------------------------------------------------
*/
$current_u = get_logged_in_user();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php
        echo isset($page_title)
            ? htmlspecialchars($page_title) . ' | Udyojika'
            : 'Udyojika - Empowering Women Home Entrepreneurs';
        ?>
    </title>

    <meta name="description"
        content="Udyojika is a marketplace connecting home chefs, artisans, and women makers with conscious consumers. 100% homemade, pure and handcrafted with love.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Rozha+One&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-cream-50 d-flex flex-column min-vh-100">


    <!-- =========================================================
     TOP ANNOUNCEMENT BAR
========================================================= -->

    <div class="top-notice-bar py-2 px-3">

        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div class="d-flex align-items-center gap-2">

                <span class="badge bg-warning text-dark fw-bold px-2 py-1">
                    <i class="fa-solid fa-gift me-1"></i>
                    SPECIAL
                </span>

                <span>
                    Get <strong>10% OFF</strong> your first order with code:
                    <strong class="text-warning">UDYOJIKA10</strong>
                </span>

            </div>

            <div class="d-none d-md-flex align-items-center gap-4 text-white-50 small">

                <span>
                    <i class="fa-solid fa-heart text-danger me-1"></i>
                    Supporting 5,000+ Women Makers
                </span>

                <span>
                    <i class="fa-solid fa-truck-fast text-warning me-1"></i>
                    Pan-India Express Delivery
                </span>

                <span>
                    <i class="fa-brands fa-whatsapp text-success me-1"></i>
                    Helpline: +91 98220 12345
                </span>

            </div>

        </div>

    </div>


    <!-- =========================================================
     MAIN HEADER
========================================================= -->

    <header class="sticky-header">

        <div class="container py-3">

            <div class="row align-items-center g-3">


                <!-- =================================================
                 LOGO
            ================================================== -->

                <div class="col-6 col-lg-3 d-flex align-items-center gap-3">

                    <!-- Mobile Menu Button -->
                    <button
                        class="btn btn-light d-lg-none p-2 border"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#mobileMenuDrawer"
                        aria-controls="mobileMenuDrawer">

                        <i class="fa-solid fa-bars fs-5 text-maroon-800"></i>

                    </button>


                    <!-- Logo -->
                    <a href="index.php"
                        class="text-decoration-none d-flex align-items-center gap-2">

                        <div
                            class="bg-maroon-800 text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width: 44px; height: 44px;">

                            <i class="fa-solid fa-spa text-warning fs-5"></i>

                        </div>

                        <div>

                            <span class="brand-font fs-3 fw-bold text-maroon-800 d-block lh-1">
                                Udyojika
                            </span>

                            <small
                                class="text-muted fw-semibold"
                                style="font-size: 0.72rem; letter-spacing: 1px;">

                                HOMEMADE WITH LOVE

                            </small>

                        </div>

                    </a>

                </div>


                <!-- =================================================
                 SEARCH BAR
            ================================================== -->

                <div class="col-12 col-lg-5 order-3 order-lg-2">

                    <form
                        action="products.php"
                        method="GET"
                        class="search-container d-flex align-items-center"
                        onsubmit="return validateSearch();">

                        <select
                            name="category"
                            id="searchCategory"
                            class="d-none d-sm-block text-secondary border-end">
                            <option value="">All Categories</option>

                            <?php foreach ($categories_nav as $cat): ?>

                                <option value="<?php echo htmlspecialchars($cat['slug']); ?>">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>

                            <?php endforeach; ?>

                        </select>


                        <input
                            type="text"
                            name="q"
                            id="searchInput"
                            class="form-control"
                            placeholder="Search authentic pickles, snacks, sarees, clay jewellery..."
                            aria-label="Search">


                        <button
                            type="submit"
                            class="btn btn-maroon rounded-circle m-1 d-flex align-items-center justify-content-center"
                            style="width: 38px; height: 38px; padding: 0;"
                            title="Search">

                            <i class="fa-solid fa-magnifying-glass fs-6"></i>

                        </button>

                    </form>

                </div>


                <!-- =================================================
                 USER ACTIONS
            ================================================== -->

                <div class="col-6 col-lg-4 order-2 order-lg-3 d-flex align-items-center justify-content-end gap-2 gap-sm-3">


                    <!-- Become Seller -->
                    <?php if (!$current_u || $current_u['role'] === 'customer'): ?>

                        <a
                            href="become-seller.php"
                            class="btn btn-outline-maroon btn-sm d-none d-xl-inline-flex align-items-center gap-1">

                            <i class="fa-solid fa-store text-terracotta"></i>

                            <span>Sell With Us</span>

                        </a>

                    <?php endif; ?>


                    <!-- Wishlist -->

                    <a
                        href="wishlist.php"
                        class="btn btn-light rounded-circle position-relative border p-2 text-dark"
                        title="Wishlist">

                        <i class="fa-regular fa-heart fs-5"></i>

                        <span
                            class="wishlist-count-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="display:none; font-size: 0.65rem;">

                            0

                        </span>

                    </a>


                    <!-- Cart -->

                    <a
                        href="cart.php"
                        class="btn btn-maroon rounded-pill position-relative px-3 py-2 d-flex align-items-center gap-2"
                        title="Shopping Cart">

                        <i class="fa-solid fa-bag-shopping fs-5 text-warning"></i>

                        <span class="d-none d-sm-inline fw-semibold">
                            Cart
                        </span>

                        <span
                            class="cart-count-badge badge bg-warning text-dark rounded-pill fw-bold"
                            style="display:none; font-size: 0.75rem;">

                            0

                        </span>

                    </a>


                    <!-- =================================================
                     USER ACCOUNT DROPDOWN
                ================================================== -->

                    <div class="dropdown">

                        <button
                            class="btn btn-light rounded-circle border p-2"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <i class="fa-regular fa-user fs-5 text-dark"></i>

                        </button>


                        <ul
                            class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2"
                            style="min-width: 230px;">


                            <?php if ($current_u): ?>


                                <!-- Logged In User -->

                                <li>

                                    <div class="px-3 py-2 border-bottom bg-light rounded-top">

                                        <strong class="d-block text-maroon-900 small">

                                            <?php
                                            echo htmlspecialchars($current_u['name']);
                                            ?>

                                        </strong>

                                        <span
                                            class="badge bg-warning text-dark text-capitalize"
                                            style="font-size: 0.7rem;">

                                            <?php
                                            echo htmlspecialchars($current_u['role']);
                                            ?>

                                        </span>

                                    </div>

                                </li>


                                <!-- ADMIN -->

                                <?php if ($current_u['role'] === 'admin'): ?>

                                    <li>

                                        <a
                                            class="dropdown-item py-2 fw-bold text-danger"
                                            href="admin/index.php">

                                            <i class="fa-solid fa-chart-pie me-2"></i>

                                            Admin Dashboard

                                        </a>

                                    </li>


                                    <!-- SELLER -->

                                <?php elseif ($current_u['role'] === 'seller'): ?>

                                    <li>

                                        <a
                                            class="dropdown-item py-2 fw-bold text-terracotta"
                                            href="seller/index.php">

                                            <i class="fa-solid fa-store me-2"></i>

                                            Maker Dashboard

                                        </a>

                                    </li>


                                    <!-- CUSTOMER -->

                                <?php else: ?>

                                    <li>

                                        <a
                                            class="dropdown-item py-2 fw-bold text-maroon-800"
                                            href="customer/index.php">

                                            <i class="fa-solid fa-user me-2"></i>

                                            Customer Dashboard

                                        </a>

                                    </li>

                                <?php endif; ?>


                                <!-- My Orders -->

                                <?php if ($current_u['role'] === 'customer'): ?>

                                    <li>

                                        <a
                                            class="dropdown-item py-2"
                                            href="customer/orders.php">

                                            <i class="fa-solid fa-box me-2 text-muted"></i>

                                            My Orders

                                        </a>

                                    </li>

                                <?php endif; ?>


                                <li>
                                    <hr class="dropdown-divider">
                                </li>


                                <!-- Logout -->

                                <li>

                                    <a
                                        class="dropdown-item py-2 text-danger"
                                        href="logout.php">

                                        <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>

                                        Sign Out

                                    </a>

                                </li>


                            <?php else: ?>


                                <!-- =================================================
                                 LOGGED OUT USER
                            ================================================== -->

                                <li>

                                    <h6 class="dropdown-header text-maroon-800 fw-bold">
                                        My Account
                                    </h6>

                                </li>


                                <li>

                                    <a
                                        class="dropdown-item py-2"
                                        href="login.php">

                                        <i class="fa-solid fa-arrow-right-to-bracket me-2 text-muted"></i>

                                        Sign In / Login

                                    </a>

                                </li>


                                <li>

                                    <a
                                        class="dropdown-item py-2"
                                        href="register.php">

                                        <i class="fa-solid fa-user-plus me-2 text-muted"></i>

                                        Create Account

                                    </a>

                                </li>


                                <li>
                                    <hr class="dropdown-divider">
                                </li>


                                <!-- Become Seller -->

                                <li>

                                    <a
                                        class="dropdown-item py-2 fw-semibold text-terracotta"
                                        href="become-seller.php">

                                        <i class="fa-solid fa-mortar-pestle me-2"></i>

                                        Join as Women Maker

                                    </a>

                                </li>


                            <?php endif; ?>

                        </ul>

                    </div>

                </div>

            </div>

        </div>


        <!-- =========================================================
         DESKTOP NAVIGATION
    ========================================================== -->

        <nav class="navbar navbar-expand-lg border-top py-1 d-none d-lg-block bg-white">

            <div class="container">

                <ul class="navbar-nav d-flex flex-row gap-4 align-items-center py-1">


                    <!-- HOME -->

                    <li class="nav-item">

                        <a
                            class="nav-link <?php echo $current_page === 'index' ? 'active' : ''; ?>"
                            href="index.php">

                            <i class="fa-solid fa-house me-1 text-muted"></i>

                            Home

                        </a>

                    </li>


                    <!-- PRODUCTS -->

                    <li class="nav-item">

                        <a
                            class="nav-link <?php echo $current_page === 'products' ? 'active' : ''; ?>"
                            href="products.php">

                            <i class="fa-solid fa-boxes-stacked me-1 text-muted"></i>

                            All Products

                        </a>

                    </li>


                    <!-- CATEGORIES -->

                    <li class="nav-item">

                        <a
                            class="nav-link <?php echo $current_page === 'categories' ? 'active' : ''; ?>"
                            href="categories.php">

                            <i class="fa-solid fa-shapes me-1 text-muted"></i>

                            Categories

                        </a>

                    </li>


                    <!-- WOMEN ENTREPRENEURS -->

                    <li class="nav-item">

                        <a
                            class="nav-link <?php echo $current_page === 'businesses' ? 'active' : ''; ?>"
                            href="businesses.php">

                            <i class="fa-solid fa-users me-1 text-muted"></i>

                            Women Entrepreneurs

                        </a>

                    </li>


                    <!-- BECOME SELLER -->

                    <?php if (!$current_u || $current_u['role'] === 'customer'): ?>

                        <li class="nav-item">

                            <a
                                class="nav-link <?php echo $current_page === 'become-seller' ? 'active' : ''; ?>"
                                href="become-seller.php">

                                <i class="fa-solid fa-hand-holding-heart me-1 text-muted"></i>

                                Become a Seller

                            </a>

                        </li>

                    <?php endif; ?>


                    <!-- ABOUT -->

                    <li class="nav-item">

                        <a
                            class="nav-link <?php echo $current_page === 'about' ? 'active' : ''; ?>"
                            href="about.php">

                            <i class="fa-solid fa-leaf me-1 text-muted"></i>

                            Our Story & Impact

                        </a>

                    </li>


                    <!-- CONTACT -->

                    <li class="nav-item">

                        <a
                            class="nav-link <?php echo $current_page === 'contact' ? 'active' : ''; ?>"
                            href="contact.php">

                            <i class="fa-solid fa-headset me-1 text-muted"></i>

                            Support & Contact

                        </a>

                    </li>

                </ul>

            </div>

        </nav>

    </header>


    <!-- =========================================================
     MOBILE MENU
========================================================= -->

    <div
        class="offcanvas offcanvas-start"
        tabindex="-1"
        id="mobileMenuDrawer"
        aria-labelledby="mobileMenuDrawerLabel">


        <!-- Mobile Header -->

        <div class="offcanvas-header bg-cream-100 border-bottom">

            <div class="d-flex align-items-center gap-2">

                <div
                    class="bg-maroon-800 text-white rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 36px; height: 36px;">

                    <i class="fa-solid fa-spa text-warning"></i>

                </div>

                <h5
                    class="offcanvas-title brand-font text-maroon-800 mb-0"
                    id="mobileMenuDrawerLabel">

                    Udyojika

                </h5>

            </div>


            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close">
            </button>

        </div>


        <div class="offcanvas-body p-0">


            <!-- Start Selling Button -->

            <?php if (!$current_u || $current_u['role'] === 'customer'): ?>

                <div class="p-3 bg-light border-bottom">

                    <a
                        href="become-seller.php"
                        class="btn btn-terracotta w-100 d-flex align-items-center justify-content-center gap-2">

                        <i class="fa-solid fa-store"></i>

                        Start Selling From Home

                    </a>

                </div>

            <?php endif; ?>


            <!-- Mobile Navigation -->

            <div class="list-group list-group-flush">


                <a
                    href="index.php"
                    class="list-group-item list-group-item-action py-3">

                    <i class="fa-solid fa-house me-3 text-maroon-800"></i>

                    Home

                </a>


                <a
                    href="products.php"
                    class="list-group-item list-group-item-action py-3">

                    <i class="fa-solid fa-boxes-stacked me-3 text-maroon-800"></i>

                    Browse All Products

                </a>


                <a
                    href="categories.php"
                    class="list-group-item list-group-item-action py-3">

                    <i class="fa-solid fa-shapes me-3 text-maroon-800"></i>

                    Categories

                </a>


                <a
                    href="businesses.php"
                    class="list-group-item list-group-item-action py-3">

                    <i class="fa-solid fa-users me-3 text-maroon-800"></i>

                    Women Home Entrepreneurs

                </a>


                <a
                    href="wishlist.php"
                    class="list-group-item list-group-item-action py-3">

                    <i class="fa-regular fa-heart me-3 text-danger"></i>

                    My Wishlist

                </a>


                <a
                    href="cart.php"
                    class="list-group-item list-group-item-action py-3">

                    <i class="fa-solid fa-bag-shopping me-3 text-warning"></i>

                    Shopping Cart

                </a>


                <a
                    href="about.php"
                    class="list-group-item list-group-item-action py-3">

                    <i class="fa-solid fa-leaf me-3 text-success"></i>

                    Our Mission & Impact

                </a>


                <a
                    href="contact.php"
                    class="list-group-item list-group-item-action py-3">

                    <i class="fa-solid fa-headset me-3 text-primary"></i>

                    Help & Contact

                </a>


                <!-- =================================================
                 MOBILE ACCOUNT SECTION
            ================================================== -->

                <?php if ($current_u): ?>


                    <!-- Logged In User -->

                    <div class="list-group-item bg-light border-top">

                        <div class="fw-bold text-maroon-800">

                            <?php
                            echo htmlspecialchars($current_u['name']);
                            ?>

                        </div>

                        <small class="text-muted text-capitalize">

                            <?php
                            echo htmlspecialchars($current_u['role']);
                            ?>

                        </small>

                    </div>


                    <!-- Admin -->

                    <?php if ($current_u['role'] === 'admin'): ?>

                        <a
                            href="admin/index.php"
                            class="list-group-item list-group-item-action py-3">

                            <i class="fa-solid fa-chart-pie me-3 text-danger"></i>

                            Admin Dashboard

                        </a>


                        <!-- Seller -->

                    <?php elseif ($current_u['role'] === 'seller'): ?>

                        <a
                            href="seller/index.php"
                            class="list-group-item list-group-item-action py-3">

                            <i class="fa-solid fa-store me-3 text-warning"></i>

                            Maker Dashboard

                        </a>


                        <!-- Customer -->

                    <?php else: ?>

                        <a
                            href="customer/index.php"
                            class="list-group-item list-group-item-action py-3">

                            <i class="fa-solid fa-user me-3 text-maroon-800"></i>

                            Customer Dashboard

                        </a>


                        <a
                            href="customer/orders.php"
                            class="list-group-item list-group-item-action py-3">

                            <i class="fa-solid fa-box me-3 text-muted"></i>

                            My Orders

                        </a>

                    <?php endif; ?>


                    <!-- Logout -->

                    <a
                        href="logout.php"
                        class="list-group-item list-group-item-action py-3 text-danger">

                        <i class="fa-solid fa-arrow-right-from-bracket me-3"></i>

                        Sign Out

                    </a>


                <?php else: ?>


                    <!-- Logged Out -->

                    <a
                        href="login.php"
                        class="list-group-item list-group-item-action py-3">

                        <i class="fa-solid fa-arrow-right-to-bracket me-3 text-muted"></i>

                        Customer Sign In

                    </a>


                    <a
                        href="register.php"
                        class="list-group-item list-group-item-action py-3">

                        <i class="fa-solid fa-user-plus me-3 text-muted"></i>

                        Register New Account

                    </a>

                <?php endif; ?>


            </div>

        </div>

    </div>


    <!-- =========================================================
     MAIN PAGE CONTENT
========================================================= -->

    <main class="flex-grow-1">