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

<title>3DA Has Unequalled Experience in Insider Trading Data and Analysis and Corporate Governance || 3d adviso</title>

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
                	<div id="column_full">
                    	<div id="content_full">
                            
                            <div style="float: right; width: 230px; margin: 0 25px;">
                                <form name="login" method="post" action="index.php";>
                                <input type="hidden" name="Submit" value="LOGIN">
                                <input type="text" name="userid" maxlength="10" class="inpt" onFocus="if(this.value=='Username')this.value='';" value="Username" <?php if ($checked) {echo 'value='.$cookieUserid;} else {echo 'value='.'Username';} ?>>
                                <input type="password" name="password" maxlength="10" class="inpt" onFocus="if(this.value=='Password')this.value='';" value="Password" <?php if ($checked) {echo 'value='.$cookiePassword;} else {echo 'value='.'Password';} ?>>
                                <input class="checkbox" type="checkbox" name="rememberMeFlag" value="Y" <?php if ($checked) echo 'checked'; ?>>
                                <span class="checkbox_span">Remember my username/password</span><br/>
                                <div class="forgot_pass_get_access">
                                    <a href="mailto:support@3dadvxxisors.com&subject=3dAxxdvisors.com Forgot Password Request">Forgot Password</a> | <a href="mailto:support@3dadvisxxors.com&subject=3dAdvixxsors.com Access Request">Get Access</a><br/>
                                    <input style="margin-top: 5px;" type="image" src="images/bttn_login.png" name="Submit" value="LOGIN" alt="[Submit]" title="Submit" /></div>
                               </form>
                            </div>
                            
                            <h1>our methods</h1>
                            
                            <p>The <font class="red"><strong>3DAdviso, LLC (3DA)</strong></font> research team uses a 
                            	unique combination of behavioral and fundamental analysis to identify mispriced stocks. 
                                Our basic research premise is that changes in executive behavior – <font class="red">
                                <strong>changes that are sometimes subtle and difficult to detect</strong></font> – can 
                                signal that there are shifts in underlying fundamental or financial risks not accurately 
                                reflected in stock prices. We start by identifying and analyzing pattern changes in 
                                specific executive behaviors in <em>three different areas</em>. We follow up the behavioral 
                                analysis with fundamental analysis and further investigation. We will sometimes retain 
                                outside consultants with expertise in specific industries or sectors to supplement our 
                                fundamental analysis. </p>
							
                            <p>In an overvalued situation, here are examples of some of the changes in behavior that 
                            	we seek to identify in each of the three areas we focus on during the first step of our 
                                research process:</p>
                            
                            <ol>
                            	<li class="border_left"><div style="margin-left: 7px;">
                                	<font style="color: #740000; font-size: 16px; font-style: italic;">Insider trading 
                                	behavior</font> that includes <u>abusive manipulation or disclosure of 
                                	options-related transactions</u>, 10b5-1 trading plans, forward sales, equity 
                                    exchange funds, collars and other derivative transactions; </div></li>
                            	<li class="border_left"><div style="margin-left: 7px;">
                                	<font style="color: #740000; font-size: 16px; font-style: italic;">Accounting 
                                	behavior</font>, especially the use of practices and policies that appear <u>designed 
                                	to hide or disguise</u> financial or operating problems or overstate results;</div></li>
                            	<li class="border_left"><div style="margin-left: 7px;">
                                	<font style="color: #740000; font-size: 16px; font-style: italic;">Corporate governance 
                                	behavior</font>, particularly <u>executive compensation plans</u> and related-party 
                                    transactions that appear self-serving, selective or misleading financial disclosure, 
                                    and questionable board composition and structure.</div></li>
                            </ol>
                            
                            <p><strong>The mosaic of behavioral changes we uncover</strong> -- coupled with deep fundamental 
                            	analysis and investigation that seeks to offer a plausible explanation for the observed 
                                changes in behavior -- form the basis of our over or undervalued thesis. Simply put, we use 
                                specific behavioral markers to figure out where to do additional fundamental research to 
                                uncover situations where stock prices inaccurately reflect changes in fundamental or financial 
                                risks. Our two-step methodology has proven to be a highly unique and successful approach to 
                                identifying mispriced stocks.</p>
                                
                            <img src="images/about_us_graph.png" alt="" align="right" />
                            
                            <p><strong>We cover all U.S. industries and sectors, focusing on stocks with market caps of at least 
                            	$1 billion</strong>. We deliver our research reports to subscribers via our website 
                                (<a href="http://www.3dadvisxxors.com">www.3DAdviso</a>), and publish full reports covering 
                                behavioral and fundamental findings, update reports, and other special reports on a regular 
                                basis. We add an average of one new company report, update report on a previously covered 
                                company or special report per week.</p>
                            
                            <p><font style="color: #1f1e1e; font-weight: bold;">For all clients we will review current 
                            	and historical insider trading behavior at specified companies on request</font>. Clients 
                                frequently request trading analysis for their long as well as short positions, and often 
                                for smaller cap companies. The number of requests we will support and their priority level 
                                is dependent on the clients’ subscription level. Typically, we provide a written summary 
                                of our analysis and supporting documentation within 2 to 3 days. This research is entirely 
                                proprietary to the requesting client and not shared with any other client.</p>
                            
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