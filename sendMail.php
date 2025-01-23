<?php
require 'Third-party/vendor/autoload.php';
require 'inclu/config.php';
require 'inclu/Mailer.php';

$result = $conn->query("SELECT * FROM mail_save");

$resp = [];
$i = 0;
while ($row = $result->fetch_assoc()) {
    $email = $row['email'];
    $name = $row['name'];
    $subject = $row['subject'];
    $prompt = $row['prompt'];
    $resp[$i] = sendEmail($email, $name, $subject, $prompt, '');
    $i++;
}

if ($result->num_rows == $i) {
    $j = 0;
    while ($ro = $result->fetch_assoc()) {
        $stmt = $conn->prepare("INSERT INTO mailautomationai (email, name, subject, prompt) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $ro['email'], $ro['name'], $ro['subject'], $ro['prompt']);
        $stmt->execute();
        $j++;
    }

    if ($i == $j) {
        echo json_encode(['status' => 'success', 'message' => 'Email Send Successfully']);
    }
} else {
    $stmt = $conn->prepare("delete from mail_save");
    $stmt->execute();
    echo json_encode(['status' => 'error', 'message' => 'Failed to send mail']);
}

$stmt->close();
