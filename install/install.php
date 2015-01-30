<?php

chdir(dirname(__FILE__));
require_once("../models/vRoot.php");
require_once("installHelper.php");

//check if anything was passed to the script, if not, display request for info
$count = count($argv);
$un = "";
$pw = "";

if ($count > 2) {
	//we can setup/update the admin connection
	$un = $argv[1];
	$pw = $argv[2];
	//$db = vDb::getRootDb($un, $pw, VAL_DB_NAME);
	//assert(!empty($db), "Could not connect to PDO MySQL connection.");
} else {
	assert(false, "SQL username and password were not passed.");
}
//create/update the database files in the regular database
installHelper::install($un, $pw, VAL_DB_NAME);
//create/update the database files in the test database
installHelper::install($un, $pw, vDb::getTestDbName());
?>