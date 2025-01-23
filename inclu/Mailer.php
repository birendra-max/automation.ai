<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Third-Party/vendor/autoload.php';

function sendEmail($recipientEmail, $recipientName, $subject, $htmlBody, $altBody)
{
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'admin@bravodentdesigns.com';
        $mail->Password = 'Admin@new@pass@3214';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('admin@bravodentdesigns.com', 'BravoDent Design Admin');
        $mail->addAddress($recipientEmail, $recipientName);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = $altBody;

        $mail->send();
        return 'Email sent successfully!';
    } catch (Exception $e) {
        return "Failed to send email. Error: {$mail->ErrorInfo}";
    }
}

// Example of how to use the function
// $response = sendEmail('info@bravodentdesigns.com', 'Admin', 'Test Email via PHPMailer', '<h1>Hello, World!</h1><p>This is a test email sent using PHPMailer.</p>', 'This is the plain text version of the email content.');
// echo $response;
