<?php
// anotherPage.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['username'] ?? '';   // Example for name-0 field
    $email = $_POST['emailid'] ?? ''; 
    
    // Process the data (e.g., save to database, send email, etc.)
    
    // Respond back (e.g., send a success message)
    echo json_encode(['status' => 'success', 'message' => 'Data received!']);
}
?>
