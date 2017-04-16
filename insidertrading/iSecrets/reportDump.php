<?php require_once("connection.php"); 
 require_once("report.php"); 
$result = mysql_query("SELECT * FROM 3da_reports");
while($row = mysql_fetch_array($result))
  {
		  header('Status: 200');
          header('Content-type: application/pdf');
          header('Content-length:'.$rowObject->report_filesize);
//            header("Content-Disposition: attachment; filename=".$rowObject->report_filename); 
          header('Content-Description: PHP Generated Data'."\r\n");
          echo $row['report_file'];
  }
 ?>
