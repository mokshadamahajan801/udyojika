<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Sign In";



/*
|--------------------------------------------------------------------------
| If Customer Is Already Logged In
|--------------------------------------------------------------------------
|
| IMPORTANT:
| We only check customer_id here.
| Admin session has NO effect on this page.
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['customer_id'])) {

    header("Location: index.php");
    exit;
}



$error = '';



/*
|--------------------------------------------------------------------------
| Login Form Submitted
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';



    /*
    |--------------------------------------------------------------------------
    | Validate Input
    |--------------------------------------------------------------------------
    */

    if ($email === '' || $password === '') {

        $error = "Please enter email and password.";

    } else {


        /*
        |--------------------------------------------------------------------------
        | Find User
        |--------------------------------------------------------------------------
        */

        $stmt = $pdo->prepare("
            SELECT *
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);



        /*
        |--------------------------------------------------------------------------
        | Verify Login
        |--------------------------------------------------------------------------
        */

        if ($user && password_verify($password, $user['password'])) {


            /*
            |--------------------------------------------------------------------------
            | Check Account Status
            |--------------------------------------------------------------------------
            */

            if ($user['status'] !== 'active') {

                $error = "Your account is not active.";

            }

            /*
            |--------------------------------------------------------------------------
            | ONLY CUSTOMER LOGIN
            |--------------------------------------------------------------------------
            */

            elseif ($user['role'] !== 'customer') {

                $error = "This login is only for customers.";

            }

            else {


                /*
                |--------------------------------------------------------------------------
                | Regenerate Session ID
                |--------------------------------------------------------------------------
                */

                session_regenerate_id(true);



                /*
                |--------------------------------------------------------------------------
                | Create CUSTOMER Session
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                | We DO NOT use:
                | $_SESSION['user_id']
                | $_SESSION['role']
                |
                | Customer has its own session keys.
                |--------------------------------------------------------------------------
                */

                $_SESSION['customer_id'] = (int) $user['id'];
                $_SESSION['customer_name'] = $user['name'] ?? '';
                $_SESSION['customer_email'] = $user['email'] ?? '';
                $_SESSION['customer_user'] = $user;



                /*
                |--------------------------------------------------------------------------
                | Customer → Website Home Page
                |--------------------------------------------------------------------------
                */

                header("Location: index.php");
                exit;
            }

        } else {

            $error = "Invalid email or password.";
        }
    }
}



require_once __DIR__ . '/includes/auth-header.php';

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Login - Udyojika</title>


    <!-- Bootstrap 5.3.3 -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>


<body>


<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-body p-4">


                    <!-- Title -->

                    <h2 class="text-center mb-4">
                        Welcome Back
                    </h2>


                    <!-- Error Message -->

                    <?php if ($error): ?>

                        <div class="alert alert-danger">

                            <?= htmlspecialchars($error) ?>

                        </div>

                    <?php endif; ?>


                    <!-- Login Form -->

                    <form method="POST">


                        <!-- Email -->

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                required>

                        </div>


                        <!-- Password -->

                        <div class="mb-3">

                            <label class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required>

                        </div>


                        <!-- Login Button -->

                        <button
                            type="submit"
                            class="btn btn-danger w-100">

                            Sign In

                        </button>


                    </form>


                    <!-- Register -->

                    <div class="text-center mt-3">

                        Don't have an account?

                        <a href="register.php">
                            Create Account
                        </a>

                    </div>


                </div>

            </div>

        </div>

    </div>

</div>


</body>

</html>
