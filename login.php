<?php
$page_title = "Sign In - Udyojika";
require_once __DIR__ . '/includes/header.php';

$auth_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email'] ?? '');
    $auth_message = "Signed in successfully as {$email}! (Session demo initialized)";
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                
                <div class="text-center mb-4">
                    <div class="bg-maroon-800 text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px;">
                        <i class="fa-solid fa-spa text-warning fs-4"></i>
                    </div>
                    <h3 class="font-serif fw-bold text-maroon-900 mb-1">Welcome Back</h3>
                    <p class="text-muted small">Sign in to your Udyojika customer or seller account</p>
                </div>

                <?php if (!empty($auth_message)): ?>
                    <div class="alert alert-success small mb-3">
                        <i class="fa-solid fa-circle-check me-1"></i> <?php echo $auth_message; ?>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="name@example.com" required value="demo@udyojika.in">
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label small fw-bold">Password</label>
                            <a href="#" class="small text-maroon-800 text-decoration-none">Forgot?</a>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required value="password123">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe" checked>
                        <label class="form-check-label small text-muted" for="rememberMe">Keep me signed in</label>
                    </div>
                    <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold">
                        Sign In <i class="fa-solid fa-arrow-right-to-bracket ms-1"></i>
                    </button>
                </form>

                <div class="mt-4 pt-3 border-top text-center small text-muted">
                    Don't have an account yet? <a href="register.php" class="text-maroon-800 fw-bold">Create Account</a>
                    <div class="mt-2">
                        Are you a homemaker? <a href="become-seller.php" class="text-terracotta fw-bold">Register as a Seller</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
