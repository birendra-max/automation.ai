<?php
session_start();
header('Content-Type: text/xml');

$callerId = '+16505907520';
$to = $_REQUEST['To'] ?? '';

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<Response>
    <?php if (!empty($to)): ?>
        <Dial callerId="<?= htmlspecialchars($callerId) ?>">
            <?php if (preg_match('/^[\d\+\-\(\) ]+$/', $to)): ?>
                <Number><?= htmlspecialchars($to) ?></Number>
            <?php else: ?>
                <Client><?= htmlspecialchars($to) ?></Client>
            <?php endif; ?>
        </Dial>
    <?php else: ?>
        <Dial>
            <!-- This identity must match what your browser client uses -->
            <Client>admin</Client>
        </Dial>
    <?php endif; ?>
</Response>

