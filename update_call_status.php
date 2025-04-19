<?php
include 'inclu/config.php'; // include your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $number = $_POST['phone_number'];

    // Update call status
    $query = "UPDATE call_schedule SET status = 'Completed' WHERE phno = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $number);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update']);
    }
}
