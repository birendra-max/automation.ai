<?php
date_default_timezone_set('Asia/Calcutta');

require 'Third-party/vendor/autoload.php';
require 'inclu/config.php';
require 'inclu/Mailer.php';

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emails = $_POST['emailid'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['emailbody'] ?? '';

    if (empty($emails) || empty($subject) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }


    $emailArray = array_map('trim', explode(',', $emails));

    $totalEmails = count($emailArray);
    $sentCount = 0;
    $failedCount = 0;
    $errors = [];

    $currentTime = date('Y-m-d h:i:s A');

    $mailSent = sendEmail($emailArray, $subject, $message, '');



    foreach ($emailArray as $email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($mailSent) {
                $stmt = $conn->prepare("INSERT INTO successful_emails (email, subject, message, date_sent) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $email, $subject, $message, $currentTime);
                if (!$stmt->execute()) {
                    $errors[] = $email . ' (DB Error: ' . $stmt->error . ')';
                }
                $stmt->close();
                $sentCount++;
            } else {
                $failedCount++;
                $errors[] = $email;
                $stmt = $conn->prepare("INSERT INTO failed_emails (email, subject, message, error_message, date_sent) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $email, $subject, $message, 'Mail sending failed', $currentTime);
                if (!$stmt->execute()) {
                    $errors[] = $email . ' (DB Error: ' . $stmt->error . ')';
                }
                $stmt->close();
            }
        } else {
            $failedCount++;
            $errors[] = $email;
            $stmt = $conn->prepare("INSERT INTO failed_emails (email, subject, message, error_message, date_failed) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $email, $subject, $message, 'Invalid email format', $currentTime);
            if (!$stmt->execute()) {
                $errors[] = $email . ' (DB Error: ' . $stmt->error . ')';
            }
            $stmt->close();
        }
    }

    if (empty($errors)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Emails sent successfully.',
            'total_emails' => $totalEmails,
            'sent_emails' => $sentCount,
            'failed_emails' => $failedCount
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to send emails to some recipients.',
            'total_emails' => $totalEmails,
            'sent_emails' => $sentCount,
            'failed_emails' => $failedCount,
            'failed_email_list' => $errors
        ]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
