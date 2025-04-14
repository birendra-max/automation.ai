<?php
require __DIR__ . '/Third-party/vendor/autoload.php';

use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

$accountSid = 'AC4ecb1ba7f942506281dbafac19ddd398';
$apiKeySid = 'SKa2ae7ea7201ddfeeaff4bf3cb66201c9';
$apiKeySecret = 'NP70531hpRPOnBpvKZGrCFRUezw65saO';
$twimlAppSid = 'AP18b523b07ca9758eacc1d731c9c5ef7f';

// Optional identity – can be anything unique per user/session
$identity = 'Admin';

// Create access token
$token = new AccessToken(
    $accountSid,
    $apiKeySid,
    $apiKeySecret,
    3600,
    $identity
);

// Grant voice access
$voiceGrant = new VoiceGrant();
$voiceGrant->setOutgoingApplicationSid($twimlAppSid);
$token->addGrant($voiceGrant);

// Return token as JSON
header('Content-Type: application/json');
echo json_encode(['token' => $token->toJWT()]);
