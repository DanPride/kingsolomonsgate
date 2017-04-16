<?php
    /************************************************************
     *
     *  user.php
     *
     *  Provides user create, retrieve, update, delete functions
     *
     *  Author: Scott Day
     *  Date:   5-24-03
     *
     ************************************************************/
    function createUserAndCustomer($userid, $password, $active, $adminUser,
                                   $prefix, $firstName, $middleInitial, $lastName, $suffix,
                                   $title, $companyName, $streetAddress, $city, $state, $country,
                                   $postalCode, $emailAddress, $officePhone, $officeFax)
    {
        //to do - add txn integrity with MySQL 'START TRANSACTION;', 'COMMIT;', 'ROLLBACK'
        $result = createUser($userid, $password, $active, $adminUser);
        if ($result->isSuccess())
        {
            $result = createCustomer($userid, $prefix, $firstName, $middleInitial, $lastName, $suffix, $title,
                                     $companyName, $streetAddress, $city, $state, $country, $postalCode, 
                                     $emailAddress, $officePhone, $officeFax);
        }

        return $result;
    }
    //Create User
    function createUser($userid, $password, $active, $adminUser)
    {
        $result = connectToDb();
        if ($result->isError())
        {
            //error log - 'Cannot connect to db
            //$result = new Result(ERROR, 'Cannot connect to database', NULL);
        }
        else
        {
            //check to see if user already exists
            $query = "select count(*) from 3da_users where
                          userid = '$userid'";

            $dbresult = db_query( $query );
            if(!($dbresult))
            {
                //echo 'Cannot run query.';
                $result =  new Result(ERROR, 'Cannot run user already exists query', NULL);
            }
            else
            {
                $count = db_result( $dbresult, 0, 0 );

                if ( $count > 0 )
                {
                    //echo User already exists
                    $result = new Result(ERROR, 'User already exists', NULL);
             
                }
                else
                {
                    $crypt_password = encryptString($password);
                    $defaultLastLogin = NEW_USER_DATE;

                     // Insert user into db
                    $query = "insert into 3da_users values
                             (NULL, '$userid', '$crypt_password', '$active', 
                              '$defaultLastLogin', '$adminUser', NULL)";

                    $dbresult = db_query( $query );

                    if(!($dbresult))
                    {
                        //error log - 'Cannot run query.';
                        $result = new Result(ERROR, 'Cannot run insert user query', NULL);
                    }
                    else
                    {
                        $result = new Result(SUCCESS, 'New user created', $dbresult);
                    }

                }
            }
        }

        return $result;

    }
    //Create Customer
    function createCustomer($userid, $prefix, $firstName, $middleInitial, $lastName, $suffix, $title,
                            $companyName, $streetAddress, $city, $state, $country, $postalCode, 
                            $emailAddress, $officePhone, $officeFax)
    {
        $result = connectToDb();
        if ($result->isError())
        {
            //error log - 'Cannot connect to db
            //$result = new Result(ERROR, 'Cannot connect to database', NULL);
        }
        else
        {
            if (empty($country)) {
                $country = 'US';
            }
            // Insert customer into db
            $query = "insert into 3da_customers values
                     (NULL, '$userid', '$prefix', '$firstName', '$middleInitial', '$lastName', '$suffix', '$companyName',
                      '$title','$streetAddress', '$city', '$state', '$postalCode', '$country', 
                      '$emailAddress', '$officePhone', '$officeFax', NULL)";

            $dbresult = db_query( $query );

            if(!($dbresult))
            {
                //error log - 'Cannot run query.';
                $result = new Result(ERROR, 'Cannot run insert customer query', NULL);
            }
            else
            {
                $result = new Result(SUCCESS, 'New customer created', $dbresult);
            }
        }

        return $result;
    }
    //retrieve user
    function retrieveUser($userid)
    {

        $result = connectToDb();
        if ($result->isError())
        {
            //error log - 'Cannot connect to db
            //$result = new Result(ERROR, 'Cannot connect to database', NULL);
        }
        else
        {

            //check to see if user already exists
            $query = "select u.userid, u.password, u.active, u.admin_user, u.last_login, u.create_timestamp, 
                        c.first_name, c.last_name, c.company_name
                        from 3da_users as u, 3da_customers as c
                        where u.userid = '$userid' and c.userid = '$userid'";

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
                     $result = new Result(SUCCESS, 'All users retrieved', $rows);
                 }
                 else
                 {
                     //to do - error 'No matches for search criteria'
                     $result = new Result(ERROR, 'No matches for search criteria', NULL);
                 }
            }
        }

        return $result;
    }
    //Retrieve all users
    function retrieveAllUsers()
    {

        $result = connectToDb();
        if ($result->isError())
        {
            //error log - 'Cannot connect to db
            //$result = new Result(ERROR, 'Cannot connect to database', NULL);
        }
        else
        {

            //check to see if user already exists
            $query = "select u.userid, u.password, u.active, u.admin_user, u.last_login, u.create_timestamp, 
                        c.first_name, c.last_name, c.company_name
                        from 3da_users as u, 3da_customers as c
                        where u.userid = c.userid";

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
                     $result = new Result(SUCCESS, 'All users retrieved', $rows);
                 }
                 else
                 {
                     //to do - error 'No matches for search criteria'
                     $result = new Result(ERROR, 'No rows to return', NULL);
                 }
            }
        }

        return $result;
    }
    //Update User
    function updateUser($userid, $password, $active, $adminUser)
    {
        $result = connectToDb();
        if ($result->isError())
        {
        	$result = new Result(ERROR, 'Can Not Connect to Database', NULL);
        }
        else
        {
            $crypt_password = encryptString($password);
			
            $query = "update 3da_users 
                        set password = '$crypt_password',
                            active = '$active',
                            admin_user = '$adminUser'
                        where userid = '$userid'";

            $dbresult = db_query( $query );
            if(!($dbresult))
            {
                $result = new Result(ERROR, 'Cannot run query', NULL);
            }
            else
            {
                if ((mysql_affected_rows() > 0))
                {
                    $result = new Result(SUCCESS, 'User values updated', $dbresult);
                }
                else
                {
                    $result = new Result(ERROR, 'No rows affected', NULL);
                }
            }
        
        }

        return $result;
    }
    //Delete a user
  /*  function deleteUser($userid)
    {

        $result = connectToDb();
        if ($result->isError())
        {
            //error log - 'Cannot connect to db
            //$result = new Result(ERROR, 'Cannot connect to database', NULL);
        }
        else
        {
            $query = "delete from 3da_users where userid = '$userid'";

            $dbresult = db_query( $query );
            if(!($dbresult))
            {
                $result = new Result(ERROR, 'Cannot run query', NULL);
            }
            else
            {
                if ((mysql_affected_rows() > 0))
                {
                    $result = new Result(SUCCESS, 'User deleted', $dbresult);
                }
                else
                {
                    $result = new Result(ERROR, 'No rows affected', NULL);
                }
                
            }

        }

        return $result;  
    }
    /*/
    //Make a user inactive
    function activateUser($userid, $activeStatus)
    {

        $result = connectToDb();
        if ($result->isError())
        {
            //error log - 'Cannot connect to db
            //$result = new Result(ERROR, 'Cannot connect to database', NULL);
        }
        else
        {
            $query = "update 3da_users 
                        set active = '$activeStatus'
                            where userid = '$userid'";

            $dbresult = db_query( $query );
            if(!($dbresult))
            {
                $result = new Result(ERROR, 'Cannot run query', NULL);
            }
            else
            {
                if ((mysql_affected_rows() > 0))
                {
                    if ($activeStatus == YES) 
                    {
                        $result = new Result(SUCCESS, 'User active', $dbresult);
                    }
                    else 
                    {
                        $result = new Result(SUCCESS, 'User inactive', $dbresult);
                    }
                    
                }
                else
                {
                    $result = new Result(ERROR, 'No rows affected', NULL);
                }
                
            }

        }

        return $result;  
    }   //end - activateUser()

?>
