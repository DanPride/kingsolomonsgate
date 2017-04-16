<?php
    //3DA Administrator Login Page
    require_once('adminincludes.php');

    //create short names for variables
    $userid = trim($_POST['userid']);
    $password = trim($_POST['password']);
    $userAction = $_POST['userAction'];

    if (!empty($userAction))
    {
        session_start();

        if (!isset($_SESSION['loginFailures']))
        {
            $_SESSION['loginFailures'] = 0;
        }
        else if ($_SESSION['loginFailures'] >= 13)
        {
            echo '<b>Sorry, no more tries<b>';
            exit;
        }

        $result = validateForm($_SERVER[PHP_SELF], $_POST);
        
        if(empty($result))
        {
            $result = adminLogin($userid, $password, false);
            if ($result->isSuccess()) 
            {
                $_SESSION['loginSuccess'] = true;
                $_SESSION['userid'] = $userid;
                $_SESSION['adminUser'] = true;
                header("Location: adminmenu.php");
                exit;
            }
            else
            {
                $_SESSION['loginFailures']++;
                $displayString = $result->resultString();
            }
        }
        else
        {
            $_SESSION['loginFailures']++;
            foreach ($result as $resultValue)
            {
                $displayString = $displayString.$resultValue->resultString().'<BR>';
            }
        }
    }
?>
<!DOCTYPE html PUBLIC "-W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="images/favicon.png" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Administration Login || 3d adviso</title>

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

                            <h1>Free Access</h1>
                            
                            <p>Free Access!</p>
                        
							<?php
                            	echo '<b>', $displayString, '<b><br>';
                            ?>
                        
                            <form method="post" action=<?php echo $adminLoginURL; ?> >
                                <table border="0" width="100%">
                                    <tr>
                                        <th width="25%"><h5 style="text-align: right;">Username</h5></th>
                                        <td width="76%"><input type="text" name="userid" size=50 maxlength=10> </td>
                                    </tr>
                                    <tr>
                                        <th><h5 style="text-align: right;">Password</h5></th>
                                        <td><input type="password" name="password" size=50 maxlength=10> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left">
                                        	<input style="margin-left: 400px;" type="submit" name=userAction value="Free Access">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            
                            <br/>
                            
                            <p>[<a href = "../index.php" >Return to 3DA customer web site</a>]</p>
                            
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