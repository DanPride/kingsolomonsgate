<?php require_once("connection.php"); 


$result = mysql_query("SELECT * FROM 3da_audittrail");
echo "Reports list and column headers from 3da advisors 3da_audittrail table <br>";
echo "id, txn_id, txn_data, related_table_name, related_record_id, create_timestamp<br><br><br>";
while($row = mysql_fetch_array($result))
  {
  echo $row['id'] . ", " . $row['txn_id'] . ", " . $row['txn_userid'] . ", " . $row['txn_data'] . ", " . $row['related_table_name']
. ", " . $row['related_record_id']. ", " . $row['create_timestamp'];
  echo "<br />";
  }
 ?>
