<?php

require_once __DIR__ . '/includes/db.php';

require_once __DIR__ . '/includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$page_title = "Sign In";

require_once __DIR__ . '/includes/auth-header.php';


/*
|--------------------------------------------------------------------------
| If User Is Already Logged In
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['user_id'])) {

    if ($_SESSION['role'] === 'customer') {
        header("Location: customer/index.php");
        exit;
    }

    if ($_SESSION['role'] === 'seller') {
        header("Location: seller/index.php");
        exit;
    }

    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/index.php");
        exit;
    }
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

            } else {


                /*
                |--------------------------------------------------------------------------
                | Create Session
                |--------------------------------------------------------------------------
                */

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];


                /*
                |--------------------------------------------------------------------------
                | Redirect According To Role
                |--------------------------------------------------------------------------
                */

                if ($user['role'] === 'customer') {

                    header("Location: customer/index.php");
                    exit;

                } elseif ($user['role'] === 'seller') {

                    header("Location: seller/index.php");
                    exit;

                } elseif ($user['role'] === 'admin') {

                    header("Location: admin/index.php");
                    exit;

                } else {

                    $error = "Invalid user role.";
                }
            }

        } else {

            $error = "Invalid email or password.";
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

    <title>Login - Udyojika</title>

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

                    <h2 class="text-center mb-4">
                        Welcome Back
                    </h2>


                    <?php if ($error): ?>

                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>

                    <?php endif; ?>


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
                                required
                            >

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
                                required
                            >

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