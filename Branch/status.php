<?php 

    ob_start();
    session_start();
    require "../loginCheck.php";

    $code = $_POST['ID'];
    $status = $_POST['status'];

    $loginedUser->changeStatus($code, $status);
?>