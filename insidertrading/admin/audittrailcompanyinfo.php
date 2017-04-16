<?php
    require_once('adminincludes.php');
    require_once('../common/Date.php');
    session_start();
    validAdminUse();

    $report         = trim($_GET['report']);
    $companySymbol   = trim($_POST['companySymbol']);
    $reportAction   = $_POST['reportAction'];

    $auditTrail = new AuditTrail();

    if (!empty($reportAction))
    {
        if (!empty($companySymbol)) {
            //Retrieve Company Report Views Report
            $result = $auditTrail->retrieveCompanyReportViews($companySymbol);
            if ($result->isError()) 
            {
                $resultString = $result->resultString();
            }
            else
            {
                $resultArray = $result->resultObject;
            }
        }
        else
        {
            echo '<b>Error - Company ticker symbol not entered</b>';
        }
    }

    function formatBeginDateString($inDate)
    {
        $dateString = substr_replace($inDate->getDate(DATE_FORMAT_TIMESTAMP), '000000', 8, 6);
        return $dateString;
    }
    
    function formatEndDateString($inDate)
    {
        $dateString = substr_replace($inDate->getDate(DATE_FORMAT_TIMESTAMP), '235959', 8, 6);
        return $dateString;
    }

?>
<!DOCTYPE html PUBLIC "-W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="images/favicon.png" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Research Report Views by User || 3d adviso</title>

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

                            <h1>Research Report Views By User</h1>
                            
                            <p>Enter the company ticker symbol to retrieve report of company research reports viewed by users</p>
                            
                            <p>[<a href="adminmenu.php">Return to Admin Menu</a>]</p>
                            
                            <p>
                            <form method="post" action=<?php echo "$_SERVER[PHP_SELF]"; ?>>
                            <table border="0" cols=2>
                                <tr>
                                    <th><h5>Company Ticker Symbol</h5></th>
                                    <td><input type="text" name="companySymbol" size=5 maxlength=5></td>
                                    <td><input type="submit" name ="reportAction" value="Submit"></td>
                                </tr>
                            </table>
                            </form><p>
							<?php
                                 if (!(empty($resultString))) 
                                 {
                                     echo '<b>Error:</b> ', $resultString;
                                 }           
                                 else
                                 {
                                     if (isset($resultArray))
                                     {
                                        echo '<h3>Report results for: ', $companySymbol, '</h3>';
                                        echo '<table border="0" width="100%">
                                                <tr>
                                                <th class="search_results">Report View Date</th>
                                                <th class="search_results">User Name</th>
                                                <th class="search_results">Client Name</th>
                                                <th class="search_results">Report Date</th></tr>';
                                        foreach ($resultArray as $rowValue)
                                        {   echo '<tr>';
                                            echo '<td class="search_results" align="center">', dateFormat($rowValue['create_timestamp'], 'EST'), '</td>';
                                            echo '<td class="search_results" align="center">', $rowValue['txn_userid'], '</td>';
                                            echo '<td class="search_results" align="center">', $rowValue['company_name'], '</td>';
                                            echo '<td class="search_results" align="center">', reportDateFormat($rowValue['report_date']), '</td>';
                                            echo '</tr>';
                                        }
                                        echo '</table>';
                                     }
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