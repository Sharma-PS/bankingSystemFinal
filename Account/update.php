<?php
    ob_start();
    session_start();
    require "../loginCheck.php";

    $id = $_POST['ID'];
    $status = $_POST['status'];
    
    $loginedUser->changeStatus($id, $status);    
?>