<?php
    /*****************************************
     *
     *  Validate data submitted via HTML forms
     *
     *  - check for data not empty in required fields
     *  - check for valid data in all fields
     *
     *  Author: Scott Day
     *  Date:   6/6/03
     *
     *****************************************/
    function validateForm($pageName, $postArray)
    {
	$pageName = substr($pageName, strlen('/insidertrading'));
        switch($pageName) {
        case '/index.php':
            $result = validateLoginForm($postArray);
            break;
        case '/admin/index.php':
            $result = validateLoginForm($postArray);
            break;
        case '/search.php':
            $result = validateSearchForm($postArray);
            break;
        case '/admin/edituser.php':
            $result = validateUserForm($postArray);
            break;
        default:
            $errorArray[] = new Result(ERROR, 'Invalid form submission', 'form');
            $result = $errorArray;
            break;
        }
        return $result;
    }
    function validateLoginForm($postArray)
    {
        $userid = $postArray['userid'];
        $password = $postArray['password'];
        $rememberMe = $postArray['rememberMeCookie'];

        //Check for data in required fields
        if(empty($userid)||(empty($password)))
        {
            $errorArray[] = new Result(ERROR, 'User name and password must be entered', 'userid');
        }
        //Validate data in form
        if (empty($errorArray))
        {
            if (!(ereg('[a-zA-Z0-9]{6,10}', $userid)))
            {
               $errorArray[] = new Result(ERROR, 'Invalid user name, 6-10 alphaNumerics', 'userid');
            }
//            if (!(pregmatch('/[[:alnum:]]{6,10}/', $password)))
            if (!(ereg('[a-zA-Z0-9]{6,10}', $password)))
            {
                $errorArray[] = new Result(ERROR, 'Invalid password, 6-10 alphaNumerics', 'password');
            }
        }
        return $errorArray;
    }
    function validateSearchForm($postArray)
    {
//        echo 'validating search form fields';
        return;
    }
    function validateUserForm($postArray)
    {
        $ignoreArray = array('active', 'adminUser', 'middleInitial', 'prefix', 'suffix', 'title', 'officeFax', 'streetAddress', 
                             'city', 'state', 'zip');

        //Check for data in required fields
        foreach ($postArray as $formKey => $formValue)
        {
            if (in_array($formKey, $ignoreArray)) {
                ;//do nothing
            }
            else if (empty($formValue))
            {
                $errorArray[] = new Result(ERROR, "$formKey must be entered", $formKey);
            }
        }

        //Validate data in form
        if (empty($errorArray))
        {
            if (!(ereg('[a-zA-Z0-9]{6,11}', $postArray['userid'])))
            {
               $errorArray[] = new Result(ERROR, 
                                          'User name must be must be 6-10 AlphaNumerics',
                                          'userid');
            }

            if (!(ereg('[a-zA-Z0-9]{6,11}', $postArray['password'])))
            {
                $errorArray[] = new Result(ERROR, 
                                           'Password must be 6-10 AlphaNumerics',
                                           'password');
            }

            if (!(ereg('[a-zA-Z]', $postArray['firstName'])))
            {
               $errorArray[] = new Result(ERROR, 
                                          'First name must be all alpha characters',
                                          'firstName');
            }

/**
            if (!(ereg('[a-zA-Z]', $postArray['middleInitial'])))
            {
               $errorArray[] = new Result(ERROR, 
                                          'Middle initial must be all alpha characters',
                                          'middleInitial');
            }
**/
            if (!(ereg('[a-zA-Z]', $postArray['lastName'])))
            {
               $errorArray[] = new Result(ERROR, 
                                          'Last name must be all alpha characters',
                                          'lastName');
            }
/**
            if (!(ereg('[a-zA-Z]', $postArray['city'])))
            {
               $errorArray[] = new Result(ERROR, 
                                          'City must be all alpha characters',
                                          'city');
            }

            if (!(ereg('[0-9]{5,9}', $postArray['zip'])))
            {
               $errorArray[] = new Result(ERROR, 
                                          'Zip must be all numeric characters',
                                          'zip');
            }
**/
            if (!(ereg('^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $postArray['emailAddress'])))
            {
               $errorArray[] = new Result(ERROR, 
                                          'Invalid email address',
                                          'emailAddress');
            }

            if (!(ereg('[0-9]{6,11}', $postArray['officePhone'])))
            {
               $errorArray[] = new Result(ERROR, 
                                          'Office phone must be 6-10 Numeric characters',
                                          'officePhone');
            }
/**
            if (!(ereg('[0-9]{11}', $postArray['officeFax'])))
            {
               $errorArray[] = new Result(ERROR, 
                                          'Office fax must be all 6-10 numeric characters',
                                          'officeFax');
            }
**/
        }

        return $errorArray;
    }
    function validateDate($dateString)
    {
        return;
    }
?>
