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

	// base64 encode (URL safe)
	public static function base64_url_encode($input) {
		return strtr(base64_encode($input), '+/=', ',_-');
	}

	// base64 decode (URL safe)
	public static function base64_url_decode($input) {
		return base64_decode(strtr($input, ',_-', '+/='));
	}

	// generate 56-character key string (used for tokens, salts, sessions, etc.)
	public static function genKey() {
		return base64_encode(mcrypt_create_iv(40, MCRYPT_DEV_URANDOM));
	}

}
