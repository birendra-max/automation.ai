<?php
// anotherPage.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $name = $_POST['username'] ?? ''; 
    $email = $_POST['emailid'] ?? ''; 
    
    echo json_encode(['status' => 'success', 'message' => 'Data received!']);
}
?>
