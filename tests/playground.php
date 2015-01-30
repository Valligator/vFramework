<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

$include = 'ini_set(\'display_errors\',1); 
error_reporting(E_ALL);
require_once("../models/vRoot.php");';

$code = "";

if (isset($_POST["test_php"]))
	$code = $_POST["test_php"];

display_test_page($include, $code);

if (strlen($code) > 0) {
	file_output($include, $code);
	display_frame();
} else
	echo "No code to interpret";

function display_test_page($include, $code) {
	//include ('../html/top.php');
	echo ("<br />");
	echo("Enter PHP code:<br />&lt;?php<br />".nl2br($include)."<br />");
	//echo('<div width="100%">');
	echo('<form method="post" >');
	echo('<textarea cols="100%" rows="10" id="test_php" name="test_php">');
	echo("$code</textarea><br />?&gt;<br />");
	echo('<input type="submit" /><br />');
	echo("</form>");
	//echo('</div>');
	//include ('../html/bot.php');
}

function display_frame() {
	//write file out
	echo "Results:<br />";
	echo '<iframe name="ifr2" id="ifr2" src="test_code.php" frameborder="1" border="1"';
	echo 'cellspacing="0" width="700" height="300" >';
	echo "</iframe><br />";
}

function file_output($include, $code) {
	$code = "<pre><?php\n$include\n".$code."\n?></pre>";
	$myFile = "test_code.php";
	$fh = fopen($myFile, 'w') or die("can't open file");
	fwrite($fh, $code);
	fclose($fh);
}

?>