<?php

/**
 * Description of vTaskParam
 *
 * @author sbossen
 */
class vTaskParam {
	
	public $task_ref = 0; //id of the task this parameter is associated with
	public $param_keyname = ""; //The key name of the parameter
	public $module_ref = ""; //The module id of module this parameter came from (to make lookups easier)
	public $param_displayname = ""; //Name of the parameter
	public $param_type = ""; //Type of the parameter
	public $param_value = ""; //Actual value of the parameter

}
