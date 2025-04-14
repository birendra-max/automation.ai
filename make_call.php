<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require __DIR__ . '/Third-party/vendor/autoload.php';
require "inclu/config.php"; // assumes $conn is set here

use Twilio\Rest\Client;

function callClientAndWaitForResult($toNumber)
{
    global $conn;

    $account_sid = 'YOUR_TWILIO_SID';
    $auth_token = 'YOUR_TWILIO_AUTH_TOKEN';
    $twilio_number = 'YOUR_TWILIO_PHONE';


    $client = new Client($account_sid, $auth_token);

    try {
        $call = $client->calls->create(
            $toNumber,
            $twilio_number,
            [
                'url' => 'https://4688-27-4-49-79.ngrok-free.app/automation.ai/twiml.php?to=' . urlencode($toNumber),
                'record' => true
            ]
        );
        error_log("Call initiated. SID: {$call->sid}");
    } catch (Exception $e) {
        error_log("Twilio call failed: " . $e->getMessage());
        return ['error' => 'Twilio call failed: ' . $e->getMessage()];
    }

    $callSid = $call->sid;

    // Wait for call to finish
    $maxWait = 60;
    $waited = 0;
    $status = '';

    while ($waited < $maxWait) {
        sleep(3);
        $waited += 3;

        try {
            $call = $client->calls($callSid)->fetch();
            $status = $call->status;
            if (in_array($status, ['completed', 'failed', 'busy', 'no-answer'])) {
                break;
            }
        } catch (Exception $e) {
            error_log("Error fetching call status: " . $e->getMessage());
            break;
        }
    }

    $duration = isset($call->duration) ? $call->duration : '0';
    error_log("Call status: $status, duration: $duration");

    // Recording download
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

        // Use curl to authenticate and download the recording
        $ch = curl_init($recordingUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$account_sid:$auth_token");
        $audio = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($audio !== false && $httpCode === 200) {
            file_put_contents($localPath, $audio);
            error_log("Recording saved to: $localPath");
        } else {
            error_log("Failed to download recording. HTTP Code: $httpCode");
        }
    } else {
        error_log("No recording found for this call.");
    }

    // Save to DB
    if ($conn->connect_error) {
        error_log("DB connection failed: " . $conn->connect_error);
        return ['error' => 'DB connection failed: ' . $conn->connect_error];
    }

    $stmt = $conn->prepare("INSERT INTO call_logs (to_number, call_sid, status, duration, recording_url, date, time) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return ['error' => 'Prepare failed: ' . $conn->error];
    }

    $date = date('Y-m-d');
    $time = date('H:i:s');
    $stmt->bind_param("sssssss", $toNumber, $callSid, $status, $duration, $recordingUrl, $date, $time);

    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        return ['error' => 'Execute failed: ' . $stmt->error];
    }

    $stmt->close();

    $response = [
        'call_sid' => $callSid,
        'status' => $status,
        'duration_seconds' => $duration,
        'recording_url' => $recordingUrl,
        'local_file' => $localPath
    ];

    error_log("Returning: " . json_encode($response));
    return $response;
}

// Handle request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['number'])) {
    $toNumber = $_POST['number'];
    $response = callClientAndWaitForResult($toNumber);
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Invalid request. Phone number missing.']);
}
