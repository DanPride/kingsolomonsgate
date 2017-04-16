<?php
    //Display all current session variables
    session_start();
    if (empty($_SESSION)) {
        echo 'No session active<br>';
    }
    else {
        echo 'Session is active<br>';
    }
    echo 'Session Name: ', session_name(), '<br>';
    echo 'Session ID: ', session_id(), '<br>';
    echo 'Session variables: ', '<br>';
    foreach ($_SESSION as $sessKey => $sessValue)
    {
        echo 'key: ', $sessKey, ' value: ', $sessValue,'<br>';
    }
    exit;
?>
