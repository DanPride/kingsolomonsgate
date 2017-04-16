<?php
    /************************************************************
     *
     *  authenticate.php
     *
     *  Provides customer and admin login and logout functionality
     *
     *  Author: Scott Day
     *  Date:   5-24-03
     *
     ************************************************************/
    require_once('dbutil.php');
    require_once('audittrail.php');
$password = "aaa";
echo encryptString($password);

    function encryptString($password)
    {
        //Use PHP encryption algorithm to encrypt a string password
        global $Salt;
        $crypt_string = crypt($password, $Salt);
        return $crypt_string;
    }
?>
