<?php
    $errorType = $_GET['error_type'];
    switch($errorType) {
        case 'system' :
            $errorTitle = 'System Error';
            $errorText = 'There was a system error or the system is temporarily unavailable.  Please hit the back button and try again or try again later at <a href="index.php">www.3dadviso.com</a>.';
            break;
        case 'authorization' :
            $errorTitle = 'Authorization Error';
            $errorText  = 'You are not authorized to view this page.  Please hit the back button or return to the <a href="index.php">home page</a>.';
            break;
        default :
            $errorTitle = 'Page Request Error';
            $errorText  = 'An invalid page request or other error occurred.  Please hit the back button and try again or return to the <a href="index.php">home page</a> and login again.';
            break;
    }
?>
<!DOCTYPE html PUBLIC "-W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="images/favicon.png" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>3D adviso - <?php echo $errorTitle; ?></title>

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
                        
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                                <tr>
                                    <td align="center" valign="top" width="100%" height="100%">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                                            <tr>
                                                <td align="left" valign="top" width="29" height="100%" bgcolor="#FFFFFF"><img src="../content/img/spacer.gif" alt="" border="0" height="1" width="29"></td>
                                                <td align="left" valign="top" width="504" height="100%" bgcolor="#FFFFFF">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                                                        <tr>
                                                            <td align="left" valign="top" width="504" height="10" colspan="5"><img src="../content/img/spacer.gif" alt="" border="0" height="10" width="1"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="top" width="362"><img src="../content/img/spacer.gif" alt="" border="0" height="1" width="1"></td>
                                                            <td align="left" valign="top" width="1"><img src="../content/img/spacer.gif" alt="" border="0" height="1" width="1"></td>
                                                            <td align="left" valign="top" width="1"><img src="../content/img/spacer.gif" alt="" border="0" height="1" width="1"></td>
                                                            <td align="left" valign="top" width="20"><img src="../content/img/spacer.gif" alt="" border="0" height="1" width="20"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="top" width="504" height="17" colspan="5"><img src="../content/img/spacer.gif" alt="" border="0" height="17" width="1"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="top" width="504" height="4" colspan="5" background="../content/img/3da_nav_border.gif"><img src="../content/img/spacer.gif" alt="" border="0" height="1" width="1"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="top" width="504" height="100%" colspan="5">
                                                                <p class="sTitle"><?php echo $errorTitle; ?></p><br>
                                                                <?php echo $errorText; ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
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