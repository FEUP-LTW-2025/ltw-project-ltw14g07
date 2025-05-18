<?php
require_once(__DIR__ . '/../template/common.tpl.php');
require_once(__DIR__ . '/../template/admin.tpl.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/category.class.php'); // New Category class
require_once(__DIR__ . '/../database/service.class.php');  // For service statistics
require_once(__DIR__ . '/../middleware/admin_auth.php');
ensureAdminAccess();

session_start();

// Authentication
if (!isset($_SESSION['userID'])) {
    header("Location: /pages/signup.php?q=l");
    exit();
}

global $db;
$currentUser = User::getUserByID($db, $_SESSION['userID']);

if (!$currentUser || !$currentUser->isAdmin()) {
    header("Location: /pages/signup.php?q=l");
    exit();
}

// Handle all admin actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'promote_user':
                $userEmail = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
                $targetUser = User::getUserByEmail($db, $userEmail);
                
                if ($targetUser && User::promoteToAdmin($db, $targetUser->userID)) {
                    User::logSystemAction(
                        $db, 
                        $currentUser->userID, 
                        "Promoted user to admin", 
                        "Promoted: " . $targetUser->email
                    );
                    $_SESSION['admin_message'] = "User promoted successfully";
                } else {
                    $_SESSION['admin_error'] = "User not found or already admin";
                }
                break;
                
            case 'demote_user':
                $userID = (int)($_POST['user_id'] ?? 0);
                if ($userID !== $currentUser->userID && User::demoteUser($db, $userID)) {
                    User::logSystemAction(
                        $db,
                        $currentUser->userID,
                        "Demoted admin",
                        "User ID: $userID"
                    );
                    $_SESSION['admin_message'] = "User demoted successfully";
                } else {
                    $_SESSION['admin_error'] = "Cannot demote yourself or operation failed";
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
                    // Add to your POST handler switch case:
case 'add_language':
    $name = trim($_POST['language_name']);
    if (!empty($name)) {
        $stmt = $db->prepare("INSERT INTO Language (language) VALUES (?)");
        if ($stmt->execute([$name])) {
            $_SESSION['admin_message'] = "Language added!";
        }
    }
    break;

case 'delete_language':
    $name = trim($_POST['name']);
    // First check if used in any service
    $inUse = $db->prepare("SELECT COUNT(*) FROM ServiceLanguage WHERE language = ?")
               ->execute([$name])->fetchColumn();
    
    if ($inUse > 0) {
        $_SESSION['admin_error'] = "Cannot delete - language is in use!";
    } else {
        $db->prepare("DELETE FROM Language WHERE language = ?")->execute([$name]);
        $_SESSION['admin_message'] = "Language deleted!";
    }
    break;

case 'add_field':
    $name = trim($_POST['field_name']);
    if (!empty($name)) {
        $stmt = $db->prepare("INSERT INTO Field (field) VALUES (?)");
        if ($stmt->execute([$name])) {
            $_SESSION['admin_message'] = "Field added!";
        }
    }
    break;

case 'delete_field':
    $name = trim($_POST['name']);
    // Check if used in any service
    $inUse = $db->prepare("SELECT COUNT(*) FROM ServiceField WHERE field = ?")
               ->execute([$name])->fetchColumn();
    
    if ($inUse > 0) {
        $_SESSION['admin_error'] = "Cannot delete - field is in use!";
    } else {
        $db->prepare("DELETE FROM Field WHERE field = ?")->execute([$name]);
        $_SESSION['admin_message'] = "Field deleted!";
    }
    break;
                
            default:
                $_SESSION['admin_error'] = "Invalid action";
        }
    } catch (PDOException $e) {
        $_SESSION['admin_error'] = "Database error: " . $e->getMessage();
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
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
?>