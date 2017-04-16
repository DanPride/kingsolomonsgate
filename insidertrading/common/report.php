<?php
    /************************************************************
     *
     *  report.php
     *
     *  Provides research report create, retrieve, update, delete
     *  functions.
     *
     *  Author: Scott Day
     *  Date:   5-24-03
     *
     ************************************************************/

    /***********************************************************
     *
     * Function to create report record and associated report
     * attributes (including company references) in the report db
     *
     ***********************************************************/    
    function createReport($companySymbol, $companyName, $reportActive, $reportUpdate,
                          $reportHeadline, $reportAuthor, $researchCorpGov, $researchInsider,
                          $researchQoe, $reportDate, $reportFileLocation, $refArray)
    {
        $result = connectToDb();
        if ($result->isError())
        {
            //error log - 'Cannot connect to db
            //$result = new Result(ERROR, 'Cannot connect to database', NULL);
        }
        else
        {
            if (file_exists($reportFileLocation))
            {
                $reportFileSize = filesize($reportFileLocation);
                $baseFileName = basename($reportFileLocation);
                $reportData = addslashes(fread(fopen($reportFileLocation, 'r'), $reportFileSize));
                $query = "insert into 3da_reports
                          values(NULL, '$companyName', '$companySymbol', '$reportActive', '$reportUpdate',
                                 '$reportHeadline', '$reportAuthor', '$researchCorpGov', '$researchInsider',
                                 '$researchQoe', '$reportDate', '$baseFileName', NULL,
                                 '$reportFileSize', '$reportData');";
                $dbresult = db_query( $query );

                if(!$dbresult)
                {
                    //error log - 'Cannot run query.'.mysql_error().'<BR>';
                    $result = new Result(ERROR, 'Cannot run query', NULL);
                }
                else
                {
                    $refArraySize = sizeof($refArray);

                    //DB SUCCESS - Add row to 3da_report_references
                    if ($refArraySize > 0 ) {
                        $query = "insert into 3da_report_references values";
                        $index = 0;
                        foreach ($refArray as $refArrayKey => $refArrayValue)
                        {
                            if ($index == 0) {
                                $query = $query."(LAST_INSERT_ID(), '$refArrayValue', '$refArrayKey')";
                            }
                            else
                            {
                                $query = $query.",(LAST_INSERT_ID(), '$refArrayValue', '$refArrayKey')";
                            }
                            $index = $index + 1;
                        }
                        $query = $query.";";

                        $dbresult = db_query( $query );
                        if(!$dbresult)
                        {
                            //error log - 'Cannot run query.'.mysql_error().'<BR>';
                            $result = new Result(ERROR, 'Cannot run query', NULL);
                        }
                        else
                        {
                            //error log - 'Report DB Records inserted: '.mysql_affected_rows().'<BR>';
                            $result = new Result(SUCCESS, 'New report created', $dbresult);
                        }
                    }
                }
            }
            else
            {
                $result = new Result(ERROR, 'Problem locating report file', NULL);
            }
        }

        return $result;
    }
    //Retrieve the report record by the company symbol
    function retrieveReportBySymbol($companySymbol)
    {
        $result = connectToDb();
        if ($result->isError())
        {
        }
        else
        {
	
            $companySymbolWC =  $companySymbol.'%';          
				$query = "SELECT f.report_id, f.company_symbol, f.company_name, 
					r.id, r.report_active, r.report_update, r.report_headline, r.report_author,
			            r.research_corpgov, r.research_insider, r.research_qoe,
			            r.report_date, r.report_filename 
					FROM 3da_report_references AS f
					JOIN 3da_reports AS r ON f.report_id = r.id
					WHERE f.company_symbol =  '$companySymbol'
					OR f.company_symbol LIKE  '$companySymbolWC'
					AND r.report_active =  'y'
					ORDER BY r.report_date DESC";					
            $dbresult = db_query( $query );
            if(!$dbresult)
            {
                $result = new Result(ERROR, 'Cannot run query', NULL);
            }
            else
            {
                $num_results = mysql_num_rows($dbresult);
                if ($num_results > 0)
                {
                    for ($counter=0; $counter < $num_results; $counter++) { //to do - there may be more than one row
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

	function retrieveAllReportsAdmin()
    {
        $result = connectToDb();
        if ($result->isError())
        {
        }
        else
        {
	        
				$query = "SELECT f.report_id, f.company_symbol, f.company_name, 
					r.id, r.report_active, r.report_update, r.report_headline, r.report_author,
			            r.research_corpgov, r.research_insider, r.research_qoe,
			            r.report_date, r.report_filename 
					FROM 3da_report_references AS f
					JOIN 3da_reports AS r ON f.report_id = r.id
					ORDER BY r.report_date DESC";					
            $dbresult = db_query( $query );
            if(!$dbresult)
            {
                $result = new Result(ERROR, 'Cannot run query', NULL);
            }
            else
            {
                $num_results = mysql_num_rows($dbresult);
                if ($num_results > 0)
                {
                    for ($counter=0; $counter < $num_results; $counter++) { //to do - there may be more than one row
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


    /**********************************************
     *
     * Retrieve reports by company name
     *
     **********************************************/
    
    function retrieveReportByName($companyName)
    {
        $result = connectToDb();
        if ($result->isError())
        {
            //error log - 'Cannot connect to db
            //$result = new Result(ERROR, 'Cannot connect to database', NULL);
        }
        else
        {
			$companyName = '%' . $companyName . '%';
			$query =  "SELECT f.report_id, f.company_symbol, f.company_name, 
					r.id, r.report_active, r.report_update, r.report_headline, r.report_author,
			            r.research_corpgov, r.research_insider, r.research_qoe,
			            r.report_date, r.report_filename 
				FROM 3da_report_references AS f
				JOIN 3da_reports AS r ON f.report_id = r.id
				WHERE f.company_name LIKE  '$companyName'
				AND r.report_active =  'y'
				ORDER BY r.report_date DESC, f.company_symbol ASC";
				
            $dbresult = db_query( $query );
            if(!$dbresult)
            {
                //echo 'Cannot run query.';
                $result = new Result(ERROR, 'Cannot run query', NULL);
            }
            else
            {
                $num_results = mysql_num_rows($dbresult);
                if ($num_results > 0)
                {
                    //to do - there may be more than one row
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
    //Retrieve by report date
    function retrieveReportByDate($reportDate)
    {
		$Year = substr($reportDate, 0, 4);
		$Month = substr($reportDate, 5, 2);
		$Day = substr($reportDate, 8, 2);
        $result = connectToDb();
        if ($result->isError())
        {
            //error log - 'Cannot connect to db
            //$result = new Result(ERROR, 'Cannot connect to database', NULL);
        }
        else
        {
			$query =  "SELECT f.report_id, f.company_symbol, f.company_name, r.id, r.report_active, r.report_update, r.report_headline, r.report_author, r.research_corpgov, r.research_insider, r.research_qoe, r.report_date as 'report_date', r.report_filename
			FROM 3da_report_references AS f
			JOIN 3da_reports AS r ON f.report_id = r.id
			WHERE YEAR( r.report_date ) =  '$Year'
			AND MONTH( r.report_date ) =  '$Month'
			AND DAY( r.report_date ) =  '$Day'
			ORDER BY r.report_date DESC , f.company_symbol ASC";
				
            $dbresult = db_query( $query );
            if(!$dbresult)
            {
                //echo 'Cannot run query.';
                $result = new Result(ERROR, 'Cannot run query', NULL);
            }
            else
            {
                $num_results = mysql_num_rows($dbresult);
                if ($num_results > 0)
                {
                    //to do - there may be more than one row
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

    /*********************************************************
     *
     * Retrieve all reports sorting by descending report date 
     * and limited by the $limit value
     *
     *********************************************************/
    function retrieveAllReportsByDate($limit)
    {
        $orderParm = " order by report_date DESC, company_symbol ASC";
        $result = retrieveAllReports($limit, $orderParm);
        return $result;
    }
    /*********************************************************
     *
     * Retrieve all reports sorting by ascending company name 
     * and limited by the $limit value
     *
     *********************************************************/
    function retrieveAllReportsByName($limit)
    {
        $orderParm = " order by company_name ASC, report_date DESC";
        $result = retrieveAllReports($limit, $orderParm);
        return $result;
    }
    /*********************************************************
     *
     * Retrieve all reports limited by the $limit value
     *
     *********************************************************/
    function retrieveAllReports($limit, $orderParm)
    {
        $result = connectToDb();
        if ($result->isError())
        {
            //error log - 'Cannot connect to db
            //$result = new Result(ERROR, 'Cannot connect to database', NULL);
        }
        else
        {
	
			$query = "SELECT f.report_id, f.company_symbol, f.company_name, 
				r.id, r.report_active, r.report_update, r.report_headline, r.report_author,
		            r.research_corpgov, r.research_insider, r.research_qoe,
		            r.report_date, r.report_filename 
				FROM 3da_report_references AS f
				JOIN 3da_reports AS r ON f.report_id = r.id
				WHERE  r.report_active =  'y' AND f.company_symbol !='RISR'";
				
            if (isset($orderParm)) {
                $query = $query.$orderParm;
            }
           
                $query = $query." LIMIT $limit";

            $dbresult = db_query( $query );
            if(!$dbresult)
            {
                //echo 'Cannot run query.';
                $result = new Result(ERROR, 'Cannot run query', NULL);
            }
            else
            {
                $num_results = mysql_num_rows($dbresult);
                if ($num_results > 0)
                {
                    for ($counter=0; $counter < $num_results; $counter++) {
                         $rows[]=mysql_fetch_array($dbresult);
                    }
                    $result = new Result(SUCCESS, 'All reports retrieved', $rows);
                }
                else
                {
                    //to do - error 'No matches for search criteria'
                    $result = new Result(ERROR, 'No rows to return', NULL);
                }
            }
        }
        return $result;
    }
    /*********************************************************
     *
     * Retrieve all reports limited by the $limit value
     *
     *********************************************************/
    function retrieveOpenFileReport($limit)
    {
        $result = connectToDb();
        if ($result->isError())
        {
            //error log - 'Cannot connect to db
            //$result = new Result(ERROR, 'Cannot connect to database', NULL);
        }
        else
        {
            
		$query = "SELECT f.report_id, f.company_symbol, f.company_name, 
					r.id, r.report_active, r.report_update, r.report_headline, r.report_author,
			            r.research_corpgov, r.research_insider, r.research_qoe,
			            r.report_date, r.report_filename 
					FROM 3da_report_references AS f
					JOIN 3da_reports AS r ON f.report_id = r.id
					WHERE r.id = f.report_id
	                 and r.report_active = 'y'
	                 and f.company_symbol <> 'INSID'
	                 and f.company_symbol <> 'SRPT'
	                 and f.company_symbol <> 'NOTES'
	                order by f.company_name ASC, r.report_date DESC";

            if (isset($orderParm)) {
                $query = $query.$orderParm;
            }
            if (is_integer($limit))
            {
                $query = $query." LIMIT $limit";
            }

            $dbresult = db_query( $query );
            if(!$dbresult)
            {
                //echo 'Cannot run query.';
                $result = new Result(ERROR, 'Cannot run query', NULL);
            }
            else
            {
                $num_results = mysql_num_rows($dbresult);
                if ($num_results > 0)
                {
                    for ($counter=0; $counter < $num_results; $counter++) {
                         $rows[]=mysql_fetch_array($dbresult);
                    }
                    $result = new Result(SUCCESS, 'All reports retrieved', $rows);
                }
                else
                {
                    //to do - error 'No matches for search criteria'
                    $result = new Result(ERROR, 'No rows to return', NULL);
                }
            }
        }
        return $result;
    }
    //Retrieve report data
    function retrieveReportData($reportid, $userid)
    {
        if(isset($reportid))
        {
            $result = connectToDb();
            if ($result->isError())
            {
                //error log - 'Cannot connect to db
                //$result = new Result(ERROR, 'Cannot connect to database', NULL);
            }
            else
            {
                $query = "select report_filesize, report_filename, report_file from 3da_reports where id=$reportid";

                $dbresult = db_query( $query );
                if(!$dbresult)
                {
                    //echo 'Cannot run query.';
                    $result = new Result(ERROR, 'Cannot run query', NULL);
                }
                else
                {
                    $num_results = mysql_num_rows($dbresult);
                    if ($num_results > 0)
                    {
                        //to do - there may be more than one row
                        $row = mysql_fetch_object($dbresult);
                        $result = new Result(SUCCESS, 'Successfully retrieved report(s)', $row);
                        $audit = new AuditTrail(TX_VIEWREPORT, $userid, 'Report retrieved', 
                                                '3da_reports', $reportid);
                        $audit->createEntry();
                    }
                    else
                    {
                        $result = new Result(ERROR, 'No matches for search criteria', NULL);
                    }

                }
            }

        }
        return $result;
    }
    
    //*****************************
        //Retrieve report data
    function retrieveCurrentReportData()
    {
      
            $result = connectToDb();
            if ($result->isError())
            {
                //error log - 'Cannot connect to db
                //$result = new Result(ERROR, 'Cannot connect to database', NULL);
            }
            else
            {
                $query = "SELECT report_filesize, report_filename, report_file FROM 3da_reports WHERE company_symbol =  'RISR' ORDER BY  `id` DESC LIMIT 1";

                $dbresult = db_query( $query );
                if(!$dbresult)
                {
                    //echo 'Cannot run query.';
                    $result = new Result(ERROR, 'Cannot run query', NULL);
                }
                else
                {
                    $num_results = mysql_num_rows($dbresult);
                    if ($num_results > 0)
                    {
                        //to do - there may be more than one row
                        $row = mysql_fetch_object($dbresult);
                        $result = new Result(SUCCESS, 'Successfully retrieved report(s)', $row);
                        $audit = new AuditTrail(TX_VIEWREPORT, $userid, 'Report retrieved', 
                                                '3da_reports', $reportid);
                        $audit->createEntry();
                    }
                    else
                    {
                        $result = new Result(ERROR, 'No matches for search criteria', NULL);
                    }

                }
            

        }
        return $result;
    }
    
    //*****************************
    //Update the report attributes and/or report references
    function updateReport($companySymbol, $companyName, $reportActive, $reportUpdate,
                          $reportHeadline, $reportAuthor, $researchTypeGov, $researchTypeInsider,
                          $researchTypeQoe, $reportDate, $reportFileLocation, $refArray)
    {
        //TO DO
        //1. Need to add check to only update those columns that have changed or have new values
        //   May be easiest to retrieve report first and allow user to change what needs to change
        //2. Make sure to update report references and remove those that need removing

        $result = connectToDb();
        if ($result->isError())
        {
            //error log - 'Cannot connect to db
            //$result = new Result(ERROR, 'Cannot connect to database', NULL);
        }
        else
        {
            $query = "select id from 3da_reports 
                        where company_symbol = '$companySymbol'
                        and report_date = '$reportDate'";
            
            $dbresult = db_query( $query );
            if(!($dbresult))
            {
                $result = new Result(ERROR, 'Cannot run query', NULL);
            }
            else
            {
                $num_results = mysql_num_rows($dbresult);
                if ($num_results > 0)
                {
                    //to do - there may be more than one row
                    $row = mysql_fetch_object($dbresult);
                    $reportId = $row->id;
            
                    $query = "update 3da_reports 
                                set company_name = '$companyName',
                                    report_active = '$reportActive',
                                    report_update = '$reportUpdate',
                                    report_headline = '$reportHeadline',
                                    report_author = '$reportAuthor',
                                    research_corpgov = '$researchTypeGov',
                                    research_insider = '$researchTypeInsider',
                                    research_qoe = '$researchTypeQoe',
                                    report_filename = '$reportFileLocation'
                                where id = '$reportId'";
                    
                    $dbresult = db_query( $query );
                    if(!($dbresult))
                    {
                        $result = new Result(ERROR, 'Cannot run query', NULL);
                    }
                    else
                    {
                        $refArraySize = sizeof($refArray);

                        //DB SUCCESS - Add rows to 3da_report_references
                        if ($refArraySize > 0 ) 
                        {
                            $query = "insert into 3da_report_references values";
                            $index = 0;
                            foreach ($refArray as $refArrayKey => $refArrayValue)
                            {
                                if ($index == 0) {
                                    $query = $query."('$reportId', '$refArrayValue', '$refArrayKey')";
                                }
                                else
                                {
                                    $query = $query.",('$reportId', '$refArrayValue', '$refArrayKey')";
                                }
                                $index = $index + 1;
                            }
                            $query = $query.";";

                            $dbresult = db_query( $query );
                            if(!$dbresult)
                            {
                                //error log - 'Cannot run query.'.mysql_error().'<BR>';
                                $result = new Result(ERROR, 'Cannot run query', NULL);
                            }
                            else
                            {
                                //error log - 'Report DB Records inserted: '.mysql_affected_rows().'<BR>';
                                $result = new Result(SUCCESS, 'Report updated', $dbresult);
                            }
                        }
                    }
                }
                else
                {
                    echo "No rows match values passed in";
                }
            }
        }
        return $result;
    }
    //Delete the report record identified by the company symbol
    //and delete the associated rows in report references
    function deleteReport($companySymbol, $reportDate)
    {

        $result = connectToDb();
        if ($result->isError())
        {
            //error log - 'Cannot connect to db
            //$result = new Result(ERROR, 'Cannot connect to database', NULL);
        }
        else
        {
            $query = "select id from 3da_reports 
                        where company_symbol = '$companySymbol'
                        and report_date = '$reportDate'";
            $dbresult = db_query( $query );
            if(!($dbresult))
            {
                $result = new Result(ERROR, 'Cannot run query', NULL);
            }
            else
            {
                $num_results = mysql_num_rows($dbresult);
                if ($num_results > 0)
                {
                    //to do - there may be more than one row
                    $row = mysql_fetch_object($dbresult);
                    $reportId = $row->id;

                    $query = "delete from 3da_reports 
                                where id = '$reportId'";

                    $dbresult = db_query( $query );
                    if(!($dbresult))
                    {
                        $result = new Result(ERROR, 'Cannot run query', NULL);
                    }
                    else
                    {
                        if ((mysql_affected_rows() > 0))
                        {
                            //Delete associated rows in 3da_report_references
                            $query = "delete from 3da_report_references where report_id = '$reportId'";
                            $dbresult = db_query( $query );
                            if(!($dbresult))
                            {
                                $result = new Result(ERROR, 'Cannot run query', NULL);
                            }
                            else
                            {
                                $result = new Result(SUCCESS, 'Report deleted', $dbresult);
                            }
                        }
                        else
                        {
                            $result = new Result(ERROR, 'No rows affected', NULL);
                        }
                    }
                }
                else
                {
                    $result = new Result(ERROR, 'No matches for search criteria', NULL);
                }
            }
        }
        return $result;  
    }

	function lengthPop(){
		//<option selected="selected">Saab</option>
		switch ($_POST['reportLength'])
		{
		case 10:
			$Length10 = " selected=\"selected\"";
			 break;
		case 25:
		  $Length25 = " selected=\"selected\"";
		  break;  
		case 50:
		 	$Length50 = " selected=\"selected\"";
		  	break;
		case 100:
		 	$Length100 = " selected=\"selected\"";
		  	break;
		case 150:
		 	$Length150 = " selected=\"selected\"";
		  	break;
		case 200:
			$Length200 = " selected=\"selected\"";
	 		break;
		default:
		 $Length25 = " selected=\"selected\"";
		  break;
		}

		$lengthPop = "<select name=\"reportLength\" size=\"1\">";
		$lengthPop .= "<option" . $Length10 . ">10</option>";
		$lengthPop .= "<option" . $Length25 . ">25</option>";
		$lengthPop .= "<option" . $Length50 . ">50</option>";
		$lengthPop .= "<option" . $Length100 . ">100</option>";
		$lengthPop .= "<option" . $Length150 . ">150</option>";
		$lengthPop .= "<option" . $Length200 . ">200</option>";
		$lengthPop .= "</select>";
		echo $lengthPop;
	}
	function reportOrderPop(){
		//<option selected="selected">Saab</option>
		switch ($_POST['reportOrder'])
		{
		case Date:
		  $Date = " selected=\"selected\"";
		  break;  
		case Ticker:
		 $Ticker = " selected=\"selected\"";
		  break;
		case Name:
		 $Name = " selected=\"selected\"";
		  break;
		default:
		 $Date = " selected=\"selected\"";
		  break;
		}

		$orderPop = " by: <select name=\"reportOrder\" size=\"1\">";
		$orderPop .= "<option" . $Date . ">Date</option>";
		$orderPop .= "<option" . $Ticker . ">Ticker</option>";
		$orderPop .= "<option" . $Name . ">Name</option>";
		$orderPop .= "</select>";
		echo $orderPop;
	}
	function updownPop(){
		switch ($_SESSION['UpDown'])
		{
		case ASC:
		  $ASC = " selected=\"selected\"";
		  break;  
		case DESC:
		 $DESC = " selected=\"selected\"";
		  break;
		}
			$updownPop = " <select name=\"UpDown\">";
			$updownPop .= "<option" . $ASC . ">ASC</option>";
			$updownPop .= "<option" . $DESC . ">DESC</option>";
			$updownPop .= "</select>";
			echo $updownPop;
			}
?>