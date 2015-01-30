<?php
require_once("../models/vRoot.php");

$mod = new vModule();
$mod->enabled = 1;

$result = vModuleDb::insert($mod);
if ($result){
	$read_mod_from_db = vModuleDb::getModule($result);
}
vDebug::debugLog("Tester");
assert($result >0);

