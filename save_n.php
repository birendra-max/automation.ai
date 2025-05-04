<?php
include 'inclu/config.php';

if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed.";
    exit;
}

$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';

if ($phone === '' || $name === '') {
    http_response_code(400);
    echo "Phone number and name are required.";
    exit;
}

$stmt = $conn->prepare("INSERT INTO saved_numbers (name, phone) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $phone);

if ($stmt->execute()) {
    echo "Number saved successfully.";
} else {
    http_response_code(500);
    echo "Failed to save number.";
}

$stmt->close();
$conn->close();
