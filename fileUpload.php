<?php

if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($_FILES['file']['name']);
    $filePath = $uploadDir . $fileName;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
        $response = array('fileUrl' => $filePath);
        echo json_encode($response);
    } else {
        echo json_encode(array('error' => 'File upload failed.'));
    }
} else {
    echo json_encode(array('error' => 'No file uploaded or upload error.'));
}
