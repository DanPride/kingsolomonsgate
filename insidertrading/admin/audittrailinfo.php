<?php
    require_once('adminincludes.php');
    require_once('../common/Date.php');
    session_start();
    validAdminUse();

    $report         = trim($_GET['report']);
    $beginYear      = trim($_POST['beginYear']);
    $beginMonth     = trim($_POST['beginMonth']);
    $beginDay       = trim($_POST['beginDay']);
    $endYear        = trim($_POST['endYear']);
    $endMonth       = trim($_POST['endMonth']);
    $endDay         = trim($_POST['endDay']);
    $reportAction   = $_POST['reportAction'];

    $beginDate = new Date();
    $beginDate->setYear($beginYear);
    $beginDate->setMonth($beginMonth);
    $beginDate->setDay($beginDay);
    $endDate = new Date();
    $endDate->setYear($endYear);
    $endDate->setMonth($endMonth);
    $endDate->setDay($endDay);
    $auditTrail = new AuditTrail();
    $today = new Date();
    $beginDateString = NULL;
    $endDateString = NULL;

    if (!empty($report))
    {
        switch($report) {
        case 'user' :
            $titleString = 'User Audit Trail Reports*';
            $txn_id = TX_LOGIN;
            $resultsHeader = '<span class="search_results">Number of User Logins</span>';
        
            break;

        case 'research' :
            $titleString = 'Research Report Audit Trail Reports*';
            $txn_id = TX_VIEWREPORT;
            $resultsHeader = 'Number of Research Report Views';
            break;
        }
    }
    else
    {
        echo '<b>Error: No report specified.  Reports will not display.</b><br>';
    }

    if (!empty($reportAction))
    {
        switch($reportAction) {
            case 'Yesterday' :
                //Retrieve Report for Yesterday
                $yesterday = $today->getPrevDay();
                $beginDateString = formatBeginDateString($yesterday);
                $endDateString = formatEndDateString($yesterday);
                $result = $auditTrail->retrieveTotalsForRange($beginDateString, $endDateString, $txn_id);
                if ($result->isError()) 
                {
                    $resultString = $result->resultString();
                }
                else
                {
                    $dateString = $yesterday->format('%D');
                    $rowObject = $result->resultObject;
                }
                break;

            case 'Today' :
                //Retrieve Report for Today
                $beginDateString = formatBeginDateString($today);
                $endDateString = formatEndDateString($today);
                $result = $auditTrail->retrieveTotalsForRange($beginDateString, $endDateString, $txn_id);
                if ($result->isError()) 
                {
                    $resultString = $result->resultString();
                }
                else
                {
                    $dateString = $today->format('%D');
                    $rowObject = $result->resultObject;
                }
                break;

            case 'Specified Range' :
                //Retrieve Report for Specified Range
                $beginDateString = formatBeginDateString($beginDate);
                $endDateString = formatEndDateString($endDate);
                $result = $auditTrail->retrieveTotalsForRange($beginDateString, $endDateString, $txn_id);
                if ($result->isError()) 
                {
                    $resultString = $result->resultString();
                }
                else
                {
                    $dateString = $beginDate->format('%D').'-'.$endDate->format('%D');
                    $rowObject = $result->resultObject;
                }
                break;

            case 'Total Count' :
                //Retrieve Report for Entire Range
                $result = $auditTrail->retrieveTotals($txn_id);
                if ($result->isError()) 
                {
                    $resultString = $result->resultString();
                }
                else
                {
                    $dateString = 'All';
                    $rowObject = $result->resultObject;
                }
                break;
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
							
							<p>Select Audit Trail report range and, if necessary, specify dates to view report</p>
							
							<p>[<a href="adminmenu.php">Return to Admin Menu</a>]</p>
							
							<p>
							<form method="post" action=<?php echo "$_SERVER[PHP_SELF]",'?report=',$report; ?>>
							<table width="668" border="0" cols=2>
							<tr>
								<th width="73"><h5>Begin Date</h5></th>
								<td width="220"> <?php dateSelector("begin"); ?> </td>
								<td width="10"></td>
								<th width="63"><h5>End Date</h5></th>
								<td width="252"> <?php dateSelector("end"); ?> </td>
								<td width="24"></td>
							</tr>
							</table>
							<table border="0" cols=2>
								<tr>
                                    <td colspan="2" align="center">
                                    <input type="submit" name ="reportAction" value="Yesterday">
                                    <input type="submit" name ="reportAction" value="Today">
                                    <input type="submit" name ="reportAction" value="Total Count">
                                    <input type="submit" name ="reportAction" value="Specified Range">
                                    </td>
                              </tr>
							</table>
							</form>
							
							<p>
							<?php
								 if (!(empty($resultString))) 
								 {
									 echo 'Error: ', $resultString;
								 }           
								 else
								 {
									 if (isset($rowObject))
									 {
										echo '<h3>Report results:</h3>';
										echo '<table border="0" width="300"><tr>
												<tr>
												<th width="150" align="left" class="search_results">Date</th>
												<th width="150" align="center">', $resultsHeader, '</th></tr>';
										echo '<tr>';
										echo '<td class="search_results">', $dateString, '</td>';
										echo '<td class="search_results" align="center">', '<a href=audittraildetailinfo.php?report=', $report, '&begindate=', 
											$beginDateString, '&enddate=', $endDateString, '>', $rowObject->total, '</a>', '</td>';
										echo '</tr>';
										echo '</table>';
									 }
								 }
							?>
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
    
    </div>
    
    <?php include("../includes/analytics.php"); ?>
    
</body>
</html>