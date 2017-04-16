<?php 
    /***********************************************************
     *
     *  datetime.php
     *
     *  A series of date/time related utility functions.
     *
     ***********************************************************/
    // *** Convert to MySQL date format *** 
    function mysql_cvdate($s) 
    { 
        //take a user-entered date value and express it in MySQL's date format 
        // ***Use this to parse date input from a form into a MySQL database. 
        return timestamp_to_mysql_date(cvdate($s)); 
    } 
    //we use UNIX's time specification as the base specification 
    function mysql_datetime_to_human($dt) 
    { 
        $yr=strval(substr($dt,0,4)); 
        $mo=strval(substr($dt,4,2)); 
        $da=strval(substr($dt,6,2)); 
        $hr=strval(substr($dt,8,2)); 
        $mi=strval(substr($dt,10,2)); 
//      $se=strval(substr($dt,12,2)); 

        return date("m/d/Y H:i", mktime ($hr,$mi,0,$mo,$da,$yr))." MST"; 
    } 
    function mysql_date_to_human($dt) 
    { 
        //intercept mysql's default ZERO date value. 
        if ($dt=="0000-00-00") return ""; 

        $yr=strval(substr($dt,0,4)); 
        $mo=strval(substr($dt,4,2)); 
        $da=strval(substr($dt,6,2)); 

        return date("m/d/Y", mktime (0,0,0,$mo,$da,$yr)); 
    } 
    function mysql_timestamp_to_human($dt) 
    { 

        $yr=strval(substr($dt,0,4)); 
        $mo=strval(substr($dt,4,2)); 
        $da=strval(substr($dt,6,2)); 
        $hr=strval(substr($dt,8,2)); 
        $mi=strval(substr($dt,10,2)); 
        //$se=strval(substr($dt,12,2)); 

        return date("m/d/Y H:i", mktime ($hr,$mi,0,$mo,$da,$yr))." MST"; 
    } 
    function mysql_timestamp_to_human_with_timezone($dt, $timezone) 
    { 

        $yr=strval(substr($dt,0,4)); 
        $mo=strval(substr($dt,4,2)); 
        $da=strval(substr($dt,6,2)); 
        $hr=strval(substr($dt,8,2)); 
        $mi=strval(substr($dt,10,2));
        $unix_dt = mktime ($hr,$mi,0,$mo,$da,$yr);
        switch($timezone) {
        case 'EST' :
            $unix_dt = $unix_dt - 18000;
            break;
        case 'EDT' :
            $unix_dt = $unix_dt - 14400;
            break;
        default:
            $timezone = 'GMT';
        }

        $timezone = ' '.$timezone;

        return date("m/d/Y H:i", $unix_dt).$timezone; 
    } 
    function unix_timestamp_to_human_with_timezone($dt, $timezone) 
    { 

        switch($timezone) {
        case 'EST' :
            $dt = $dt - 18000;
            break;
        case 'EDT' :
            $dt = $dt - 14400;
            break;
        default:
            $timezone = 'GMT';
        }


        $timezone = ' '.$timezone;

        //$se=strval(substr($dt,12,2)); 

        return date("m/d/Y H:i", $dt).$timezone; 
    } 
    function mysql_timestamp_to_timestamp($dt) 
    { 
        $yr=strval(substr($dt,0,4)); 
        $mo=strval(substr($dt,4,2)); 
        $da=strval(substr($dt,6,2)); 
        $hr=strval(substr($dt,8,2)); 
        $mi=strval(substr($dt,10,2)); 
        $se=strval(substr($dt,12,2)); 

        return mktime($hr,$mi,$se,$mo,$da,$yr); 
    } 
    function mysql_datetime_to_timestamp($dt) 
    { 
        $yr=strval(substr($dt,0,4)); 
        $mo=strval(substr($dt,4,2)); 
        $da=strval(substr($dt,6,2)); 
        $hr=strval(substr($dt,8,2)); 
        $mi=strval(substr($dt,10,2)); 
        $se=strval(substr($dt,12,2)); 

        return mktime($hr,$mi,$se,$mo,$da,$yr); 
    } 
    function timestamp_to_mysql_timestamp($ts) 
    { 
        $d=getdate($ts); 

        $yr=$d["year"]; 
        $mo=$d["mon"]; 
        $da=$d["mday"]; 
        $hr=$d["hours"]; 
        $mi=$d["minutes"]; 
        $se=$d["seconds"]; 

        return sprintf("%04d%02d%02d%02d%02d%02d",$yr,$mo,$da,$hr,$mi,$se); 
    } 
    function timestamp_to_mysql_date($ts) 
    { 
        $d=getdate($ts); 

        $yr=$d["year"]; 
        $mo=$d["mon"]; 
        $da=$d["mday"]; 

        return sprintf("%04d-%02d-%02d",$yr,$mo,$da); 
    } 
    function timeleft($begin,$end) 
    { 
        //for two timestamp format dates, returns the plain english difference between them. 
        //note these dates are UNIX timestamps 


        $dif=$end-$begin; 

        $years=intval($dif/(60*60*24*365)); 
        $dif=$dif-($years*(60*60*24*365)); 

        $months=intval($dif/(60*60*24*30)); 
        $dif=$dif-($months*(60*60*24*30)); 

        $weeks=intval($dif/(60*60*24*7)); 
        $dif=$dif-($weeks*(60*60*24*7)); 

        $days=intval($dif/(60*60*24)); 
        $dif=$dif-($days*(60*60*24)); 

        $hours=intval($dif/(60*60)); 
        $dif=$dif-($hours*(60*60)); 

        $minutes=intval($dif/(60)); 
        $seconds=$dif-($minutes*60); 

        $s=""; 

        //if ($years<>0) $s.= $years." years "; 
        //if ($months<>0) $s.= $months." months "; 
        if ($weeks<>0) $s.= $weeks." weeks "; 
        if ($days<>0) $s.= $days." days "; 
        if ($hours<>0) $s.= $hours." hours "; 
        if ($minutes<>0) $s.= $minutes." minutes "; 
        //if ($seconds<>0) $s.= $seconds." seconds "; 

        return $s; 

    } 
    function cvdate($s) 
    { 
        //this function takes a "human" date and converts it into a UNIX timestamp, zero if error. 
       //this function supports dash,slash or space delimiting, numeric/english months, and two-digit years. 

        //what is the delimiting character? (support space, slash, dash) 
         $delimiter=""; 
        if (strpos($s,"-")>0) $delimiter="-"; 
        if (strpos($s,"/")>0) $delimiter="/"; 
        if (strpos($s," ")>0) $delimiter=" "; 

        if ($delimiter=="") return 0; 

        //chop it up 
        $p1=strpos($s,$delimiter); 
        $p2=strpos($s,$delimiter,$p1+1); 

        $x=substr($s,0,$p1); 
        $y=substr($s,$p1+1,$p2-$p1); 
        $z=substr($s,$p2+1); 

        //debug 
//        echo("$x/$y/$z"); 

        //the last value is always the year, so check it for 2- to 4-digit convertion 
        if (intval($z)<100) 
        { 
            if (intval($z)>69) $z=strval(1900+intval($z)); else $z=strval(2000+intval($z)); 
        } 

        //intelligently select which converter to use 
        //(default is M/D/Y, but if the month is "spelled out" then the format is D/M/Y) 
        if (intval($y)==0) 
        { 
            return cvdate_english($x,$y,$z); 
        } 
        else 
        { 
            return cvdate_numeric($x,$y,$z); 
        } 

    } 
    //just a helper function 
    function cvdate_english($d,$m,$y) 
    { 
        $d2=0; $m2=0; $y2=0; 

        $d2=intval($d); 
          
        $m=strtolower($m); 
        switch(substr($m,0,3)) 
        { 
            case "jan": $m2=1; break; 
            case "feb": $m2=2; break; 
            case "mar": $m2=3; break; 
            case "apr": $m2=4; break; 
            case "may": $m2=5; break; 
            case "jun": $m2=6; break; 
            case "jul": $m2=7; break; 
            case "aug": $m2=8; break; 
            case "sep": $m2=9; break; 
            case "oct": $m2=10; break; 
            case "nov": $m2=11; break; 
            case "dec": $m2=12; break;                  
        } 

        $y2=intval($y); 

        //check for errors! 
        if (($d2==0)||($m2==0)||($y2==0)) return 0; 

        //debug 
        //echo("$m2/$d2/$y2<br>\n"); 

        return mktime(0,0,0,$m2,$d2,$y2); 
    } 
    //just a helper function 
    function cvdate_numeric($m,$d,$y) 
    { 
        $d2=0; $m2=0; $y2=0; 

        $d2=intval($d); 
        $m2=intval($m); 
        $y2=intval($y); 

        //check for errors! 
        if (($d2==0)||($m2==0)||($y2==0)) return 0; 

        //debug 
        //echo("$m2/$d2/$y2<br>\n"); 

        return mktime(0,0,0,$m2,$d2,$y2); 
    } 
    //UI data selector control
    function dateSelector($inName, $useDate=0) 
    { 
        //create array so we can name months 
        $monthName = array(1=> "January",  "February",  "March", 
            "April",  "May",  "June",  "July",  "August", 
            "September",  "October",  "November",  "December"); 

        //if date invalid or not supplied, use current time 
        if($useDate == 0) 
        { 
            $useDate = Time(); 
        } 

        /* 
        ** make month selector 
        */ 
        print("<select name=" . $inName .  "Month>\n"); 
        for($currentMonth = 1; $currentMonth <= 12; $currentMonth++) 
        { 
            print("<option value=\""); 
            print(intval($currentMonth)); 
            print("\""); 
            if(intval(date( "m", $useDate))==$currentMonth) 
            { 
                print(" selected"); 
            } 
            print(">" . $monthName[$currentMonth] .  "\n"); 
        } 
        print("</select>"); 


        /* 
        ** make day selector 
        */ 
        print("<select name=" . $inName .  "Day>\n"); 
        for($currentDay=1; $currentDay <= 31; $currentDay++) 
        { 
            print("<option value=\"$currentDay\""); 
            if(intval(date( "d", $useDate))==$currentDay) 
            { 
                print(" selected"); 
            } 
            print(">$currentDay\n"); 
        } 
        print("</select>"); 


        /* 
        ** make year selector 
        */ 
        print("<select name=" . $inName .  "Year>\n"); 
        $startYear = date( "Y", $useDate); 
        for($currentYear = $startYear - 3; $currentYear <= $startYear+5;$currentYear++) 
        { 
            print("<option value=\"$currentYear\""); 
            if(date( "Y", $useDate)==$currentYear) 
            { 
                print(" selected"); 
            } 
            print(">$currentYear\n"); 
        } 
        print("</select>"); 
    }
?>
