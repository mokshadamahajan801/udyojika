<?php
$page_title = "Create an Account - Udyojika";
require_once __DIR__ . '/includes/header.php';

$reg_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $reg_message = "Account created successfully for {$name}! You can now start placing orders.";
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                
                <div class="text-center mb-4">
                    <div class="bg-maroon-800 text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px;">
                        <i class="fa-solid fa-user-plus text-warning fs-4"></i>
                    </div>
                    <h3 class="font-serif fw-bold text-maroon-900 mb-1">Create an Account</h3>
                    <p class="text-muted small">Join our community supporting local women makers</p>
                </div>

                <?php if (!empty($reg_message)): ?>
                    <div class="alert alert-success small mb-3">
                        <i class="fa-solid fa-circle-check me-1"></i> <?php echo $reg_message; ?>
                    </div>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Shalini Deshmukh" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address *</label>
                        <input type="email" name="email" class="form-control" placeholder="shalini@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Mobile Number (for order SMS) *</label>
                        <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Create Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="termsCheck" required checked>
                        <label class="form-check-label small text-muted" for="termsCheck">I agree to the Terms of Service & Privacy Policy</label>
                    </div>
                    <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold">
                        Register Account <i class="fa-solid fa-arrow-right ms-1"></i>
                    </button>
                </form>

                <div class="mt-4 pt-3 border-top text-center small text-muted">
                    Already have an account? <a href="login.php" class="text-maroon-800 fw-bold">Sign In</a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
