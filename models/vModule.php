<?php


/**
 * Container for the values to represent a module object
 * Validation code should go here
 */
class vModule {
	public $id = 0;
	public $name = "";
	public $startfile = "";
	public $enabled = 1;
	public $time_created = "";
	public $time_lastrun = "";
	
	/**
	 * This function reads the passed object from the HTML POST/GET and sanitizes the data
	 * @return \vModule
	 */
	public static function readModuleFromPost() {
		$mod = new vModule();
		//sanitize input
		$mod->id = vNetUtils::filter_both(self::MOD_ID, FILTER_SANITIZE_NUMBER_INT);
		$mod->name = vNetUtils::filter_both(self::MOD_NAME, FILTER_SANITIZE_NUMBER_INT);
		$mod->startfile = vNetUtils::filter_both(self::MOD_STARTFILE, FILTER_SANITIZE_NUMBER_INT);
		$mod->enabled = vNetUtils::filter_both(self::MOD_ENABLED, FILTER_SANITIZE_NUMBER_INT);
		$mod->time_created = vNetUtils::filter_both(self::MOD_TIME_CREATED, FILTER_SANITIZE_NUMBER_INT);
		$mod->time_lastrun = vNetUtils::filter_both(self::MOD_TIME_LASTRUN, FILTER_SANITIZE_NUMBER_INT);
		return $mod;
	}
	



}
?>