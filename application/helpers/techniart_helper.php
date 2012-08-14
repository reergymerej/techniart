<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//	return the approriate class to indicate that this column is sorted on
if ( ! function_exists('table_sort')){
    function table_sort($filter, $column){
        //	sorting by this column?
		if( $filter['sort_by'] == $column ){
			//	which direction?
			return $filter['direction'];
		}
		return;
    }   
}

//	return { value"XXX" selected="selected" } to re-populate forms
if( !function_exists('form_option') ){
	function form_option($optionValue, $valueToTest){
		$str = 'value="' . $optionValue . '"';
		if($optionValue == $valueToTest){
			$str .= ' selected="selected"';	
		}
		
		return $str;
	}
}