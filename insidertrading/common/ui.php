<?php
    //Common user interface functions
    function validAdminUse()
    {
        if (!($_SESSION['loginSuccess'])||(!($_SESSION['adminUser'])))
        {
            $errorText = '<B>ERROR<B>: Invalid page request';
           echo $errorText;
           trigger_error('Invalid page request: common/report.php', E_USER_ERROR);
           exit;
        }
        return;
    }

    function validCustomerUse()
    {
        if (!($_SESSION['loginSuccess'])||($_SESSION['adminUser']))
        {
           $errorText = '<B>ERROR<B>: Invalid page request';
           echo $errorText;
           trigger_error('Invalid page request: common/report.php', E_USER_ERROR);
           exit;
        }
        return;
    }

/***
    function validCustomerUse()
    {
        $result = true;
        if (!($_SESSION['loginSuccess'])||($_SESSION['adminUser']))
        {
            $errorText = '<B>ERROR<B>: Invalid page request';
            trigger_error('Invalid page request: common/report.php', E_USER_ERROR);
            $result = false;
        }
        return $result;
    }
*/

    function prefixSelector($inputName)
    {
        $prefixes = array('MR' => 'Mr.',
                          'MS' => 'Ms.',
                          'MISS' => 'Miss',
                          'MRS' => 'Mrs.',
                          'DR' => 'Dr.');

        print("<SELECT NAME=". $inputName . ">\n");
        print("<option value= \"\">");
        foreach ($prefixes as $prefixKey => $prefixValue) 
        { 
            print("<option value=\""); 
            print($prefixKey); 
            print("\""); 
            print($prefixValue); 
            print(">" . $prefixValue .  "\n"); 
        } 
        print("</SELECT>");
        return;
    }

    function suffixSelector($inputName)
    {
        $suffixes = array('JR' => 'Jr.',
                          'SR' => 'Sr.',
                          'III' => 'III',
                          'IV' => 'IV');

        print("<SELECT NAME=". $inputName . ">\n");
        print("<option value= \"\">");
        foreach ($suffixes as $suffixKey => $suffixValue) 
        { 
            print("<option value=\""); 
            print($suffixKey); 
            print("\""); 
            print($suffixValue); 
            print(">" . $suffixValue .  "\n"); 
        } 
        print("</SELECT>");
        return;
    }

    function stateSelector($inputName)
    {
        $states = array('AL' => 'Alabama',
                        'AK' => 'Alaska',
                        'AZ' => 'Arizona',
                        'AR' => 'Arkansas',
                        'CA' => 'California',
                        'CO' => 'Colorado',
                        'CT' => 'Connecticut',
                        'DE' => 'Delaware',
                        'DC' => 'District of Columbia',
                        'FL' => 'Florida',
                        'GA' => 'Georgia',
                        'GU' => 'Guam',
                        'HI' => 'Hawaii',
                        'ID' => 'Idaho',
                        'IL' => 'Illinois',
                        'IN' => 'Indiana',
                        'IA' => 'Iowa',
                        'KS' => 'Kansas',
                        'KY' => 'Kentucky',
                        'LA' => 'Louisiana',
                        'ME' => 'Maine',
                        'MD' => 'Maryland',
                        'MA' => 'Massachusetts',
                        'MI' => 'Michigan',
                        'MN' => 'Minnesota',
                        'MS' => 'Mississippi',
                        'MO' => 'Missouri',
                        'MT' => 'Montana',
                        'NE' => 'Nebraska',
                        'NV' => 'Nevada',
                        'NH' => 'New Hampshire',
                        'NJ' => 'New Jersey',
                        'NM' => 'New Mexico',
                        'NY' => 'New York',
                        'NC' => 'North Carolina',
                        'ND' => 'North Dakota',
                        'OH' => 'Ohio',
                        'OK' => 'Oklahoma',
                        'OR' => 'Oregon',
                        'PA' => 'Pennsylvania',
                        'PR' => 'Puerto Rico',
                        'RI' => 'Rhode Island',
                        'SC' => 'South Carolina',
                        'SD' => 'South Dakota',
                        'TN' => 'Tennessee',
                        'TX' => 'Texas',
                        'UT' => 'Utah',
                        'VT' => 'Vermont',
                        'VI' => 'Virgin Islands',
                        'VA' => 'Virginia',
                        'WA' => 'Washington',
                        'WV' => 'West Virginia',
                        'WI' => 'Wisconsin',
                        'WY' => 'Wyoming');

        print("<SELECT NAME=". $inputName . ">\n");
        print("<option value= \"\">");
        foreach ($states as $stateKey => $stateValue) 
        { 
            print("<option value=\""); 
            print($stateKey); 
            print("\""); 
            print($stateValue); 
            print(">" . $stateValue .  "\n"); 
        } 
        print("</SELECT>");
        return;
    }
?>
