<?php
// Include database configuration file
require 'inclu/config.php';

// Check for a successful database connection
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

<<<<<<< HEAD
// Updated query to ensure both SELECT statements have the same columns and structure
$query = "
    SELECT id, email, subject, message, 'Sent' AS status, date_sent AS date FROM successful_emails
";
=======
// Get the current page and rows per page from the query parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$rowsPerPage = isset($_GET['rowsPerPage']) ? (int)$_GET['rowsPerPage'] : 10;

// Calculate the starting index for pagination
$startIndex = ($page - 1) * $rowsPerPage;

// Query to fetch email data based on pagination
$query = "SELECT id, email, subject, message, status, DATE_FORMAT(date_sent, '%Y-%m-%d %H:%i:%s') as date
          FROM successful_emails
          ORDER BY date_sent DESC
          LIMIT $startIndex, $rowsPerPage";
>>>>>>> 716e5f61ed08a7f3b8c1112f6968cc831b0e4470

$result = $conn->query($query);

$data = [];
if ($result->num_rows > 0) {
    // Fetch all the email rows and add to the data array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Query to get the total number of emails (for pagination)
$totalQuery = "SELECT COUNT(*) AS total FROM successful_emails";
$totalResult = $conn->query($totalQuery);
$totalEmails = 0;

if ($totalResult->num_rows > 0) {
    $totalRow = $totalResult->fetch_assoc();
    $totalEmails = $totalRow['total'];
}

// Close the database connection
$conn->close();

// Prepare response with data and pagination details
$response = [
    'emails' => $data,
    'totalEmails' => $totalEmails,
    'totalPages' => ceil($totalEmails / $rowsPerPage),
    'currentPage' => $page
];

// Return the data as JSON
echo json_encode($response);
