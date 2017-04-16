<?php
    /**********************************************
     *
     * AuditTrail.php
     *
     * Defines audit trail class and functions
     *
     * Author: Scott Day
     * Date:   06-10-2003
     *
     ***********************************************/
    class AuditTrail  {
        var $transactionId;
        var $userid;
        var $transactionData;
        var $relatedTableName;
        var $relatedRecordId;
        var $transactionDateTime;

        //AuditTrail Constructor
        function AuditTrail($transactionId = NULL, $userid = NULL, $transactionData = NULL, 
                            $relatedTableName = NULL, $relatedRecordId = NULL)
        {
            $this->transactionId = $transactionId;
            $this->userid = $userid;
            $this->transactionData = $transactionData;
            $this->relatedTableName = $relatedTableName;
            $this->relatedRecordId = $relatedRecordId;
            return;
        }

        function transactionId()
        {
            return $this->transactionId;
        }

        function userid()
        {
            return $this->userid;
        }

        function transactionData()
        {
            return $this->transactionData;
        }

        function relatedTableName()
        {
            return $this->relatedTableName;
        }

        function relatedRecordId()
        {
            return $this->relatedRecordId;
        }

        function transactionDateTime()
        {
            return $this->transactionDateTime;
        }

        function createEntry()
        {
            $result = connectToDb();
            if ($result->isError())
            {
                //error log - 'Cannot connect to db
                //$result = new Result(ERROR, 'Cannot connect to database', NULL);
            }
            else
            {
                $query = "insert into 3da_audittrail
                          values(NULL, '$this->transactionId', '$this->userid', 
                                 '$this->transactionData', 
                                 '$this->relatedTableName', 
                                 '$this->relatedRecordId', NULL)";

                $dbresult = db_query( $query );

                if(!$dbresult)
                {
                    //error log - 'Cannot run query.'.mysql_error().'<BR>';
                    $result = new Result(ERROR, 'Cannot run query', NULL);
                }
                else
                {
                    //error log - 'Audit Trail DB Record inserted: '.mysql_affected_rows().'<BR>';
                    $result = new Result(SUCCESS, 'Audit trail entry created', $dbresult);
                }
            }
            return $result;
        }

        function retrieveTotals($txn_id)
        {
            return $this->retrieveTotalsForRange(NULL, NULL, $txn_id);
        }

        function retrieveTotalsForRange($beginDate, $endDate, $txn_id)
        {
            $result = connectToDb();
            if ($result->isError())
            {
                //error log - 'Cannot connect to db
                //$result = new Result(ERROR, 'Cannot connect to database', NULL);
            }
            else
            {
                if (isset($beginDate) && isset($endDate))
                {
                    $whereClause = "where txn_id= '$txn_id' and create_timestamp between '$beginDate' and '$endDate'";
                }
                else
                {
                    $whereClause = "where txn_id= '$txn_id'";
                }

                $query = 'select count(*) as total from 3da_audittrail '.$whereClause;

                $dbresult = db_query( $query );

                if(!$dbresult)
                {
                    //error log - 'Cannot run query.'.mysql_error().'<BR>';
                    $result = new Result(ERROR, 'Cannot run query', NULL);
                }
                else
                {
                    //error log - 'Audit Trail user report retrieved'<BR>';
                    $num_results = mysql_num_rows($dbresult);
                    if ($num_results > 0)
                    {
                        $row = mysql_fetch_object($dbresult);
                        $result = new Result(SUCCESS, 'Successfully retrieved report(s)', $row);
                    }
                    else
                    {
                        $result = new Result(ERROR, 'No matches for search criteria', NULL);
                    }
                }
            }
            return $result;
        }

        function retrieveUserDetailsForRange($beginDate, $endDate)
        {
            $result = connectToDb();
            if ($result->isError())
            {
                //error log - 'Cannot connect to db
                //$result = new Result(ERROR, 'Cannot connect to database', NULL);
            }
            else
            {
            	//$beginDate = '20100619000000';
            	//$endDate = '20100619235959';
                if (isset($beginDate) && isset($endDate))
                {
                    $whereClause = "where a.txn_id= ".TX_LOGIN." and a.create_timestamp between '{$beginDate}' and '{$endDate}'";
                }
                else
                {
                    $whereClause = "where a.txn_id= ".TX_LOGIN;
                }
					$query = "SELECT a.id, a.txn_id, a.txn_userid, a.related_table_name, a.related_record_id, a.create_timestamp, c.company_name, c.id 
					FROM 3da_audittrail AS a
					JOIN 3da_customers AS c ON a.txn_id = c.id
					$whereClause";
               
                $dbresult = db_query( $query );

                if(!$dbresult)
                {
                    //error log - 'Cannot run query.'.mysql_error().'<BR>';
                    $result = new Result(ERROR, 'Cannot run query', NULL);
                }
                else
                {
                    //error log - 'Audit Trail user report retrieved'<BR>';
                    $num_results = mysql_num_rows($dbresult);
                    if ($num_results > 0)
                    {
                        //there may be more than one row
                        for ($counter=0; $counter < $num_results; $counter++) {
                             $rows[]=mysql_fetch_array($dbresult);
                        }
                        $result = new Result(SUCCESS, 'Successfully retrieved report(s)', $rows);
                    }
                    else
                    {
                        $result = new Result(ERROR, 'No matches for search criteria', NULL);
                    }

                }
            }
            return $result;
        }

        function retrieveReportDetailsForRange($beginDate, $endDate)
        {
            $result = connectToDb();
            if ($result->isError())
            {
                //error log - 'Cannot connect to db
                //$result = new Result(ERROR, 'Cannot connect to database', NULL);
            }
            else
            {
                if (isset($beginDate) && isset($endDate))
                {
                	$whereClause = "where a.txn_id= ".TX_VIEWREPORT." and a.create_timestamp between '{$beginDate}' and '{$endDate}'";
                    //$whereClause = "where a.txn_id= ".TX_VIEWREPORT." and a.create_timestamp between '$beginDate' and '$endDate'";
                }
                else
                {
                    $whereClause = "where a.txn_id= ".TX_VIEWREPORT;
                }

                $query = "SELECT a.id, a.txn_id, a.txn_userid, a.related_table_name, a.related_record_id, a.create_timestamp, c.company_name, c.id, c.company_name, c.company_symbol, c.report_date
FROM 3da_audittrail AS a
JOIN 3da_reports AS c ON a.txn_id = c.id " . $whereClause;
					
               // $query = 'select a.id, a.txn_id, a.txn_userid, a.related_table_name, a.related_record_id, a.create_timestamp, '.
                //         'r.company_symbol, r.report_date '.
                //         'from 3da_audittrail as a left join 3da_reports as r on a.related_record_id = r.id '.$whereClause;
                $dbresult = db_query( $query );

                if(!$dbresult)
                {
                    //error log - 'Cannot run query.'.mysql_error().'<BR>';
                    $result = new Result(ERROR, 'Cannot run query', NULL);
                }
                else
                {
                    //error log - 'Audit Trail user report retrieved'<BR>';
                    $num_results = mysql_num_rows($dbresult);
                    if ($num_results > 0)
                    {
                        //there may be more than one row
                        for ($counter=0; $counter < $num_results; $counter++) {
                             $rows[]=mysql_fetch_array($dbresult);
                        }
                        $result = new Result(SUCCESS, 'Successfully retrieved report(s)', $rows);
                    }
                    else
                    {
                        $result = new Result(ERROR, 'No matches for search criteria', NULL);
                    }
                }
            }
            return $result;
        }
        //Retrieve of listing of user views of company reports
        function retrieveCompanyReportViews($companySymbol)
        {
            $result = connectToDb();
            if ($result->isError())
            {
                //error log - 'Cannot connect to db
                //$result = new Result(ERROR, 'Cannot connect to database', NULL);
            }
            else
            {
                if (isset($companySymbol))
                {   
//                    $query = 'select a.create_timestamp, a.txn_userid, b.report_date '.
//                             'from 3da_audittrail as a, 3da_reports as b where a.related_record_id = '.
//                             "b.id and b.company_symbol = '$companySymbol' order by a.create_timestamp desc";
                    $query = 'select a.create_timestamp, a.txn_userid, b.report_date, c.company_name '.
                             'from 3da_audittrail as a, 3da_reports as b, 3da_customers as c where a.related_record_id = '.
                             "b.id and b.company_symbol = '$companySymbol' and a.txn_userid = c.userid ".
                             'order by a.create_timestamp desc';
                    $dbresult = db_query( $query );
                   
                    if(!$dbresult)
                    {
                        //error log - 'Cannot run query.'.mysql_error().'<BR>';
                        $result = new Result(ERROR, 'Cannot run query', NULL);
                    }
                    else
                    {
                        //error log - 'Audit Trail user report retrieved'<BR>';
                        $num_results = mysql_num_rows($dbresult);
                        if ($num_results > 0)
                        {
                            //there may be more than one row
                            for ($counter=0; $counter < $num_results; $counter++) {
                                 $rows[]=mysql_fetch_array($dbresult);
                            }
                            $result = new Result(SUCCESS, 'Successfully retrieved report(s)', $rows);
                        }
                        else
                        {
                            $result = new Result(ERROR, 'No matches for search criteria', NULL);
                        }
                    }
                }
                else
                {
                    $result = new Result(ERROR, 'Company ticker symbol not provided', NULL);
                }
            }

            return $result;
        }
    }
?>
