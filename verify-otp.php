<?php

/**
 * Udyojika - Verify Password Reset OTP
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/mailer.php';

$error_message = '';
$success_message = '';

/*
|--------------------------------------------------------------------------
| Check Reset Session
|--------------------------------------------------------------------------
*/

if (empty($_SESSION['reset_email'])) {

    header('Location: forgot-password.php');
    exit;
}

$email = $_SESSION['reset_email'];


/*
|--------------------------------------------------------------------------
| Resend OTP
|--------------------------------------------------------------------------
*/

if (isset($_GET['resend']) && $_GET['resend'] === '1') {

    /*
    |--------------------------------------------------------------------------
    | Check 60 Second Cooldown
    |--------------------------------------------------------------------------
    */

    $last_sent = $_SESSION['otp_last_sent'] ?? 0;

    if (time() - $last_sent < 60) {

        $remaining = 60 - (time() - $last_sent);

        $error_message =
            "Please wait {$remaining} seconds before requesting another OTP.";

    } else {

        /*
        |--------------------------------------------------------------------------
        | Get User
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

            unset($_SESSION['reset_email']);

            header('Location: forgot-password.php');
            exit;

        } else {

            /*
            |--------------------------------------------------------------------------
            | Generate New OTP
            |--------------------------------------------------------------------------
            */

            $otp = (string) random_int(100000, 999999);

            /*
            |--------------------------------------------------------------------------
            | Expiry = 10 Minutes
            |--------------------------------------------------------------------------
            */

            $expires_at = gmdate(
                'Y-m-d H:i:s',
                time() + (10 * 60)
            );

            /*
            |--------------------------------------------------------------------------
            | Delete Old OTP
            |--------------------------------------------------------------------------
            */

            $stmt = $pdo->prepare("
                DELETE FROM password_resets
                WHERE email = ?
            ");

            $stmt->execute([$email]);

            /*
            |--------------------------------------------------------------------------
            | Save New OTP
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
            | Send New OTP
            |--------------------------------------------------------------------------
            */

            if (send_otp_email($email, $user['name'], $otp)) {

                $_SESSION['otp_last_sent'] = time();

                $success_message =
                    'A new OTP has been sent to your email address.';

            } else {

                // Remove OTP if email failed
                $stmt = $pdo->prepare("
                    DELETE FROM password_resets
                    WHERE email = ?
                ");

                $stmt->execute([$email]);

                $error_message =
                    'Unable to send OTP. Please check your Gmail SMTP configuration.';
            }
        }
    }
}


/*
|--------------------------------------------------------------------------
| Verify OTP
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $otp = trim($_POST['otp'] ?? '');

    if ($otp === '') {

        $error_message = 'Please enter the OTP.';

    } elseif (!preg_match('/^[0-9]{6}$/', $otp)) {

        $error_message =
            'Please enter a valid 6-digit OTP.';

    } else {

        /*
        |--------------------------------------------------------------------------
        | Check OTP
        |--------------------------------------------------------------------------
        */

        $stmt = $pdo->prepare("
            SELECT *
            FROM password_resets
            WHERE email = ?
            AND otp = ?
            AND expires_at > UTC_TIMESTAMP()
            AND verified = 0
            ORDER BY id DESC
            LIMIT 1
        ");

        $stmt->execute([
            $email,
            $otp
        ]);

        $reset = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reset) {

            $error_message =
                'Invalid or expired OTP. Please request a new OTP.';

        } else {

            /*
            |--------------------------------------------------------------------------
            | Mark OTP Verified
            |--------------------------------------------------------------------------
            */

            $stmt = $pdo->prepare("
                UPDATE password_resets
                SET verified = 1
                WHERE id = ?
            ");

            $stmt->execute([
                $reset['id']
            ]);

            /*
            |--------------------------------------------------------------------------
            | Allow Password Reset
            |--------------------------------------------------------------------------
            */

            $_SESSION['reset_verified'] = true;

            header('Location: reset-password.php');
            exit;
        }
    }
}


$page_title = "Verify OTP - Udyojika";

require_once __DIR__ . '/includes/header.php';
?>


<!-- Page Header -->
<div class="bg-cream-100 py-4 border-bottom">

    <div class="container text-center">

        <h2 class="font-serif fw-bold text-maroon-900 mb-1">
            Verify OTP
        </h2>

        <p class="text-muted small mb-0">
            Enter the verification code sent to your email
        </p>

    </div>

</div>


<!-- OTP Section -->
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

                        <i class="fa-solid fa-shield-halved text-warning fs-3"></i>

                    </div>

                    <h4 class="font-serif fw-bold text-maroon-900">
                        Enter OTP
                    </h4>

                    <p class="text-muted small">
                        We sent a 6-digit verification code to your email.
                    </p>

                </div>


                <!-- Success Message -->
                <?php if (!empty($success_message)): ?>

                    <div class="alert alert-success small d-flex align-items-center gap-2">

                        <i class="fa-solid fa-circle-check"></i>

                        <div>
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>

                    </div>

                <?php endif; ?>


                <!-- Error Message -->
                <?php if (!empty($error_message)): ?>

                    <div class="alert alert-danger small d-flex align-items-center gap-2">

                        <i class="fa-solid fa-triangle-exclamation"></i>

                        <div>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>

                    </div>

                <?php endif; ?>


                <!-- OTP Form -->
                <form action="verify-otp.php" method="POST">

                    <div class="mb-4">

                        <label class="form-label small fw-bold">
                            Verification Code
                        </label>

                        <input
                            type="text"
                            name="otp"
                            class="form-control text-center fs-4"
                            placeholder="000000"
                            maxlength="6"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            required>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-maroon w-100 py-2 fw-bold shadow-sm">

                        Verify OTP

                        <i class="fa-solid fa-check ms-1"></i>

                    </button>

                </form>

                <!-- Resend OTP -->
<div class="mt-4 pt-3 border-top text-center">

    <p class="small text-muted mb-2">
        Didn't receive the OTP?
    </p>

    <a
        id="resendOtp"
        href="verify-otp.php?resend=1"
        class="small fw-bold text-decoration-none"
        style="pointer-events: none; opacity: 0.5;">

        <i class="fa-solid fa-clock me-1"></i>
        Resend OTP in <span id="resendTimer">60</span>s

    </a>

    <p class="small text-muted mt-2 mb-0">
        A new OTP will be valid for 10 minutes.
    </p>

</div>


                <!-- Back -->
                <div class="mt-3 text-center">

                    <a
                        href="login.php"
                        class="small text-muted text-decoration-none">

                        <i class="fa-solid fa-arrow-left me-1"></i>
                        Back to Sign In

                    </a>

                </div>


            </div>

        </div>

    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const resendButton = document.getElementById('resendOtp');
    const timerElement = document.getElementById('resendTimer');

    if (!resendButton || !timerElement) {
        return;
    }

    let seconds = 60;

    const countdown = setInterval(function () {

        seconds--;

        timerElement.textContent = seconds;

        if (seconds <= 0) {

            clearInterval(countdown);

            resendButton.style.pointerEvents = 'auto';
            resendButton.style.opacity = '1';

            resendButton.innerHTML =
                '<i class="fa-solid fa-rotate-right me-1"></i> Resend OTP';
        }

    }, 1000);

});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>