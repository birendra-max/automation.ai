<?php
require 'inclu/config.php';
// IMAP configuration
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX'; // IMAP server (adjust if needed)
$username = 'birendrapradhan112@gmail.com'; // Hostinger email
$password = 'ljlf qysq vurc bocz'; // Email password

// Connect to Hostinger IMAP
$inbox = imap_open($hostname, $username, $password) or die("IMAP Connection Failed: " . imap_last_error());

// Fetch unread emails
$emails = imap_search($inbox, 'UNSEEN');

if ($emails) {
    rsort($emails); // Sort newest to oldest
    foreach ($emails as $email_number) {
        // Fetch email details
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        $message = imap_fetchbody($inbox, $email_number, 1.1);
        if ($message == "") {
            $message = imap_fetchbody($inbox, $email_number, 1);
        }

        // Extract email ID from subject (custom logic)
        $email_id = extractEmailIdFromSubject($overview[0]->subject);

        // Check if email_id is valid
        if ($email_id !== null) {
            $from = extractEmailAddress($overview[0]->from);
            $date = date("Y-m-d H:i:s", strtotime($overview[0]->date));

            // Insert into `replies` table
            $stmt = $conn->prepare("INSERT INTO replies (email_id, user, message, date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $email_id, $from, $message, $date);
            $stmt->execute();
            $stmt->close();

            // Mark email as read
            imap_setflag_full($inbox, $email_number, "\\Seen");
        } else {
            // Log or handle cases where email_id is not found in the subject
            echo "No email ID found for subject: " . $overview[0]->subject . "\n";
        }
    }
} else {
    echo "No unread emails found.\n";
}

// Close IMAP & MySQL connection
imap_close($inbox);
$conn->close();

echo "Replies stored successfully!\n";

// Helper function to extract email ID from subject (custom logic based on your format)
function extractEmailIdFromSubject($subject)
{
    preg_match('/#(\d+)/', $subject, $matches);
    return isset($matches[1]) ? intval($matches[1]) : null; // Return the email ID if found, otherwise null
}

// Helper function to extract email address from "From" field
function extractEmailAddress($from)
{
    // Regex to extract the email address from "Name <email@domain.com>"
    preg_match('/<(.+)>/', $from, $matches);
    return isset($matches[1]) ? $matches[1] : $from; // Return the email address or the full string if not found
}
