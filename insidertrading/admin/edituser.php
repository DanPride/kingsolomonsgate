<?php
    require_once('adminincludes.php');
    require_once('../common/Date.php');
    session_start();
    validAdminUse();
  
    $userAction = $_POST['userAction'];
  
    if (!empty($userAction))
    {
        //form data cleanup
        foreach ($_POST as $formValue)
        {
            trim($formValue);
        }

        $_POST['zip'] = ereg_replace('[^0-9]','', $_POST['zip']);
        $_POST['officePhone'] = ereg_replace('[^0-9]','', $_POST['officePhone']);
        $_POST['officeFax'] = ereg_replace('[^0-9]','', $_POST['officeFax']);

        //create short names for variables
        $userid   = $_POST['userid'];
        $password = $_POST['password'];
        $active   = $_POST['active'];
        $adminUser = $_POST['adminUser'];
        $prefix   = $_POST['prefix'];
        $firstName = $_POST['firstName'];
        $middleInitial = $_POST['middleInitial'];
        $lastName = $_POST['lastName'];
        $suffix   = $_POST['suffix'];
        $title    = $_POST['title'];
        $companyName = $_POST['companyName'];
        $streetAddress = $_POST['streetAddress'];
        $city     = $_POST['city'];
        $state    = $_POST['state'];
        $zip      = $_POST['zip'];
        $emailAddress = $_POST['emailAddress'];
        $officePhone = $_POST['officePhone'];
        $officeFax    = $_POST['officeFax'];
        $userAction = $_POST['userAction'];

        if (empty($active)) 
        {
            $active = NO;
        }

        if (empty($adminUser)) {
            $adminUser = NO;
        }

        switch($userAction) {
        case 'Create' :
            //Create a User
            $result = validateForm($_SERVER[PHP_SELF], $_POST);

            if(empty($result))
            {
                $result = createUserAndCustomer($userid, $password, $active, $adminUser,
                                   $prefix, $firstName, $middleInitial, $lastName, $suffix,
                                   $title, $companyName, $streetAddress, $city, $state, NULL,
                                   $zip, $emailAddress, $officePhone, $officeFax);                
//                $result = createUser($userid, $password, $active, $adminUser);
                if ($result->isSuccess())
                {
                    $displayString = "User $userid successfully created";
                }
                else
                {
                    $displayString = 'Error: '.$result->resultString();
                }
            }
            else
            {
                foreach ($result as $resultValue)
                {
                    $displayString = $displayString.$resultValue->resultString().'<BR>';
                }
            }
            break;

        case 'Retrieve' :
            //Retrieve a User
            if(empty($userid))
            {
                $displayString = 'Please enter User ID to retrieve user';
            }
            else
            {
                $result = retrieveUser($userid);
                if ($result->isError())
                {
                    $displayString = 'Error: '.$result->resultString();

                }
                else
                {
                    $displayString = 'User retrieved - details below';
                    $resultArray = $result->resultObject();
                }
            }

            break;

        case 'Update' :
            //Update a User
            if(empty($userid)||empty($password))
            {
                $displayString = 'Please enter User ID and Password';
            }
            else
            {
                $result = updateUser($userid, $password, $active, $adminUser);
                if ($result->isSuccess())
                {
                    $displayString = "User $userid successfully updated";
                }
                else
                {
                    $displayString = 'Error: '.$result->resultString();
                }
            }
            break;

  /*      case 'Delete' :
            //Delete a User
            if(empty($userid))
            {
                $displayString = 'Please enter User ID to delete user';
            }
            else
            {
                $result = deleteUser($userid);
                if ($result->isError())
                {
                    $displayString = 'Error: '.$result->resultString();

                }
                else
                {
                    $displayString = "User $userid successfully deleted";

                }
            }
            break;
*/
        case    'Make Inactive':
            //Make a User Inactive
            if(empty($userid))
            {
                $displayString = 'Please enter User ID to make user inactive';
            }
            else
            {
                $result = activateUser($userid, NO);
                if ($result->isError())
                {
                    $displayString = 'Error: '.$result->resultString();

                }
                else
                {
                    $displayString = "User $userid successfully inactivated";

                }
            }
            break;

        case    'Make Active':
            //Make a User Active
            if(empty($userid))
            {
                $displayString = 'Please enter User ID to make user active';
            }
            else
            {
                $result = activateUser($userid, YES);
                if ($result->isError())
                {
                    $displayString = 'Error: '.$result->resultString();

                }
                else
                {
                    $displayString = "User $userid successfully activated";

                }
            }
            break;


        case 'Retrieve All' :
            //Retrieve all users
            $result = retrieveAllUsers();
            if ($result->isError())
            {
                $displayString = 'Error: '.$result->resultString();

            }
            else
            {
                $displayString = 'All users retrieved below';
                $resultArray = $result->resultObject();
            }
            break;

        }
    }

