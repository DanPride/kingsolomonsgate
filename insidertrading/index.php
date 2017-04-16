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
        else if ($_SESSION['loginFailures'] >= 33)
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
<!DOCTYPE html PUBLIC "-W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="images/favicon.png" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>3DA Focuses on Insider Trading Behavior, Accounting Behavior, and Corporate Governance Behavior to Provide You the Results that Benefit You || 3d advisors</title>

<link href="css/style.css" rel="stylesheet" type="text/css" />

<!-- Start Popup Function -->
<script type="text/javascript">
<!--
function popup(url) {
	 var width  = 435;
	 var height = 380;
	 var left   = (screen.width  - width)/2;
	 var top    = (screen.height - height)/2;
	 var params = 'width='+width+', height='+height;
	 params += ', top='+top+', left='+left;
	 params += ', directories=no';
	 params += ', location=no';
	 params += ', menubar=no';
	 params += ', resizable=no';
	 params += ', scrollbars=no';
	 params += ', status=no';
	 params += ', toolbar=no';
	 newwin=window.open(url,'windowname5', params);
	 if (window.focus) {newwin.focus()}
	 return false;
}
// -->
</script>

<!-- End Popup Function -->

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
                            <h1>what we do</h1>
							<h2 style="color:green"><large><a href="http://www.google.com/#hl=en&source=hp&q=3dadvisors.com&aq=f&aqi=&aql=&oq=&gs_rfai=C3129WvhMTJS_HZmMhQSLxvCJBAAAAKoEBU_Qs7Rp&pbx=1&fp=b6f5afd20980ff7e">Google Search</a> <h2 style="color:blue"></h2>
                          <h2> <br></h2>

                            <p><font class="red"><strong>3Dadvisors, LLC (3DA)</strong></font> is an <a href="javascript: void(0)" 
                                onclick="popup('popup_independent_and_unbiased.html')">independent 
                            	and unbiased</a> institutional equity research firm that develops investment ideas 
                                (long and short) using a multi-step process that combines behavioral and fundamental 
                                analysis. We start with an examination of changes in executive <em>behavior</em> in 
                                the areas of insider trading, accounting and corporate governance to determine where 
                                to focus fundamental analysis and further investigation. <strong><em>Our basic research premise 
                                is that certain changes in management behavioral patterns can indicate hidden or 
                                underappreciated changes in fundamental or financial risks -- which in turn result 
                                in mispriced securities.</em></strong> Our research team has extensive expertise in 
                                analyzing subtle and difficult to detect changes in management behavior.</p>
                                
                            <p>3DA limits access to its research and support services to a small number of qualified 
                            	institutional investors.</p>
							
                                
                    		<p><a href="contact_us.php">Click here</a> to contact us for more information.</p>
                            
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div id="column_right">
                    	<div id="content_right">
							<form name="login" method="post" action="index.php";>
							<input type="hidden" name="Submit" value="LOGIN">
							<input type="text" name="userid" maxlength="10" class="inpt" value="admin1";} ?>
                           	<input type="password" name="password" maxlength="10" class="inpt" value="aaa";} ?>
							<input class="checkbox" type="checkbox" name="rememberMeFlag" value="Y" <?php if ($checked) echo 'checked'; ?>>
							<span class="checkbox_span">Remember my username/password</span><br/>
                            <div class="forgot_pass_get_access">
								<a href="mailto:support@3dadvisors.com&subject=3dAdvisors.com Forgot Password Request">Forgot Password</a> | <a href="mailto:support@3dadvisors.com&subject=3dAdvisors.com Access Request">Get Access</a><br/>
								<input style="margin-top: 5px;" type="image" src="images/freeaccess.jpg" name="Submit" value="LOGIN" alt="[Submit]" title="Submit" /></div>
                           </form>
                            <br/>
                            
                            <div style="margin: 0 10px 0 15px;">
                            <h2><a href="admin/adminlogin.php">Admin Login</a><br></h2>
                                <h2>3Dadvisors limits access to its research and support services to a small number 
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
    	<p>
        	website by <a href="http://www.kingsolomonsgate.com/">Daniel Pride</a>.</p>
    </div>
    
    <?php include("includes/analytics.php"); ?>
    
</body>
</html>