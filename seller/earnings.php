<?php
$page_title = "Earnings & Bank Payouts - Maker Portal";
$page_header = "Direct Payouts & Finance";
$page_subheader = "Track sales revenue, 100% direct payouts to your bank account with zero platform fees";
require_once __DIR__ . '/includes/header.php';

$stats = get_seller_dashboard_stats($seller_id);
?>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-title">Total Lifetime Income</div>
            <div class="stat-value text-success">₹<?php echo number_format($stats['total_sales']); ?></div>
            <p class="small text-muted mb-0 mt-2"><i class="fa-solid fa-circle-check text-success me-1"></i> 100% Paid directly to your bank account</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-title">Current Cleared Balance</div>
            <div class="stat-value text-maroon-900">₹<?php echo number_format($stats['pending_earnings']); ?></div>
            <p class="small text-muted mb-0 mt-2">Scheduled for auto-settlement on Tuesday</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-title">Platform Commission Deducted</div>
            <div class="stat-value text-primary">₹0.00</div>
            <p class="small text-muted mb-0 mt-2">Zero commission nonprofit maker empowerment</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Settlement History Table -->
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title"><i class="fa-solid fa-receipt text-maroon-800"></i> Payout Settlements History</h5>
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
                            <td><span class="badge bg-success">Transferred</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">#PAY-8902</td>
                            <td>03 Aug - 09 Aug 2024</td>
                            <td>8 orders</td>
                            <td class="fw-bold text-success">₹6,180</td>
                            <td class="small text-muted">NEFT/98012344/HDFC</td>
                            <td><span class="badge bg-success">Transferred</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">#PAY-8874</td>
                            <td>27 Jul - 02 Aug 2024</td>
                            <td>5 orders</td>
                            <td class="fw-bold text-success">₹3,400</td>
                            <td class="small text-muted">NEFT/77192003/HDFC</td>
                            <td><span class="badge bg-success">Transferred</span></td>
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
                <h5 class="dashboard-card-title"><i class="fa-solid fa-building-columns text-terracotta"></i> Direct Payout Bank Account</h5>
            </div>
            <div class="p-3">
                <div class="p-3 bg-cream-100 rounded-3 border mb-3">
                    <span class="badge bg-success text-white mb-2">Verified Primary Bank</span>
                    <strong class="d-block text-maroon-900"><?php echo htmlspecialchars($seller_profile['owner_name']); ?></strong>
                    <div class="small text-secondary mt-1">Bank: <strong>HDFC Bank Ltd</strong></div>
                    <div class="small text-secondary">A/C: <strong>•••• •••• 4912</strong></div>
                    <div class="small text-secondary">IFSC: <strong>HDFC0000182</strong></div>
                    <div class="small text-secondary">UPI ID: <strong>sunita@okhdfcbank</strong></div>
                </div>

                <button class="btn btn-outline-maroon btn-sm w-100" onclick="alert('Bank change request submitted to admin for verification.');">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Update Bank Details
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
