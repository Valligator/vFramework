<?php
require_once("vResult.php");
require_once("vParameters.php");
require_once("vDebug.php");
require_once("vConfig.php");
require_once("vModuleDb.php");

interface vModuleInterface {
	/**
	 * This function performs the actual validation or inspection that is needed
	 * and must return a vResult object
	 * @param vParameters $params
	 */
	public function launcher(vParameters $params);
}
?>