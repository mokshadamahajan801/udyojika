<?php

/**
 * Udyojika - User Login
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$error_message = '';
$info_message = '';
$redirect_url = '';

/*
|--------------------------------------------------------------------------
| Authentication Messages
|--------------------------------------------------------------------------
*/
if (isset($_GET['msg'])) {

    switch ($_GET['msg']) {

        case 'auth_required':
            $info_message = 'Please sign in to access your dashboard.';
            break;

        case 'logged_out':
            $info_message = 'You have been successfully signed out.';
            break;
    }
}

/*
|--------------------------------------------------------------------------
| Login Processing
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /*
    |--------------------------------------------------------------------------
    | Get Submitted Values
    |--------------------------------------------------------------------------
    */
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    /*
    |--------------------------------------------------------------------------
    | Validate Input
    |--------------------------------------------------------------------------
    */
    if ($email === '' || $password === '') {

        $error_message = 'Please enter your email address and password.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error_message = 'Please enter a valid email address.';
    } else {

        /*
        |--------------------------------------------------------------------------
        | Authenticate User
        |--------------------------------------------------------------------------
        */
        $remember_me = isset($_POST['remember_me']);

        $user = login_user($email, $password, $remember_me);
        if ($user) {

            /*
            |--------------------------------------------------------------------------
            | Regenerate Session ID
            |--------------------------------------------------------------------------
            */
            session_regenerate_id(true);

            /*
            |--------------------------------------------------------------------------
            | Store Authentication Session
            |--------------------------------------------------------------------------
            */
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user'] = $user;

            /*
            |--------------------------------------------------------------------------
            | Role-Based Dashboard Redirect
            |--------------------------------------------------------------------------
            */
            switch ($user['role']) {

                case 'admin':
                    $redirect_url = 'admin/index.php';
                    break;

                case 'seller':
                    $redirect_url = 'seller/index.php';
                    break;

                case 'customer':
                    $redirect_url = 'customer/index.php';
                    break;

                default:
                    unset(
                        $_SESSION['user'],
                        $_SESSION['user_id'],
                        $_SESSION['user_role']
                    );

                    $error_message =
                        'Your account role is invalid. Please contact the administrator.';
                    break;
            }
        } else {

            /*
            |--------------------------------------------------------------------------
            | Authentication Failed
            |--------------------------------------------------------------------------
            */
            $error_message =
                'Invalid email address or password. Please try again.';
        }
    }
}

$page_title = "Sign In - Udyojika";

require_once __DIR__ . '/includes/header.php';
?>

