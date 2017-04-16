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
    function customerLogin($userid, $password, $passwordEncrypted)
    {
        return login($userid, $password, NO, $passwordEncrypted);
    }
    function adminLogin($userid, $password, $passwordEncrypted)
    {
        return login($userid, $password, YES, $passwordEncrypted);
    }
    function login($userid, $password, $adminUser, $passwordEncrypted)
    {
        $result = connectToDb();
        if ($result->isError())
        {
            //error log - 'Cannot connect to db
            //$result = new Result(ERROR, 'Cannot connect to database', NULL);
        }
        else
        {
            if ($passwordEncrypted)
            {
                $crypt_password = $password;
            }
            else
            {
                $crypt_password = encryptString($password);
            }

            // query the database to see if there is a record which matches
            $query = "select id from 3da_users where
                              userid = '$userid' and
                              password = '$crypt_password' and
                              active = 'y' and 
                              admin_user = '$adminUser'";

            $dbresult = db_query( $query );

            if(!($dbresult))
            {
                //echo 'Cannot run query.';
                $result = new Result(ERROR, 'Cannot run query', NULL);
            }
            else
            {
                $num_results = mysql_num_rows($dbresult);
                if ($num_results > 0)
                {
                    //to do - there may be more than one row
                    for ($counter=0; $counter < $num_results; $counter++) {
                         $rows[]=mysql_fetch_array($dbresult);
                    }

                    $recordId = $rows[0]['id'];

                    // visitor's userid and password combination are correct
                    // set last_login to current timestamp
                    $query ="update 3da_users set last_login = NULL where
                             id = $recordId";

                    $dbresult = db_query( $query );
                    if(!($dbresult))
                    {
                        // $result = new Result(ERROR, 'Cannot run query', NULL);
                    }
                    $result = new Result(SUCCESS, 'Login successful', NULL);
                    $audit = new AuditTrail(TX_LOGIN, $userid, 'User Login', '3da_users', $recordId);
                    $audit->createEntry();
                }
                else
                {
                    // visitor's userid and password combination are not correct
                    $result =  new Result(ERROR, 'User ID or password is incorrect', NULL);
                }
            }
        }
        return $result;
    }
    function logout()
    {
        foreach ($_SESSION as $sessKey)
        {
            unset($sessKey);
        }
        session_destroy();
        return new Result(SUCCESS, 'Logout successful', NULL);
    }

    function encryptString($password)
    {
        //Use PHP encryption algorithm to encrypt a string password
        global $Salt;
        $crypt_string = crypt($password, $Salt);
        return $crypt_string;
    }
?>
