<?php
/**
* Helper Functions | app/Helpers/Helper.php
*
* @package     DLaw\Helpers
*/

if(!function_exists('IsNullOrEmptyString')) {
    /**
     * Checks if the string is null or empty or a valid date.
     * 
     * @param string $str
     * @return boolean
     */
    function IsNullOrEmptyString($str){
        return (!isset($str) || trim($str) === '' || $str == '0001-01-01 00:00:00.000000' || $str == '0001-01-01 00:00:00' || $str == '0001-01-01-00:00:00.000000');
    }
}

if(!function_exists('create_action_number')) {
    /**
     * Creates valid ACTION NO by using ACTION TYPE, CASE NO and CASE YEAR.
     * 
     * @param string $actionType    ACTION TYPE
     * @param integer $caseNo       CASE NO
     * @param integer $caseYear     CASE YEAR
     * @return string
     */
    function create_action_number($actionType, $caseNo, $caseYear) {
        $case       = ltrim(trim($caseNo), '0');
        $actionNo   = trim($actionType).$case.'/'.trim($caseYear);
        
        return $actionNo;
    }
}

if(!function_exists('substr_in_array')) {
    /**
     * Finds if the input string present in an array (Starts With Input String)
     * 
     * @param string $needle
     * @param array $haystack
     * @return array
     */
    function substr_in_array($needle, array $haystack)
    {
        return array_reduce($haystack, function ($inArray, $item) use ($needle) {
            return $inArray || starts_with($item, $needle);// false !== strpos($item, $needle)
        }, false);
    }
}

if(!function_exists('str_in_array')) {
    /**
     * Find if the input string present in an array (Exact match Input String)
     * 
     * @param string $needle
     * @param array $haystack
     * @return array
     */
    function str_in_array($needle, array $haystack)
    {
        return array_reduce($haystack, function ($inArray, $item) use ($needle) {
            return $inArray || strtolower($item) === strtolower($needle);
        }, false);
    }
}

if(!function_exists('find_array_has')) {
    /**
     * Finds if any element of source array is present in target array
     * 
     * @param array $haystack
     * @param array $needles
     * @return boolean
     */
    function find_array_has($haystack, $needles)
    {
        $found = false;
        foreach($haystack as $data) {
            if (in_array($data, $needles)) {
               $found = true; break;
            }
        }
        return $found;
    }
}

if(!function_exists('filter_array_values')) {
    /**
     * Filter array values by removing the empty values
     * 
     * @param array $data
     * @return array
     */
    function filter_array_values($data)
    {
        return array_filter($data, function($value) { return $value !== ''; });
    }
}

if(!function_exists('get_parse_date')) {
    /**
     * Creates and Get a Carbon parsed date from any valid date format to a desired format.
     * 
     * @param string $date
     * @param string $format Desired Date Format, Default is 'd/m/Y'
     * @return string|null
     */
    function get_parse_date($date, $format='d/m/Y')
    {
        $date_time = str_ireplace_n('-', ' ', $date, 3);
        return !IsNullOrEmptyString($date) ? \Carbon\Carbon::parse($date_time)->format($format) : '';
    }
}

if(!function_exists('set_parse_date')) {
    /**
     * Creates and Sets a Carbon parsed date from any valid date format to a desired format to save in database.
     * 
     * @param string $date
     * @param string $format Desired Date Format, Default is 'Y-m-d H:i:s'
     * @return string|null
     */
    function set_parse_date($date, $format='Y-m-d H:i:s')
    {
        $date_time1 = str_ireplace_n('-', ' ', $date, 3);
        $date_time = str_ireplace('/', '-', $date_time1);
        return !empty($date) ? \Carbon\Carbon::parse($date_time)->format($format) : null;
    }
}

if(!function_exists('convert_string_into_amount')) {
    /**
    * Converts Claim Amount string into Currency Format
    * 
    * @param string $string  	Claim Amount
    * @return string 		Currency Formatted Claim Amount
    */
    function convert_string_into_amount($string)
    {
        $amounts = explode(';', $string);
        
        $result = array();
        foreach ($amounts as $amount) {
            $str = str_replace(',', '', trim($amount));
            $number = preg_replace('/[^0-9.]/', '', $str);
            $new_amount = !empty($number) ? number_format($number, 2) : 0;//number_format($number, 2, '.', ',');
            $result[] = str_ireplace($number, $new_amount, $str);
        }
        return implode('; ', $result);
    }
}

