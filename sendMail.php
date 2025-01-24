<?php
require 'Third-party/vendor/autoload.php';
require 'inclu/config.php';
require 'inclu/Mailer.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract form data
    $emails = $_POST['emailid'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['editor'] ?? ''; // Adjust field name if needed

    // Validate data
    if (empty($emails) || empty($subject) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Split emails by comma and trim whitespace
    $emailArray = array_map('trim', explode(',', $emails));

    // Email sending logic
    $errors = [];
    foreach ($emailArray as $email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mailSent = sendEmail($email, '', $subject, $message, '');
            if (!$mailSent) {
                $errors[] = $email;
            }
        } else {
            $errors[] = $email;
        }
    }

    // Prepare response
    if (empty($errors)) {
        echo json_encode(['status' => 'success', 'message' => 'Emails sent successfully.']);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to send emails to some recipients.',
            'failed_emails' => $errors
        ]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
