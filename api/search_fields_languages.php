<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/field.class.php');
require_once(__DIR__ . '/../database/language.class.php');

$db = getDatabaseConnection();

$searchTerm = $_GET['search'] ?? '';
$type = $_GET['type'] ?? 'all'; // 'fields', 'languages', or 'all'

// Search fields and/or languages based on type
$results = [];

if ($type === 'all' || $type === 'fields') {
    $fields = Field::searchFields($db, $searchTerm);
    $results['fields'] = $fields;
}

if ($type === 'all' || $type === 'languages') {
    $languages = Language::searchLanguages($db, $searchTerm);
    $results['languages'] = $languages;
}

header('Content-Type: application/json');
echo json_encode($results);
?>