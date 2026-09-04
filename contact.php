<?php
$page_title = "Contact Us & Maker Support - Udyojika";

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$feedback_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['topic'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $subject === '' || $message === '') {

        $error_msg = "Please fill in all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error_msg = "Please enter a valid email address.";

    } else {

        try {

            $stmt = $pdo->prepare("
                INSERT INTO contact_messages
                (name, email, phone, subject, message, status)
                VALUES (?, ?, ?, ?, ?, 'new')
            ");

            $stmt->execute([
                $name,
                $email,
                $phone ?: null,
                $subject,
                $message
            ]);

            $feedback_msg = "Thank you for reaching out! Your message has been received and our team will respond within 4 business hours.";

        } catch (PDOException $e) {

            $error_msg = "Sorry, your message could not be sent. Please try again.";
        }
    }
}
?>

<div class="bg-cream-100 py-4 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active text-maroon-800 fw-bold" aria-current="page">Contact & Support</li>
            </ol>
        </nav>
        <h2 class="font-serif fw-bold text-maroon-900 mb-0">We are Here to Help You</h2>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        
        <!-- Contact Form Column -->
        <div class="col-lg-7">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                <h4 class="font-serif fw-bold text-maroon-900 mb-2">Send Us a Message</h4>
                <p class="text-muted small mb-4">Whether you are a customer inquiring about an order or a home maker wanting to sell with us, reach out below:</p>

                <?php if (!empty($error_msg)): ?>
    <div class="alert alert-danger mb-4">
        <i class="fa-solid fa-circle-exclamation me-2"></i>
        <?php echo htmlspecialchars($error_msg); ?>
    </div>
<?php endif; ?>
                <?php if (!empty($feedback_msg)): ?>
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                        <i class="fa-solid fa-circle-check fs-4"></i>
                        <div><?php echo $feedback_msg; ?></div>
                    </div>
                <?php endif; ?>

                <form action="contact.php" method="POST" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Your Name *</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Priya Patil">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address *</label>
                            <input type="email" name="email" class="form-control" required placeholder="priya@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Phone / WhatsApp Number</label>
                            <input type="tel" name="phone" class="form-control" placeholder="+91 98220 00000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Topic *</label>
                            <select name="topic" class="form-select" required>
                                <option value="Buyer Support">Order / Delivery Status</option>
                                <option value="Seller Onboarding">Want to Sell My Homemade Products</option>
                                <option value="Bulk Orders">Corporate Gifting / Bulk Wedding Orders</option>
                                <option value="Feedback">General Feedback / Compliment</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Your Message *</label>
                            <textarea name="message" class="form-control" rows="4" required placeholder="How can we assist you today?"></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-maroon px-4 py-2 fw-bold">
                                Send Message <i class="fa-solid fa-paper-plane ms-1"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contact Info & Helplines -->
        <div class="col-lg-5">
            <div class="d-flex flex-column gap-4">
                
                <div class="p-4 bg-cream-100 rounded-4 border">
                    <h5 class="font-serif fw-bold text-maroon-900 mb-3">Direct Maker Helpline</h5>
                    <div class="d-flex flex-column gap-3 small">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success text-white rounded-circle p-2 fs-5" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <div>
                                <span class="text-muted d-block">WhatsApp Support (9 AM - 8 PM)</span>
                                <strong class="fs-6 text-dark">+91 98220 12345</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-maroon-800 text-white rounded-circle p-2 fs-5" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <span class="text-muted d-block">Email Inquiries</span>
                                <strong class="fs-6 text-dark">support@udyojika.in</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Office Locations -->
                <div class="p-4 bg-white rounded-4 border shadow-sm">
                    <h5 class="font-serif fw-bold text-maroon-900 mb-3">Maker Hub Locations</h5>
                    <div class="small text-secondary mb-3">
                        <strong>Pune Hub:</strong><br>
                        Plot 14, Lane 5, Prabhat Road, Erandwane, Pune, Maharashtra 411004
                    </div>
                    <div class="small text-secondary">
                        <strong>Mumbai Regional Cell:</strong><br>
                        204, Heritage Plaza, Fort, Mumbai, Maharashtra 400001
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
