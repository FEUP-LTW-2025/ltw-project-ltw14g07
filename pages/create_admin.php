<?php
require_once 'database/connection.db.php';
$db = getDatabaseConnection();

// Generate random admin credentials
$adminEmail = 'admin_' . bin2hex(random_bytes(4)) . '@example.com';
$adminPassword = bin2hex(random_bytes(8)); // Random password

$hashed = password_hash($adminPassword, PASSWORD_DEFAULT);

$stmt = $db->prepare("
    INSERT INTO Users (name, email, password, role) 
    VALUES (?, ?, ?, 'admin')
");
$stmt->execute(['System Admin', $adminEmail, $hashed]);

// Output credentials only once and recommend changing them
echo "Temporary admin credentials created!<br>";
echo "Email: $adminEmail<br>";
echo "Password: $adminPassword<br><br>";
echo "<strong>IMPORTANT:</strong> Change these credentials immediately after first login and delete this file!";