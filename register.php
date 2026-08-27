<?php
/**
 * Udyojika - Customer Registration
 */

$page_title = "Create an Account - Udyojika";

require_once __DIR__ . '/includes/db.php';

$reg_message = '';
$error_message = '';

$name = '';
$email = '';
$phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $terms = isset($_POST['terms']);

    /* =========================
       VALIDATION
    ========================= */

    if ($name === '' || $email === '' || $phone === '' || 
        $password === '' || $confirm_password === '') {

        $error_message = "Please fill in all required fields.";

    } elseif (strlen($name) < 2) {

        $error_message = "Please enter a valid full name.";

    } elseif (strlen($name) > 150) {

        $error_message = "Name must not exceed 150 characters.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error_message = "Please enter a valid email address.";

    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {

        $error_message = "Please enter a valid 10-digit mobile number.";

    } elseif (strlen($password) < 8) {

        $error_message = "Password must be at least 8 characters long.";

    } elseif ($password !== $confirm_password) {

        $error_message = "Passwords do not match.";

    } elseif (!$terms) {

        $error_message = "Please agree to the Terms of Service & Privacy Policy.";

    } else {

        try {

            /* =========================
               CHECK EXISTING EMAIL
            ========================= */

            $check_email = $pdo->prepare(
                "SELECT id FROM users WHERE email = ? LIMIT 1"
            );

            $check_email->execute([$email]);

            if ($check_email->fetch()) {

                $error_message = "An account with this email address already exists.";

            } else {

                /* =========================
                   HASH PASSWORD
                ========================= */

                $hashed_password = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                /* =========================
                   CREATE CUSTOMER ACCOUNT
                ========================= */

                $stmt = $pdo->prepare(
                    "INSERT INTO users
                    (name, email, password, role, phone, status)
                    VALUES (?, ?, ?, 'customer', ?, 'active')"
                );

                $stmt->execute([
                    $name,
                    $email,
                    $hashed_password,
                    $phone
                ]);

                $reg_message = "Account created successfully! You can now sign in.";

                // Clear form after successful registration
                $name = '';
                $email = '';
                $phone = '';
            }

        } catch (PDOException $e) {

            $error_message = "Unable to create your account right now. Please try again.";
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5">

            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">

                <!-- =========================
                     REGISTRATION HEADER
                ========================== -->

                <div class="text-center mb-4">

                    <div class="bg-maroon-800 text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 54px; height: 54px;">

                        <i class="fa-solid fa-user-plus text-warning fs-4"></i>

                    </div>

                    <h3 class="font-serif fw-bold text-maroon-900 mb-1">
                        Create an Account
                    </h3>

                    <p class="text-muted small">
                        Join our community supporting local women makers
                    </p>

                </div>


                <!-- =========================
                     SUCCESS MESSAGE
                ========================== -->

                <?php if (!empty($reg_message)): ?>

                    <div class="alert alert-success small mb-3">

                        <i class="fa-solid fa-circle-check me-1"></i>

                        <?php echo htmlspecialchars($reg_message); ?>

                    </div>

                <?php endif; ?>


                <!-- =========================
                     ERROR MESSAGE
                ========================== -->

                <?php if (!empty($error_message)): ?>

                    <div class="alert alert-danger small mb-3">

                        <i class="fa-solid fa-circle-exclamation me-1"></i>

                        <?php echo htmlspecialchars($error_message); ?>

                    </div>

                <?php endif; ?>


                <!-- =========================
                     REGISTRATION FORM
                ========================== -->

                <form action="register.php" method="POST">

                    <!-- FULL NAME -->

                    <div class="mb-3">

                        <label class="form-label small fw-bold">
                            Full Name *
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Enter your full name"
                            value="<?php echo htmlspecialchars($name); ?>"
                            autocomplete="name"
                            maxlength="150"
                            required
                        >

                    </div>


                    <!-- EMAIL -->

                    <div class="mb-3">

                        <label class="form-label small fw-bold">
                            Email Address *
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="Enter your email address"
                            value="<?php echo htmlspecialchars($email); ?>"
                            autocomplete="email"
                            maxlength="150"
                            required
                        >

                    </div>


                    <!-- MOBILE NUMBER -->

                    <div class="mb-3">

                        <label class="form-label small fw-bold">
                            Mobile Number (for order SMS) *
                        </label>

                        <input
                            type="tel"
                            name="phone"
                            class="form-control"
                            placeholder="Enter 10-digit mobile number"
                            value="<?php echo htmlspecialchars($phone); ?>"
                            autocomplete="tel"
                            inputmode="numeric"
                            maxlength="10"
                            pattern="[0-9]{10}"
                            required
                        >

                    </div>


                    <!-- PASSWORD -->

                    <div class="mb-3">

                        <label class="form-label small fw-bold">
                            Create Password *
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Create a password"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >

                        <div class="form-text small">
                            Password must be at least 8 characters.
                        </div>

                    </div>


                    <!-- CONFIRM PASSWORD -->

                    <div class="mb-3">

                        <label class="form-label small fw-bold">
                            Confirm Password *
                        </label>

                        <input
                            type="password"
                            name="confirm_password"
                            class="form-control"
                            placeholder="Re-enter your password"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >

                    </div>


                    <!-- TERMS -->

                    <div class="mb-3 form-check">

                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="termsCheck"
                            name="terms"
                            value="1"
                            required
                        >

                        <label
                            class="form-check-label small text-muted"
                            for="termsCheck"
                        >
                            I agree to the Terms of Service & Privacy Policy
                        </label>

                    </div>


                    <!-- REGISTER BUTTON -->

                    <button
                        type="submit"
                        class="btn btn-maroon w-100 py-2 fw-bold"
                    >
                        Register Account

                        <i class="fa-solid fa-arrow-right ms-1"></i>

                    </button>

                </form>


                <!-- =========================
                     LOGIN LINK
                ========================== -->

                <div class="mt-4 pt-3 border-top text-center small text-muted">

                    Already have an account?

                    <a
                        href="login.php"
                        class="text-maroon-800 fw-bold"
                    >
                        Sign In
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>