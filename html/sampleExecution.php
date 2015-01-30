<?php
require_once("../models/vRoot.php");

$vResult = vExecutor::executeModule("SimpleWebValidator", "SimpleWebValidator.php", 0);
print_r($vResult);
?>