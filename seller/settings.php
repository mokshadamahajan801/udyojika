<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$current_user = require_role(['seller']);

$page_title = "Settings - Udyोजिका";
$page_header = "Settings";
$page_subheader = "Manage your account preferences";


// ===============================
// SAVE SETTINGS
// ===============================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = (int) ($current_user['id'] ?? 0);

    $notification_enabled =
        isset($_POST['notification_enabled']) ? 1 : 0;

    $email_notifications =
        isset($_POST['email_notifications']) ? 1 : 0;

    $language = trim($_POST['language'] ?? 'English');

    $allowed_languages = [
        'English',
        'मराठी',
        'Hindi'
    ];

    if (!in_array($language, $allowed_languages, true)) {
        $language = 'English';
    }

    try {

        $stmt = $pdo->prepare("
            UPDATE users
            SET
                notification_enabled = ?,
                email_notifications = ?,
                language = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $notification_enabled,
            $email_notifications,
            $language,
            $user_id
        ]);

        // Refresh user data
        $stmt = $pdo->prepare("
            SELECT *
            FROM users
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$user_id]);

        $updated_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($updated_user) {
            $current_user = $updated_user;
            $_SESSION['user'] = $updated_user;
        }

        $success_message = "Settings saved successfully!";

    } catch (PDOException $e) {

        $error_message = "Unable to save settings. Please try again.";

    }
}


require_once __DIR__ . '/includes/header.php';

?>

<div class="container-fluid py-4">

    <!-- Settings Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Settings</h2>

        <p class="text-muted mb-0">
            Manage your account preferences
        </p>
    </div>


    <!-- Success Message -->
    <?php if (!empty($success_message)): ?>

        <div class="alert alert-success border-0 shadow-sm rounded-3">
            <i class="fa-solid fa-circle-check me-2"></i>
            <?php echo htmlspecialchars($success_message); ?>
        </div>

    <?php endif; ?>


    <!-- Error Message -->
    <?php if (!empty($error_message)): ?>

        <div class="alert alert-danger border-0 shadow-sm rounded-3">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            <?php echo htmlspecialchars($error_message); ?>
        </div>

    <?php endif; ?>


    <!-- General Settings -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 p-4">

            <h5 class="fw-bold mb-1">
                <i
                    class="fa-solid fa-sliders me-2"
                    style="color:#7b1731;"
                ></i>

                General Settings
            </h5>

            <p class="text-muted small mb-0">
                Manage basic settings of your seller account
            </p>

        </div>


        <form method="POST">

            <div class="card-body p-4">


                <!-- Notifications -->
                <div
                    class="d-flex align-items-center justify-content-between py-3 border-bottom"
                >

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="
                                width:42px;
                                height:42px;
                                background:#f8eef1;
                                color:#7b1731;
                            "
                        >
                            <i class="fa-solid fa-bell"></i>
                        </div>


                        <div>

                            <strong class="d-block">
                                Notifications
                            </strong>

                            <small class="text-muted">
                                Receive notifications about your seller account
                            </small>

                        </div>

                    </div>


                    <div class="form-check form-switch">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="notification_enabled"
                            id="notifications"
                            <?php
                            echo !empty(
                                $current_user['notification_enabled']
                            ) ? 'checked' : '';
                            ?>
                        >

                    </div>

                </div>


                <!-- Language -->
                <div
                    class="d-flex align-items-center justify-content-between py-3 border-bottom"
                >

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="
                                width:42px;
                                height:42px;
                                background:#f8eef1;
                                color:#7b1731;
                            "
                        >
                            <i class="fa-solid fa-language"></i>
                        </div>


                        <div>

                            <strong class="d-block">
                                Language
                            </strong>

                            <small class="text-muted">
                                Select your preferred language
                            </small>

                        </div>

                    </div>


                    <select
                        name="language"
                        class="form-select form-select-sm"
                        style="width:130px;"
                    >

                        <option
                            value="English"
                            <?php
                            echo (
                                ($current_user['language'] ?? 'English')
                                === 'English'
                            ) ? 'selected' : '';
                            ?>
                        >
                            English
                        </option>


                        <option
                            value="मराठी"
                            <?php
                            echo (
                                ($current_user['language'] ?? 'English')
                                === 'मराठी'
                            ) ? 'selected' : '';
                            ?>
                        >
                            मराठी
                        </option>


                        <option
                            value="Hindi"
                            <?php
                            echo (
                                ($current_user['language'] ?? 'English')
                                === 'Hindi'
                            ) ? 'selected' : '';
                            ?>
                        >
                            Hindi
                        </option>

                    </select>

                </div>


                <!-- Email Notifications -->
                <div
                    class="d-flex align-items-center justify-content-between py-3"
                >

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="
                                width:42px;
                                height:42px;
                                background:#f8eef1;
                                color:#7b1731;
                            "
                        >
                            <i class="fa-solid fa-envelope"></i>
                        </div>


                        <div>

                            <strong class="d-block">
                                Email Notifications
                            </strong>

                            <small class="text-muted">
                                Receive important updates through email
                            </small>

                        </div>

                    </div>


                    <div class="form-check form-switch">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="email_notifications"
                            id="emailNotifications"
                            <?php
                            echo !empty(
                                $current_user['email_notifications']
                            ) ? 'checked' : '';
                            ?>
                        >

                    </div>

                </div>

            </div>


            <!-- Save Button -->
            <div class="card-footer bg-white border-0 p-4 text-end">

                <button
                    type="submit"
                    class="btn px-4 py-2 rounded-3 text-white"
                    style="background:#7b1731;"
                >

                    <i class="fa-solid fa-check me-2"></i>

                    Save Settings

                </button>

            </div>

        </form>

    </div>


    <!-- Account Information -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 p-4">

            <h5 class="fw-bold mb-1">

                <i
                    class="fa-solid fa-circle-info me-2"
                    style="color:#7b1731;"
                ></i>

                Account Information

            </h5>

            <p class="text-muted small mb-0">
                Information about your seller account
            </p>

        </div>


        <div class="card-body p-4">

            <div class="row g-4">


                <!-- Account Status -->
                <div class="col-md-6">

                    <div class="p-3 rounded-3 bg-light">

                        <small class="text-muted d-block mb-1">
                            Account Status
                        </small>

                        <span class="badge bg-success px-3 py-2">

                            <?php
                            echo htmlspecialchars(
                                ucfirst(
                                    $current_user['status'] ?? 'Active'
                                )
                            );
                            ?>

                        </span>

                    </div>

                </div>


                <!-- Account Role -->
                <div class="col-md-6">

                    <div class="p-3 rounded-3 bg-light">

                        <small class="text-muted d-block mb-1">
                            Account Role
                        </small>

                        <strong>

                            <?php
                            echo htmlspecialchars(
                                ucfirst(
                                    $current_user['role'] ?? 'Seller'
                                )
                            );
                            ?>

                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>