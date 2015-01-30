<?php
/**
 * These are commonly used or convenience functions
 */
class vUtils {
	//put your code here
	public static function setEntryIfExists($arr, $index, &$target) {
		if (!empty($arr) && array_key_exists($index, $arr)) {
			$target = $arr[$index];
		}
	}
	
	public static function getMicrotimeMS(){
        list($usec, $sec) = explode(" ", microtime()); 
        return round(1000 * ((float)$usec + (float)$sec));
	}
}
