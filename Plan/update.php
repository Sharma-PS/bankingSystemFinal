<?php
    ob_start();
    session_start();
    require "../loginCheck.php";

    $id = $_POST['ID'];
    $rate = $_POST['Rate'];
    $plan = $_POST['Plan'];

    $loginedUser->changeRate($id, $rate, $plan);
?>