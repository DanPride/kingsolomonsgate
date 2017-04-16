<?php
    require_once('adminincludes.php');
    session_start();
    logout();
    header("Location: index.php");
    exit;
?>
