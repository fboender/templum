#!/usr/bin/php
<?php

if (count($argv) != 2) {
	print("Usage: ".$argv[0]." FILE\n");
	exit(0);
}

$path = $argv[1];
$contents = file_get_contents($path);

$contents = htmlentities($contents);
if (substr($path, -4) == '.php') {
	// Parse start-line PHP tags ("@" at the beginning of the line).
	$contents = preg_replace('/^(\/\/.*)$/m', "<font color=\"#C0C0C0\">\\1</font>", $contents);
} else {
	$contents = str_replace(
		array(
			'{{', 
			'}}', 
			'[[', 
			']]'
		),
		array(
			'<font color="#FFFF00">{{', 
			'}}</font>',
			'<font color="#D0D0D0"><font color="#FFFF00">[[</font>', 
			'<font color="#FFFF00">]]</font></font>',
		),
		$contents
	);
	// Parse start-line PHP tags ("@" at the beginning of the line).
	$contents = preg_replace('/^(\s*@.*)$/m', "<font color=\"#FFFF00\">\\1</font>", $contents);
}

print("<pre class=\"code\">");
print($contents);
print("</pre>");
?>
