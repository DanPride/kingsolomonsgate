<?php
    require_once('adminincludes.php');
    session_start();
    validAdminUse();

    //create short names for variables
    $reportFileTempLocation = $_FILES['userfile']['tmp_name'];
    $reportFileName = $_FILES['userfile']['name'];
    $reportFileSize = $_FILES['userfile']['size'];
    $reportFileType = $_FILES['userfile']['type'];
    $reportFileError = $_FILES['userfile']['error'];
    
    $companySymbol  = trim($_POST['companySymbol']);
    $companyName    = trim($_POST['companyName']);
    $reportActive   = $_POST['reportActive'];
    $reportUpdate   = $_POST['reportUpdate'];
    $reportHeadline = trim($_POST['reportHeadline']);
    $reportAuthor   = trim($_POST['reportAuthor']);
    $researchTypeGov = $_POST['researchTypeGov'];
    $researchTypeInsider = $_POST['researchTypeInsider'];
    $researchTypeQoe = $_POST['researchTypeQoe'];
    $reportMonth    = trim($_POST['reportMonth']);
    $reportDay      = trim($_POST['reportDay']);
    $reportYear     = trim($_POST['reportYear']);
    $companySymbol1  = trim($_POST['companySymbol1']);
    $companyName1    = trim($_POST['companyName1']);
    $companySymbol2  = trim($_POST['companySymbol2']);
    $companyName2    = trim($_POST['companyName2']);
    $companySymbol3  = trim($_POST['companySymbol3']);
    $companyName3    = trim($_POST['companyName3']);
    $companySymbol4  = trim($_POST['companySymbol4']);
    $companyName4    = trim($_POST['companyName4']);
    $companySymbol5  = trim($_POST['companySymbol5']);
    $companyName5    = trim($_POST['companyName5']);
    $companySymbol6  = trim($_POST['companySymbol6']);
    $companyName6    = trim($_POST['companyName6']);
    $companySymbol7  = trim($_POST['companySymbol7']);
    $companyName7    = trim($_POST['companyName7']);
    $companySymbol8  = trim($_POST['companySymbol8']);
    $companyName8    = trim($_POST['companyName8']);
    $companySymbol9  = trim($_POST['companySymbol9']);
    $companyName9    = trim($_POST['companyName9']);
    $companySymbol10  = trim($_POST['companySymbol10']);
    $companyName10    = trim($_POST['companyName10']);
    $companySymbol11  = trim($_POST['companySymbol11']);
    $companyName11    = trim($_POST['companyName11']);
    $companySymbol12  = trim($_POST['companySymbol12']);
    $companyName12    = trim($_POST['companyName12']);
    $companySymbol13  = trim($_POST['companySymbol13']);
    $companyName13    = trim($_POST['companyName13']);
    $companySymbol14  = trim($_POST['companySymbol14']);
    $companyName14    = trim($_POST['companyName14']);
    $companySymbol15  = trim($_POST['companySymbol15']);
    $companyName15    = trim($_POST['companyName15']);
    $reportAction    = $_POST['reportAction'];

    $companySymbol = strtoupper($companySymbol);
    $reportDate     = $reportMonth.'-'.$reportDay.'-'.$reportYear;
    $reportDate = mysql_cvdate($reportDate);
