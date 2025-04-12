<!-- Makes a call using Twilio.

Waits until the call is completed.

Retrieves the recording.

Saves the .mp3 file to a local /recordings/ directory.

Stores the call details (number, SID, status, duration, recording URL, date/time) in a MySQL database.

Returns a JSON response. -->


<?php
require __DIR__ . '/Third-party/vendor/autoload.php';

use Twilio\Rest\Client;

function callClientAndWaitForResult($toNumber)
{
    // Twilio credentials
    $account_sid = 'YOUR_TWILIO_SID';
    $auth_token = 'YOUR_TWILIO_AUTH_TOKEN';
    $twilio_number = 'YOUR_TWILIO_PHONE';

    $client = new Client($account_sid, $auth_token);

    // Make the outbound call
    $call = $client->calls->create(
        $toNumber,
        $twilio_number,
        [
            'url' => 'https://yourdomain.com/twiml.xml',
            'record' => true
        ]
    );

    $callSid = $call->sid;

    // Wait for the call to complete
    $maxWait = 60;
    $waited = 0;
    $status = '';
    while ($waited < $maxWait) {
        sleep(3);
        $waited += 3;

        $call = $client->calls($callSid)->fetch();
        $status = $call->status;

        if (in_array($status, ['completed', 'failed', 'busy', 'no-answer'])) {
            break;
        }
    }

    // Fetch recording
    $recordings = $client->recordings->read(['callSid' => $callSid]);
    $recordingUrl = null;
    $localPath = null;

    if (!empty($recordings)) {
        $recording = $recordings[0];
        $recordingUrl = 'https://api.twilio.com' . $recording->uri . '.mp3';

        // Save recording to /recordings folder
        $saveDir = __DIR__ . '/recordings/';
        if (!is_dir($saveDir)) {
            mkdir($saveDir, 0755, true);
        }

        $timestamp = date('Ymd_His');
        $localPath = $saveDir . "call_{$callSid}_{$timestamp}.mp3";

        $audio = file_get_contents($recordingUrl);
        if ($audio) {
            file_put_contents($localPath, $audio);
        }
    }

    // Save to MySQL
    $conn = new mysqli("localhost", "db_user", "db_pass", "your_database");

    if ($conn->connect_error) {
        die("DB Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO call_logs (to_number, call_sid, status, duration, recording_url, date, time) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $date = date('Y-m-d');
    $time = date('H:i:s');
    $duration = isset($call->duration) ? $call->duration : null;
    $stmt->bind_param("sssssss", $toNumber, $callSid, $status, $duration, $recordingUrl, $date, $time);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    return [
        'call_sid' => $callSid,
        'status' => $status,
        'duration_seconds' => $duration,
        'recording_url' => $recordingUrl,
        'local_file' => $localPath
    ];
}

// Test call
header('Content-Type: application/json');
// echo json_encode(callClientAndWaitForResult('+15558675310'));



/*CREATE TABLE call_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    to_number VARCHAR(20),
    call_sid VARCHAR(50),
    status VARCHAR(20),
    duration VARCHAR(10),
    recording_url TEXT,
    date DATE,
    time TIME
);*/