if(!function_exists('str_ireplace_n')) {
    /**
    * String replace nth occurrence - case-insensitive
    * 
    * @param string $find  	Search string
    * @param string $replace 	Replace string
    * @param string $subject 	Source string
    * @param integer $occurrence 	Nth occurrence
    * @return string 		Replaced string
    */
    function str_ireplace_n($find, $replace, $subject, $occurrence)
    {
        $search = preg_quote($find);
        return preg_replace("/^((?:(?:.*?$search){".--$occurrence."}.*?))$search/i", "$1$replace", $subject);
    }
}

if(!function_exists('getClaimAmount')) {
    /**
    * Creates Claim Amount structure for case modification page
    * 
    * @param string $amount  	Claim Amount
    * @return array
    */
    function getClaimAmount($amount)
    {
        $claimAmount = array(
            'rent' => 0.00,
            'rent_months' => 0,
            'rent_days' => 0,
            'rate' => 0.00,
            'rate_months' => 0,
            'rate_days' => 0,
            'fee' => 0.00,
            'fee_months' => 0,
            'fee_days' => 0,
            'renovation' => 0.00,
            'others' => 0.00
        );
        if(!empty($amount)) {
            $sTmpAmount = explode(";", $amount);
            //echo '<pre>';
            //print_r($sTmpAmount);
            $count = count($sTmpAmount);
            for ($i=0; $i<$count; $i++) {
                $sTmpData = explode(" ", trim($sTmpAmount[$i]));
                //print_r($sTmpData);
                $sAmtType = $sTmpData[0];
                if($sAmtType == 'Rent') {
                    $claimAmount['rent'] = $sTmpData[2];
                    $claimAmount['rent_months'] = $sTmpData[3];
                    $claimAmount['rent_days'] = $sTmpData[6];
                } else if($sAmtType == 'Rate') {
                    $claimAmount['rate'] = $sTmpData[2];
                    $claimAmount['rate_months'] = $sTmpData[3];
                    $claimAmount['rate_days'] = $sTmpData[6];
                } else if($sAmtType == 'Mgmt') {
                    $claimAmount['fee'] = $sTmpData[3];
                    $claimAmount['fee_months'] = $sTmpData[4];
                    $claimAmount['fee_days'] = $sTmpData[7];
                } else if($sAmtType == 'Renovation') {
                    $claimAmount['renovation'] = $sTmpData[2];
                    $claimAmount['others'] = isset($sTmpData[5]) ? $sTmpData[5] : 0.00;
                } else if($sAmtType == 'Others') {
                    $claimAmount['others'] = $sTmpData[2];
                }
            }
        }
        //print_r($claimAmount);
        //echo '</pre>';
        return $claimAmount;
    }
}

if(!function_exists('create_file_name')) {
    /**
    * Creates file name for Daily Cause List
    * 
    * @param string $file_name
    * @param string $action_no
    * @param string $filing_date
    * @return string
    */
    function create_file_name($file_name, $action_no, $filing_date)
    {
        $array = explode('.', trim($file_name));
        $extension = end($array);
        $name = str_ireplace('/', '-', $action_no);
        $date = get_parse_date($filing_date, 'dMY');
        return $name . '_' . $date . '.' . $extension;
    }
}

if(!function_exists('remove_special_characters')) {
    /**
     * Remove special characters from any string
     * 
     * @param string $str
     * @return string
     */
    function remove_special_characters($str)
    {
        $temp_1 = preg_replace('/\s+/', '', $str);
        $temp_2 = str_replace(array('[', ']', '\'', '(', ')', '.', ',', ':', '-', '/', '"', '&'), '', $temp_1);
        return strtolower($temp_2);
    }
}

if (!function_exists('address_format')) {
    /**
     * Creates formatted address to append Lot, DD and Cark Park texts.
     * 
     * @param array $addressData
     * @return array
     */
    function address_format($addressData) {
        $address = array();
        
        foreach($addressData as $field => $value):
            if($field !== 'ADDRESS_ID' && $field !== 'ADDRESS_SEQ' && !empty(trim($value))):
                if($field === 'REGION' && strtoupper(trim($value)) === 'FOREIGN') {
                    $value = '';
                } else if($field === 'LOT_NO') {
                    $value = 'Lot ' . trim($value);
                } else if($field === 'DD_NO') {
                    $value = 'DD ' . trim($value);
                } else if($field === 'CAR_PARK_NO') {
                    $value = 'Car Parking Space ' . trim($value);
                } else {
                    $value = trim($value);
                }
                $address[] = $value;
            endif;
        endforeach;
        
        return $address;
    }
}