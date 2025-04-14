<?php
header('Content-Type: text/xml');
require __DIR__ . '/Third-party/vendor/autoload.php';

use Twilio\TwiML\VoiceResponse;

$response = new VoiceResponse();

if (isset($_POST['To'])) {
    $dial = $response->dial('', ['callerId' => '+16505907520']);
    $dial->number($_POST['To']);
} else {
    $response->say("Thank you for calling.");
}

echo $response;
