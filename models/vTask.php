<?php


/**
 * Container for the values to represent a module object
 * Validation code should go here
 */
class vTask {
	public $task_id = 0;
	public $task_status = 0;
	public $module_ref = 0;
	public $time_created = "";
	public $time_to_execute = "";
	public $time_start = "";
	public $time_completed = "";
	public $result_module_code = 0;
	public $result_module_msg = "";
	public $result_execution_level = 0;
	public $result_execution_code = 0;
	public $result_execution_msg = "";
	public $module_startfile = "";
	
	/**
	 * This function reads the passed object from the HTML POST/GET and sanitizes the data
	 * @return \vTask
	 */
	public static function readTaskFromPost() {
		$item = new vTask();
		//sanitize input
		$item->id = vNetUtils::filter_both(self::MOD_ID, FILTER_SANITIZE_NUMBER_INT);
		$item->name = vNetUtils::filter_both(self::MOD_NAME, FILTER_SANITIZE_NUMBER_INT);
		$item->startfile = vNetUtils::filter_both(self::MOD_STARTFILE, FILTER_SANITIZE_NUMBER_INT);
		$item->enabled = vNetUtils::filter_both(self::MOD_ENABLED, FILTER_SANITIZE_NUMBER_INT);
		$item->time_created = vNetUtils::filter_both(self::MOD_TIME_CREATED, FILTER_SANITIZE_NUMBER_INT);
		$item->time_lastrun = vNetUtils::filter_both(self::MOD_TIME_LASTRUN, FILTER_SANITIZE_NUMBER_INT);
		return $item;
	}
	



}
?>
