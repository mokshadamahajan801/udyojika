<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$current_user = require_role(['seller']);

/*
|--------------------------------------------------------------------------
| Get Seller Profile
|--------------------------------------------------------------------------
*/
$seller_id = 0;
$seller_profile = null;

if (!empty($current_user['id'])) {

    $stmt = $pdo->prepare("
        SELECT *
        FROM sellers
        WHERE user_id = ?
        LIMIT 1
    ");

    $stmt->execute([
        (int) $current_user['id']
    ]);

    $seller_profile = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($seller_profile) {
        $seller_id = (int) $seller_profile['id'];
    }
}


/*
|--------------------------------------------------------------------------
| Update Bank Details
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_bank_details'])) {

    $bank_name = trim($_POST['bank_name'] ?? '');
    $account_number = trim($_POST['account_number'] ?? '');
    $ifsc_code = strtoupper(trim($_POST['ifsc_code'] ?? ''));
    $upi_id = trim($_POST['upi_id'] ?? '');

    if (
        !empty($current_user['id']) &&
        !empty($bank_name) &&
        !empty($account_number) &&
        !empty($ifsc_code) &&
        !empty($upi_id)
    ) {

        $stmt = $pdo->prepare("
            UPDATE users
            SET
                bank_name = ?,
                account_number = ?,
                ifsc_code = ?,
                upi_id = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $bank_name,
            $account_number,
            $ifsc_code,
            $upi_id,
            (int) $current_user['id']
        ]);

        header("Location: earnings.php?bank_updated=1");
        exit;
    }
}


/*
|--------------------------------------------------------------------------
| Page Header
|--------------------------------------------------------------------------
*/
$page_title = "Earnings & Bank Payouts - Maker Portal";
$page_header = "Direct Payouts & Finance";
$page_subheader = "Track sales revenue, 100% direct payouts to your bank account with zero platform fees";

$bank_details = [
    'bank_name' => '',
    'account_number' => '',
    'ifsc_code' => '',
    'upi_id' => ''
];

if (!empty($current_user['id'])) {

    $stmt = $pdo->prepare("
        SELECT bank_name, account_number, ifsc_code, upi_id
        FROM users
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([
        (int) $current_user['id']
    ]);

    $bank_details = $stmt->fetch(PDO::FETCH_ASSOC) ?: $bank_details;
}

require_once __DIR__ . '/includes/header.php';


/*
|--------------------------------------------------------------------------
| Seller Dashboard Stats
|--------------------------------------------------------------------------
*/
$stats = [];

if ($seller_id > 0) {
    $stats = get_seller_dashboard_stats($seller_id);
}


/*
|--------------------------------------------------------------------------
| Safe Stats Values
|--------------------------------------------------------------------------
*/
$total_sales = $stats['total_sales'] ?? 0;
$pending_earnings = $stats['pending_earnings'] ?? 0;

?>
<?php if (isset($_GET['bank_updated']) && $_GET['bank_updated'] == '1'): ?>

    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">

        <i class="fa-solid fa-circle-check me-2"></i>

        <strong>Bank details updated successfully!</strong>
        Your payout bank information has been saved.

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close">
        </button>

    </div>

<?php endif; ?>
 
<div class="row g-4 mb-4"> 
    <div class="col-md-4"> 
        <div class="stat-card"> 
            <div class="stat-title">Total Lifetime Income</div> 
            <div class="stat-value text-success">
                ₹<?php echo number_format($total_sales); ?>
            </div> 
            <p class="small text-muted mb-0 mt-2">
                <i class="fa-solid fa-circle-check text-success me-1"></i>
                100% Paid directly to your bank account
            </p> 
        </div> 
    </div> 

    <div class="col-md-4"> 
        <div class="stat-card"> 
            <div class="stat-title">Current Cleared Balance</div> 
            <div class="stat-value text-maroon-900">
                ₹<?php echo number_format($pending_earnings); ?>
            </div> 
            <p class="small text-muted mb-0 mt-2">
                Scheduled for auto-settlement on Tuesday
            </p> 
        </div> 
    </div> 

    <div class="col-md-4"> 
        <div class="stat-card"> 
            <div class="stat-title">Platform Commission Deducted</div> 
            <div class="stat-value text-primary">₹0.00</div> 
            <p class="small text-muted mb-0 mt-2">
                Zero commission nonprofit maker empowerment
            </p> 
        </div> 
    </div> 
</div> 
 
<div class="row g-4"> 

    <!-- Settlement History Table --> 
    <div class="col-lg-8"> 
        <div class="dashboard-card"> 
            <div class="dashboard-card-header"> 
                <h5 class="dashboard-card-title">
                    <i class="fa-solid fa-receipt text-maroon-800"></i>
                    Payout Settlements History
                </h5> 
            </div> 

            <div class="table-responsive"> 
                <table class="dashboard-table"> 
                    <thead> 
                        <tr> 
                            <th>Payout ID</th> 
                            <th>Cycle Period</th> 
                            <th>Orders</th> 
                            <th>Amount Transferred</th> 
                            <th>Bank Reference #</th> 
                            <th>Status</th> 
                        </tr> 
                    </thead> 

                    <tbody> 
                        <tr> 
                            <td class="fw-bold text-muted">#PAY-8921</td> 
                            <td>10 Aug - 17 Aug 2024</td> 
                            <td>6 orders</td> 
                            <td class="fw-bold text-success">₹4,250</td> 
                            <td class="small text-muted">UPI/24890123/HDFC</td> 
                            <td>
                                <span class="badge bg-success">Transferred</span>
                            </td> 
                        </tr> 

                        <tr> 
                            <td class="fw-bold text-muted">#PAY-8902</td> 
                            <td>03 Aug - 09 Aug 2024</td> 
                            <td>8 orders</td> 
                            <td class="fw-bold text-success">₹6,180</td> 
                            <td class="small text-muted">NEFT/98012344/HDFC</td> 
                            <td>
                                <span class="badge bg-success">Transferred</span>
                            </td> 
                        </tr> 

                        <tr> 
                            <td class="fw-bold text-muted">#PAY-8874</td> 
                            <td>27 Jul - 02 Aug 2024</td> 
                            <td>5 orders</td> 
                            <td class="fw-bold text-success">₹3,400</td> 
                            <td class="small text-muted">NEFT/77192003/HDFC</td> 
                            <td>
                                <span class="badge bg-success">Transferred</span>
                            </td> 
                        </tr> 
                    </tbody> 
                </table> 
            </div> 
        </div> 
    </div> 
 
    <!-- Bank Account Details Card --> 
    <div class="col-lg-4"> 
        <div class="dashboard-card"> 
            <div class="dashboard-card-header"> 
                <h5 class="dashboard-card-title">
                    <i class="fa-solid fa-building-columns text-terracotta"></i>
                    Direct Payout Bank Account
                </h5> 
            </div> 

            <div class="p-3"> 
                <div class="p-3 bg-cream-100 rounded-3 border mb-3"> 

                    <span class="badge bg-success text-white mb-2">
                        Primary Bank Account
                    </span> 

                    <strong class="d-block text-maroon-900">
                       <?php echo htmlspecialchars($bank_details['bank_name'] ?? 'Not added'); ?>
                    </strong> 

                    <div class="small text-secondary mt-1">
    Bank:
    <strong>
        <?php echo htmlspecialchars($bank_details['bank_name'] ?? 'Not added'); ?>
    </strong>
</div>

<div class="small text-secondary">
    A/C:
    <strong>
        <?php
$account_number = $bank_details['account_number'] ?? '';

if (!empty($account_number)) {
    echo '•••• •••• ' . htmlspecialchars(substr($account_number, -4));
} else {
    echo 'Not added';
}
?>
    </strong>
</div>

<div class="small text-secondary">
    IFSC:
    <strong>
        <?php echo htmlspecialchars($bank_details['ifsc_code'] ?? 'Not added'); ?>
    </strong>
</div>

<div class="small text-secondary">
    UPI ID:
    <strong>
        <?php echo htmlspecialchars($bank_details['upi_id'] ?? 'Not added'); ?>
    </strong>
</div>

                </div> 

                <button
    type="button"
    class="btn btn-outline-maroon btn-sm w-100"
    data-bs-toggle="modal"
    data-bs-target="#bankDetailsModal"
>
    <i class="fa-solid fa-pen-to-square me-1"></i>
    Update Bank Details
</button>

            </div> 
        </div> 
    </div> 

</div> 
 <!-- Update Bank Details Modal -->
<div class="modal fade" id="bankDetailsModal" tabindex="-1" aria-labelledby="bankDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">

            <div class="modal-header">
                <h5 class="modal-title fw-bold text-maroon-900" id="bankDetailsModalLabel">
                    <i class="fa-solid fa-building-columns me-2"></i>
                    Update Bank Details
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>
            </div>

            <form method="POST">

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Bank Name
                        </label>

                        <input
                            type="text"
                            name="bank_name"
                            class="form-control"
                            value="<?php echo htmlspecialchars($bank_details['bank_name'] ?? 'Not added'); ?>"
                            placeholder="Enter bank name"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Account Number
                        </label>

                        <input
                            type="text"
                            name="account_number"
                            class="form-control"
                            value="<?php
$account_number = $bank_details['account_number'] ?? '';

if (!empty($account_number)) {
    echo '•••• •••• ' . htmlspecialchars(substr($account_number, -4));
} else {
    echo 'Not added';
}
?>"
                            placeholder="Enter account number"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            IFSC Code
                        </label>

                        <input
                            type="text"
                            name="ifsc_code"
                            class="form-control text-uppercase"
                            value="<?php echo htmlspecialchars($bank_details['ifsc_code'] ?? 'Not added'); ?>"
                            placeholder="Example: HDFC0000182"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            UPI ID
                        </label>

                        <input
                            type="text"
                            name="upi_id"
                            class="form-control"
                            value="<?php echo htmlspecialchars($bank_details['upi_id'] ?? 'Not added'); ?>"
                            placeholder="Example: name@upi"
                            required
                        >
                    </div>

                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        name="update_bank_details"
                        class="btn btn-success">
                        <i class="fa-solid fa-check me-1"></i>
                        Save Bank Details
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


<?php require_once __DIR__ . '/includes/footer.php'; ?>