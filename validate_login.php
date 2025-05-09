<?php
session_start();
include 'inclu/config.php';
date_default_timezone_set('Asia/Calcutta');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    // Check if the user exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if ($password == $user['password_hash']) {
            $uniqueId = str_pad(mt_rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT);
            $_SESSION['email'] = $_POST['email'];

            $_SESSION['user_details'] = array(
                'email' => $_POST['email'],
                'login_id' => $uniqueId,
                "first_name" => $user['first_name'],
                'last_name' => $user['last_name'],
                'role' => $user['role'],
                'user_access' => $user['user_access']
            );

            // Update the user's active status and last login time
            $active = 1;
            $last_login = date('Y-m-d H:i:s');
            $stmt = $conn->prepare("UPDATE users SET is_active = ?, last_login = ? WHERE email = ?");
            $stmt->bind_param("iss", $active, $last_login, $_POST['email']);

            if ($stmt->execute()) {
                $login_datetime = date('Y-m-d H:i:s');
                $login_date = date('Y-m-d');
                $login_time = date('Y-m-d H:i:s');

                $ip_address = $_SERVER['REMOTE_ADDR'];
                $user_agent = $_SERVER['HTTP_USER_AGENT'];

                $stmt = $conn->prepare("INSERT INTO login_history (user_email, login_id, login_date, login_time, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)");

                $stmt->bind_param("ssssss", $_POST['email'], $uniqueId, $login_date, $login_time, $ip_address, $user_agent);
                $stmt->execute();

                if ($user['role'] == 'admin') {
                    echo "<script>
                    const userDetails = " . json_encode($_SESSION['user_details']) . ";
                    localStorage.setItem('userDetails', JSON.stringify(userDetails));
                    window.location.href = 'dashboard.php'; // Redirect to the dashboard
                </script>";
                } else if ($_SESSION['user_details']['role'] == 'user' && $_SESSION['user_details']['user_access'] == 'call_schudle') {
                    echo "<script>
                    const userDetails = " . json_encode($_SESSION['user_details']) . ";
                    localStorage.setItem('userDetails', JSON.stringify(userDetails));
                    window.location.href = 'calling.php'; // Redirect to the dashboard
                </script>";
                } else {
                    echo "<script>
                    const userDetails = " . json_encode($_SESSION['user_details']) . ";
                    localStorage.setItem('userDetails', JSON.stringify(userDetails));
                    window.location.href = 'mailai.php'; // Redirect to the dashboard
                </script>";
                }
                exit;
            } else {
                $_SESSION['error'] = 'There was an issue updating your login information.';
                header("Location: index.php");
                exit;
            }
        } else {
            $_SESSION['error'] = 'Invalid username or password!';
            header("Location: index.php");
            exit;
        }
    } else {
        $_SESSION['error'] = 'Invalid username or password!';
        header("Location:index.php");
    }

    $stmt->close();
}
$conn->close();
