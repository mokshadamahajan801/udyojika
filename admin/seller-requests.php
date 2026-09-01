<?php

require_once __DIR__ . '/includes/auth.php';
require_admin();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';


// =========================================================
// PROCESS APPROVE / REJECT
// =========================================================

$action_msg = '';
$action_type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $req_id = $_POST['request_id'] ?? 0;
    $action = $_POST['action'] ?? '';

    if (!is_numeric($req_id)) {
        header('Location: seller-requests.php?error=invalid_request');
        exit;
    }

    $req_id = (int) $req_id;


    // Check request exists
    $stmt = $pdo->prepare("
        SELECT id, full_name, business_name, status
        FROM seller_requests
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([$req_id]);

    $request = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$request) {

        header('Location: seller-requests.php?error=request_not_found');
        exit;

    }


    // -----------------------------------------------------
    // APPROVE
    // -----------------------------------------------------

    if ($action === 'approve') {

        if ($request['status'] !== 'pending') {

            header('Location: seller-requests.php?error=already_processed');
            exit;

        }

        $stmt = $pdo->prepare("
            UPDATE seller_requests
            SET status = 'approved'
            WHERE id = ?
        ");

        $stmt->execute([$req_id]);


        header('Location: seller-requests.php?success=approved');
        exit;
    }


    // -----------------------------------------------------
    // REJECT
    // -----------------------------------------------------

    if ($action === 'reject') {

        if ($request['status'] !== 'pending') {

            header('Location: seller-requests.php?error=already_processed');
            exit;

        }

        $stmt = $pdo->prepare("
            UPDATE seller_requests
            SET status = 'rejected'
            WHERE id = ?
        ");

        $stmt->execute([$req_id]);


        header('Location: seller-requests.php?success=rejected');
        exit;
    }


    header('Location: seller-requests.php?error=invalid_action');
    exit;
}


// =========================================================
// SUCCESS / ERROR MESSAGE
// =========================================================

if (isset($_GET['success'])) {

    if ($_GET['success'] === 'approved') {

        $action_msg = 'Maker application has been approved successfully.';
        $action_type = 'success';

    } elseif ($_GET['success'] === 'rejected') {

        $action_msg = 'Maker application has been rejected.';
        $action_type = 'warning';

    }

}


if (isset($_GET['error'])) {

    if ($_GET['error'] === 'invalid_request') {

        $action_msg = 'Invalid application request.';
        $action_type = 'danger';

    } elseif ($_GET['error'] === 'request_not_found') {

        $action_msg = 'Application not found.';
        $action_type = 'danger';

    } elseif ($_GET['error'] === 'already_processed') {

        $action_msg = 'This application has already been processed.';
        $action_type = 'warning';

    }

}


// =========================================================
// GET ALL SELLER REQUESTS FROM DATABASE
// =========================================================

$stmt = $pdo->query("
    SELECT
        id,
        full_name,
        business_name,
        phone,
        city,
        category,
        description,
        sample_products,
        status,
        created_at
    FROM seller_requests
    ORDER BY
        CASE
            WHEN status = 'pending' THEN 1
            WHEN status = 'approved' THEN 2
            WHEN status = 'rejected' THEN 3
        END,
        created_at DESC
");

$seller_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);


// =========================================================
// PENDING COUNT
// =========================================================

$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM seller_requests
    WHERE status = 'pending'
");

$pending_count = (int) $stmt->fetchColumn();


// =========================================================
// PAGE HEADER
// =========================================================

$page_title = "Maker Registration Applications - Admin Portal";
$page_header = "Women Maker Onboarding Requests";
$page_subheader = "Review, verify, approve or reject home entrepreneur applications";

require_once __DIR__ . '/includes/header.php';

?>


<!-- =========================================================
     ALERT MESSAGE
========================================================= -->

<?php if (!empty($action_msg)): ?>

    <div class="alert alert-<?php echo $action_type; ?> d-flex align-items-center gap-2 mb-4">

        <?php if ($action_type === 'success'): ?>

            <i class="fa-solid fa-circle-check fs-4"></i>

        <?php elseif ($action_type === 'warning'): ?>

            <i class="fa-solid fa-triangle-exclamation fs-4"></i>

        <?php else: ?>

            <i class="fa-solid fa-circle-xmark fs-4"></i>

        <?php endif; ?>

        <div>
            <?php echo htmlspecialchars($action_msg); ?>
        </div>

    </div>

<?php endif; ?>


<!-- =========================================================
     SELLER REQUESTS
========================================================= -->

<div class="dashboard-card">

    <div class="dashboard-card-header">

        <h5 class="dashboard-card-title">

            <i class="fa-solid fa-user-plus text-warning"></i>

            Incoming Maker Registration Requests

        </h5>


        <span class="badge bg-danger rounded-pill px-3 py-2">

            <?php echo $pending_count; ?> Pending Review

        </span>

    </div>


    <div class="p-3">

        <?php if (empty($seller_requests)): ?>

            <div class="text-center py-5">

                <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>

                <h5 class="text-muted">
                    No Maker Applications
                </h5>

                <p class="text-muted mb-0">
                    There are currently no seller registration requests.
                </p>

            </div>


        <?php else: ?>


            <div class="d-flex flex-column gap-3">


                <?php foreach ($seller_requests as $req): ?>


                    <div class="p-4 border rounded-4 bg-white shadow-sm">

                        <div class="row align-items-start g-3">


                            <!-- =================================================
                                 LEFT SIDE
                            ================================================== -->

                            <div class="col-lg-8">


                                <!-- Name + badges -->

                                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">

                                    <h5 class="font-serif fw-bold text-maroon-900 mb-0">

                                        <?php echo htmlspecialchars($req['full_name']); ?>

                                    </h5>


                                    <span class="badge bg-warning text-dark px-2 py-1">

                                        <i class="fa-solid fa-tag me-1"></i>

                                        <?php echo htmlspecialchars($req['category']); ?>

                                    </span>


                                    <span class="badge-status-<?php echo htmlspecialchars($req['status']); ?>">

                                        <?php echo ucfirst(htmlspecialchars($req['status'])); ?>

                                    </span>

                                </div>


                                <!-- Basic details -->

                                <div class="row g-2 small text-muted mb-3">


                                    <div class="col-sm-6">

                                        <strong>Proposed Brand:</strong>

                                        <span class="text-dark fw-bold">

                                            <?php echo htmlspecialchars($req['business_name']); ?>

                                        </span>

                                    </div>


                                    <div class="col-sm-6">

                                        <strong>City / Region:</strong>

                                        <span class="text-dark">

                                            <?php echo htmlspecialchars($req['city']); ?>

                                        </span>

                                    </div>


                                    <div class="col-sm-6">

                                        <strong>WhatsApp Phone:</strong>

                                        <a
                                            href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $req['phone']); ?>"
                                            target="_blank"
                                            class="text-success fw-bold text-decoration-none"
                                        >

                                            <i class="fa-brands fa-whatsapp"></i>

                                            <?php echo htmlspecialchars($req['phone']); ?>

                                        </a>

                                    </div>


                                    <div class="col-sm-6">

                                        <strong>Applied On:</strong>

                                        <?php
                                        echo date(
                                            'd M Y, h:i A',
                                            strtotime($req['created_at'])
                                        );
                                        ?>

                                    </div>


                                </div>


                                <!-- Description -->

                                <div class="p-3 bg-light rounded-3 mb-2">

                                    <strong class="small d-block text-maroon-900 mb-1">

                                        Maker Story & Description:

                                    </strong>


                                    <p class="small text-secondary mb-3">

                                        <?php
                                        echo !empty($req['description'])
                                            ? nl2br(htmlspecialchars($req['description']))
                                            : 'No description provided.';
                                        ?>

                                    </p>


                                    <strong class="small d-block text-maroon-900 mb-1">

                                        Sample Products:

                                    </strong>


                                    <?php if (!empty($req['sample_products'])): ?>

                                        <span class="badge bg-white border text-dark">

                                            <?php
                                            echo htmlspecialchars($req['sample_products']);
                                            ?>

                                        </span>

                                    <?php else: ?>

                                        <span class="text-muted small">
                                            Not provided
                                        </span>

                                    <?php endif; ?>

                                </div>


                            </div>


                            <!-- =================================================
                                 RIGHT SIDE
                            ================================================== -->

                            <div class="col-lg-4">


                                <!-- Verification -->

                                <div class="p-3 bg-cream-100 rounded-3 border mb-3">


                                    <small class="text-muted d-block fw-bold mb-2">

                                        Verification Checklist:

                                    </small>


                                    <ul class="small list-unstyled mb-0 d-flex flex-column gap-2 text-secondary">


                                        <li>

                                            <i class="fa-solid fa-check text-success me-1"></i>

                                            Contact Phone provided

                                        </li>


                                        <li>

                                            <i class="fa-solid fa-check text-success me-1"></i>

                                            Homemade business declared

                                        </li>


                                        <li>

                                            <i class="fa-solid fa-clock text-warning me-1"></i>

                                            Admin verification required

                                        </li>


                                    </ul>

                                </div>


                                <!-- ACTIONS -->

                                <form
                                    method="POST"
                                    action="seller-requests.php"
                                    class="d-flex justify-content-lg-end gap-2"
                                >


                                    <input
                                        type="hidden"
                                        name="request_id"
                                        value="<?php echo (int) $req['id']; ?>"
                                    >


                                    <?php if ($req['status'] === 'pending'): ?>


                                        <!-- APPROVE -->

                                        <button
                                            type="submit"
                                            name="action"
                                            value="approve"
                                            class="btn btn-success fw-bold"
                                            onclick="return confirm('Approve <?php echo htmlspecialchars($req['business_name'], ENT_QUOTES); ?> as a maker?');"
                                        >

                                            <i class="fa-solid fa-circle-check me-1"></i>

                                            Approve Maker

                                        </button>


                                        <!-- REJECT -->

                                        <button
                                            type="submit"
                                            name="action"
                                            value="reject"
                                            class="btn btn-outline-danger fw-bold"
                                            onclick="return confirm('Are you sure you want to reject this application?');"
                                        >

                                            <i class="fa-solid fa-ban me-1"></i>

                                            Reject

                                        </button>


                                    <?php elseif ($req['status'] === 'approved'): ?>


                                        <button
                                            type="button"
                                            class="btn btn-light border text-success fw-bold"
                                            disabled
                                        >

                                            <i class="fa-solid fa-circle-check me-1"></i>

                                            Approved

                                        </button>


                                    <?php elseif ($req['status'] === 'rejected'): ?>


                                        <button
                                            type="button"
                                            class="btn btn-light border text-danger fw-bold"
                                            disabled
                                        >

                                            <i class="fa-solid fa-circle-xmark me-1"></i>

                                            Rejected

                                        </button>


                                    <?php endif; ?>


                                </form>


                            </div>


                        </div>

                    </div>


                <?php endforeach; ?>


            </div>


        <?php endif; ?>

    </div>

</div>


<?php require_once __DIR__ . '/includes/footer.php'; ?>