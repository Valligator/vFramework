ECHO show utf8 characters properly in windows
ECHO NOTE: Make sure to use Lucida Console font in command prompt as well
CHCP 65001
phpunit --stop-on-failure vModConfigDb_Test.php
phpunit --stop-on-failure vModuleDb_Test.php
ECHO chcp 1252 to switch back to latin-1 encoding