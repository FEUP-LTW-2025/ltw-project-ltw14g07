<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/service.class.php');

header('Content-Type: application/json');

try {
    $db = getDatabaseConnection();
    $services = Service::getAllServices($db, 100); // Adjust limit as needed
    
    // Format for JSON response
    $response = array_map(function($service) {
        return [
            'id' => $service->serviceID,
            'title' => $service->title,
            'description' => $service->description,
            'hourlyRate' => $service->hourlyRate,
            'status' => $service->status,
            'tags' => $service->fields // Assuming fields act as tags
        ];
    }, $services);
    
    echo json_encode($response);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}