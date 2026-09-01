<?php

require_once __DIR__ . '/includes/auth.php';
require_admin();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = "Manage Users - Admin Portal";
$page_header = "User & Account Directory";
$page_subheader = "View, filter and manage all admins, women sellers and customer accounts";

$users = get_all_users();

$filter_role = $_GET['role'] ?? 'all';

require_once __DIR__ . '/includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <!-- Role Tabs -->
    <div class="btn-group shadow-sm bg-white p-1 rounded-3 border" role="group">
        <a href="users.php?role=all" class="btn btn-sm <?php echo $filter_role === 'all' ? 'btn-maroon' : 'btn-light'; ?>">All Users (<?php echo count($users); ?>)</a>
        <a href="users.php?role=customer" class="btn btn-sm <?php echo $filter_role === 'customer' ? 'btn-maroon' : 'btn-light'; ?>">Customers</a>
        <a href="users.php?role=seller" class="btn btn-sm <?php echo $filter_role === 'seller' ? 'btn-maroon' : 'btn-light'; ?>">Sellers / Makers</a>
        <a href="users.php?role=admin" class="btn btn-sm <?php echo $filter_role === 'admin' ? 'btn-maroon' : 'btn-light'; ?>">Admins</a>
    </div>

    <!-- Quick Search Input -->
    <div style="max-width: 300px; width: 100%;">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="text" id="dashboardTableSearch" class="form-control" placeholder="Search name, email, phone...">
        </div>
    </div>
</div>

<div class="dashboard-card">
    <div class="dashboard-card-header">
        <h5 class="dashboard-card-title">
            <i class="fa-solid fa-users text-maroon-800"></i>
            Registered Platform Accounts
        </h5>

        <div class="d-flex gap-2">
            <a href="add-admin.php" class="btn btn-maroon btn-sm">
                <i class="fa-solid fa-user-plus me-1"></i> Add Admin
            </a>

            <button class="btn btn-maroon btn-sm"
                onclick="alert('User export initiated in CSV format.');">
                <i class="fa-solid fa-download me-1"></i> Export Users
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Full Name & Avatar</th>
                    <th>Email Address</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u):
                    if ($filter_role !== 'all' && $u['role'] !== $filter_role) continue;
                ?>
                    <tr>
                        <td class="fw-bold text-muted">#USR-<?php echo str_pad($u['id'], 4, '0', STR_PAD_LEFT); ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?php echo $u['avatar'] ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=300&auto=format&fit=crop'; ?>" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;" alt="">
                                <div>
                                    <strong class="text-dark d-block"><?php echo htmlspecialchars($u['name']); ?></strong>
                                    <?php if (!empty($u['business_name'])): ?>
                                        <small class="text-terracotta"><i class="fa-solid fa-store me-1"></i><?php echo htmlspecialchars($u['business_name']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><a href="mailto:<?php echo $u['email']; ?>" class="text-decoration-none text-dark"><?php echo htmlspecialchars($u['email']); ?></a></td>
                        <td><?php echo htmlspecialchars($u['phone']); ?></td>
                        <td>
                            <?php if ($u['role'] === 'admin'): ?>
                                <span class="badge bg-danger">Admin</span>
                            <?php elseif ($u['role'] === 'seller'): ?>
                                <span class="badge bg-warning text-dark">Seller / Maker</span>
                            <?php else: ?>
                                <span class="badge bg-success">Customer</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge-status-<?php echo $u['status']; ?>"><?php echo ucfirst($u['status']); ?></span>
                        </td>
                        <td class="small text-muted"><?php echo date('d M, Y', strtotime($u['created_at'])); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a
                                    href="user-view.php?id=<?php echo $u['id']; ?>"
                                    class="btn btn-light border"
                                    title="View User">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a
                                    href="user-edit.php?id=<?php echo $u['id']; ?>"
                                    class="btn btn-light border"
                                    title="Edit User">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <?php if ($u['role'] !== 'admin'): ?>
                                    <a href="user-suspend.php?id=<?php echo $u['id']; ?>"
                                        class="btn btn-light border text-danger"
                                        title="<?php echo $u['status'] === 'active' ? 'Suspend User' : 'Activate User'; ?>"
                                        onclick="return confirm('<?php echo $u['status'] === 'active' ? 'Suspend' : 'Activate'; ?> account for <?php echo htmlspecialchars($u['name']); ?>?');">
                                        <i class="fa-solid <?php echo $u['status'] === 'active' ? 'fa-ban' : 'fa-check'; ?>"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>