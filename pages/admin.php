<?php
require_once(__DIR__ . '/../template/common.tpl.php');
require_once(__DIR__ . '/../template/admin.tpl.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/category.class.php');
require_once(__DIR__ . '/../database/service.class.php');
require_once(__DIR__ . '/../middleware/admin_auth.php');
ensureAdminAccess();

// Start secure session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_secure' => true,
        'cookie_httponly' => true,
        'use_strict_mode' => true
    ]);
}

global $db;
$currentUser = User::getUserByID($db, $_SESSION['userID']);

// Log admin panel access
User::logSystemAction(
    $db,
    $_SESSION['userID'],
    "Admin panel accessed",
    "Page: " . basename($_SERVER['PHP_SELF']) . " | IP: " . $_SERVER['REMOTE_ADDR']
);

// Handle all admin actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'promote_user':
                $userEmail = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
            
                // Get current admin user (must be logged in)
                $admin = User::getUserByID($db, $_SESSION['userID'] ?? 0);
            
                if (!$admin || $admin->role !== 'admin') {
                    $_SESSION['admin_error'] = "Unauthorized action.";
                    break;
                }
            
                if (!$userEmail) {
                    $_SESSION['admin_error'] = "Invalid email.";
                    break;
                }
            
                // Get the target user by email
                $targetUser = User::getUserByEmail($db, $userEmail);
            
                if (!$targetUser) {
                    $_SESSION['admin_error'] = "User not found.";
                    break;
                }
            
                if (User::promoteToAdmin($db, $targetUser->userID)) {
                    $_SESSION['admin_message'] = "User promoted successfully.";
                } else {
                    $_SESSION['admin_error'] = "Promotion failed.";
                }
                break;
            
            
                
                case 'demote_user':
                    $userID = isset($_POST['demoteUserID']) ? (int)$_POST['demoteUserID'] : 0;
                    
                    $admin = User::getUserByID($db, $_SESSION['userID'] ?? 0);
                    
                    if (!$admin || $admin->role !== 'admin') {
                        $_SESSION['admin_error'] = "Unauthorized action.";
                        break;
                    }
                    
                    // Prevent self-demotion
                    if ($userID === $admin->userID) {
                        $_SESSION['admin_error'] = "You cannot demote yourself";
                        break;
                    }
                    
                    if ($userID <= 0) {
                        $_SESSION['admin_error'] = "Invalid user ID.";
                        break;
                    }
                    
                    if (User::demoteUser($db, $userID)) {
                        User::logSystemAction(
                            $db,
                            $admin->userID,
                            "Demoted admin",
                            "User ID: $userID"
                        );
                        $_SESSION['admin_message'] = "User demoted successfully";
                    } else {
                        $_SESSION['admin_error'] = "Demotion failed";
                    }
                    break;
                
                
            case 'add_category':
                $name = trim($_POST['category_name']);
                $description = trim($_POST['category_description']);
                
                if (!empty($name) && Category::create($db, $name, $description, $currentUser->userID)) {
                    User::logSystemAction(
                        $db,
                        $currentUser->userID,
                        "Added category",
                        "Category: $name"
                    );
                    $_SESSION['admin_message'] = "Category added successfully";
                } else {
                    $_SESSION['admin_error'] = "Failed to add category";
                }
                break;
                
            case 'delete_category':
                // Require password confirmation for sensitive actions
                if (!verifyAdminPassword($db, $currentUser, $_POST['password_confirm'] ?? '')) {
                    $_SESSION['admin_error'] = "Password verification failed";
                    break;
                }
                
                $categoryID = (int)($_POST['category_id'] ?? 0);
                if (Category::delete($db, $categoryID)) {
                    User::logSystemAction(
                        $db,
                        $currentUser->userID,
                        "Deleted category",
                        "Category ID: $categoryID"
                    );
                    $_SESSION['admin_message'] = "Category deleted successfully";
                } else {
                    $_SESSION['admin_error'] = "Failed to delete category";
                }
                break;
                
            case 'update_category':
                $categoryID = (int)($_POST['category_id'] ?? 0);
                $name = trim($_POST['category_name']);
                $description = trim($_POST['category_description']);
                
                if (!empty($name) && Category::update($db, $categoryID, $name, $description)) {
                    User::logSystemAction(
                        $db,
                        $currentUser->userID,
                        "Updated category",
                        "Category ID: $categoryID"
                    );
                    $_SESSION['admin_message'] = "Category updated successfully";
                } else {
                    $_SESSION['admin_error'] = "Failed to update category";
                }
                break;
                
            case 'add_language':
                $name = trim($_POST['language_name']);
                if (!empty($name)) {
                    $stmt = $db->prepare("INSERT INTO Language (language) VALUES (?)");
                    if ($stmt->execute([$name])) {
                        User::logSystemAction(
                            $db,
                            $currentUser->userID,
                            "Added language",
                            "Language: $name"
                        );
                        $_SESSION['admin_message'] = "Language added!";
                    }
                }
                break;

            case 'delete_language':
                // Require password confirmation for sensitive actions
                if (!verifyAdminPassword($db, $currentUser, $_POST['password_confirm'] ?? '')) {
                    $_SESSION['admin_error'] = "Password verification failed";
                    break;
                }
                
                $name = trim($_POST['name']);
                $inUse = $db->prepare("SELECT COUNT(*) FROM ServiceLanguage WHERE language = ?")
                           ->execute([$name])->fetchColumn();
                
                if ($inUse > 0) {
                    $_SESSION['admin_error'] = "Cannot delete - language is in use!";
                } else {
                    if ($db->prepare("DELETE FROM Language WHERE language = ?")->execute([$name])) {
                        User::logSystemAction(
                            $db,
                            $currentUser->userID,
                            "Deleted language",
                            "Language: $name"
                        );
                        $_SESSION['admin_message'] = "Language deleted!";
                    }
                }
                break;

            case 'add_field':
                $name = trim($_POST['field_name']);
                if (!empty($name)) {
                    $stmt = $db->prepare("INSERT INTO Field (field) VALUES (?)");
                    if ($stmt->execute([$name])) {
                        User::logSystemAction(
                            $db,
                            $currentUser->userID,
                            "Added field",
                            "Field: $name"
                        );
                        $_SESSION['admin_message'] = "Field added!";
                    }
                }
                break;

            case 'delete_field':
                // Require password confirmation for sensitive actions
                if (!verifyAdminPassword($db, $currentUser, $_POST['password_confirm'] ?? '')) {
                    $_SESSION['admin_error'] = "Password verification failed";
                    break;
                }
                
                $name = trim($_POST['name']);
                $inUse = $db->prepare("SELECT COUNT(*) FROM ServiceField WHERE field = ?")
                           ->execute([$name])->fetchColumn();
                
                if ($inUse > 0) {
                    $_SESSION['admin_error'] = "Cannot delete - field is in use!";
                } else {
                    if ($db->prepare("DELETE FROM Field WHERE field = ?")->execute([$name])) {
                        User::logSystemAction(
                            $db,
                            $currentUser->userID,
                            "Deleted field",
                            "Field: $name"
                        );
                        $_SESSION['admin_message'] = "Field deleted!";
                    }
                }
                break;
                
            default:
                $_SESSION['admin_error'] = "Invalid action";
        }
    } catch (PDOException $e) {
        User::logSystemAction(
            $db,
            $currentUser->userID,
            "Admin action error",
            "Action: $action | Error: " . $e->getMessage()
        );
        $_SESSION['admin_error'] = "Database error: " . $e->getMessage();
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Helper function to verify admin password for sensitive actions
function verifyAdminPassword(PDO $db, User $admin, string $password): bool {
    if (empty($password)) {
        return false;
    }
    
    $result = password_verify($password, $admin->password);
    if (!$result) {
        User::logSystemAction(
            $db,
            $admin->userID,
            "Failed password verification",
            "IP: " . $_SERVER['REMOTE_ADDR']
        );
    }
    return $result;
}

// Prepare data for the template
$messages = [
    'success' => $_SESSION['admin_message'] ?? null,
    'error' => $_SESSION['admin_error'] ?? null
];

$templateData = [
    'messages' => $messages,
    'admins' => User::getAllAdmins($db),
    'categories' => Category::getAll($db),
    'languages' => $db->query("SELECT * FROM Language ORDER BY language")->fetchAll(),
    'fields' => $db->query("SELECT * FROM Field ORDER BY field")->fetchAll(),
    'stats' => [
        'total_users' => User::getTotalUsers($db),
        'active_services' => Service::getActiveCount($db),
        'total_categories' => Category::getCount($db),
        'total_languages' => $db->query("SELECT COUNT(*) FROM Language")->fetchColumn(),
        'total_fields' => $db->query("SELECT COUNT(*) FROM Field")->fetchColumn()
    ],
    'recent_logs' => User::getRecentLogs($db, 5)
];

unset($_SESSION['admin_message'], $_SESSION['admin_error']);

// Render admin interface
draw_header('admin-dashboard');
draw_admin_controls($templateData);
draw_footer();