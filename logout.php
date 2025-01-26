<?php
session_start();
include 'inclu/config.php';
date_default_timezone_set('Asia/Calcutta');

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $inactive = 0;
    $stmt = $conn->prepare("UPDATE users SET is_active = ? WHERE email = ?");
    $stmt->bind_param("is", $inactive, $email);

    if ($stmt->execute()) {
        session_unset();
        session_destroy();

        // Redirect to a page that includes JavaScript to clear localStorage and then redirect
        echo "<script>
            localStorage.removeItem('userDetails');  // Clear user details from localStorage
            window.location.href = 'index.php';  // Redirect to the homepage after logout
        </script>";
        exit;
    } else {
        $_SESSION['error'] = 'There was an issue logging you out. Please try again.';
        header("Location: dashboard.php");
        exit;
    }

    $stmt->close();
} else {
    header('Location: index.php');
    exit;
}

$conn->close();
