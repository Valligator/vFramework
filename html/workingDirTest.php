<?php
chdir(dirname(__FILE__));
require_once("../models/vRoot.php");
$mod = new vModule();
$mod->enabled = 1;
$wk_dir = "Working dir is: ".getcwd();
echo($wk_dir ."\r\n");
$mod->name = $wk_dir;
$caller_api = "caller is:". php_sapi_name();
echo($caller_api ."\r\n");
$mod->startfile = $caller_api;
$result = vModuleDb::insert($mod);
