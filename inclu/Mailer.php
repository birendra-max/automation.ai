<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/opt/lampp/htdocs/automation.ai/Third-party/vendor/autoload.php';

function sendEmail($emails, $subject, $htmlBody, $altBody)
{
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'devansh@dentigolab.com';
        $mail->Password = 'keca jazv uagc wcuy';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('devansh@dentigolab.com', 'Dr.Devansh Thakur');

        // foreach ($emails as $email) {
        //     if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //         $mail->addBCC($email);
        //     }
        // }

        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mail->addAddress($email);
            }
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = $altBody;

        // Send the email
        if ($mail->send()) {
            return 'Emails sent successfully!';
        } else {
            return 'Failed to send emails.';
        }
    } catch (Exception $e) {
        return "Error: {$mail->ErrorInfo}";
    }
}

// Example of how to use the function
// You can call this function by passing an array of emails
// $emails = ['birendrapradhan112@gmail.com'];
// $response = sendEmail($emails, 'Test Subject', '<h1>Hello, World!</h1><p>This is a test email sent using PHPMailer.</p>', 'This is the plain text version of the email content.');
// echo $response;
