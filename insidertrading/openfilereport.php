<?php
    require_once('baseincludes.php');
    require_once('common/Date.php');

    session_start();
    validCustomerUse();

    $result = retrieveOpenFileReport(NULL);
    if ($result->isError())
    {
        $displayString = 'Error: '.$result->resultString();

    }
    else
    {
//        $displayString = 'All reports retrieved below';
        $resultArray = $result->resultObject();
    }


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="images/favicon.png" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>3d adviso</title>

<link href="css/style.css" rel="stylesheet" type="text/css" />

<script language="JavaScript">
	<!--
	function displayDate() {
		var dayNames = new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
		var monthNames = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		
		var now = new Date();
		var name = now.getDay();
		var day = now.getDate();
		var month = now.getMonth();
		var year = now.getYear();
		if (year < 1000) {
				year +=1900;
	}
	
	return(monthNames[month]+" "+day+", "+year);
	}
	//-->
</script>
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
                        <div id="content_open_file_report">
                            <table width="100%" cellpadding="0" cellspacing="0" id="openFileReport">
                                <tr>
                                    <td align="right" colspan="8">
                                        <font face="arial" size="1" color="#777777">
                                            <script language="JavaScript">
                                                <!--
                                                    document.write (displayDate());
                                                //-->
                                            </script>
                                        </font>&nbsp;&nbsp;			
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6"><div align="left"><h2>3DAdviso, LLC Open File Report</h2></div></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" colspan="8">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" 
                                        		style="padding-top: 7px; 
                                                padding-bottom: 7px; 
                                                border-bottom: 1px solid #500;">
                                            <tr>
                                                <td align="left" valign="top" width="2" height="17">
                                                	<img src="/content/img/3da_nav_split.gif" 
                                                    	alt="" 
                                                        border="0" 
                                                        height="17" 
                                                        width="2"></td>
                                                <td align="right" valign="top" width="845" height="17">
                                                	<h5>[<a href="search.php">Search</a>]</h5></td>
                                                <td align="right" valign="top" width="56" height="17" colspan="5">
                                                	<h5>[<a href="logout.php">Logout</a>]</h5></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr class="orRow">
                                    <td width="9" class="columnHeader">&nbsp;</td>
                                    <td width="170" class="columnHeader column_company"><div style="text-align: left;">Company</div></td>
                                    <td width="61" class="columnHeader column_ticker">Ticker</td>
                                    <td width="110" class="columnHeader column_date">Date</td>
                                    <td width="253" class="columnHeader column_headline"><div style="text-align: left;">Headline</div></td>
                                    <td width="100" class="columnHeader column_insider">Insider</td>
                                    <td width="100" class="columnHeader column_accounting">Accounting</td>
                                    <td width="100" class="columnHeader column_governance">Governance</td>
                                </tr>
								<?php
                                
                                   if (!(empty($displayString))) {
                                       echo '<b>', $displayString, '<b>', '<p>';
                                   }
                                ?>
                                <?php
                                     if (!(empty($resultString))) 
                                     {
                                         echo 'Error: ', $resultString;
                                     }           
                                     else
                                     {
                                         if (isset($resultArray))
                                         {
                                            $currentCompany = $resultArray[0]['company_symbol'];
                                
                                            foreach ($resultArray as $rowValue)
                                            {
                                                if ($currentCompany == $rowValue['company_symbol']) {
                                                    ;//do nothing
                                                }
                                                else
                                                {
                                                    echo '<tr><td colspan="5">&nbsp;</td><td class="column6"></td>';
                                                    echo '<td class="column7"></td><td class="column8"></td></tr>';
                                                    $currentCompany = $rowValue['company_symbol'];
                                                }
                                
                                                echo '<tr>';
                                                echo '<td class="column1"></td>';
                                                echo '<td class="column2">',$rowValue['company_name'], '</td>';
                                                echo '<td class="column3">',$rowValue['company_symbol'],'</td>';
                                                echo '<td class="column4">',reportDateFormat($rowValue['report_date']), '</td>';
                                                echo '<td class="column5"><a href=', $displayReportURL, '?reportid=', $rowValue['id'], ' target=window', '> ',
                                                    $rowValue['report_headline'], ' </a></td>';
                                                if (($rowValue['research_insider'] == 'y') ||
                                                   ($rowValue['research_insider'] == 'Y') )
                                                {
                                                    echo '<td class="column6">','X', '</td>';
                                                }
                                                else
                                                {
                                                    echo '<td class="column6">',' ', '</td>';
                                                }
                                                if (($rowValue['research_qoe'] == 'y') ||
                                                   ($rowValue['research_qoe'] == 'Y') )
                                                {
                                                    echo '<td class="column7">','X', '</td>';
                                                }
                                                else
                                                {
                                                    echo '<td class="column7">',' ', '</td>';
                                                }
                                
                                                if (($rowValue['research_corpgov'] == 'y') ||
                                                   ($rowValue['research_corpgov'] == 'Y') )
                                                {
                                                    echo '<td class="column8">','X', '</td>';
                                                }
                                                else
                                                {
                                                    echo '<td class="column8">',' ', '</td>';
                                                }
                                                echo '</tr>';
                                            }
                                            echo '<tr><td class="topReport" colspan="8"><br/><p><a href="#openFileReport">Back to top</a></p></td></tr>';
                                            echo '</table>';
                                         }
                                     }
                                
                                ?>
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