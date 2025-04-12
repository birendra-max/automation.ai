<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require __DIR__ . '/Third-party/vendor/autoload.php';

use Twilio\Rest\Client;

function callClientAndWaitForResult($toNumber)
{
    $account_sid = '';
    $auth_token = '';
    $twilio_number = '';

    $client = new Client($account_sid, $auth_token);

    try {
        $call = $client->calls->create(
            $toNumber,
            $twilio_number,
            [
                'url' => 'https://yourdomain.com/twiml.php?to=' . urlencode($toNumber),
                'record' => true
            ]
        );
    } catch (Exception $e) {
        return ['error' => 'Twilio call failed: ' . $e->getMessage()];
    }

    $callSid = $call->sid;

    // Wait for call completion
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

    // Recording
    $recordings = $client->recordings->read(['callSid' => $callSid]);
    $recordingUrl = null;
    $localPath = null;

    if (!empty($recordings)) {
        $recording = $recordings[0];
        $recordingUrl = 'https://api.twilio.com' . $recording->uri . '.mp3';

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
        return ['error' => 'DB Connection failed: ' . $conn->connect_error];
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

// Handle incoming POST request (form-urlencoded)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['number'])) {
    $toNumber = $_POST['number'];
    $result = callClientAndWaitForResult($toNumber);
    echo json_encode($result);
} else {
    echo json_encode(['error' => 'Invalid request. Phone number missing.']);
}
