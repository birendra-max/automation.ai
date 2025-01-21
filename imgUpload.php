<?php
header('Content-Type: application/json');

// Define allowed file types and size limit (e.g., 5MB)
$allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
$maxFileSize = 5 * 1024 * 1024; // 5MB

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['upload'])) {
    $file = $_FILES['upload'];

    // Validate file size
    if ($file['size'] > $maxFileSize) {
        http_response_code(400);
        echo json_encode(['error' => 'File size exceeds the 5MB limit.']);
        exit;
    }

    // Validate file type
    if (!in_array($file['type'], $allowedFileTypes)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file type. Only JPEG, PNG, and GIF are allowed.']);
        exit;
    }

    // Set upload directory
    $uploadDir = __DIR__ . '/public/img/';
    $uploadPath = $uploadDir . basename($file['name']);

    // Create the uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        echo json_encode(['url' => '/public/img/' . basename($file['name'])]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to upload file.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.']);
}
