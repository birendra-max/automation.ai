<?php

require 'Third-party/vendor/autoload.php';
require 'inclu/config.php';
require 'inclu/Mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the updated data is provided
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        // Get the updated data from the request
        $id = $_POST['id']; // Item ID to identify which record to update
        $email = $_POST['email'];
        $name = $_POST['name'];
        $subject = $_POST['subject'];
        $prompt = $_POST['prompt'];

        // Prepare the SQL UPDATE query
        $stmt = $conn->prepare("UPDATE mailautomationai SET email = ?, name = ?, subject = ?, prompt = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $email, $name, $subject, $prompt, $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
