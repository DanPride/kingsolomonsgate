<?php
    //Display research report
    require_once('adminincludes.php');
    session_start();
    validAdminUse();

    //create short names for variables
    $reportid  = trim($_GET['reportid']);
    $userid = $_SESSION['userid'];

    if (empty($reportid) || empty($userid) )
    {
        echo 'Error: Unable to display report<br>';
    }
    else
    {
        $result = retrieveReportData($reportid, $userid);
        if ($result->isError()) 
        {
            echo $result->resultString();
        }
        else
        {
            $rowObject = $result->resultObject();
//            header("Pragma: public");
            header('Status: 200');
            header('Content-type: application/pdf');
            header('Content-length:'.$rowObject->report_filesize);
//            header("Content-Disposition: attachment; filename=".$rowObject->report_filename); 
            header('Content-Description: PHP Generated Data'."\r\n");
            echo $rowObject->report_file;
        }
    }
    exit;
?>

