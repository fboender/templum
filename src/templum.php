<?php

$phpVersion = substr(phpversion(), 0, 1);
if($phpVersion == 5) {
	require_once('templum_php5.php');
} elseif ($phpVersion == 4) {
	require_once('templum_php4.php');
}

?>