//    echo $reportDate;
//    $reportDate = cvdate($reportDate);
//    echo $reportDate;
//    $reportDate = timestamp_to_mysql_date($reportDate);
//    echo $reportDate;

    if (!empty($reportAction))
    {

        if (empty($researchTypeGov)) {
            $researchTypeGov = 'n';
        }

        if (empty($researchTypeInsider)) {
            $researchTypeInsider = 'n';
        }

        if (empty($researchTypeQoe)) {
            $researchTypeQoe = 'n';
        }

        switch($reportAction) {
            case 'Create' :
                //Create a Report
                if ($reportFileError > 0)
                {
                    switch ($reportFileError) 
                    {
                        case 1: $displayString =  'Error: File exceeded upload_max_filesize'; break;
                        case 2: $displayString =  'Error: File exceeded max_file size'; break;
                        case 3: $displayString =  'Error: File only partially uploaded'; break;
                        case 4: $displayString =  'Error: No file uploaded'; break;
                    }
                }
                else
                {
                    if(empty($companySymbol)||empty($companyName)||empty($reportAuthor)||empty($reportDate))
                    {
                        $displayString = 'Please enter required fields: Company Symbol, Company Name, Author, and Date';
                    }
                    else
                    {
                       if (is_uploaded_file($reportFileTempLocation))
                       {
                           $reportFileLocation = $ReportFilePath.$reportFileName;
//                           $reportFileLocation = addSlashes($reportFileLocation);

                           if ((!move_uploaded_file($reportFileTempLocation, $reportFileLocation)))
                           {
                           	$displayString = "TempLoc - " . $reportFileTempLocation . 'ReportFileLoc - ' . $reportFileLocation;
                              // $displayString = 'Error: could not move file to final destination location';
                           }
                           else
                           {
                               $refArray = createReferencedCompanyArray ($companySymbol, $companyName,
                                            $companySymbol1, $companyName1, $companySymbol2, $companyName2,
                                            $companySymbol3, $companyName3, $companySymbol4, $companyName4,
                                            $companySymbol5, $companyName5, $companySymbol6, $companyName6,
                                            $companySymbol7, $companyName7, $companySymbol8, $companyName8,
                                            $companySymbol9, $companyName9, $companySymbol10, $companyName10,
                                            $companySymbol11, $companyName11, $companySymbol12, $companyName12,
                                            $companySymbol13, $companyName13, $companySymbol14, $companyName14,
                                            $companySymbol15, $companyName15);

                               $dbResult = createReport($companySymbol, $companyName, $reportActive, $reportUpdate,
                                                 $reportHeadline, $reportAuthor, $researchTypeGov, $researchTypeInsider,
                                                 $researchTypeQoe, $reportDate, $reportFileLocation,
                                                 $refArray);

                               if (!($dbResult))
                               {
                                   $displayString = 'Error: could not create report db record';
                               }
                               else
                               {
                                   $displayString = 'File uploaded successfully';
                               }

                           }
                        }
                        else
                        {
                           $displayString = 'Error: possible file upload problem. Filename: '.$reportFileName;
                        }

                    }

                }
                break;

            case 'Retrieve' :
            //Retrieve a Report
            if(empty($companySymbol))
            {
                $displayString = 'Please enter Company Symbol to retrieve report';
            }
            else
            {
                $result = retrieveReportBySymbol($companySymbol);
                if ($result->isError())
                {
                    $displayString = 'Error: '.$result->resultString();

                }
                else
                {
                    $displayString = 'All reports retrieved';
                    $resultArray = $result->resultObject();
                }
            }

            break;

        case 'Update' :
            //Update a Report
            if(empty($companySymbol)||empty($reportDate))
            {
                $displayString = 'Please enter required fields: Company Symbol and Report Date';
            }
            else
            {
                $refArray = createReferencedCompanyArray ($companySymbol, $companyName,
                             $companySymbol1, $companyName1, $companySymbol2, $companyName2,
                             $companySymbol3, $companyName3, $companySymbol4, $companyName4,
                             $companySymbol5, $companyName5, $companySymbol6, $companyName6,
                             $companySymbol7, $companyName7, $companySymbol8, $companyName8,
                             $companySymbol9, $companyName9, $companySymbol10, $companyName10,
                             $companySymbol11, $companyName11, $companySymbol12, $companyName12,
                             $companySymbol13, $companyName13, $companySymbol14, $companyName14,
                             $companySymbol15, $companyName15);

                $result = updateReport($companySymbol, $companyName, $reportActive, $reportUpdate,
                                                 $reportHeadline, $reportAuthor, $researchTypeGov, $researchTypeInsider,
                                                 $researchTypeQoe, $reportDate, $reportFileLocation, $refArray);
                if ($result->isSuccess())
                {
                    $displayString = "Report data successfully updated";
                }
                else
                {
                    $displayString = 'Error: '.$result->resultString();
                }
            }
            break;

       /* case 'Delete' :
            //Delete a Report
            if(empty($companySymbol)||empty($reportDate))
            {
                $displayString = 'Please enter Company Symbol and Report Date to delete report';
            }
            else
            {
                $result = deleteReport($companySymbol, $reportDate);
                if ($result->isError())
                {
                    $displayString = 'Error: '.$result->resultString();

                }
                else
                {
                    $displayString = 'Report successfully deleted';

                }
            }
            break;
*/
        case 'Retrieve All' :
            //Retrieve all report
            $result = retrieveAllReportsAdmin();
            if ($result->isError())
            {
                $displayString = 'Error: '.$result->resultString();

            }
            else
            {
                $displayString = 'All reports retrieved below';
                $resultArray = $result->resultObject();
            }
            break;
      }
  }

