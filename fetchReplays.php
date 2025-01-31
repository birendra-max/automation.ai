<?php
require 'inclu/config.php'; // Ensure DB connection is included

// Query to fetch replies
$sql = "SELECT id, user, message, date FROM replies ORDER BY date ASC";

$result = $conn->query($sql);

$replies = [];
while ($row = $result->fetch_assoc()) {
    $emailId = $row['id'];

    if (!isset($replies[$emailId])) {
        $replies[$emailId] = [];
    }

    $replies[$emailId][] = [
        'message' => $row['message'],
        'date' => $row['date']
    ];
}

// Return replies as JSON
header('Content-Type: application/json');
echo json_encode($replies);
?>
