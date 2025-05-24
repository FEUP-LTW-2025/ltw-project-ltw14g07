<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

// Start session and get current user
session_start();
$db = getDatabaseConnection();
$currentUser = null;
if (isset($_SESSION['userID']) && is_int($_SESSION['userID'])) {
    try {
        $currentUser = User::getUserByID($db, $_SESSION['userID']);
    } catch (Exception $e) {
        error_log("User lookup failed: " . $e->getMessage());
        session_unset(); // Clear invalid session
    }
}
?>

<?php function draw_header($id) {
    global $currentUser; ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Placeholder</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/layout.css">
        <link rel="stylesheet" href="../css/responsive.css">
        <?php if (strpos($id, 'admin-') === 0): ?>
            <link rel="stylesheet" href="../css/admin.css?v=<?= filemtime('../css/admin.css') ?>">
        <?php endif; ?>
    </head>
    <body>
        <header>
            <div>
                <h1><a href="index.php">Placeholder</a></h1>
                <section class="spaced">
                    <h3>Let programmers work for you</h3>

                    <!-- Integrated Search Bar -->
                    <div class="search-container">
                        <input type="text" id="serviceSearch" placeholder="Search services, fields, languages..." class="search-input">
                        
                        <!-- Advanced Filters Dropdown -->
                        <div id="advancedFilters" style="display:none;">
                            <select id="filterLanguage" class="filter-dropdown">
                                <option value="">All Languages</option>
                                <?php
                                if (isset($currentUser) && class_exists('Filters')) {
                                    $filters = Filters::getAllFilters(getDatabaseConnection());
                                    foreach ($filters->languages as $language): ?>
                                        <option value="<?= htmlspecialchars($language) ?>"><?= htmlspecialchars($language) ?></option>
                                    <?php endforeach;
                                }
                                ?>
                            </select>
                            <select id="filterField" class="filter-dropdown">
                                <option value="">All Fields</option>
                                <?php
                                if (isset($currentUser) && class_exists('Filters')) {
                                    foreach ($filters->fields as $field): ?>
                                        <option value="<?= htmlspecialchars($field) ?>"><?= htmlspecialchars($field) ?></option>
                                    <?php endforeach;
                                }
                                ?>
                            </select>
                        </div>
                        
                        <button id="searchButton" class="green-button">Search</button>
                        <button id="toggleFilters" class="filter-button">Filters</button>
                    </div>

                    <div id="signup">
                        <?php if ($currentUser): ?>
                            <span class="user-greeting">Hello, <?= htmlspecialchars($currentUser->name) ?>
                                <?php if ($currentUser->isAdmin()): ?>
                                    <span class="admin-badge">ADMIN</span>
                                <?php endif; ?>
                            </span>
                            <?php if ($currentUser->isAdmin()): ?>
                                <a href="/pages/admin.php" class="admin-link">Admin Panel</a>
                            <?php endif; ?>
                            <a href="/pages/profile.php">Profile</a>
                            <a href="/action/actionLogout.php">Logout</a>
                        <?php else: ?>
                            <a href="/pages/signup.php?q=r">Register</a>
                            <a href="/pages/signup.php?q=l">Login</a>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </header>

        <main id="<?= $id ?>">
            <!-- Services list will be dynamically populated here by search.js -->
            <div id="servicesList" class="pin-board"></div>
<?php } ?>

<?php function draw_footer() { ?>
        </main>
        <footer>
            <div class="footer-links">
                <a href='/pages/createService.php'>Create Service</a>
                <a href="/pages/index.php">Home</a>
                <a href="/pages/profile.php">Profile</a>
                <?php global $currentUser; ?>
                <?php if ($currentUser && $currentUser->isAdmin()): ?>
                    <a href="/pages/admin.php">Admin Dashboard</a>
                <?php endif; ?>
            </div>
        </footer>
        <script src="/js/search.js"></script>
    </body>
    </html>
<?php } ?>