<?php require_once("connection.php"); 


$result = mysql_query("SELECT * FROM 3da_report_references");
echo "Reports list and column headers from 3da_report_references<br>";
echo "reportid, company_name, company_symbol<br><br>";
while($row = mysql_fetch_array($result))
  {
  echo $row['report_id'] . ", " . $row['company_name'] . ", " . $row['company_symbol'];
  echo "<br />";
  }
 ?>
