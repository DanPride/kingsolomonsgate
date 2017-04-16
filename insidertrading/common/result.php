<?php
    /**********************************************
     *
     * result.php
     *
     * Defines Result class and functions
     *
     * Author: Scott Day
     * Date:   06-01-2003
     *
     ***********************************************/
    class Result  {
    var $severity;
    var $resultString;
    var $resultObject;
    function Result($severity, $resultString, $resultObject)
    {
        $this->severity = $severity;
        $this->resultString = $resultString;
        $this->resultObject = $resultObject;
        return;
    }
    function severity()
    {
        return $this->severity;
    }
    function resultString()
    {
        return $this->resultString;
    }
    function resultObject()
    {
        return $this->resultObject;
    }
    function setResultObject($newResultObject)
    {
        $this->resultObject = $newResultObject;
    }
    function isSuccess()
    {
        return ($this->severity == SUCCESS);
    }
    function isError()
    {
        return ($this->severity > WARNING);
    }
    function isWarning()
    {
        return ($this->severity > SUCCESS);
    }
}
?>
