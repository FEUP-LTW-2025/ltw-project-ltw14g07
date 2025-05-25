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

<style>
/* Container & Layout */
.admin-services {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 1rem 2rem;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #fafafa;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

/* Heading */
.admin-services h1 {
    font-weight: 700;
    font-size: 2.4rem;
    color: #2c3e50;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid #2980b9;
    padding-bottom: 0.5rem;
}

/* Filters */
.filters {
    margin-bottom: 1.5rem;
}
.filters label {
    font-weight: 600;
    margin-right: 0.8rem;
    color: #34495e;
}
.filters select {
    padding: 0.4rem 0.7rem;
    font-size: 1rem;
    border-radius: 5px;
    border: 1px solid #ccc;
    transition: border-color 0.3s ease;
}
.filters select:hover,
.filters select:focus {
    border-color: #2980b9;
    outline: none;
}

/* Table */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.07);
    border-radius: 10px;
    overflow: hidden;
}
thead tr {
    background-color: #2980b9;
    color: #ecf0f1;
    text-align: left;
    font-weight: 700;
    font-size: 1.1rem;
}
thead th {
    padding: 1rem 1.2rem;
}
tbody tr {
    background: #fff;
    transition: background-color 0.25s ease;
    cursor: default;
}
tbody tr:hover {
    background-color: #f0f8ff;
}

/* Table cells */
tbody td {
    padding: 1rem 1.2rem;
    border-bottom: 1px solid #ecf0f1;
    vertical-align: middle;
    color: #2c3e50;
    font-weight: 500;
}

/* Status form select */
.status-form select {
    padding: 0.35rem 0.5rem;
    font-size: 0.95rem;
    border: 1.5px solid #bbb;
    border-radius: 6px;
    background: #fff;
    cursor: pointer;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}
.status-form select:hover,
.status-form select:focus {
    border-color: #2980b9;
    box-shadow: 0 0 6px rgba(41, 128, 185, 0.5);
    outline: none;
}

/* Action buttons */
.btn-view {
    display: inline-block;
    padding: 0.5rem 1rem;
    background-color: #2980b9;
    color: #fff;
    font-weight: 600;
    border-radius: 6px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}
.btn-view:hover {
    background-color: #1f6391;
}

/* Responsive */
@media (max-width: 768px) {
    .admin-services {
        padding: 1rem;
    }
    thead tr {
        display: none;
    }
    tbody tr {
        display: block;
        margin-bottom: 1rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        border-radius: 8px;
        padding: 1rem;
    }
    tbody td {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: none;
    }
    tbody td::before {
        content: attr(data-label);
        font-weight: 700;
        color: #2980b9;
    }
    .status-form select,
    .btn-view {
        width: 100%;
        box-sizing: border-box;
    }
}
</style>

<?php
draw_footer();
?>
