<?php
require_once("vDebug.php");
/**
 * Description of vNetUtils
 *
 * @author sbossen
 */
class vNetUtils {
	
	/**
	 * This function gets the 
	 * @param type $url
	 * @param type $postParamsArray
	 * @return \vResult
	 */
	public static function getSite($url, $postParamsArray=array()) {
		$result = new vResult();
		//using try here so any errors will be caught by this script
		try {
			// use key 'http' even if you send the request to https://...
			$options = array(
				'http' => array(
					'header' => "Content-type: application/x-www-form-urlencoded\r\n",
					'method' => 'POST',
					'content' => http_build_query($postParamsArray),
				),
			);
			$context = stream_context_create($options);
			$content = file_get_contents($url, false, $context);
			$result->resultMsg = $content;
			//check for a 404 error, since that only generates a warning, not a failure
			//$http_response_header is a magic header that is filled with this info
			$error = self::checkForNetworkError($http_response_header);
			if (!empty($error)) {
				$result->exeCode = vResult::EXECUTION_NETWORK_FAILURE;
				$result->exeErrorMsg = $error;
			} else {
				$result->exeCode = vResult::EXECUTION_PASS;
			}
		} catch (Exception $e) {
			$result->exeCode = vResult::EXECUTION_FAILURE;
			$result->exeErrorMsg = $e->getMessage();
			//output to system error log
			$logError = "$url - Execution Error - ".$e->getMessage()."\r\n".$e->getTraceAsString();
			vDebug::errorLog($logError);
		}
		return $result;
	}
	
	/**
	 * Returns an error message if an error was found in the HTTP response header,
	 * otherwise returns false
	 * @param type $response
	 * @return boolean
	 */
	 public static function checkForNetworkError($response) {
		if (!empty($response) && is_array($response)) {
			$msg1 = $response[0];
			if (!stristr($msg1, "200")) {
				return var_export($response, true);
			}
		}
		return false;
	}
	
	/**
	 * Convenience function to check both GET and POST variables when
	 * performing filtering on user input
	 * @param type $field
	 * @param type $filter
	 * @return string, null or false
	 */
	public static function filter_both($field, $filter = FILTER_DEFAULT) {
		$val = filter_input(INPUT_POST, $field, $filter);
		if (is_null($val)) {
			$val = filter_input(INPUT_GET, $field, $filter);
		}
		return $val;
	}
	 
}
