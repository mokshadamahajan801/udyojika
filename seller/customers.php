<?php
$page_title = "My Customers - Maker Portal";
$page_header = "My Patrons & Repeat Buyers";
$page_subheader = "View customers who have ordered your homemade specialties";
require_once __DIR__ . '/includes/header.php';

$customers = [
    [
        'name' => 'Priya Patil',
        'email' => 'priya@example.com',
        'phone' => '+91 98765 43210',
        'city' => 'Pune, Model Colony',
        'orders_count' => 3,
        'total_spent' => 2480,
        'last_order' => '2024-08-20',
        'favorite_item' => 'Authentic Bhajanichi Chakli'
    ],
    [
        'name' => 'Aditi Sharma',
        'email' => 'aditi@example.com',
        'phone' => '+91 98234 56789',
        'city' => 'Pune, Viman Nagar',
        'orders_count' => 2,
        'total_spent' => 1680,
        'last_order' => '2024-08-15',
        'favorite_item' => 'Pure A2 Cow Ghee Besan Ladoo'
    ],
    [
        'name' => 'Meera Joshi',
        'email' => 'meera.j@gmail.com',
        'phone' => '+91 98221 00998',
        'city' => 'Nagpur',
        'orders_count' => 1,
        'total_spent' => 840,
        'last_order' => '2024-07-28',
        'favorite_item' => 'Besan Ladoo & Poha Chivda'
    ]
];
?>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-users text-maroon-800"></i> Loyal Patrons Directory</h5>
    </div>
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Location</th>
                    <th>Total Orders</th>
                    <th>Total Spend</th>
                    <th>Favorite Homemade Item</th>
                    <th>Last Order Date</th>
                    <th>Direct Contact</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $c): ?>
                    <tr>
                        <td>
                            <strong class="text-dark d-block"><?php echo htmlspecialchars($c['name']); ?></strong>
                            <small class="text-muted"><?php echo htmlspecialchars($c['email']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($c['city']); ?></td>
                        <td><span class="badge bg-cream-200 text-maroon-900 border"><?php echo $c['orders_count']; ?> orders</span></td>
                        <td><strong class="text-maroon-800">₹<?php echo number_format($c['total_spent']); ?></strong></td>
                        <td class="small text-secondary"><?php echo htmlspecialchars($c['favorite_item']); ?></td>
                        <td class="small text-muted"><?php echo date('d M, Y', strtotime($c['last_order'])); ?></td>
                        <td>
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $c['phone']); ?>" target="_blank" class="btn btn-sm btn-success py-1 px-2" title="Send WhatsApp">
                                <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
