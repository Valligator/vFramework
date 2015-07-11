<?php

@require_once ('configBase.php');

unset($auth);
$auth = array();
$auth['logged'] = 0;


// FOR TESTING - Force to logged in
$auth['logged'] = 1;


// Determine if user is logged in
if ((int)$auth['logged'] === 1) {

 

} else {
 @header("Location: ".WEB_HTML_LOGIN);
 exit();
}

?>
