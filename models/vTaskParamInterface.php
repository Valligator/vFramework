<?php

/**
 * Description of vTaskParamInterface
 *
 * @author sbossen
 */
class vTaskParamInterface {
	/**
	 * Contains the parameters to be posted to each page prior to reading the data
	 * Common usage is to perform login functions
	 * Format for global post params is:
	 * $post_params_array = array();
	 * $post_params_array['usernm'] = 'valueA';
	 * $post_params_array['passwd'] = 'valueB';
	 *	 * @var type array
	 */
	protected $globalPostParams = array(); 
	
	/**
	 * This variable contains the user specified values of all the fields needed by the module
	 * @var type array of fields and arrays
	 */
	protected $values = array();

	/**
	 * Retrieves the global fields to be used to post to each page
	 * @return type array
	 */
	public function getGlobalPostParams() {
		return $this->globalPostParams;
	}
	
	/**
	 * Used to retrieve a specific value from fields required by the modules
	 * @param type $keyName
	 * @return type array
	 */
	public function getParamArr($keyName) {
		if (array_key_exists($keyName, $this->values)) {
			return $this->values[$keyName];
		} else {
			return array();
		}
	}
	
	/**
	 * Gets the parameters for the specified execution instance of a module
	 * @param type $taskId
	 * @return \vTaskParam
	 */
	public static function getModuleParameters($taskId) {
		//Demo code
		$id = intval($taskId);
		return new vTaskParam();
	}

}
