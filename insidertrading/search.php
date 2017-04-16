<?php
    require_once('baseincludes.php');

    session_start();
    validCustomerUse();
    $companySymbol  = trim($_POST['companySymbol']);
    $companyName    = trim($_POST['companyName']);
    $reportMonth    = trim($_POST['reportMonth']);
    $reportDay      = trim($_POST['reportDay']);
    $reportYear     = trim($_POST['reportYear']);
    $userAction     = $_POST['userAction'];
    $ticker         = trim($_GET['ticker']);
    
    $reportDate     = $reportMonth.'-'.$reportDay.'-'.$reportYear;
    $reportDate = mysql_cvdate($reportDate);

    if (!(empty($ticker)))
    {
            $result = retrieveReportBySymbol($ticker);
            if ($result->isError()) 
            {
                $resultString = $result->resultString();
            }
            else
            {
                $resultArray = $result->resultObject();
            }
    }

    switch($userAction) {
        case 'Search By Ticker' :
            if(!(empty($companySymbol)))
            {
                $result = retrieveReportBySymbol($companySymbol);
                if ($result->isError()) 
                {
                    $resultString = $result->resultString();
                }
                else
                {
                    $resultArray = $result->resultObject();
                    //echo '<br>'.var_dump($resultArray);
                }
            }
            else
            {
                $displayString = 'Enter company stock symbol to search by ticker';
            }
            break;
        case 'Search By Name' :
            if(!(empty($companyName)))
            {
                $result = retrieveReportByName($companyName);
                if ($result->isError()) 
                {
                    $resultString = $result->resultString();
                }
                else
                {
                    $resultArray = $result->resultObject();
                }
            }
            else
            {
                $displayString = 'Enter company name to search by name';
            }
            break;

        	case 'Search By Date' :
	            if(!(empty($reportDate)))
	            {
	                $result = retrieveReportByDate($reportDate);
	                if ($result->isError()) 
	                {
	                    $resultString = $result->resultString();
	                }
	                else
	                {
	                    $resultArray = $result->resultObject();
	                }
	            }
	            else
	            {
	                $displayString = 'Enter report date to search by date';
	            }
	            break;
				

        case 'Browse Most Recent' :
			switch ($_POST['reportOrder'])
 				{
					case Date:
						$reportOrder = "r.report_date";
						break;
					case Ticker:
						$reportOrder = "f.company_symbol";
						break;
					case Name:
						$reportOrder = "f.company_name";
						break;
					default:
						$reportOrder = "r.report_date";
						break;
				}
			$reportOrder = "r.report_date";
            $orderString = "ORDER BY {$reportOrder} DESC" ;
            $result = retrieveAllReports($_POST['reportLength'], $orderString);
            if ($result->isError()) 
            {
                $resultString = $result->resultString();
            }
            else
            {
                $resultArray = $result->resultObject();
            }
            break;
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="images/favicon.png" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>3d adviso</title>

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
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                                <tr>
                                    <td align="center" valign="top" width="100%" height="100%">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                                            <tr>
                                                <td align="left" valign="top" width="29" height="100%" bgcolor="#ffffff">
                                                	<img src="content/img/spacer.gif" alt="" border="0" height="1" width="29"></td>
                                                <td align="left" valign="top" width="504" height="100%" bgcolor="#ffffff">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                                                        <tr>
                                                            <td align="left" valign="top" width="504" height="10" colspan="5">
                                                            	<img src="content/img/spacer.gif" alt="" border="0" height="10" width="1"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="top" width="100%" colspan="5">
                                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                    <tr>
                                                                        <td align="right" valign="top" width="342" height="17" colspan="5">
                                                                        	<h5>[<a href="logout.php">Logout</a>]</h5></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="top" width="100%" bgcolor="#bbbbbb">
                                                                <img src="content/img/spacer.gif" alt="" border="0" height="1" width="1"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="top" width="504" colspan="5">
                                                                <h3>"Open File" Report</h3>
                                                                <p>When 3DA first publishes a research report on an individual 
                                                                	company, that company is then considered an "open file" and is monitored 
                                                                    continuously along all three dimensions for further developments. The Open 
                                                                    File Report is a list of all companies that are currently being monitored, 
                                                                    and contains details on the reports that have been published on each company. 
                                                                    Click below to see the current Open File Report:</p>
                                                                <div align="center">
                                                                	<a href="opencurrentfile.php">
                                                                   Open Current File</a>
                                                                </div>
                                                                <br>
                                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                    <tr>
                                                                        <td align="left" valign="top" width="100%" bgcolor="#bbbbbb">
                                                                        	<img src="content/img/spacer.gif" alt="" border="0" height="1" width="1"></td>
                                                                    </tr>									
                                                                </table>
                                                                <h3>Search research reports</h3>
                                                                <p class="hpContent">Enter search criteria or select one of the displayed reports:<br><b>Add .pdf to the file name if not viewing</br>
	<br><a href="rawFileList.htm">Raw Files List and SQL Download</a></b></p>
                                                                <p class="hpContent">Enter search criteria or select one of the displayed reports:</p>
                                                                <p class="hpContent">
																	<?php
                                                                         if (!(empty($displayString))) {
                                                                             echo $displayString, '<br>';
                                                                         }
                                                                    ?>
                                                                <form method="post" action=<?php echo "$_SERVER[PHP_SELF]", '#searchresults'; ?> >
                                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                        <tr class="searchRow">
                                                                            <th align="left" valign="top" width="195"><h4>Company Stock Ticker</h4></th>
                                                                            <td align="left" valign="top" width="224"> 
                                                                            	<input type="text" name="companySymbol" size=5 maxlength=5 class="login"> </td>
                                                                            <td align="left" valign="top" width="343"> 
                                                                            	<input type="submit" name="userAction" value="Search By Ticker" class="searcha"> </td>
                                                                        </tr>
                                                                        <tr class="searchRow">
                                                                            <th align="left" valign="top" width="195"><h4>Company Name</h4></th>
                                                                            <td align="left" valign="top" width="224"> 
                                                                            	<input type="text" name="companyName" size=32 maxlength=128 class="login"> </td>
                                                                            <td align="left" valign="top" width="343"> 
                                                                            	<input type="submit" name="userAction" value="Search By Name" class="searcha"> </td>
                                                                        </tr>
                                                                        <tr class="searchRow">
                                                                            <th align="left" valign="top" width="195"><h4>Report Date</h4></th>
                                                                            <td align="left" valign="top" width="224"> <?php dateSelector("report"); ?> </td>
                                                                            <td align="left" valign="top" width="343"> 
                                                                            	<input type="submit" name="userAction" value="Search By Date" class="searcha"> </td>
                                                                        </tr>
                                                                        <tr class="searchRow">
                                                                           	<td colspan="3" align="left" valign="top" class="search_results"><br>
                                                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                <input type="submit" name="userAction" value="Browse Most Recent" class="searchb">&nbsp;&nbsp;
                                                                    				<?php lengthPop(); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    			
                                                                    			<input type="reset" value="Reset fields" class="searchb">&nbsp;&nbsp;
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </form>
																
                                                                <p>&nbsp;</p>
																<?php
                                                                     echo '<a name="searchresults"></A>';
                                                                     if (!(empty($resultString))) 
                                                                     {
                                                                         echo '<h3>Search results</h3>';
                                                                         echo 'Sorry: ', $resultString;
                                                                     }           
                                                                     else
                                                                     {
                                                                      if (isset($resultArray))
                                                                      {
                                                                          echo '<h3>Search results</h3>';
                                                                          echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                              <tr class="searchRow">
                                                                                  <th align="left" valign="top" width="60"><p><strong>DateR</strong></p></th>
                                                                                  <th align="left" valign="top" width="70"><p><strong>Ticker</strong></p></th>
                                                                                  <th align="left" valign="top" width="130"><p><strong>Company Name</strong></p></th>
                                                                                  <th align="left" valign="top" width="240"><p><strong>Headline</strong></p></th>
                                                                              </tr>';
                            
                                                                          foreach ($resultArray as $rowValue)
                                                                          {
                                                                              echo '<tr class=searchRow>';
                                                                              echo '<td class="search_results" align=left valign=top class=sResults width=60>',substr($rowValue['report_date'],0,10), '</td>';
                                                                              echo '<td class="search_results" align=left valign=top class=sResults width=70>',$rowValue['company_symbol'],'</td>';
                                                                              echo '<td class="search_results" align=left valign=top class=sResults width=130>',$rowValue['company_name'], '</td>';
                                                                              echo '<td class="search_results" align=left valign=top class=sResults width=240>
																			  	<a href=', $displayReportURL, '?reportid=', $rowValue['report_id'], ' target=window', '> ',
                                                                                    $rowValue['report_headline'], ' </a></td>';
                                                                              echo '</tr>';
                                                                              echo '<tr>';
                                                                        	}
                                                                    	echo '</table>';
                                                                	}
                                                            	}
                                                        	?>
                                                        </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="top" width="504" height="100%" colspan="5">
                                                                <p class="copy"><a href="#top">New Search</a></p>
                                                                <p>&nbsp;</p>
                                                                <p class="copy"><a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank">
                                                                    	<img src="content/img/getadobe.gif" alt="Get the Adobe Acrobat Reader" 
                                                                        	border="0" 
                                                                            width="88" 
                                                                            height="31"></a><br>
                                                                    You will need the <a href="http://www.adobe.com/products/acrobat/readstep2.html" 
                                                                    	target="_blank">Adobe Acrobat</a> reader to view the reports.
                                                                </p>
                                                                <p class="copy"><a href="javascript:void(0)" 
                                                                	onclick="window.open('content/copyright.html','','width=500,height=300,scrollbars=yes');" 
                                                                    onmouseout="window.status=' '" 
                                                                    onmouseover="window.status=' '; return true;">3DAdviso, LLC Copyright and Disclaimer</a></p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td align="left" valign="top" width="29" height="100%" bgcolor="#ffffff">
                                                	<img src="content/img/spacer.gif" alt="" border="0" height="1" width="29"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
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