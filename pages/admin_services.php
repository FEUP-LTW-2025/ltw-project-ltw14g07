<?php
require_once(__DIR__ . '/../template/common.tpl.php');
require_once(__DIR__ . '/../database/service.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../middleware/admin_auth.php');
ensureAdminAccess();

session_start();
if (!isset($_SESSION['userID']) || !User::getUserByID($db, $_SESSION['userID'])->isAdmin()) {
    header("Location: /pages/signup.php?q=l");
    exit();
}

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        $serviceID = (int)$_POST['service_id'];
        $newStatus = $_POST['new_status'];
        
        if (Service::updateStatus($db, $serviceID, $newStatus)) {
            User::logSystemAction(
                $db,
                $_SESSION['userID'],
                "Updated service status",
                "Service ID: $serviceID, New Status: $newStatus"
            );
            $_SESSION['admin_message'] = "Service status updated successfully";
        } else {
            $_SESSION['admin_error'] = "Failed to update service status";
        }
    } catch (PDOException $e) {
        $_SESSION['admin_error'] = "Database error: " . $e->getMessage();
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Get all services with filtering
$statusFilter = $_GET['status'] ?? null;
$filters = [];
if ($statusFilter && in_array($statusFilter, ['active', 'pending', 'suspended'])) {
    $filters['status'] = $statusFilter;
}

$services = Service::getFilteredServices($db, $filters);

draw_header('admin-services', true);
?>
<section class="admin-services">
    <h1>Service Management</h1>
    
    <div class="filters">
        <form method="GET">
            <label for="status">Filter by Status:</label>
            <select name="status" id="status" onchange="this.form.submit()">
                <option value="">All Services</option>
                <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="suspended" <?= $statusFilter === 'suspended' ? 'selected' : '' ?>>Suspended</option>
            </select>
        </form>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Provider</th>
                <th>Rate</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service): ?>
            <tr>
                <td><?= $service->serviceID ?></td>
                <td><?= htmlspecialchars($service->title) ?></td>
                <td><?= htmlspecialchars($service->userName) ?></td>
                <td>$<?= $service->hourlyRate ?>/hr</td>
                <td>
                    <form method="POST" class="status-form">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="service_id" value="<?= $service->serviceID ?>">
                        <select name="new_status" onchange="this.form.submit()">
                            <option value="active" <?= $service->status === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="pending" <?= $service->status === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="suspended" <?= $service->status === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                        </select>
                    </form>
                </td>
                <td>
                    <a href="/pages/service.php?serviceID=<?= $service->serviceID ?>" class="btn-view">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php
draw_footer();
?>