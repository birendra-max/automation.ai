<?php
// Set the directory where images will be saved
$uploadDir = 'uploads/';

// Check if the upload directory exists, if not, create it
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_FILES['upload']) {
    $file = $_FILES['upload'];
    $fileName = basename($file['name']);
    $filePath = $uploadDir . $fileName;

    // Ensure the file is an image
    $fileType = mime_content_type($file['tmp_name']);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($fileType, $allowedTypes)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file type. Only JPG, PNG, and GIF files are allowed.']);
        exit;
    }

    // Move the file to the upload directory
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Return the uploaded file's URL
        echo json_encode([
            'url' => 'http://' . $_SERVER['HTTP_HOST'] . '/' . $filePath
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to move the uploaded file.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No file was uploaded.']);
}
