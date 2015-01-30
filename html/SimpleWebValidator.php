<?php

/**
 * This module is used to validate a given list of URLs.
 * It tests that the URL contains the given strings and that it does not contain any of the given
 * error strings.
 */
class SimpleWebValidator implements vModuleInterface {

	public function launcher(vParameters $params) {
		$result = new vResult();

		//First get the global post params - for logging in for example
		$globalPostParams = $params->getGlobalPostParams();
		//Now get module post params - in case any fields need to be sent using HTTP POST
		$moduleSpecificPostParams = $params->getParamArr('post_params');
		//combine all post items into one array
		$postParams = array_merge($globalPostParams, $moduleSpecificPostParams);
		//get the URLs that we are testing
		$urls = $params->getParamArr('urls');
		$expectedArr = $params->getParamArr('expected_strings');
		$errorIndicationArr = $params->getParamArr('error_strings');

		//Perform the validation:
		//If nothing gets marked as a failure, then consider this test passed
		$result->moduleCode = vResult::VALIDATION_PASS;
		
		//Loop through the urls and get their content to perform the inspection
		foreach ($urls as $url) {
			$networkResult = vNetUtils::getSite($url, $postParams);
		
			if ($networkResult->exeCode === vResult::EXECUTION_PASS) {
				//We successfully retrieved the page from the network
				//Test checking the expected strings
				$expectedResult = $this->checkForExpectedStrings($expectedArr, $networkResult, $url);
				//if an error was found, do not continue
				if ($expectedResult->moduleCode === vResult::VALIDATION_FAIL) {
					//Expected result check failed, so stop checking
					$result->copy($expectedResult);
					break;
				}

				//Expected items passed ok, start next phase of testing
				//Test checking for strings that should not be present
				$errIndicationResult = $this->checkForErrorStrings($errorIndicationArr, $networkResult, $url);
				if ($errIndicationResult->moduleCode === vResult::VALIDATION_FAIL) {
					//Expected result check failed, so stop checking
					$result->copy($errIndicationResult);
					break;
				}
			} else {
				//We could not retrieve the page from the network (ie. I/O error or network error)
				$result->copyExecutionResult($networkResult);
				$result->moduleCode = vResult::VALIDATION_FAIL;
				$result->resultMsg = "Could get the URL to evaualate it from '$url'";
				//Do not bother checking any other items
				break;
			}
		}

		return $result;
	}

	public function checkForExpectedStrings($expectedArr, &$networkResult, $url) {
		$result = vResult();
		foreach ($expectedArr as $pass_str) {
			if (!stristr($networkResult->resultMsg, $pass_str)) {
				$result->moduleCode = vResult::VALIDATION_FAIL;
				$result->resultMsg = "Validation indication: '$pass_str' was not found in page content on '$url'.\r\n";
				break;
			}
		}
		return $result;
	}

	public function checkForErrorStrings($errorIndicationArr, vResult &$networkResult, $url) {
		$result = vResult();
		foreach ($errorIndicationArr as $err_str) {
			if (stristr($networkResult->resultMsg, $err_str)) {
				$result->moduleCode = vResult::VALIDATION_FAIL;
				$result->resultMsg = "Suspected error indication: '$err_str' found in page content on '$url'.\r\n";
				break;
			}
		}
		return $result;
	}

	public function setup() {
		
	}

	public function tearDown() {
		
	}

}
