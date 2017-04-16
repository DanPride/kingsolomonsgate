<?php require_once("connection.php"); 


$result = mysql_query("SELECT * FROM 3da_customers");
echo "Reports list and column headers from 3da advisors 3da_customers table<br>";
echo "id, userid, prefix, first_name, middle_initial, last_name, suffix, company_name, title, street_address, city, state, postal_code, country, email_address, office_phone, office_fax, create_timestamp<br><br><br>";
while($row = mysql_fetch_array($result))
  {
  echo $row['id'] . ", " . $row['userid'] . ", " . $row['prefix'] . ", " . $row['first_name'] . ", " . $row['middle_initial']
. ", " . $row['last_name']. ", " . $row['suffix']. ", " . $row['company_name']. ", " . $row['title']. ", " . $row['street_address']. ", " . $row['city']. ", " . $row['state']. ", " . $row['postal_code']. ", " . $row['country']. ", " . $row['email_address']. ", " . $row['office_phone']. ", " . $row['office_fax']. ", " . $row['create_timestamp'];
  echo "<br />";
  }
 ?>
