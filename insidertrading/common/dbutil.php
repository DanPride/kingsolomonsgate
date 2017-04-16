<?php
    /****************************************************************
     *  dbutil.php
     *
     *  Database wrapper functions to provide dbms portability
     *
     *  Author: Scott Day
     *  Date:   5/20/03
     *
     ****************************************************************/
    require_once('constants.php');
    require_once('config.php');
    require_once('result.php');
    function connectToDb()
    {
        global $DbDatabase, $DbHost, $DbUser, $DbPassword;

        $dbresult = db_connect( $DbHost, $DbUser, $DbPassword );
        if(!$dbresult)
        {
            //error log - 'Cannot connect to database.'
            return (new Result(ERROR, 'Cannot connect to database', NULL));
        }
        else
        {
            // select the appropriate database
            $dbresult = db_selectdb( $DbDatabase );
            if(!$dbresult)
            {
                //error log - 'Cannot select database.'
                return (new Result(ERROR, 'Cannot select database', NULL));
            }
        }

        return (new Result(SUCCESS, 'Connected to database', NULL));
    }
    function db_connect($hostname, $username, $password)
    {
        $result = mysql_connect( $hostname, $username, $password );
        return $result;
    }
    function db_selectdb($database)
    {
        $result = mysql_select_db( $database );
        return $result;
    }
    function db_query($query)
    {
        $result = mysql_query( $query );
        return $result;
    }
    function db_result($dbresult, $num1, $num2)
    {
        $result = mysql_result( $dbresult, $num1, $num2 );
        return $result;
    }
?>
