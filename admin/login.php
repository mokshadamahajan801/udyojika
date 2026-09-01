
<?php

/**
 * Udyojika - Admin Login
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$error_message = '';

/*
|--------------------------------------------------------------------------
| If already logged in as admin
|--------------------------------------------------------------------------
*/

if (
    isset($_SESSION['user_id']) &&
    isset($_SESSION['user_role']) &&
    $_SESSION['user_role'] === 'admin'
) {
    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Admin Login Processing
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    /*
    |--------------------------------------------------------------------------
    | Validation
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

        $user = login_user($email, $password, false);

        if ($user) {

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            | Only ADMIN is allowed here
            |--------------------------------------------------------------------------
            */

            if ($user['role'] !== 'admin') {

                $error_message =
                    'Access denied. This login is only for administrators.';

            } else {

                /*
                |--------------------------------------------------------------------------
                | Regenerate Session
                |--------------------------------------------------------------------------
                */

                session_regenerate_id(true);

                /*
                |--------------------------------------------------------------------------
                | Store Admin Session
                |--------------------------------------------------------------------------
                */

                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['user_role'] = 'admin';
                $_SESSION['user'] = $user;

                /*
                |--------------------------------------------------------------------------
                | Redirect to Admin Dashboard
                |--------------------------------------------------------------------------
                */

                header("Location: index.php");
                exit;
            }

        } else {

            $error_message =
                'Invalid email address or password.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Login - Udyojika</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet">

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;

            font-family: "DM Sans", sans-serif;

            background:
                radial-gradient(
                    circle at top left,
                    rgba(143, 41, 56, 0.08),
                    transparent 35%
                ),
                #fff8e8;

            color: #333;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 25px;
        }

        /*
        |--------------------------------------------------------------------------
        | Main Container
        |--------------------------------------------------------------------------
        */

        .admin-login-wrapper {
            width: 100%;
            max-width: 1050px;
        }

        .admin-login-card {
            background: #ffffff;

            border-radius: 24px;

            overflow: hidden;

            box-shadow:
                0 25px 70px rgba(74, 44, 22, 0.15);

            border: 1px solid rgba(74, 44, 22, 0.08);
        }

        /*
        |--------------------------------------------------------------------------
        | Left Panel
        |--------------------------------------------------------------------------
        */

        .admin-brand-panel {

            min-height: 650px;

            padding: 55px 45px;

            background:
                linear-gradient(
                    145deg,
                    #5d1724,
                    #8f2938
                );

            color: #ffffff;

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            text-align: center;

            position: relative;

            overflow: hidden;
        }

        .admin-brand-panel::before {

            content: "";

            position: absolute;

            width: 320px;
            height: 320px;

            border-radius: 50%;

            background: rgba(255,255,255,0.06);

            top: -130px;
            left: -130px;
        }

        .admin-brand-panel::after {

            content: "";

            position: absolute;

            width: 400px;
            height: 400px;

            border-radius: 50%;

            background: rgba(255,255,255,0.05);

            bottom: -220px;
            right: -160px;
        }

        /*
        |--------------------------------------------------------------------------
        | Admin Icon
        |--------------------------------------------------------------------------
        */

        .admin-icon {

            width: 105px;
            height: 105px;

            border-radius: 50%;

            background: rgba(255,255,255,0.12);

            border: 2px solid rgba(255,255,255,0.25);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 42px;

            margin-bottom: 25px;

            position: relative;

            z-index: 2;
        }

        .admin-brand-title {

            font-family: "Playfair Display", serif;

            font-size: 46px;

            font-weight: 700;

            margin-bottom: 10px;

            position: relative;

            z-index: 2;
        }

        .admin-brand-subtitle {

            font-size: 15px;

            line-height: 1.7;

            max-width: 360px;

            color: rgba(255,255,255,0.85);

            position: relative;

            z-index: 2;
        }

        .admin-badge {

            margin-top: 25px;

            padding: 8px 18px;

            border-radius: 50px;

            background: rgba(255,255,255,0.12);

            border: 1px solid rgba(255,255,255,0.20);

            font-size: 13px;

            font-weight: 600;

            position: relative;

            z-index: 2;
        }

        /*
        |--------------------------------------------------------------------------
        | Right Login Panel
        |--------------------------------------------------------------------------
        */

        .admin-form-panel {

            min-height: 650px;

            padding: 55px;

            display: flex;

            align-items: center;
        }

        .admin-form-container {

            width: 100%;

            max-width: 430px;

            margin: auto;
        }

        .login-small-icon {

            width: 62px;
            height: 62px;

            background: #6d1f2b;

            color: #ffffff;

            border-radius: 16px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 24px;

            margin-bottom: 20px;
        }

        .login-title {

            font-family: "Playfair Display", serif;

            color: #5d1724;

            font-size: 34px;

            font-weight: 700;

            margin-bottom: 8px;
        }

        .login-description {

            color: #777;

            font-size: 14px;

            margin-bottom: 30px;
        }

        /*
        |--------------------------------------------------------------------------
        | Form
        |--------------------------------------------------------------------------
        */

        .form-label {

            font-size: 14px;

            font-weight: 600;

            color: #333;
        }

        .input-group {

            border-radius: 10px;

            overflow: hidden;
        }

        .input-group-text {

            background: #f8f8f8;

            border: 1px solid #ddd;

            border-right: 0;

            color: #777;

            min-width: 48px;

            justify-content: center;
        }

        .form-control {

            border: 1px solid #ddd;

            padding: 13px 14px;

            font-size: 14px;
        }

        .form-control:focus {

            border-color: #8f2938;

            box-shadow:
                0 0 0 0.2rem rgba(143, 41, 56, 0.12);
        }

        /*
        |--------------------------------------------------------------------------
        | Login Button
        |--------------------------------------------------------------------------
        */

        .btn-admin-login {

            width: 100%;

            border: none;

            border-radius: 10px;

            padding: 13px;

            background: #6d1f2b;

            color: #ffffff;

            font-size: 15px;

            font-weight: 700;

            transition: 0.2s ease;
        }

        .btn-admin-login:hover {

            background: #8f2938;

            color: #ffffff;

            transform: translateY(-1px);

            box-shadow:
                0 8px 20px rgba(109, 31, 43, 0.20);
        }

        /*
        |--------------------------------------------------------------------------
        | Error
        |--------------------------------------------------------------------------
        */

        .alert {

            border-radius: 10px;

            font-size: 13px;
        }

        /*
        |--------------------------------------------------------------------------
        | Footer Text
        |--------------------------------------------------------------------------
        */

        .admin-footer {

            margin-top: 28px;

            padding-top: 20px;

            border-top: 1px solid #eee;

            text-align: center;

            color: #888;

            font-size: 12px;
        }

        .back-link {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            margin-top: 15px;

            color: #6d1f2b;

            text-decoration: none;

            font-size: 13px;

            font-weight: 600;
        }

        .back-link:hover {

            text-decoration: underline;
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile
        |--------------------------------------------------------------------------
        */

        @media (max-width: 991px) {

            .admin-brand-panel {

                min-height: auto;

                padding: 45px 30px;
            }

            .admin-form-panel {

                min-height: auto;

                padding: 45px 30px;
            }

            .admin-brand-title {

                font-size: 40px;
            }
        }

        @media (max-width: 575px) {

            body {

                padding: 12px;
            }

            .admin-login-card {

                border-radius: 18px;
            }

            .admin-brand-panel {

                padding: 35px 20px;
            }

            .admin-form-panel {

                padding: 35px 20px;
            }

            .admin-brand-title {

                font-size: 34px;
            }

            .login-title {

                font-size: 29px;
            }

            .admin-brand-subtitle {

                font-size: 14px;
            }
        }

    </style>

</head>

<body>

<div class="admin-login-wrapper">

    <div class="admin-login-card">

        <div class="row g-0">

            <!-- =========================================================
                 LEFT SIDE
            ========================================================== -->

            <div class="col-lg-5">

                <div class="admin-brand-panel">

                    <div class="admin-icon">

                        <i class="fa-solid fa-shield-halved"></i>

                    </div>

                    <h1 class="admin-brand-title">
                        Udyojika
                    </h1>

                    <p class="admin-brand-subtitle">
                        Welcome to the Udyojika Administration Panel.
                        Manage users, businesses, products, orders
                        and platform activities from one place.
                    </p>

                    <div class="admin-badge">

                        <i class="fa-solid fa-lock me-2"></i>

                        ADMINISTRATOR ACCESS

                    </div>

                </div>

            </div>


            <!-- =========================================================
                 RIGHT SIDE
            ========================================================== -->

            <div class="col-lg-7">

                <div class="admin-form-panel">

                    <div class="admin-form-container">

                        <div class="login-small-icon">

                            <i class="fa-solid fa-user-shield"></i>

                        </div>

                        <h2 class="login-title">
                            Admin Login
                        </h2>

                        <p class="login-description">
                            Sign in to access the Udyojika administration panel.
                        </p>


                        <!-- ERROR MESSAGE -->

                        <?php if (!empty($error_message)): ?>

                            <div
                                class="alert alert-danger d-flex align-items-center gap-2 mb-4">

                                <i class="fa-solid fa-circle-exclamation"></i>

                                <span>
                                    <?php
                                    echo htmlspecialchars($error_message);
                                    ?>
                                </span>

                            </div>

                        <?php endif; ?>


                        <!-- LOGIN FORM -->

                        <form
                            action="login.php"
                            method="POST"
                            autocomplete="off">


                            <!-- EMAIL -->

                            <div class="mb-3">

                                <label
                                    for="email"
                                    class="form-label">

                                    Admin Email Address

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="fa-regular fa-envelope"></i>

                                    </span>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-control"
                                        placeholder="admin@example.com"
                                        autocomplete="username"
                                        required>

                                </div>

                            </div>


                            <!-- PASSWORD -->

                            <div class="mb-4">

                                <label
                                    for="password"
                                    class="form-label">

                                    Password

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="fa-solid fa-lock"></i>

                                    </span>

                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="form-control"
                                        placeholder="Enter admin password"
                                        autocomplete="current-password"
                                        required>

                                    <button
                                        type="button"
                                        class="btn btn-light border"
                                        id="togglePassword"
                                        title="Show / Hide Password">

                                        <i
                                            class="fa-solid fa-eye"
                                            id="passwordIcon">
                                        </i>

                                    </button>

                                </div>

                            </div>


                            <!-- LOGIN BUTTON -->

                            <button
                                type="submit"
                                class="btn-admin-login">

                                <i class="fa-solid fa-right-to-bracket me-2"></i>

                                Sign In as Admin

                            </button>

                        </form>


                        <!-- FOOTER -->

                        <div class="admin-footer">

                            <div>
                                <i class="fa-solid fa-lock me-1"></i>

                                Secure Administrator Login
                            </div>

                            <a
                                href="../login.php"
                                class="back-link">

                                <i class="fa-solid fa-arrow-left"></i>

                                Back to User Login

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<script>

/*
|--------------------------------------------------------------------------
| Show / Hide Password
|--------------------------------------------------------------------------
*/

const togglePassword =
    document.getElementById("togglePassword");

const password =
    document.getElementById("password");

const passwordIcon =
    document.getElementById("passwordIcon");


togglePassword.addEventListener("click", function () {

    if (password.type === "password") {

        password.type = "text";

        passwordIcon.classList.remove("fa-eye");

        passwordIcon.classList.add("fa-eye-slash");

    } else {

        password.type = "password";

        passwordIcon.classList.remove("fa-eye-slash");

        passwordIcon.classList.add("fa-eye");

    }

});

</script>

</body>

</html>
