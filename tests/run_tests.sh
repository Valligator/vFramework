#!/bin/bash
#performs test and displays errors if they occur
function t {
	echo Running: phpunit --stop-on-failure $1
	if phpunit --stop-on-failure $1 > test_out.txt
	then 
		echo "passed"
	else
		echo "*** FAILED ***"
		cat test_out.txt | more
		echo Run: phpunit --stop-on-failure $1
		exit 1
	fi
} 

#record start
echo Start:$(date)
#echo $(date) > timestart.txt

#tests to run
t /usr/VALLIGATOR/vFramework/tests/vModuleDb_Test.php
t /usr/VALLIGATOR/vFramework/tests/vModConfigDb_Test.php
t /usr/VALLIGATOR/vFramework/tests/vTaskDb_Test.php

echo End:$(date)
#echo $(date) > timeend.txt
