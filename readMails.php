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

        // Fetch attachments (if any)
        $attachments = getEmailAttachments($inbox, $email_number, $structure);

        // Insert into the `replies` table
        $stmt = $conn->prepare("INSERT INTO replies (email_id, user, message, date, attachments) VALUES (?, ?, ?, ?, ?)");
        $attachments_json = json_encode($attachments); // Save attachments as JSON
        $stmt->bind_param("issss", $email_id, $from, $message, $date, $attachments_json);
        $stmt->execute();
        $stmt->close();

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

// Helper function to extract email ID from subject (custom logic based on your format)
function extractEmailIdFromSubject($subject)
{
    preg_match('/#(\d+)/', $subject, $matches);
    return isset($matches[1]) ? intval($matches[1]) : null; // Return the email ID if found, otherwise null
}

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
    if ($structure->type == 0) { // Text or HTML message
        $body = imap_fetchbody($inbox, $email_number, 1); // Default to part 1 (text or HTML)
    } else if ($structure->type == 1) { // Text message
        $body = imap_fetchbody($inbox, $email_number, 1); // Text part
    }
    return $body;
}

// Helper function to extract attachments from email
function getEmailAttachments($inbox, $email_number, $structure)
{
    $attachments = [];

    if (isset($structure->parts) && count($structure->parts)) {
        foreach ($structure->parts as $index => $part) {
            if ($part->disposition && $part->disposition == 'ATTACHMENT') {
                // This part is an attachment, so fetch it
                $attachment = [];
                $attachment['filename'] = $part->dparameters[0]->value;
                $attachment['data'] = imap_fetchbody($inbox, $email_number, $index + 1);
                // Decode the attachment if necessary
                if ($part->encoding == 3) { // Base64 encoding
                    $attachment['data'] = base64_decode($attachment['data']);
                } elseif ($part->encoding == 4) { // Quoted-printable encoding
                    $attachment['data'] = quoted_printable_decode($attachment['data']);
                }
                $attachments[] = $attachment;
            }
        }
    }
    return $attachments;
}
