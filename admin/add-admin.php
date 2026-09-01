<?php
$page_title = "Add Admin - Admin Portal";
$page_header = "Add New Admin";
$page_subheader = "Create a new administrator account";

require_once __DIR__ . '/includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if ($name === '' || $email === '' || $password === '') {
        $error = "Name, email and password are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {

        // Check whether email already exists
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = "An account with this email already exists.";
        } else {

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin
            $stmt = $pdo->prepare("
                INSERT INTO users
                (name, email, password, role, phone, status, created_at, updated_at)
                VALUES
                (?, ?, ?, 'admin', ?, 'active', NOW(), NOW())
            ");

            $stmt->execute([
                $name,
                $email,
                $hashed_password,
                $phone !== '' ? $phone : null
            ]);

            $success = "New admin account created successfully!";
        }
    }
}
?>

<div class="dashboard-card">

    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title">
            <i class="fa-solid fa-user-plus text-maroon-800"></i>
            Create New Admin
        </h5>
    </div>

    <div class="p-4">

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check me-2"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input
                    type="text"
                    name="phone"
                    class="form-control"
                    value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    required
                >
            </div>

            <button type="submit" class="btn btn-maroon">
                <i class="fa-solid fa-user-plus me-1"></i>
                Create Admin
            </button>

            <a href="users.php" class="btn btn-light border ms-2">
                Cancel
            </a>

        </form>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>