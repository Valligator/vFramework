<?php
session_start();

@require_once('config.php');
@require_once(ABS_CONFIG.'/configFramework.php');

$action = '';
$token = '';
$val = array();
if (isset($_REQUEST['action']) == true) $action = $_REQUEST['action'];
if (isset($_SESSION['csrf-token']) == true) $token = $_SESSION['csrf-token'];
if ($action != '' && $token != '' && $token === $_SESSION['csrf-token']) {
 switch($action) {

     CASE "viewModList":
         // Return listing of all modules
         unset($mod);
         $mod = vModuleDb::getModules();
         if (isset($mod) == true) {
             $val = (array)$mod;
             //$val['test'] = 'asdf';
         }
         break;

 }
}
if (isset($val) == true) {
 @header('Content-Type: application/json');
 echo json_encode($val);
}
exit();
?>