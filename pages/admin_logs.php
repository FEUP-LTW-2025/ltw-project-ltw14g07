<?php
require_once(__DIR__ . '/../template/common.tpl.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../middleware/admin_auth.php');
ensureAdminAccess();

global $db;

// Enable foreign keys
$db->exec('PRAGMA foreign_keys = ON;');

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

// Fixed SQL query (notice the spaces after commas and proper line breaks)
$query = "
    SELECT 
        l.LogID,
        l.UserID,
        l.Action, 
        l.Details,
        l.Timestamp,
        u.email AS admin_email
    FROM SystemLogs l
    LEFT JOIN Users u ON l.UserID = u.UserID
    ORDER BY l.Timestamp DESC
    LIMIT :limit OFFSET :offset
";

// Prepare and execute with parameters
$stmt = $db->prepare($query);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$logs = $stmt->fetchAll();

// Get total count
$totalLogs = $db->query("SELECT COUNT(*) FROM SystemLogs")->fetchColumn();

// Prepare template data
$templateData = [
    'logs' => $logs,
    'pagination' => [
        'current_page' => $page,
        'total_pages' => ceil($totalLogs / $limit),
        'has_previous' => $page > 1,
        'has_next' => ($page * $limit) < $totalLogs
    ]
];

// Render
draw_header('Admin Logs');
?>
<div class="admin-logs-container">
    <h1>System Activity Logs</h1>
    <a href="admin.php" class="back-link">← Back to Admin Panel</a>
    
    <table class="log-table">
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>Admin</th>
                <th>Action</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($templateData['logs'] as $log): ?>
            <tr>
                <td><?= htmlspecialchars($log['Timestamp']) ?></td>
                <td><?= htmlspecialchars($log['admin_email'] ?? 'System') ?></td>
                <td><?= htmlspecialchars($log['Action']) ?></td>
                <td><?= htmlspecialchars($log['Details']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="pagination">
        <?php if ($templateData['pagination']['has_previous']): ?>
            <a href="?page=<?= $page - 1 ?>">Previous</a>
        <?php endif; ?>
        
        <span>Page <?= $page ?> of <?= $templateData['pagination']['total_pages'] ?></span>
        
        <?php if ($templateData['pagination']['has_next']): ?>
            <a href="?page=<?= $page + 1 ?>">Next</a>
        <?php endif; ?>
    </div>
</div>

<style>
    .admin-logs-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 1rem;
    }
    
    .log-table {
        width: 100%;
        border-collapse: collapse;
        margin: 1rem 0;
    }
    
    .log-table th, .log-table td {
        padding: 0.75rem;
        border: 1px solid #ddd;
        text-align: left;
    }
    
    .log-table th {
        background-color: #f5f5f5;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .back-link {
        display: inline-block;
        margin-bottom: 1rem;
    }
</style>

<?php draw_footer(); ?>