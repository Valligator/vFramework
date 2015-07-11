<?php

/**
 * This convenience function just adds a line break on each printed line
 */
function print_ln($str) {
	$str .= $str."\r\n";
	print($str);
}
