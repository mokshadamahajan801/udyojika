<?php
$page_title = "Reports & Analytics - Admin Portal";
$page_header = "Financial & Impact Reports";
$page_subheader = "Analyze GMV, maker income generation, order velocity and state-wise performance";
require_once __DIR__ . '/includes/header.php';

$stats = get_admin_dashboard_stats();
?>

<!-- Financial Summary Row -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-title">Gross Merchandise Value (GMV)</div>
            <div class="stat-value text-maroon-900">₹<?php echo number_format($stats['total_sales']); ?></div>
            <p class="small text-muted mb-0 mt-2">Cumulative value of all customer orders</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-title">Direct Maker Payout (100%)</div>
            <div class="stat-value text-success">₹<?php echo number_format($stats['total_sales']); ?></div>
            <p class="small text-muted mb-0 mt-2">Zero commission platform direct payout to women</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-title">Average Order Value (AOV)</div>
            <div class="stat-value text-terracotta">₹<?php echo round($stats['total_sales'] / max(1, $stats['total_orders'])); ?></div>
            <p class="small text-muted mb-0 mt-2">Per checkout basket size</p>
        </div>
    </div>
</div>

<!-- Regional Performance Breakdown -->
<div class="dashboard-card mb-4">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-map-location-dot text-maroon-800"></i> State-wise Maker Density & Revenue</h5>
        <button class="btn btn-sm btn-outline-maroon" onclick="window.print();"><i class="fa-solid fa-file-pdf me-1"></i> Export PDF Report</button>
    </div>
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>State / Region</th>
                    <th>Active Makers</th>
                    <th>Top Category</th>
                    <th>Delivered Orders</th>
                    <th>Generated Income</th>
                    <th>Growth</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Maharashtra (Pune, Nashik, Nagpur)</strong></td>
                    <td>2,150</td>
                    <td>Homemade Food & Faral</td>
                    <td>14,200</td>
                    <td class="fw-bold text-maroon-900">₹94,50,000</td>
                    <td><span class="text-success fw-bold">+24%</span></td>
                </tr>
                <tr>
                    <td><strong>West Bengal (Kolkata, Shantiniketan)</strong></td>
                    <td>1,240</td>
                    <td>Terracotta Art & Kantha Sarees</td>
                    <td>8,900</td>
                    <td class="fw-bold text-maroon-900">₹58,20,000</td>
                    <td><span class="text-success fw-bold">+18%</span></td>
                </tr>
                <tr>
                    <td><strong>Tamil Nadu (Coimbatore, Madurai)</strong></td>
                    <td>890</td>
                    <td>Handloom & Essential Oils</td>
                    <td>6,400</td>
                    <td class="fw-bold text-maroon-900">₹42,80,000</td>
                    <td><span class="text-success fw-bold">+15%</span></td>
                </tr>
                <tr>
                    <td><strong>Gujarat (Ahmedabad, Surat)</strong></td>
                    <td>720</td>
                    <td>Snacks (Khakhra, Fafda) & Bandhani</td>
                    <td>5,100</td>
                    <td class="fw-bold text-maroon-900">₹34,50,000</td>
                    <td><span class="text-success fw-bold">+22%</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
