<?php

require_once __DIR__ . '/includes/auth.php';
require_admin();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';


/*
|--------------------------------------------------------------------------
| Get User ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: users.php?error=invalid_user');
    exit;
}

$user_id = (int) $_GET['id'];


/*
|--------------------------------------------------------------------------
| Fetch User
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT id, name, email, phone, role, status
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: users.php?error=user_not_found');
    exit;
}


/*
|--------------------------------------------------------------------------
| Update User
|--------------------------------------------------------------------------
*/

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name   = trim($_POST['name'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $phone  = trim($_POST['phone'] ?? '');
    $status = $_POST['status'] ?? '';


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    $allowed_status = ['active', 'inactive'];

    if (!in_array($status, $allowed_status, true)) {
        $errors[] = 'Invalid user status.';
    }


    /*
    |--------------------------------------------------------------------------
    | Role Protection
    |--------------------------------------------------------------------------
    |
    | Admin's role cannot be changed.
    | Customer/Seller role remains unchanged from database.
    |
    */

    $role = $user['role'];


    /*
    |--------------------------------------------------------------------------
    | Duplicate Email Check
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        $stmt = $pdo->prepare("
            SELECT id
            FROM users
            WHERE email = ?
            AND id != ?
            LIMIT 1
        ");

        $stmt->execute([
            $email,
            $user_id
        ]);

        if ($stmt->fetch()) {
            $errors[] = 'This email address is already registered.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Update Database
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        $stmt = $pdo->prepare("
            UPDATE users
            SET
                name = ?,
                email = ?,
                phone = ?,
                status = ?,
                updated_at = NOW()
            WHERE id = ?
        ");

        $stmt->execute([
            $name,
            $email,
            $phone,
            $status,
            $user_id
        ]);

        header('Location: users.php?success=updated');
        exit;
    }
}


/*
|--------------------------------------------------------------------------
| Page UI
|--------------------------------------------------------------------------
*/

$page_title = "Edit User - Udyojika";
$page_header = "Edit User";
$page_subheader = "Update user account information";

require_once __DIR__ . '/includes/header.php';

?>

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div>
                        <h5 class="dashboard-card-title mb-1">
                            <i class="fa-solid fa-user-pen text-maroon-800"></i>
                            Edit User
                        </h5>

                        <small class="text-muted">
                            Update account information
                        </small>
                    </div>

                    <a href="users.php" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i>
                        Back
                    </a>

                </div>


                <div class="p-4">

                    <?php if (!empty($errors)): ?>

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                <?php foreach ($errors as $error): ?>

                                    <li>
                                        <?php echo htmlspecialchars($error); ?>
                                    </li>

                                <?php endforeach; ?>

                            </ul>

                        </div>

                    <?php endif; ?>


                    <form method="POST">


                        <!-- User ID -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                User ID
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="#USR-<?php echo str_pad($user['id'], 4, '0', STR_PAD_LEFT); ?>"
                                readonly
                            >

                        </div>


                        <!-- Full Name -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="<?php echo htmlspecialchars($user['name']); ?>"
                                required
                            >

                        </div>


                        <!-- Email -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?php echo htmlspecialchars($user['email']); ?>"
                                required
                            >

                        </div>


                        <!-- Phone -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Phone Number
                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                            >

                        </div>


                        <!-- Role -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Role
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="<?php echo ucfirst(htmlspecialchars($user['role'])); ?>"
                                readonly
                            >

                            <div class="form-text">
                                User role cannot be changed from this page.
                            </div>

                        </div>


                        <!-- Status -->

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select"
                                required
                            >

                                <option
                                    value="active"
                                    <?php echo ($user['status'] === 'active') ? 'selected' : ''; ?>
                                >
                                    Active
                                </option>

                                <option
                                    value="inactive"
                                    <?php echo ($user['status'] === 'inactive') ? 'selected' : ''; ?>
                                >
                                    Inactive
                                </option>

                            </select>

                        </div>


                        <!-- Buttons -->

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-floppy-disk me-1"></i>
                                Save Changes
                            </button>

                            <a
                                href="users.php"
                                class="btn btn-light border"
                            >
                                Cancel
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>