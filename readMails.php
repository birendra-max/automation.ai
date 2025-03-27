br<?php
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
        // Fetch email details (overview)
        $overview = imap_fetch_overview($inbox, $email_number, 0);

        // Get the full email structure
        $structure = imap_fetchstructure($inbox, $email_number);

        // Extract sender's email
        $from = extractEmailAddress($overview[0]->from);

        // Extract subject and date
        $subject = $overview[0]->subject;
        $date = date("Y-m-d H:i:s", strtotime($overview[0]->date));

        // Extract the email body (plain text or HTML)
        $message = getEmailBody($inbox, $email_number, $structure);

        // Step 3: Insert the reply into the replies table
        $stmt_reply = $conn->prepare("INSERT INTO replies (user, message, date) VALUES (?, ?, ?)");
        $stmt_reply->bind_param("sss", $from, $message, $date);
        $stmt_reply->execute();
        $stmt_reply->close();

        // Mark email as read
        imap_setflag_full($inbox, $email_number, "\\Seen");
    }
} else {
    echo "No unread emails found.\n";
}

// Close IMAP & MySQL connection
imap_close($inbox);
$conn->close();

echo "Replies stored successfully!\n";

// Helper function to extract email address from the "From" field
function extractEmailAddress($from)
{
    // Regex to extract the email address from "Name <email@domain.com>"
    preg_match('/<(.+)>/', $from, $matches);
    return isset($matches[1]) ? $matches[1] : $from; // Return the email address or the full string if not found
}

// Helper function to get the email body (either plain text or HTML)
function getEmailBody($inbox, $email_number, $structure)
{
    $body = "";
    if ($structure->type == 0) {
        $body = imap_fetchbody($inbox, $email_number, 1);
    } else if ($structure->type == 1) {
        $body = imap_fetchbody($inbox, $email_number, 1);
    }
    return $body;
}

