<pre><?php
ini_set('display_errors',1); 
error_reporting(E_ALL);
require_once("../models/vRoot.php");
require_once("../install/installHelper.php");
$vdb = vDb::getRootDb("root", "hell0");
if ($vdb === null) { throw new exception("Db connection is null"); }
$un = "root";
$pw = "hell0";
installHelper::install($un, $pw, VAL_DB_NAME);
?></pre>