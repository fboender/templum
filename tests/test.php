#!/usr/bin/php
<?php

error_reporting(E_ALL);

require_once('class.UnitTest.php');
require_once('../src/templum.php');
require_once('test.Templum_all.php');

$phpVersion = substr(phpversion(), 0, 1);
if($phpVersion == 5) {
	require_once('test.Templum_php5.php');
} elseif ($phpVersion == 4) {
	require_once('test.Templum_php4.php');
}

$tester = new UnitTest('Templum', new TestTemplum());
print($tester->getResultsText(True));
?>
