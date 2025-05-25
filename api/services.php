<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/service.class.php');

// Enable CORS and set JSON headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

try {
    $db = getDatabaseConnection();
    
    // Get and sanitize search parameters
    $searchTerm = trim($_GET['search'] ?? '');
    $language = trim($_GET['language'] ?? '');
    $field = trim($_GET['field'] ?? '');
    
    // Debug: Log incoming search parameters
    error_log("[SEARCH] Request received - search: '$searchTerm', language: '$language', field: '$field'");
    
    // Get filtered services
    $services = Service::searchServices($db, [
        'search' => $searchTerm,
        'language' => $language,
        'field' => $field,
        'limit' => 100
    ]);
    
    // Debug: Log search results
    error_log("[SEARCH] Found " . count($services) . " services");
    if (count($services) > 0) {
        error_log("[SEARCH] First service ID: " . $services[0]->serviceID);
    }
    
    // Format response with complete service data
    $response = array_map(function($service) use ($db) {
        $languages = $service->getLanguages($db);
        $fields = $service->getFields($db);
        
        // Debug: Log individual service data
        error_log("[SERVICE] ID: {$service->serviceID}, Languages: " . implode(',', $languages) . ", Fields: " . implode(',', $fields));
        
        return [
            'id' => $service->serviceID,
            'title' => $service->title,
            'description' => $service->description,
            'hourlyRate' => $service->hourlyRate,
            'status' => $service->status,
            'languages' => $languages,
            'fields' => $fields,
            'debug' => [  // Additional debug info
                'hasLanguages' => !empty($languages),
                'hasFields' => !empty($fields)
            ]
        ];
    }, $services);
    
    // Add debug info to response
    $response = [
        'success' => true,
        'data' => $response,
        'debug' => [
            'searchTerm' => $searchTerm,
            'serviceCount' => count($services),
            'timestamp' => date('Y-m-d H:i:s')
        ]
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    // Enhanced error logging
    error_log("[ERROR] Database error: " . $e->getMessage());
    error_log("[ERROR] Stack trace: " . $e->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error',
        'debug' => [
            'message' => $e->getMessage(),
            'trace' => $e->getTrace()
        ]
    ]);
} catch (Exception $e) {
    error_log("[ERROR] General error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred',
        'debug' => $e->getMessage()
    ]);
}