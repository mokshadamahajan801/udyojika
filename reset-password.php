<?php

/**
 * Udyojika - Reset Password
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$error_message = '';
$success_message = '';

if (
    empty($_SESSION['reset_email']) ||
    empty($_SESSION['reset_verified'])
) {
    header('Location: forgot-password.php');
    exit;
}

$email = $_SESSION['reset_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password === '' || $confirm_password === '') {

        $error_message = 'Please fill in both password fields.';

    } elseif (strlen($password) < 6) {

        $error_message =
            'Password must contain at least 6 characters.';

    } elseif ($password !== $confirm_password) {

        $error_message =
            'Passwords do not match.';

    } else {

        // Hash new password
        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // Update password
        $stmt = $pdo->prepare("
            UPDATE users
            SET password = ?
            WHERE email = ?
        ");

        $stmt->execute([
            $hashed_password,
            $email
        ]);

        // Delete used reset record
        $stmt = $pdo->prepare("
            DELETE FROM password_resets
            WHERE email = ?
        ");

        $stmt->execute([
            $email
        ]);

        // Clear reset session
        unset(
            $_SESSION['reset_email'],
            $_SESSION['reset_verified']
        );

        header('Location: login.php?msg=password_reset');
        exit;
    }
}

$page_title = "Reset Password - Udyojika";

require_once __DIR__ . '/includes/header.php';
?>

<div class="bg-cream-100 py-4 border-bottom">

    <div class="container text-center">

        <h2 class="font-serif fw-bold text-maroon-900 mb-1">
            Create New Password
        </h2>

        <p class="text-muted small mb-0">
            Set a new password for your Udyojika account
        </p>

    </div>

</div>


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-5 col-md-7">

            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">

                <div class="text-center mb-4">

                    <div
                        class="bg-maroon-800 text-white rounded-circle
                        d-inline-flex align-items-center justify-content-center
                        mb-3 shadow-sm"
                        style="width: 58px; height: 58px;">

                        <i class="fa-solid fa-lock text-warning fs-3"></i>

                    </div>

                    <h4 class="font-serif fw-bold text-maroon-900">
                        New Password
                    </h4>

                    <p class="text-muted small">
                        Enter your new password below.
                    </p>

                </div>


                <?php if (!empty($error_message)): ?>

                    <div class="alert alert-danger small">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>

                <?php endif; ?>


                <form action="reset-password.php" method="POST">

                    <!-- New Password -->
                    <div class="mb-3">

                        <label class="form-label small fw-bold">
                            New Password
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa-solid fa-lock"></i>
                            </span>

                            <input
                                type="password"
                                name="password"
                                class="form-control border-start-0"
                                placeholder="Enter new password"
                                required>

                        </div>

                    </div>


                    <!-- Confirm Password -->
                    <div class="mb-4">

                        <label class="form-label small fw-bold">
                            Confirm New Password
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa-solid fa-lock"></i>
                            </span>

                            <input
                                type="password"
                                name="confirm_password"
                                class="form-control border-start-0"
                                placeholder="Confirm new password"
                                required>

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-maroon w-100 py-2 fw-bold">

                        Reset Password

                        <i class="fa-solid fa-check ms-1"></i>

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<?php
require_once __DIR__ . '/includes/footer.php';
?>