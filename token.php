<?php
require __DIR__ . '/Third-party/vendor/autoload.php';

use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

$accountSid = '';
$apiKeySid = '';
$apiKeySecret = '';
$twimlAppSid = '';

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
