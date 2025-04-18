<?php
include "inclu/config.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing ID']);
        exit;
    }

    $dt = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("UPDATE call_schudle SET status = 'Completed', last_updated_date = ? WHERE id = ?");
    $stmt->bind_param("si", $dt, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update status']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
}
