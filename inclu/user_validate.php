<?php
session_start();
function user_validate($path)
{
    if (isset($_SESSION['login_id']) != '') {
        header("Location:$path");
    } else {
        header("Location:index.php");
    }
}
