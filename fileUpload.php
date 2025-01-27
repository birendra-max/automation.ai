<?php

// Check if a file has been uploaded
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    // Set the upload directory (change this path to your preferred folder)
    $uploadDir = 'uploads/';

    // Create the upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Get the file name and path
    $fileName = basename($_FILES['file']['name']);
    $filePath = $uploadDir . $fileName;
    // Move the uploaded file to the desired directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
        // Respond with the file URL (this will be inserted inside Summernote)
        $response = array('fileUrl' => $filePath);
        echo json_encode($response);
    } else {
        // File upload failed
        echo json_encode(array('error' => 'File upload failed.'));
    }
} else {
    // No file uploaded or error occurred
    echo json_encode(array('error' => 'No file uploaded or upload error.'));
}
