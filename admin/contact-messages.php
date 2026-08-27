<?php
$page_title = "Contact Messages - Admin Portal";
$page_header = "Public Contact Form Inquiries";
$page_subheader = "Inquiries received via the public Contact Us and Maker Support channels";
require_once __DIR__ . '/includes/header.php';

$messages = [
    [
        'id' => 1,
        'name' => 'Meenakshi Iyer',
        'email' => 'meenakshi@gmail.com',
        'phone' => '+91 98450 11223',
        'topic' => 'Corporate Gifting / Bulk Wedding Orders',
        'message' => 'We are organizing our daughter\'s wedding in Bangalore and would like 200 boxes of handmade traditional Mysore Pak & Terracotta Diyas. Please connect us with verified makers.',
        'status' => 'new',
        'created_at' => '2024-08-24 12:45:00'
    ],
    [
        'id' => 2,
        'name' => 'Rajesh Sharma',
        'email' => 'rajesh.sharma@logistics.in',
        'phone' => '+91 98220 88990',
        'topic' => 'Seller Onboarding',
        'message' => 'My mother makes authentic Rajasthani Sangri pickle and papad. How can we register her kitchen on Udyojika?',
        'status' => 'replied',
        'created_at' => '2024-08-20 10:15:00'
    ]
];
?>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-envelope-open-text text-maroon-800"></i> Contact Form Messages</h5>
    </div>
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Sender Name</th>
                    <th>Contact Info</th>
                    <th>Subject / Topic</th>
                    <th>Message Details</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $m): ?>
                    <tr>
                        <td><strong class="text-maroon-900"><?php echo htmlspecialchars($m['name']); ?></strong></td>
                        <td>
                            <div><a href="mailto:<?php echo $m['email']; ?>" class="text-dark small text-decoration-none"><?php echo htmlspecialchars($m['email']); ?></a></div>
                            <small class="text-muted"><?php echo htmlspecialchars($m['phone']); ?></small>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($m['topic']); ?></span></td>
                        <td class="small text-secondary" style="max-width: 320px;">
                            <?php echo htmlspecialchars($m['message']); ?>
                        </td>
                        <td>
                            <span class="badge-status-<?php echo $m['status'] === 'replied' ? 'completed' : 'pending'; ?>">
                                <?php echo ucfirst($m['status']); ?>
                            </span>
                        </td>
                        <td class="small text-muted"><?php echo date('d M, Y', strtotime($m['created_at'])); ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-maroon" onclick="alert('Replying to <?php echo htmlspecialchars($m['email']); ?>');"><i class="fa-solid fa-reply me-1"></i> Reply</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
