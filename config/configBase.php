<?php
@require_once ('configDb.php');

DEFINE('ABS_SYSTEM','/usr/VALLIGATOR/vFramework');
DEFINE('WEB_HTML','https://valligator.com/demo');
DEFINE('FW_VERSION','0.01-dev');



//------- Should be no need to change values below this line -------//

DEFINE('ABS_CONFIG',ABS_SYSTEM.'/config');
DEFINE('ABS_HTML',ABS_SYSTEM.'/html');
DEFINE('ABS_MODELS',ABS_SYSTEM.'/models');
DEFINE('ABS_MODULES',ABS_SYSTEM.'/modules');

DEFINE('WEB_HTML_LOGIN',WEB_HTML.'/login.php');
DEFINE('WEB_HTML_HOME',WEB.HTML.'/index.php');


// Load Autoloader
function __autoload($className) {
 static $append = '.php';
 if (file_exists(ABS_MODELS.'/'.$className.$append)) {
  @require_once (ABS_MODELS.'/'.$className.$append);
 }
}
?>
