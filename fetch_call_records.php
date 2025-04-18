<?php
require 'inclu/config.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$result = $conn->query("SELECT COUNT(*) as total FROM call_schudle");
$totalRows = $result->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

$stmt = $conn->prepare("SELECT id, phno, lab_name, status FROM call_schudle ORDER BY id asc LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    'id' => $id,
    'records' => $data,
    'totalPages' => $totalPages,
    'currentPage' => $page
]);
