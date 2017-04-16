<?php require_once("connection.php"); 


$result = mysql_query("SELECT * FROM 3da_reports");
echo "Reports list and column headers from 3da advisors<br>";
echo "id, company_name, company_symbol, report_active, report_update, report_headline, report_author, research_corpgov, research_insider, research_qoe, report_date, report_filename, create_timestamp, report_filesize<br><br><br>";
while($row = mysql_fetch_array($result))
  {
  echo $row['id'] . ", " . $row['company_name'] . ", " . $row['company_symbol'] . ", " . $row['report_active'] . ", " . $row['report_update']
. ", " . $row['report_headline']. ", " . $row['report_author']. ", " . $row['research_corpgov']. ", " . $row['research_insider']. ", " . $row['research_qoe']. ", " . $row['report_date']. ", " . $row['report_filename']. ", " . $row['create_timestamp']. ", " . $row['report_filesize'];
  echo "<br /><br /><br />";
  }
 ?>
