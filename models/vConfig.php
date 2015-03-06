<?php

/**
 * This class holds the parameter definition for items required by the module and also holds the functions to 
 * operate on them and perform validation on parameters.
 *
 * @author sbossen
 */
class vConfig {
	/**
	 * Throws an error if the current loaded validation is not valid
	 * @return boolean - true if successful
	 */
	public static function validateModuleConfig($modulename) {
		//Demo code
	}
	
	/**
	 * This function gets the config loaded in the database for the specified module
	 * @param type $modulename
	 * @return \vConfig
	 */
	public static function getModuleConfig($modulename) {
		//Demo code
		return new vConfig();
	}
	
	/**
	 * 
	 * @param vTaskParam $params
	 * @return boolean - true if successful
	 */
	public function validateModuleParameters(vTaskParam $params) {
		//Demo code
		return true;
	}
}
