<?php
session_start();

unset($action);
unset($whereFrom);
unset($token);
unset($whereTo);
unset($sendString);
unset($err);
$action = $_REQUEST['action'];
$whereFrom = $_REQUEST['whereFrom'];
$token = $_REQUEST['token'];
$whereTo = '';
$sendString = '';
$err = -1;

if ($action != '' && $token != '' && $token === $_SESSION['csrf-token']) {
 switch($action) {

  CASE "login":
	unset($un);
	unset($pw);
	$un = $_REQUEST['un'];
	$pw = $_REQUEST['pw'];
	if ($un != '' && $pw != '') {

	 // authenticate username and password
	 if (1 == 1) {
	  $err = 1;
	  $whereTo = 'index.php';
	 }	  

	}
	break;

 }
}

$_SESSION['err'] = (int)$err;
if (!$whereTo) $whereTo = $whereFrom;
if ($sendString) $sendString = '?' . $sendString;
@header("Location: ".$whereTo.$sendString);
exit();
?>
