<?php
session_start();

@require_once('config.php');


/*
$mods = vModuleDb::getModules();

echo "<pre>";
print_r($mods);
echo "</pre>";

exit();
*/


// Determine current page
unset($phpSelf);
$phpSelfArr = explode("/",$_SERVER['PHP_SELF']);
end($phpSelfArr);
$lastIndex = key($phpSelfArr);
$phpSelf = $phpSelfArr[$lastIndex];

// Create new CSRF token and store in session variable
unset($token);
$token = vAuthUser::genNewToken();
$_SESSION['csrf-token'] = $token;

// Grab error messages
$thisErrCode = 0;
$thisErrMesg = '';
if (isset($_SESSION['err_code']) == true) $thisErrCode = (int)$_SESSION['err_code'];
if (isset($_SESSION['err_mesg']) == true) $thisErrMesg = (string)$_SESSION['err_mesg'];
$_SESSION['err_code'] = '';
$_SESSION['err_mesg'] = '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge" />
 <meta name="viewport" content="width=device-width, initial-scale=1" />
 <meta name="description" content="" />
 <meta name="author" content="" />
 <title>Valligator Framework</title>
 <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />
 <link type="text/css" rel="stylesheet" href="css/login.css" />
<!--[if lt IE 9]>
 <script type="text/javascript" src="js/html5shiv.min.js"></script>
 <script type="text/javascript" src="js/respond.min.js"></script>
<![endif]-->
</head>
<body>
<div class="container">
 <form class="form-signin" name="loginForm" id="loginForm" action="process-login.php" method="post" onsubmit="return verifyLogin();">
  <h2 class="form-signin-heading text-center">Valligator Framework</h2>
  <div style="margin-top:10px;">
   <label for="inputUser" class="sr-only">Username</label>
   <input type="text" id="inputUser" name="un" class="form-control" placeholder="Username" required="required" autofocus="autofocus" />
  </div>
  <div style="margin-top:10px;">
   <label for="inputPassword" class="sr-only">Password</label>
   <input type="password" id="inputPassword" name="pw" class="form-control" placeholder="Password" required="required" />
  </div>
  <div style="margin-top:25px;"><button id="formBtn" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button></div>
<?php
if ((int)$thisErrCode < 0) { echo '<div style="margin-top:25px;text-align:center;" class="alert alert-danger" role="alert"><strong>ERROR!</strong> '.$thisErrMesg.'</div>'; } 
?>
  <input type="hidden" name="action" value="login" />
  <input type="hidden" name="whereFrom" value="<?=$phpSelf;?>" />
  <input type="hidden" name="token" value="<?=$token;?>" />
 </form>
</div>
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript">
function verifyLogin() {
 var btn = $('#formBtn').html();
 $('#formBtn').attr('disabled',true);
 $('#formBtn').html('Please Wait...');
 var trigger = 0;
 var message = "";
 if ($('#inputUser').val() == '') { trigger = 1; message = message + "* Please enter your username\n"; }
 if ($('#inputPassword').val() == '') { trigger = 1; message = message + "* Please enter your password\n"; }
 if (trigger == 1) {
  alert(message);
  $('#formBtn').html(btn);
  $('#formBtn').attr('disabled',false);
  return false;
 }
}
</script>
</body>
</html>