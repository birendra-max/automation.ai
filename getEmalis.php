<?php

require 'inclu/config.php';
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Updated query to ensure both SELECT statements have the same columns and structure
$query = "
    SELECT id, email, subject, message, 'Sent' AS status, date_sent AS date FROM successful_emails order by date_sent
";

$result = $conn->query($query);

$data = [];

if ($result->num_rows > 0) {
    // Fetch all rows
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Close connection
$conn->close();

// Return the fetched data as JSON
echo json_encode($data);
