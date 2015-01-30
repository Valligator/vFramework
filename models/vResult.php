<?php
/**

Function naming style: camelCase
Class naming style: PascalCase or camelCase when prefixed with a v for Valligator (filename matches class name)
Variable naming style: camelCase
Constants naming style: ALL_CAPS

This is a result class used to send the result of a module execution back 
to the framework

Usage: 

When a module executes, it has a validation result and a execution result.
The validation result is meant to convey information such as did the object get validated correctly or not.
The execution result is meant to further identify the cause of the error when one does occur.

For example say a page was expected to have the words "Katy Perry", and we run this test against several pages:
Page 1 - http://www.katyperry.com/ - features the words "Katy Perry".
Page 2 - http://www.time.gov/ - The US government time clock, does not feature the words "Katy Perry". 
Page 3 - http://www.nothing.here/ - This page does not exist at all.


When Page 1 is validated, I should get:
	$vResult = $module->launcher();
	//The execution result passed
	$vResult->exeCode == vExecutionResult::EXECUTION_PASS;
	//The module validation result passed
	$vResult->moduleCode == vModuleResult::VALIDATION_PASS;
	
When Page 2 is validated, I should get:
	$vResult = $module->launcher();
	//The execution result passed, because it was able to successfully read the location
	$vResult->exeCode == vExecutionResult::EXECUTION_PASS;
	//The module validation result failed
	$vResult->moduleCode == vModuleResult::VALIDATION_FAIL;
	
When Page 3 is validated, I should get (since nothing is there):
	$vResult = $module->launcher();
	//The execution result failed as nothing existed at the location
	$vResult->exeCode == vExecutionResult::EXECUTION_NETWORK_FAILURE;
	//The module validation result failed, because it never got to read data
	$vResult->moduleCode == vModuleResult::VALIDATION_FAIL;
	
*/
class vResult {

	//Execution Codes
	const EXECUTION_PASS = 0;
	const EXECUTION_NETWORK_FAILURE = -2;
	const EXECUTION_FAILURE = -3;
	const EXECUTION_NOT_STARTED = -4;

	//Execution Fields
	public $exeErrorMsg = "";
	public $exeCode = self::EXECUTION_NOT_STARTED;

	//Module Codes
	const VALIDATION_PASS = 0;
	const VALIDATION_FAIL = -1;
	const VALIDATION_NOT_EXECUTED = -2;

	//General/Module Fields
	public $resultMsg = "";
	
	//Module Specific Fields
	public $moduleCode = self::VALIDATION_NOT_EXECUTED;
	public $moduleErrorLevel = 0; //0-255

	public function copyExecutionResult(vResult $copyFrom) {
		$this->exeErrorMsg = $copyFrom->exeErrorMsg;
		$this->exeCode = $copyFrom->exeCode;
	}
	
	public function copy(vResult $copyFrom) {
		$this->copyExecutionResult($copyFrom);
		$this->resultMsg = $copyFrom->resultMsg;
		$this->moduleCode = $copyFrom->moduleCode;
		$this->moduleErrorLevel = $copyFrom->moduleErrorLevel;
	}

}

//class vExecutionResult {
//
//	const EXECUTION_PASS = 0;
//	const NETWORK_FAILURE = -2;
//	const EXECUTION_FAILURE = -3;
//	const EXECUTION_NOT_STARTED = -4;
//
//	public $errorMsg = "";
//	public $code = self::EXECUTION_NOT_STARTED;
//}
//
//class vModuleResult {
//
//	const VALIDATION_PASS = 0;
//	const VALIDATION_FAIL = -1;
//	const VALIDATION_NOT_EXECUTED = -2;
//
//	public $resultMsg = "";
//	public $code = self::VALIDATION_NOT_EXECUTED;
//	public $errorLevel = 0; //0-255
//}

