<?php
require 'inclu/config.php';

// Get the page number (default is 1) and records per page (limit)
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;  // Default limit is 10
$offset = ($page - 1) * $limit;  // Calculate the offset for SQL query

// Get the total count of records
$result = $conn->query("SELECT COUNT(*) as total FROM call_schudle");
if (!$result) {
    // Handle query failure
    echo json_encode([
        'error' => 'Failed to fetch total record count'
    ]);
    exit;
}

$totalRows = $result->fetch_assoc()['total'];  // Total number of records
$totalPages = ceil($totalRows / $limit);  // Calculate total number of pages

// Query to fetch the records for the current page
$stmt = $conn->prepare("SELECT id, phno, lab_name, status FROM call_schudle ORDER BY id ASC LIMIT ? OFFSET ?");
if ($stmt === false) {
    // Handle prepare statement failure
    echo json_encode([
        'error' => 'Failed to prepare query'
    ]);
    exit;
}

// Bind parameters and execute the query
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$res = $stmt->get_result();

// Fetch the data
$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

// Set the Content-Type header for JSON response
header('Content-Type: application/json');

// Return the data as JSON
echo json_encode([
    'records' => $data,        // The records for the current page
    'totalCount' => $totalRows, // Total records count (for pagination purposes)
    'currentPage' => $page,     // The current page
    'totalPages' => $totalPages // Total pages based on records and limit
]);

// Close the statement and the connection
$stmt->close();
$conn->close();
?>