?>
<!DOCTYPE html PUBLIC "-W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="images/favicon.png" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Create/Retrieve/Update/Delete Reports || 3d adviso</title>

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

                            <h2 style="margin-top: 50px;">Create/Retrieve/Update/Delete Research Reports</h2>
                            
                            <ul>
                                <li>Create new report by entering new report information and clicking 'Create'</li>
                                <li>Retrieve existing report information by entering report data and clicking 'Retrieve'</li>
                                <!--
                                <li>Update existing report information by entering company symbol and report date (which cannot be 
                                    updated), add data to other fields for updating, and click 'Update'</li> 
                                -->
                                <li>Delete existing report by entering company symbol and report date and then click 'Delete'</li>
                            </ul>
                            
                            <p>[<a href="adminmenu.php">Return to Admin Menu</a>]</p>
                        
							<?php
                            
                               if (!(empty($displayString))) {
                                   echo '<b>', $displayString, '<b>', '<p>';
                               }
                            ?>
                            
                          <form enctype="multipart/form-data" action=<?php echo "$_SERVER[PHP_SELF]"; ?> method="post">
                            
                            <table border="0" width="100%">
                                <tr>
                                    <td colspan="2" align="center">
                                    <input type="submit" name ="reportAction" value="Create">
                                    <input type="submit" name ="reportAction" value="Retrieve">
                                    <!-- 
                                    <input type="submit" name ="reportAction" value="Update"> 
                                    -->
                                    <input type="submit" name ="reportAction" value="Delete">
                                    <input type="submit" name ="reportAction" value="Retrieve All">
                                    <input type="reset" value="Reset Screen">
                                    </td>
                                </tr>
                                <tr>
                                    <th><h5>Company Stock Symbol</h5></th>
                                    <td> <input type="text" name="companySymbol" size=5 maxlength=5> </td>
                                </tr>
                                <tr>
                                    <th><h5>Company Name</h5></th>
                                    <td> <input type="text" name="companyName" size=56 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <th><h5>Report Active/Inactive</h5></th>
                                    <td> <input type="radio" checked name="reportActive"  value="y"> <span style="font-size: 12px;">Active</span>
                                         <input type="radio" name="reportActive"  value="n"> <span style="font-size: 12px;">Inactive</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><h5>Report New/Update</h5></th>
                                    <td> <input type="radio" checked name="reportUpdate"  value="n"> <span style="font-size: 12px;">New Report</span>
                                         <input type="radio" name="reportUpdate"  value="y"> <span style="font-size: 12px;">Updated Report</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><h5>Report Headline</h5></th>
                                    <td> <input type="text" name="reportHeadline" size=64 maxlength=255> </td>
                                </tr>
                                <tr>
                                    <th><h5>Report Author</h5></th>
                                    <td> <input type="text" name="reportAuthor" size=64 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <th><h5>Research Type</h5></th>
                                    <td> <input type="checkbox" name="researchTypeGov" value='y'><span style="font-size: 12px;">Corporate Governance</span>
                                         <input type="checkbox" name="researchTypeInsider" value='y'> <span style="font-size: 12px;">Insider Trading</span>
                                         <input type="checkbox" name="researchTypeQoe" value='y'> <span style="font-size: 12px;">Quality of Earnings</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><h5>Report Date</h5></th>
                                    <td> <?php dateSelector("report"); ?> </td>
                                </tr>
                              <tr>
                                <th><h5>Report File to Upload</h5></th>
                                    <td> <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                                         <input type="file" name="userfile" size=56 maxlength=255> </td>
                              </tr>
                            </table>
                            
                            <br/>
                            
                            <h3>Additional Report References:</h3>
                            
                            <table border="0" width="82%">
                                <tr>
                                    <th width="20%"><h5>Company Symbol</h5></th>
                                    <th width="80%"><h5 style="text-align: center;">Company Name</h5></th>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol1" size=5 maxlength=5> </td>
                                    <td align="center"><input type="text" name="companyName1" size=100 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol2" size=5 maxlength=5> </td>
                                    <td align="center"><input type="text" name="companyName2" size=100 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol3" size=5 maxlength=5> </td>
                                    <td align="center"><input type="text" name="companyName3" size=100 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol4" size=5 maxlength=5> </td>
                                    <td align="center"> <input type="text" name="companyName4" size=100 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol5" size=5 maxlength=5> </td>
                                    <td align="center"> <input type="text" name="companyName5" size=100 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol6" size=5 maxlength=5> </td>
                                    <td align="center"> <input type="text" name="companyName6" size=100 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol7" size=5 maxlength=5> </td>
                                    <td align="center"> <input type="text" name="companyName7" size=100 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol8" size=5 maxlength=5> </td>
                                    <td align="center"> <input type="text" name="companyName8" size=100 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol9" size=5 maxlength=5> </td>
                                    <td align="center"> <input type="text" name="companyName9" size=100 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol10" size=5 maxlength=5> </td>
                                    <td align="center"> <input type="text" name="companyName10" size=100 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol11" size=5 maxlength=5> </td>
                                    <td align="center"> <input type="text" name="companyName11" size=100 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol12" size=5 maxlength=5> </td>
                                    <td align="center"> <input type="text" name="companyName12" size=100 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol13" size=5 maxlength=5> </td>
                                    <td align="center"> <input type="text" name="companyName13" size=100 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol14" size=5 maxlength=5> </td>
                                    <td align="center"> <input type="text" name="companyName14" size=100 maxlength=128> </td>
                                </tr>
                                <tr>
                                    <td align="right"><input style="margin-right: 10px;" type="text" name="companySymbol15" size=5 maxlength=5> </td>
                                    <td align="center"> <input type="text" name="companyName15" size=100 maxlength=128> </td>
                                </tr>
                            </table>
                            <br>
                            <table>
                            <tr>
                                <td colspan="2" align="center">
                                <input type="submit" name ="reportAction" value="Create">
                                <input type="submit" name ="reportAction" value="Retrieve">
                        		<!-- 
                                <input type="submit" name ="reportAction" value="Update">  
                                -->
                                <input type="submit" name ="reportAction" value="Delete">
                                <input type="submit" name ="reportAction" value="Retrieve All">
                                <input type="reset" value="Reset Screen">
                                </td>
                            </tr>
                            </table>
                            </form>
                        
                        <?php
                             if (!(empty($resultString))) 
                             {
                                 echo 'Error: ', $resultString;
                             }           
                             else
                             {
                                 if (isset($resultArray))
                                 {
                                    echo '<table border="0"><tr>
                                            <tr>
                                            <th><p><strong>Date</strong></p></th>
                                            <th><p><strong>Company Symbol</strong></p></th>
                                            <th><p><strong>Company Name</strong></p></th>
                                            <th><p><strong>Headline</strong></p></th>
                                            <th><p><strong>Author</strong></p></th>
                                            </tr>';
                                 
                                    foreach ($resultArray as $rowValue)
                                    {
                                        echo '<tr>';
                                        echo '<td class="search_results">',reportDateFormat($rowValue['report_date']), '</td>';
                                        echo '<td class="search_results" style="text-align: center;">',$rowValue['company_symbol'],'</td>';
                                        echo '<td class="search_results">',$rowValue['company_name'], '</td>';
                                        echo '<td class="search_results"><a href=', $adminDisplayReportURL, '?reportid=', $rowValue['id'], ' target=window', '> ',
                                            $rowValue['report_headline'], ' </a></td>';
                                        echo  '<td class="search_results">',$rowValue['report_author'], '</td>';
                                        echo '</tr>';
                                    }
                                    echo '</table>';
                                 }
                             }
                        
                             function createReferencedCompanyArray ($companySymbol1, $companyName1, $companySymbol2, $companyName2,
                                                                    $companySymbol3, $companyName3, $companySymbol4, $companyName4,
                                                                    $companySymbol5, $companyName5, $companySymbol6, $companyName6,
                                                                    $companySymbol7, $companyName7, $companySymbol8, $companyName8,
                                                                    $companySymbol9, $companyName9, $companySymbol10, $companyName10,
                                                                    $companySymbol11, $companyName11, $companySymbol12, $companyName12,
                                                                    $companySymbol13, $companyName13, $companySymbol14, $companyName14,
                                                                    $companySymbol15, $companyName15)
                             {
                                 $companyArray = array();
                                 if (!(empty($companySymbol1)))
                                     $companyArray[$companySymbol1] = $companyName1;
                                 if (!(empty($companySymbol2)))
                                     $companyArray[$companySymbol2] = $companyName2;
                                 if (!(empty($companySymbol3)))
                                     $companyArray[$companySymbol3] = $companyName3;
                                 if (!(empty($companySymbol4)))
                                     $companyArray[$companySymbol4] = $companyName4;
                                 if (!(empty($companySymbol5)))
                                     $companyArray[$companySymbol5] = $companyName5;
                                 if (!(empty($companySymbol6)))
                                     $companyArray[$companySymbol6] = $companyName6;
                                 if (!(empty($companySymbol7)))
                                     $companyArray[$companySymbol7] = $companyName7;
                                 if (!(empty($companySymbol8)))
                                     $companyArray[$companySymbol8] = $companyName8;
                                 if (!(empty($companySymbol9)))
                                     $companyArray[$companySymbol9] = $companyName9;
                                 if (!(empty($companySymbol10)))
                                     $companyArray[$companySymbol10] = $companyName10;
                                 if (!(empty($companySymbol11)))
                                     $companyArray[$companySymbol11] = $companyName11;
                                 if (!(empty($companySymbol12)))
                                     $companyArray[$companySymbol12] = $companyName12;
                                 if (!(empty($companySymbol13)))
                                     $companyArray[$companySymbol13] = $companyName13;
                                 if (!(empty($companySymbol14)))
                                     $companyArray[$companySymbol14] = $companyName14;
                                 if (!(empty($companySymbol15)))
                                     $companyArray[$companySymbol15] = $companyName15;
                        
                                 return $companyArray;
                             }
                        ?>
                            
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
    
    </div>
    
    <?php include("../includes/analytics.php"); ?>
    
</body>
</html>