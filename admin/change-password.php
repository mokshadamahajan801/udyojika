<?php
$page_title = "Change Password";

require_once __DIR__ . '/includes/header.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($current_password === '' || $new_password === '' || $confirm_password === '') {

        $error_msg = "Please fill in all fields.";

    } elseif (!password_verify($current_password, $current_user['password'])) {

        $error_msg = "Current password is incorrect.";

    } elseif (strlen($new_password) < 6) {

        $error_msg = "New password must be at least 6 characters.";

    } elseif ($new_password !== $confirm_password) {

        $error_msg = "New passwords do not match.";

    } else {

        try {

            $hashed_password = password_hash(
                $new_password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare("
                UPDATE users
                SET password = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $hashed_password,
                $current_user['id']
            ]);

            $success_msg = "Password changed successfully!";

        } catch (PDOException $e) {

            $error_msg = "Unable to change password. Please try again.";

        }
    }
}
?>

<div class="container-fluid py-4">

    <div class="container" style="max-width: 650px;">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="mb-1">Change Password</h2>
                <p class="text-muted mb-0">
                    Update your admin account password
                </p>
            </div>

            <a href="profile.php" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Back to Profile
            </a>

        </div>


        <?php if ($success_msg): ?>

            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check me-2"></i>
                <?php echo htmlspecialchars($success_msg); ?>
            </div>

        <?php endif; ?>


        <?php if ($error_msg): ?>

            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <?php echo htmlspecialchars($error_msg); ?>
            </div>

        <?php endif; ?>


        <div class="card shadow-sm border-0">

            <div class="card-body p-4">

                <div class="text-center mb-4">

                    <i
                        class="fa-solid fa-lock"
                        style="font-size: 45px;"
                    ></i>

                    <h4 class="mt-3">
                        Update Password
                    </h4>

                    <p class="text-muted">
                        Enter your current password and choose a new one.
                    </p>

                </div>


                <form method="POST">

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Current Password
                        </label>

                        <input
                            type="password"
                            name="current_password"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            New Password
                        </label>

                        <input
                            type="password"
                            name="new_password"
                            class="form-control"
                            minlength="6"
                            required
                        >

                        <small class="text-muted">
                            Minimum 6 characters
                        </small>

                    </div>


                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Confirm New Password
                        </label>

                        <input
                            type="password"
                            name="confirm_password"
                            class="form-control"
                            minlength="6"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="btn btn-success w-100"
                    >
                        <i class="fa-solid fa-key me-2"></i>
                        Change Password
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>