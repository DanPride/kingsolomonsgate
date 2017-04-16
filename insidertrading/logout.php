<?php
    require_once('baseincludes.php');
    session_start();
    logout();
//    header("Location: index.php");
    header('Location: '.$indexURL);
    exit;
?>
