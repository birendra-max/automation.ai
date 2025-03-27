<?php
require 'inclu/config.php'; // Assuming $conn is initialized in this file

// IMAP configuration
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX'; // IMAP server (adjust if needed)
$username = 'devansh@dentigolab.com'; // Hostinger email
$password = 'keca jazv uagc wcuy'; // Email password

try {
    // Connect to Hostinger IMAP
    $inbox = @imap_open($hostname, $username, $password);
    
    if (!$inbox) {
        throw new Exception("IMAP Connection Failed: " . imap_last_error());
    }

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

            // Insert the email details into the database
            $stmt_reply = $conn->prepare("INSERT INTO replies (user, message, date) VALUES (?, ?, ?)");
            $stmt_reply->bind_param("sss", $from, $message, $date);
            $stmt_reply->execute();
            $stmt_reply->close();

            // Mark email as read
            imap_setflag_full($inbox, $email_number, "\\Seen");
        }

        echo "Replies stored successfully!\n";
    } else {
        echo "No unread emails found.\n";
    }

    // Close IMAP & MySQL connection
    imap_close($inbox);
    $conn->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    imap_close($inbox);  // Make sure to close the IMAP connection even in case of error
    $conn->close();
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

    // Check if the email is plain text or HTML or multipart
    if ($structure->type == 0) {
        // Simple text/plain or text/html email
        $body = imap_fetchbody($inbox, $email_number, 1);
    } elseif ($structure->type == 1) {
        // Multipart (text + attachments)
        foreach ($structure->parts as $partNumber => $part) {
            if ($part->subtype == 'PLAIN') {
                $body = imap_fetchbody($inbox, $email_number, $partNumber + 1);
            } elseif ($part->subtype == 'HTML') {
                $body = imap_fetchbody($inbox, $email_number, $partNumber + 1);
            }
        }
    }
    
    return $body;
}
?>
