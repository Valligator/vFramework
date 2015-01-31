<?php
session_start();

@require_once ('config.php');

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

	 $sessId = vAuthUser::createSession($un,$pw);

//echo 'userId: ' . (int)$sessId['val_user_id'] . '<br />';
//echo 'sessId: ' .  $sessId['sessId'] . '<br />';
//exit();

	 if ($sessId['sessId'] != '') {
	  $_SESSION['sessId'] = $sessId['sessId'];
	  $err = 1;
	  $whereTo = 'index.php';
	 } else {
	  $_SESSION['err_code'] = -1;
	  $_SESSION['err_mesg'] = 'Login failed, please check username and password';
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
