#!/usr/bin/php
<?php

if (count($argv) != 2) {
	print("Usage: ".$argv[0]." FILE\n");
	exit(0);
}

$path = $argv[1];
$contents = file_get_contents($path);

print $contents;

?>
