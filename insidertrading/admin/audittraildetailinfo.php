<?php
    require_once('adminincludes.php');
    require_once('../common/Date.php');
    session_start();
    validAdminUse();

    $report             = trim($_GET['report']);
    $beginDateString    = trim($_GET['begindate']);
    $endDateString      = trim($_GET['enddate']);
   
    $auditTrail = new AuditTrail();
    $today = new Date();

    if (!empty($report))
    {
        if (empty($beginDateString)) {
            $beginDateString = NULL;
        }
        if (empty($endDateString)) {
            $endDateString = NULL;
        }

        switch($report) {
        case 'user' :
            $titleString = 'User Audit Trail Detailed Report';
            $resultsHeader = 'User Logged In';
            $result = $auditTrail->retrieveUserDetailsForRange($beginDateString, $endDateString);
            break;

        case 'research' :
            $titleString = 'Research Report Audit Trail Detailed Report';
            $resultsHeader = 'User Viewed Report';
            $result = $auditTrail->retrieveReportDetailsForRange($beginDateString, $endDateString);
            break;
        }

        if ($result->isError()) 
        {
            $resultString = $result->resultString();
        }
        else
        {
//            $dateString = $beginDate->format('%D').'-'.$endDate->format('%D');
            $resultArray = $result->resultObject;
        }

    }
    else
    {
        echo '<b>Error: No report specified.  Reports will not display.</b><br>';
    }

?>
<!DOCTYPE html PUBLIC "-W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="images/favicon.png" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo $titleString; ?></title>

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

                            <h1><?php echo $titleString; ?></h1>
                            <h3>Detailed audit trail information</h3>
                            
                            <p>[<a href="audittrailinfo.php?report=<?php echo $report?>">Return to Main Audit Trail Report Page</a>]<br/>
                            	[<a href="adminmenu.php">Return to Admin Menu</a>]</p>
                          	
                            <p>
							<?php
                                 if (!(empty($resultString))) 
                                 {
                                     echo 'Error: ', $resultString;
                                 }           
                                 else
                                 {
                                     if (isset($resultArray))
                                     {
                                         switch($report) {
                                         case 'user' :
                                            echo '<h3>Report results:</h3>';
                                            echo '<table border="0" width="100%">
                                                     <tr>
													<th><p><strong>Date</strong></p></th>
                                                    <th class="search_results">', $resultsHeader, '</th>
													<th><p><strong>Company Info</strong></p></th></tr>';
                            
                                            foreach ($resultArray as $rowValue)
                                            {
                                                echo '<tr class="search_results">';
                                              
                                               echo '<td>',dateformat($rowValue['create_timestamp']), '</td>';
                                                echo '<td class="search_results" align="center">',$rowValue['txn_userid'],'</td>';
                                                echo '<td class="search_results">',$rowValue['company_name'],'</td>';
                                                echo '</tr>';
                                            }
                                            echo '</table>';
                                            break;
                            
                                         case 'research' :
                                             echo '<h3>Report results:</h3>';
                                             echo '<table border="0" width="100%">
                                                      <tr>
                                                      <th><p><strong>Date</strong></p></th>
                                                     <th class="search_results">', $resultsHeader, '</th>',
                                                     '<th><p><strong>Report Viewed</strong></p></th>
                                                      <th><p><strong>Report Date</strong></p></th></tr>';
                            
                                             foreach ($resultArray as $rowValue)
                                             {
                                                 echo '<tr class="search_results">';
                                                 echo '<td>',dateformat($rowValue['create_timestamp']), '</td>';
                                                 echo '<td class="search_results">',$rowValue['txn_userid'],'</td>';
                                                 echo '<td class="search_results">',$rowValue['company_symbol'],'</td>';
                                                  echo '<td>',reportDateFormat($rowValue['report_date']), '</td>';
                                                 //echo '<td class="search_results">',mysql_date_to_human($rowValue['report_date']),'</td>';
                                                 echo '</tr>';
                                             }
                                             echo '</table>';
                                             break;
                                         }
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