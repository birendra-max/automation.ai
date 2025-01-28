<?php
// Email credentials
$hostname = '{imap.hostinger.com:993/imap/ssl}INBOX'; // Update with Hostinger IMAP server details
$username = 'your-email@yourdomain.com'; // Your email address
$password = 'your-password'; // Your email password

// Connect to the IMAP server
$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Hostinger: ' . imap_last_error());

// Get the emails
$emails = imap_search($inbox, 'ALL'); // Search all emails in the inbox

if ($emails) {
    rsort($emails); // Sort emails from newest to oldest

    // Loop through emails
    foreach ($emails as $email_number) {
        // Get email header info
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        $message = imap_fetchbody($inbox, $email_number, 1); // Fetch the email body (plain text)

        // Display email details
        echo "<h3>Subject: " . $overview[0]->subject . "</h3>";
        echo "<p>From: " . $overview[0]->from . "</p>";
        echo "<p>Date: " . $overview[0]->date . "</p>";
        echo "<p>Message: " . nl2br($message) . "</p>";
        echo "<hr>";
    }
} else {
    echo "No emails found.";
}

// Close the connection
imap_close($inbox);
?>
