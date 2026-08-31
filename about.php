<?php
$page_title = "Our Story, Mission & Impact - Udyojika";
require_once __DIR__ . '/includes/header.php';
?>

<!-- About Hero -->
<div class="bg-cream-100 py-3 border-bottom">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- Text -->
            <div class="col-lg-6">

                <span class="text-terracotta fw-bold text-uppercase small tracking-wide">
                    Our Mission & Purpose
                </span>

                <h1 class="display-5 font-serif fw-bold text-maroon-900 mb-3">
                    Empowering Every Indian Home Maker to be an Entrepreneur
                </h1>

                <p class="lead text-secondary mb-4">
                    Udyojika was founded with a single mission: to provide Indian women
                    a dignified, effortless, and financially rewarding platform to sell
                    their authentic homemade products to consumers nationwide.
                </p>

                <div class="d-flex gap-3">
                    <a href="products.php" class="btn btn-maroon px-4 py-2">
                        Explore Marketplace
                    </a>

                    <a href="become-seller.php" class="btn btn-outline-maroon px-4 py-2">
                        Join as Maker
                    </a>
                </div>

            </div>

            <!-- Image -->
            <div class="col-lg-6">

                <img
                    src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=800&auto=format&fit=crop"
                    class="img-fluid rounded-4 shadow-lg about-hero-image"
                    alt="Women Entrepreneurs">

            </div>

        </div>
    </div>
</div>

<!-- Impact Numbers -->
<div class="container py-5">
    <div class="row g-4 text-center">
        <div class="col-6 col-md-3">
            <div class="p-4 bg-white rounded-4 border shadow-sm h-100">
                <h2 class="display-5 font-serif fw-bold text-maroon-900 mb-1">5,000+</h2>
                <span class="text-muted small fw-semibold">Women Home Makers Active</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 bg-white rounded-4 border shadow-sm h-100">
                <h2 class="display-5 font-serif fw-bold text-terracotta mb-1">₹2.4 Cr+</h2>
                <span class="text-muted small fw-semibold">Direct Household Income Generated</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 bg-white rounded-4 border shadow-sm h-100">
                <h2 class="display-5 font-serif fw-bold text-maroon-900 mb-1">50,000+</h2>
                <span class="text-muted small fw-semibold">Delighted Customers Across India</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 bg-white rounded-4 border shadow-sm h-100">
                <h2 class="display-5 font-serif fw-bold text-success mb-1">100%</h2>
                <span class="text-muted small fw-semibold">Authentic & Preservative Free</span>
            </div>
        </div>
    </div>
</div>

<!-- Our 3 Core Beliefs -->
<div class="bg-cream-50 py-5">
    <div class="container">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="text-terracotta fw-bold text-uppercase small tracking-wide">Our Values</span>
            <h2 class="font-serif fw-bold text-maroon-900">What Drives Udyojika Forward</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 shadow-sm border h-100">
                    <div class="fs-1 text-maroon-800 mb-3"><i class="fa-solid fa-hands-holding-child"></i></div>
                    <h5 class="fw-bold mb-2">Heritage Preservation</h5>
                    <p class="text-secondary small mb-0">Generational recipes, traditional weaving, and terracotta crafts are fading. We keep Indian heritage alive by supporting the women who create them.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 shadow-sm border h-100">
                    <div class="fs-1 text-terracotta mb-3"><i class="fa-solid fa-coins"></i></div>
                    <h5 class="fw-bold mb-2">Financial Self-Reliance</h5>
                    <p class="text-secondary small mb-0">When a woman earns her own income, she invests in her family's health, education, and community progress. We ensure fair and transparent pricing.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 shadow-sm border h-100">
                    <div class="fs-1 text-success mb-3"><i class="fa-solid fa-seedling"></i></div>
                    <h5 class="fw-bold mb-2">Purity & Honest Food</h5>
                    <p class="text-secondary small mb-0">Commercial mass manufacturing uses palm oil, artificial colors, and chemical shelf-extenders. Our makers use genuine cold-pressed oils, pure spices, and A2 ghee.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
