<?php

@require_once ('configBase.php');

unset($auth);
$auth = array();
$auth['logged'] = 0;

// Determine if user is logged in
if ((int)$auth['logged'] === 1) {

 

} else {
 @header("Location: ".WEB_HTML_LOGIN);
 exit();
}

?>