<?php if (!empty($redirect_url)): ?>

    <!-- Login Success Popup -->
    <div id="loginSuccessPopup" class="login-success-overlay">

        <div class="login-success-modal">

            <div class="login-success-icon">
                <i class="fa-solid fa-check"></i>
            </div>

            <h2>Success!</h2>

            <p>
                Welcome back to Udyojika!<br>
                You have successfully logged in.
            </p>

            <button
                type="button"
                class="login-success-btn"
                onclick="continueAfterLogin()">
                OK
            </button>

        </div>

    </div>

    <style>
        .login-success-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            padding: 20px;
        }

        .login-success-modal {
            width: 100%;
            max-width: 430px;
            background: #ffffff;
            border-radius: 12px;
            padding: 65px 35px 35px;
            text-align: center;
            position: relative;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.25);
            animation: loginPopup 0.25s ease-out;
        }

        .login-success-icon {
            position: absolute;
            top: -55px;
            left: 50%;
            transform: translateX(-50%);

            width: 110px;
            height: 110px;
            border-radius: 50%;

            background: #72c837;
            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 55px;
            border: 8px solid rgba(255, 255, 255, 0.95);
        }

        .login-success-modal h2 {
            margin: 20px 0 15px;
            font-size: 42px;
            font-weight: 400;
            color: #333;
        }

        .login-success-modal p {
            font-size: 18px;
            line-height: 1.5;
            color: #555;
            margin-bottom: 30px;
        }

        .login-success-btn {
            width: 100%;
            border: none;
            border-radius: 4px;
            background: #72c837;
            color: white;
            padding: 15px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .login-success-btn:hover {
            background: #61b52d;
        }

        @keyframes loginPopup {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    <script>
        function continueAfterLogin() {
            window.location.href = <?php echo json_encode($redirect_url); ?>;
        }
    </script>

<?php endif; ?>

<!-- Page Header -->
<div class="bg-cream-100 py-4 border-bottom">

    <div class="container text-center">

        <h2 class="font-serif fw-bold text-maroon-900 mb-1">
            Sign In to Udyojika
        </h2>

        <p class="text-muted small mb-0">
            Sign in to your Udyojika account
        </p>

    </div>

</div>


<!-- Login Section -->
<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-5 col-md-7">

            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">


                <!-- Logo / Welcome -->
                <div class="text-center mb-4">

                    <div
                        class="bg-maroon-800 text-white rounded-circle 
                        d-inline-flex align-items-center justify-content-center 
                        mb-3 shadow-sm"
                        style="width: 58px; height: 58px;">
                        <i class="fa-solid fa-spa text-warning fs-3"></i>
                    </div>

                    <h4 class="font-serif fw-bold text-maroon-900 mb-1">
                        Welcome Back
                    </h4>

                    <p class="text-muted small">
                        Enter your registered email and password
                    </p>

                </div>


                <!-- Info Message -->
                <?php if (!empty($info_message)): ?>

                    <div class="alert alert-warning small d-flex align-items-center gap-2 mb-3">

                        <i class="fa-solid fa-circle-info fs-5"></i>

                        <div>
                            <?php echo htmlspecialchars($info_message); ?>
                        </div>

                    </div>

                <?php endif; ?>


                <!-- Error Message -->
                <?php if (!empty($error_message)): ?>

                    <div class="alert alert-danger small d-flex align-items-center gap-2 mb-3">

                        <i class="fa-solid fa-triangle-exclamation fs-5"></i>

                        <div>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>

                    </div>

                <?php endif; ?>


                <!-- Login Form -->
                <form action="login.php" method="POST">

                    <!-- Email -->
                    <div class="mb-3">

                        <label class="form-label small fw-bold text-dark">
                            Email Address
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="fa-regular fa-envelope"></i>
                            </span>

                            <input
                                type="email"
                                name="email"
                                class="form-control border-start-0"
                                placeholder="name@example.com"
                                required>

                        </div>

                    </div>


                    <!-- Password -->
                    <div class="mb-3">

                        <div class="d-flex justify-content-between align-items-center">

                            <label class="form-label small fw-bold text-dark mb-0">
                                Password
                            </label>

                            <a
                                href="forgot-password.php"
                                class="small text-maroon-800 text-decoration-none">
                                Forgot Password?
                            </a>

                        </div>


                        <div class="input-group mt-1">

                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="fa-solid fa-lock"></i>
                            </span>

                            <input
                                type="password"
                                name="password"
                                class="form-control border-start-0"
                                placeholder="Enter your password"
                                required>

                        </div>

                    </div>


                    <!-- Remember Me -->
                    <div class="mb-3 form-check">

                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="rememberMe"
                            name="remember_me">

                        <label
                            class="form-check-label small text-muted"
                            for="rememberMe">
                            Keep me signed in
                        </label>

                    </div>


                    <!-- Sign In Button -->
                    <button
                        type="submit"
                        class="btn btn-maroon w-100 py-2 fw-bold shadow-sm">

                        Sign In

                        <i class="fa-solid fa-arrow-right-to-bracket ms-1"></i>

                    </button>

                </form>


                <!-- Registration Links -->
                <div class="mt-4 pt-3 border-top text-center small text-muted">

                    <div>

                        New to Udyojika?

                        <a
                            href="register.php"
                            class="text-maroon-800 fw-bold text-decoration-none">
                            Create Customer Account
                        </a>

                    </div>


                    <div class="mt-2">

                        Are you a home maker?

                        <a
                            href="become-seller.php"
                            class="text-terracotta fw-bold text-decoration-none">
                            Register as a Seller
                        </a>

                    </div>

                </div>


            </div>

        </div>

    </div>

</div>


<?php
require_once __DIR__ . '/includes/footer.php';
?>