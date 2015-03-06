<?php
//includes pulled in through vRoot.php

interface vModuleInterface {
	/**
	 * This function performs the actual validation or inspection that is needed
	 * and must return a vResult object
	 * @param vTaskParam $params
	 */
	public function launcher(vTaskParam $params);
}
?>