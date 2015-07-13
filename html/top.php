<?php
session_start();

@require_once('config.php');
@require_once(ABS_CONFIG.'/configFramework.php');

$navActive = ' active';
$navDash = '';
$navTask = '';
$navMod = '';
switch($topNav) {
 CASE "dash": $navDash = $navActive; break;
 CASE "task": $navTask = $navActive; break;
 CASE "mod": $navMod = $navActive; break;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8" />
 <title>Valligator Framework</title>
 <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />
</head>
<body>
<div class="navbar navbar-default" role="navigation">
 <div class="container-fluid">
  <div class="navbar-header">
   <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
   </button>
   <a class="navbar-brand" href="index.php">Valligator Framework</a>
  </div>
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
   <ul class="nav navbar-nav">
    <li class="<?php echo $navDash;?>"><a href="#">Dashboard <span class="sr-only">(current)</span></a></li>
    <li class="<?php echo $navTask;?>"><a href="#">Task Queue</a></li>
    <li class="dropdown">
     <a href="#" class="dropdown-toggle<?php echo $navMod;?>" data-toggle="dropdown" role="button" aria-expanded="false">Modules <span class="caret"></span></a>
     <ul class="dropdown-menu" role="menu">
      <li><a href="module.php">View Modules</a></li>
      <li><a href="module-import.php">Import New Module</a></li>
      <li class="divider"></li>
      <li><a href="#">Find More Modules</a></li>
     </ul>
    </li>
    <li><a href="#">Parameters</a></li>
    <li><a href="#">Logout</a></li>
   </ul>
  </div>
 </div>
</div>
