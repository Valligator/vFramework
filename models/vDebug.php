<?php

/**
 * Description of vDebug
 *
 * @author sbossen
 */
class vDebug {

	const ERROR_FILE = "../logs/error.log";
	const DEBUG_FILE = "../logs/debug.log";
	const EVENT_FILE = "../logs/event.log";

	public static function errorLog($str) {
		self::outputToFile(self::ERROR_FILE, $str);
	}

	public static function debugLog($str) {
		self::outputToFile(self::DEBUG_FILE, $str);
	}

	public static function eventLog($str) {
		self::outputToFile(self::EVENT_FILE, $str);
	}

	protected static function outputToFile($fn, $str) {

		//automatically detects file, method, and line 
		$caller = self::getOriginalCallerInfo();
		if ($caller) { //get second caller -- first is usually log_to_file
			self::append(vUtils::getMicrotimeMS()." - $caller - $str\n", $fn);
		} else {
			self::append(vUtils::getMicrotimeMS()." - ".$str."\n", $fn);
		}
	}

	/**
	 * Performs the output to the file
	 * the '@' is used on the file_put_contents(..) command in case php does not have permissions for the file
	 * 
	 * @param type $string
	 * @param type $filename
	 */
	protected static function append($string, $filename) {
		file_put_contents($filename, $string, FILE_APPEND);
	}

	/**
	 * Gets the second to last caller in the backtrace (usually the most helpful)
	 * @return type
	 */
	public static function getOriginalCallerInfo() {
		$stacktrace = debug_backtrace();
		$size = count($stacktrace);
		$node = "";
		if (!empty($stacktrace)) {
			$node = $stacktrace[$size - 1];
		}
		return self::getCallerInfoFromNode($node);
	}

	protected static function getCallerInfoFromNode($node) {
		$str = "No call stack available";
		if (!empty($node)) {
			if (array_key_exists('file', $node)) {
				$str = basename($node['file'])."::".$node['function']."(".$node['line'].")";
			}
		}
		return $str;
	}

}
