<?php

/**
 * A collection of custom filters to be used as callback with php data filtering functions
 *  - to allow empty values  if the data is invalid filter MUST returns null
 *  - to deny empty values if the data is invalid filter MUST returns false
 */
class Helper {
    

     /**
     * escape double quotes, backslash and new line
     * empty allowed
     */
    public static function FILTER_SANITIZE_TURTLE_STRING($value)
    {
        //$value = ucfirst(strtolower($value));
                 
        // quote for turle
        $value = preg_replace('/\\\\/', '\\\\\\\\', $value);    // escape backslash
        $value = preg_replace('/\r?\n|\r/', '\\n', $value);  // newline 
        $value = preg_replace('/"/', '\\"', $value);        // escape double quote
        
        return $value?:null;
    }
    
    
    public static function NORMALIZE_AMOUNT($num)
    {
        return sprintf("%1.2F",round( floatval($num??0.00),2)) ;
    }
    
}