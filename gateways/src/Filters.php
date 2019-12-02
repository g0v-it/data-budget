<?php
namespace MEF;


/**
 * A collection of custom filters to be used as callback with php data filtering functions
 * 	- to allow empty values  if the data is invalid filter MUST returns null
 * 	- to deny empty values if the data is invalid filter MUST returns false
 */
class Filters {
		
	/**
	 * substitute ` with '
	 */
	public static function FILTER_SANITIZE_DEFINITION($value)
	{
		$value = preg_replace('/`/', "'", $value);
		
		return $value?:false;
	}

}