?>
<!DOCTYPE html PUBLIC "-W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="images/favicon.png" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Create/Retrieve/Update/Delete Users || 3d adviso</title>

<link href="../css/style.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div id="wrapper">
    	<div id="wrapper_main">
        	<div id="wrapper_top">
                <div id="wrapper_header">
                	<div id="logo">
                    	<div class="logo_position">
                            <a href="../index.php"><img src="../images/3da_logo.png" alt="" border="0" /></a>
                        </div>
                    </div>
                	<div id="menu">
						<?php include("includes/site_menu.php"); ?>
                    </div>
                </div>
                <div id="wrapper_content">
                	<div id="column_full">
                    	<div id="content_full">

                            <h1>Create/Retrieve/Update/Delete Research Users</h1>
                            
                            <ul>
                                <li>Create new user by entering new user information and clicking 'Create'</li>
                                <li>Retrieve existing user information by entering user id and clicking 'Retrieve'</li>
                                <li>Update existing user information (password and active status only) by entering user id and clicking 'Update'</li>
                                <li>Delete existing user by entering user id and then clicking 'Delete'</li>
                            </ul>
                            
                            <p>[<a href="adminmenu.php">Return to Admin Menu</a>]</p>
                            
							<?php
                            
                               if (!(empty($displayString))) {
                                   echo '<b>', $displayString, '<b>', '<p>';
                               }
                            ?>
                        
                            <h3>User Information</h3>
                            <form method="post" action=<?php echo "$_SERVER[PHP_SELF]"; ?>>
                            <table border="0" cols=4>
                                <tr>
                                  <th width="77" align="right"><h5>Username</h5></th>
                                  <td width="150"> <input type="text" name="userid" size=30 maxlength=10 > </td>
                                  <th width="116" align="right"><h5 style="text-align: right;">Password</h5></th>
                                  <td width="147"> <input type="password" name="password" size=30 maxlength=10> </td>
                                </tr>
                                <tr>
                                  <th align="right"><h5>Active User?</h5></th>
                                  <td> <input type="checkbox" name="active" value='y' checked> </td>
                                  <th align="right"><h5>3DA Administrator?</h5></th>
                                  <td> <input type="checkbox" name="adminUser" value='y'> </td>
                                </tr>
                            </table>
                            
                            <h3>User Contact Information</h3>
                            
                            <table border="0" cols=2>
                                <tr>
                                    <th width="131" align="right"><h5>Prefix, First Name, M.I.</h5></th>
                                    <td width="366">
                                    <?php prefixSelector("prefix"); ?>
                                    <input type="text" name="firstName" size=32 maxlength=64>
                                    <input type="text" name="middleInitial" size=2 maxlength=2></td>
                                </tr>
                                <tr>
                                    <th align="right"><h5>Last Name, Suffix</h5></th>
                                    <td><input type="text" name="lastName" size=32 maxlength=64>
                                    <?php suffixSelector("suffix"); ?>
                                    </td>
                                </tr>
                                <tr><th align="right"><h5>Title</h5></th><td><input type="text" name="title" size=64 maxlength=128></td></tr>
                                <tr><th align="right"><h5>Company Name</h5></th><td><input type="text" name="companyName" size=64 maxlength=128></td></tr>
                                <tr><th align="right"><h5>Street Address</h5></th><td><input type="text" name="streetAddress" size=64 maxlength=255></td></tr>
                                <tr><th align="right"><h5>City, State, Zip</h5></th>
                                    <td><input type="text" name="city" size=48 maxlength=64>
                                    <?php stateSelector("state"); ?> 
                                    <input type="text" name="zip" size=9 maxlength=10></td>
                                </tr>
                                <tr><th align="right"><h5>Email Address</h5></th><td><input type="text" name="emailAddress" size=64 maxlength=128></td></tr>
                                <tr><th align="right"><h5>Office Phone</h5></th><td><input type="text" name="officePhone" size=10 maxlength=12></td></tr>
                                <tr><th align="right"><h5>Office Fax</h5></th><td><input type="text" name="officeFax" size=10 maxlength=12></td></tr>
                            </table>
                            
                            <br>
                                <input type="submit" name ="userAction" value="Create">
                                <input type="submit" name ="userAction" value="Retrieve">
                                <input type="submit" name ="userAction" value="Update">
                                <input type="submit" name ="userAction" value="Delete">
                                <input type="submit" name ="userAction" value="Make Inactive">
                                <input type="submit" name ="userAction" value="Make Active">
                                <input type="submit" name ="userAction" value="Retrieve All">
                                <input type="reset" value="Reset Screen">
                              </td>
                            </form>
                        
                            <p>
                        
							<?php
                                 if (isset($resultArray))
                                 {
                                    echo '<table border="0" width="100%"><tr>
                                            <tr>
                                            <th><p><strong>User ID</strong></p></th>
                                            <th><p><strong>User\'s Name</strong></p></th>
                                            <th><p><strong>Company</strong></p></th>
                                            <th><p><strong>Active(y/n)</strong></p></th>
                                            <th><p><strong>Admin(y/n)</strong></p></th>
                                            <th><p><strong>Last Login</strong></p></th>
                                            <th><p><strong>Id Created</strong></p></th>
                                            </tr>';
                            
                                    foreach ($resultArray as $rowValue)
                                    {
                            //            $lastLogin = new Date($rowValue['last_login']);
                            //            $createTimestamp = new Date($rowValue['create_timestamp']);
                                        echo '<tr>';
                                        echo '<td class="search_results">',$rowValue['userid'], '</td>';
                                        echo '<td class="search_results">',$rowValue['first_name'], ' ', $rowValue['last_name'], '</td>';
                                        echo '<td class="search_results">',$rowValue['company_name'], '</td>';
                                        echo '<td class="search_results" align="center">',$rowValue['active'],'</td>';
                                        echo '<td class="search_results" align="center">',$rowValue['admin_user'],'</td>';
                            //            echo '<td>', $lastLogin->format('%D %T'), ' GMT', '</td>';
                            //            echo '<td>', $createTimestamp->format('%D %T'), ' GMT', '</td>';
                                        echo '<td class="search_results" align="center">', dateFormat($rowValue['last_login']),
                                             '</td>';
                                        echo '<td class="search_results" align="center">', dateFormat($rowValue['create_timestamp']),
                                             '</td>';
                                        echo '</tr>';
                                    }
                                    echo '</table>';
                                 }
                                 
                            ?>
                            
                            <br/>
                            <br/>
                            
                            <p>[<a href = "../index.php" >Return to 3DA customer web site</a>]<p>
                            
                            <div class="clear"></div>
                        
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
        	</div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
	</div>
    <div class="clear"></div>
    <div id="wrapper_footer">
    	<p>copyright by <a href="../index.php">3d adviso, llc.</a> all rights reserved. 
        	website by <a href="http://www.taoti.com/?sourceID=3d_adviso">taoti creative</a>.</p>
    </div>
    
    <?php include("../includes/analytics.php"); ?>
    
</body>
</html>