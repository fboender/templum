#!/usr/bin/php
<?php

if (count($argv) != 2) {
	print("Usage: ".$argv[0]." FILE\n");
	exit(0);
}

$path = $argv[1];
$contents = file_get_contents($path);

$contents = preg_replace(
	array(
		'/\/\*\*.*class.*Error extends.*^}\n\n/Usm',
		'/(public|private) (static )*function/',
		'/throw new .*Error\("(.*)", .*/',
	),
	array(
		'',
		'function',
		'trigger_error("\1", E_USER_ERROR); die();',
	),
	$contents
);
print $contents;

?>
