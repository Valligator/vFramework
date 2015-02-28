<?php
require_once("vModuleInterface.php");

/*
 * The executor is responsible for running the modules, and does the following operations:
 *	1. Read config file
 *  2. Read inputs for associated module execution instance
 *  3. Validate input
 *  4. Launch module execution - using $vModule->launcher();
 *  5. Return results to the caller
 Author: sbossen btoffel
 */
class vExecutor {

	
	public static function executeModule($modulename, $filename, $taskId) {
		/*
		 * //Commented out until the other modules can be finished
		require_once($filename);
		$module = new $modulename;
		vConfig::validateModuleConfig($modulename); //Throws a module configuration file error if not valid
		$config = vConfig::getModuleConfig($modulename);
		$params = vParameters::getModuleParameters($taskId);
		$config->validateModuleParameters($params); //Throws a module parameter error if a parameter is not valid
		return $module->launcher($params);
		 * 
		 */
	}

}

?>