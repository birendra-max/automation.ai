<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['emailfile'])) {
        $file = $_FILES['emailfile'];

        // Check for any upload errors
        if ($file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'C:\xampp\htdocs\automation.ai\uploads/';
            $uploadPath = $uploadDir . basename($file['name']);

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                echo json_encode(['message' => 'File uploaded successfully', 'filePath' => $uploadPath]);
            } else {
                echo json_encode(['message' => 'Failed to move the uploaded file.']);
            }
        } else {
            echo json_encode(['message' => 'Error uploading file.', 'error' => $file['error']]);
        }
    } else {
        echo json_encode(['message' => 'No file uploaded.']);
    }
} else {
    echo json_encode(['message' => 'Invalid request method.']);
}
