<?php
    require_once('adminincludes.php');
    session_start();
    validAdminUse();
?>
<!DOCTYPE html PUBLIC "-W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="images/favicon.png" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Administration Menu || 3d adviso</title>

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

                            <h1>3DA Administration Functions</h1>
                            
                            <p>Please select the Administration Function to perform</p>
                        
                            <h3 style="margin-left: 65px;">Admin Functions:</h3>
                                
                            <p style="margin-left: 75px;"><a href='edituser.php'>Edit Users</a><br/>
                                <a href='editreport.php'>Edit Research Reports</a><br/>
                                <a href='audittrailinfo.php?report=user'>View User Audit Trail Reports</a><br/>
                                <a href='audittrailinfo.php?report=research'>View Research Report Audit Trail Report - Summary</a><br/>
                                <a href='audittrailcompanyinfo.php'>View Research Report Audit Trail Report - Reports Views By User (test mode)</a><br/>
                                <a href='adminlogout.php'>Logout</a></p>
                            
                            <br/>
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