<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/Third-party/vendor/autoload.php';

use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

// Use fallback identity if session value is not set
$identity = $_SESSION['user_details']['role'] ?? 'anonymous';

// ✅ Use the actual strings (not getenv)
$accountSid     = '';
$apiKeySid      = '';
$apiKeySecret   = '';
$twimlAppSid    = '';

// Generate the token
$token = new AccessToken(
    $accountSid,
    $apiKeySid,
    $apiKeySecret,
    3600,
    $identity
);

$voiceGrant = new VoiceGrant();
$voiceGrant->setOutgoingApplicationSid($twimlAppSid);
$voiceGrant->setIncomingAllow(true);

$token->addGrant($voiceGrant);

// Output token
header('Content-Type: application/json');
echo json_encode([
    'identity' => $identity,
    'token' => $token->toJWT()
]);
