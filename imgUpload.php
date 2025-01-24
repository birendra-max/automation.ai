<?php

// Directory where the uploaded files will be stored
$uploadDir = 'uploads/';

// Check if the uploads directory exists, create it if not
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Check if the uploaded file is an image or a file
if ($_FILES) {
    $file = $_FILES['file'] ?? $_FILES['file'];

    // Get the file extension and determine the file type
    $fileName = basename($file['name']);
    $filePath = $uploadDir . $fileName;

    // Check file type
    $fileType = pathinfo($filePath, PATHINFO_EXTENSION);
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'docx', 'zip'];

    // Check if the file type is allowed
    if (in_array($fileType, $allowedTypes)) {
        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            echo json_encode([
                'location' => $filePath  // Return the uploaded file URL to the frontend
            ]);
        } else {
            echo json_encode(['error' => 'File upload failed']);
        }
    } else {
        echo json_encode(['error' => 'Invalid file type']);
    }
}
