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
  /* Container */
  .admin-logs-container {
    max-width: 1100px;
    margin: 3rem auto;
    padding: 2rem 2.5rem;
    background: #ffffff;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
  }

  h1 {
    font-weight: 700;
    font-size: 2.2rem;
    color: #2c3e50;
    margin-bottom: 1rem;
    letter-spacing: 0.03em;
  }

  /* Back link */
  .back-link {
    display: inline-block;
    margin-bottom: 1.5rem;
    color: #3498db;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
  }
  .back-link:hover {
    color: #1d6fa5;
    text-decoration: underline;
  }

  /* Table */
  .log-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 12px; /* vertical spacing between rows */
    font-size: 1rem;
    box-shadow: 0 0 0 1px #e2e8f0;
  }

  /* Table header */
  .log-table thead tr {
    background-color: #34495e;
    color: #ecf0f1;
    text-transform: uppercase;
    font-size: 0.9rem;
    letter-spacing: 0.05em;
    border-radius: 8px 8px 0 0;
  }
  .log-table th {
    padding: 0.85rem 1rem;
    font-weight: 700;
  }

  /* Table body rows */
  .log-table tbody tr {
    background-color: #f9fafb;
    box-shadow: 0 2px 6px rgb(0 0 0 / 0.05);
    border-radius: 8px;
    transition: background-color 0.3s ease;
  }
  .log-table tbody tr:hover {
    background-color: #e1f0ff;
  }

  .log-table td {
    padding: 0.9rem 1rem;
    vertical-align: middle;
    color: #2d3436;
    border: none;
  }

  /* Pagination */
  .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1.5rem;
    margin-top: 2rem;
    font-weight: 600;
    font-size: 1rem;
  }

  .pagination a {
    padding: 0.5rem 1.25rem;
    border-radius: 6px;
    background-color: #3498db;
    color: #fff;
    text-decoration: none;
    box-shadow: 0 3px 8px rgb(52 152 219 / 0.4);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }
  .pagination a:hover {
    background-color: #1d6fa5;
    box-shadow: 0 4px 12px rgb(29 111 165 / 0.6);
  }

  .pagination span {
    color: #555;
  }
</style>


<?php draw_footer(); ?>