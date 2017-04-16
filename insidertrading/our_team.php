<?php
    require_once('baseincludes.php');

    //create short names for variables
    $cookieName1 = 'value1';
    $cookieName2 = 'value2';
    $cookieName3 = 'value3';
    $userid = trim($_POST['userid']);
    $password = trim($_POST['password']);
    $rememberMe = trim($_POST['rememberMeFlag']);
    $userAction = $_POST['Submit'];
    $cookieUserid = $_COOKIE[$cookieName1];
    $cookiePassword = $_COOKIE[$cookieName2];

    $cookieResult = setcookie($cookieName3, "true", time()+(3600*24));//, "/", $COOKIE_ROOT, 0
    $cookieEnabled = $_COOKIE[$cookieName3];

    if ($rememberMe == 'Y') {
        $checked = true;
    }
    else
    {
        $checked = false;
    }
    
    if (!empty($userAction))
    {
        session_start();

        if (!isset($_SESSION['loginFailures']))
        {
            $_SESSION['loginFailures'] = 0;
        }
        else if ($_SESSION['loginFailures'] >= 3)
        {
            echo '&nbsp;<b><font face="arial" size="2" color="#770000">Sorry, no more tries</font><b>';
            exit;
        }

        if (!isset($cookieEnabled))
//        if (!($cookieResult))
        {
			$displayString = 'Your browser is blocking cookies.<br />Please enable them to log in.';
        }
        else
        {
            $result = validateForm($_SERVER[PHP_SELF], $_POST);

            if(empty($result))
            {
                if ( (isset($cookieUserid)) && (isset($cookiePassword)) ) {
                    $result = customerLogin($userid, $password, true);
                    $encryptedPW = $password;  //for cookie if needed
                }
                else
                {
                    $result = customerLogin($userid, $password, false);
                    $encryptedPW = encryptString($password);  //for cookie if needed
                }

                if ($result->isSuccess()) 
                {
                    if ($checked)
                    {
                        setcookie($cookieName1, $userid, time()+(3600*24*90));//, "/", $COOKIE_ROOT, 0
                        setcookie($cookieName2, $encryptedPW, time()+(3600*24*90));//, "/", $COOKIE_ROOT, 0
                    }
                    else
                    {
                        setcookie($cookieName1, "", time() - 3600);//, "/", $COOKIE_ROOT, 0
                        setcookie($cookieName2, "", time() - 3600);//, "/", $COOKIE_ROOT, 0
                    }
                    $_SESSION['loginSuccess'] = true;
                    $_SESSION['userid'] = $userid;
                    $_SESSION['adminUser'] = 0;
                    header('Location: '.$WEB_ROOT.'/search.php');
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
                    $displayString = $displayString.$resultValue->resultString().'<BR>&nbsp;';
                }
            }
        }
    }
    else
    {
        if ( (isset($cookieUserid)) && (isset($cookiePassword)) ) {
            $checked = true;
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="images/favicon.png" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Our Team Focuses on Stocks with Market Caps of at Least $1 Billion || 3d adviso</title>

<link href="css/style.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div id="wrapper">
    	<div id="wrapper_main">
        	<div id="wrapper_top">
                <div id="wrapper_header">
                	<div id="logo">
                    	<div class="logo_position">
                            <a href="index.php"><img src="images/3da_logo.png" alt="" border="0" /></a>
                        </div>
                    </div>
                	<div id="menu">
						<?php include("includes/menu.php"); ?>
                    </div>
                </div>
                <div id="wrapper_content">
                	<div id="column_left">
                    	<div id="content_left">
                            
                            <h1>research team</h1>
                            
                            <p>The <font class="red"><strong>3DAdviso, LLC (3DA)</strong></font> research team 
                            	collectively has many decades of experience in the institutional data and equity 
                                research markets. The principals and senior analysts each focus on and have 
                                <u>extensive experience in specific areas utilized by our research methodology</u> 
                                including insider trading data and analysis, corporate governance analysis, 
                                accounting/quality of earnings analysis, and company and industry fundamental 
                                analysis. Our research team’s extensive network of industry contacts and 
                                consultants is frequently tapped as a resource to support our behavioral and 
                                fundamental analysis.</p>

                            <p>We have research team members with <font class="red"><strong>unequalled experience 
                            	in insider trading data and analysis and corporate governance</strong></font>. 
                                We have a team member that started his career as a CPA but later gained valuable 
                                experience as the Chairman and CEO of a large public company and an independent 
                                director and audit committee member of an investor-owned public utility, giving 
                                this individual a decidedly practical, non-academic perspective in assessing 
                                changes in accounting practices and policies. Another team member has worked as 
                                a senior analyst and portfolio manager for a well-known buy-side firm.</p>

                            <p>Please <a href="contact_us.php">contact us</a> for more information on our 
                            	<u>research team</u>.</p>
                                                            
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div id="column_right">
                    	<div id="content_right">
							<form name="login" method="post" action="index.php";>
							<input type="hidden" name="Submit" value="LOGIN">
							<input type="text" name="userid" maxlength="10" class="inpt" onFocus="if(this.value=='Username')this.value='';" value="Username" <?php if ($checked) {echo 'value='.$cookieUserid;} else {echo 'value='.'Username';} ?>>
                           	<input type="password" name="password" maxlength="10" class="inpt" onFocus="if(this.value=='Password')this.value='';" value="Password" <?php if ($checked) {echo 'value='.$cookiePassword;} else {echo 'value='.'Password';} ?>>
							<input class="checkbox" type="checkbox" name="rememberMeFlag" value="Y" <?php if ($checked) echo 'checked'; ?>>
							<span class="checkbox_span">Remember my username/password</span><br/>
                            <div class="forgot_pass_get_access">
								<a href="mailto:support@3dadvisxxors.com&subject=3dAxxdvisors.com Forgot Password Request">Forgot Password</a> | <a href="mailto:support@3dadvixxsors.com&subject=3dAdxxvisors.com Access Request">Get Access</a><br/>
								<input style="margin-top: 5px;" type="image" src="images/bttn_login.png" name="Submit" value="LOGIN" alt="[Submit]" title="Submit" /></div>
                           </form>
                            <br/>
                            
                            <div style="margin: 0 10px 0 15px;">
                                <h2>3DAdviso limits access to its research and support services to a small number 
                                    of qualified institutional investors.</h2>
                            </div>
                            
                            <br/>
                            
                            <div style="margin: 0 10px 0 15px;">
                            	<h3><a href="our_methods.php">Learn more.</a></h3>
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
	</div>
    <div class="clear"></div>
    <div id="wrapper_footer">
    	<p>copyright by <a href="index.php">3d adviso, llc.</a> all rights reserved. 
        	website by <a href="http://www.taoti.com/?sourceID=3d_adviso">taoti creative</a>.</p>
    </div>
    
    <?php include("includes/analytics.php"); ?>
    
</body>
</html>