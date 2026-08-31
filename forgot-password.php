<?php

/**
 * Udyojika - Forgot Password
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/mailer.php';

$error_message = '';
$info_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');

    if ($email === '') {

        $error_message = 'Please enter your email address.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error_message = 'Please enter a valid email address.';

    } else {

        /*
        |--------------------------------------------------------------------------
        | Check User
        |--------------------------------------------------------------------------
        */

        $stmt = $pdo->prepare("
            SELECT id, name, email
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {

            $error_message =
                'No account was found with this email address.';

        } else {

            /*
            |--------------------------------------------------------------------------
            | Generate New OTP
            |--------------------------------------------------------------------------
            */

            $otp = (string) random_int(100000, 999999);

            /*
            |--------------------------------------------------------------------------
            | OTP Valid For 10 Minutes
            |--------------------------------------------------------------------------
            */

            $expires_at = gmdate(
                'Y-m-d H:i:s',
                time() + (10 * 60)
            );

            /*
            |--------------------------------------------------------------------------
            | Delete Previous OTP
            |--------------------------------------------------------------------------
            */

            $stmt = $pdo->prepare("
                DELETE FROM password_resets
                WHERE email = ?
            ");

            $stmt->execute([$email]);

            /*
            |--------------------------------------------------------------------------
            | Store New OTP
            |--------------------------------------------------------------------------
            */

            $stmt = $pdo->prepare("
                INSERT INTO password_resets
                (email, otp, expires_at, verified)
                VALUES (?, ?, ?, 0)
            ");

            $stmt->execute([
                $email,
                $otp,
                $expires_at
            ]);

            /*
            |--------------------------------------------------------------------------
            | Store Reset Email
            |--------------------------------------------------------------------------
            */

$_SESSION['reset_email'] = $email;
$_SESSION['otp_last_sent'] = time();
            /*
            |--------------------------------------------------------------------------
            | Send OTP
            |--------------------------------------------------------------------------
            */

            if (send_otp_email($email, $user['name'], $otp)) {

                header('Location: verify-otp.php');
                exit;

            } else {

                // Remove OTP if email failed
                $stmt = $pdo->prepare("
                    DELETE FROM password_resets
                    WHERE email = ?
                ");

                $stmt->execute([$email]);

                $error_message =
                    'Unable to send OTP email. Please check your Gmail SMTP configuration.';
            }
        }
    }
}

$page_title = "Forgot Password - Udyojika";

require_once __DIR__ . '/includes/header.php';
?>


<!-- Page Header -->
<div class="bg-cream-100 py-4 border-bottom">

    <div class="container text-center">

        <h2 class="font-serif fw-bold text-maroon-900 mb-1">
            Forgot Password
        </h2>

        <p class="text-muted small mb-0">
            Reset your Udyojika account password
        </p>

    </div>

</div>


<!-- Forgot Password Section -->
<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-5 col-md-7">

            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">


                <!-- Icon -->
                <div class="text-center mb-4">

                    <div
                        class="bg-maroon-800 text-white rounded-circle
                        d-inline-flex align-items-center justify-content-center
                        mb-3 shadow-sm"
                        style="width:58px;height:58px;">

                        <i class="fa-solid fa-key text-warning fs-3"></i>

                    </div>

                    <h4 class="font-serif fw-bold text-maroon-900 mb-1">
                        Reset Your Password
                    </h4>

                    <p class="text-muted small">
                        Enter your registered email address and we'll send you
                        a verification code.
                    </p>

                </div>


                <!-- Error Message -->
                <?php if (!empty($error_message)): ?>

                    <div class="alert alert-danger small d-flex align-items-center gap-2 mb-3">

                        <i class="fa-solid fa-triangle-exclamation"></i>

                        <div>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>

                    </div>

                <?php endif; ?>


                <!-- Info Message -->
                <?php if (!empty($info_message)): ?>

                    <div class="alert alert-success small d-flex align-items-center gap-2 mb-3">

                        <i class="fa-solid fa-circle-check"></i>

                        <div>
                            <?php echo htmlspecialchars($info_message); ?>
                        </div>

                    </div>

                <?php endif; ?>


                <!-- Email Form -->
                <form action="forgot-password.php" method="POST">

                    <div class="mb-4">

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
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                required>

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-maroon w-100 py-2 fw-bold shadow-sm">

                        Send OTP

                        <i class="fa-solid fa-paper-plane ms-1"></i>

                    </button>

                </form>


                <!-- Back to Login -->
                <div class="mt-4 pt-3 border-top text-center">

                    <a
                        href="login.php"
                        class="small text-maroon-800 text-decoration-none">

                        <i class="fa-solid fa-arrow-left me-1"></i>
                        Back to Sign In

                    </a>

                </div>


            </div>

        </div>

    </div>

</div>


<?php
require_once __DIR__ . '/includes/footer.php';
?>