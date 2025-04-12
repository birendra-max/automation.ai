<?php
header('Content-Type: text/xml');

$to = $_GET['to'] ?? null;

if (!$to) {
    echo '<Response><Say>No number provided.</Say></Response>';
    exit;
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
    <Dial><?php echo htmlspecialchars($to); ?></Dial>
</Response>
