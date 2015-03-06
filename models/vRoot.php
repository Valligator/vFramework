<?php

DEFINE('ABS_MODELS','');

/*
 * This file contains all the necessary includes for running the project
 * 
 */
//Database configuration
require_once("../config/configDb.php");

//Valligator common libraries
require_once("vDb.php");
require_once("vNetUtils.php");
require_once("vUtils.php");
require_once("vDebug.php");

//Valligator models
require_once("vModuleDb.php");
require_once("vModConfigDb.php");
require_once("vTaskParamDb.php");
require_once("vTaskDb.php");
require_once("vExecutor.php");

?>