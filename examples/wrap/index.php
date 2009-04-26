<?php

require_once('templum.php');

// Some configuration variables.
$pathTemplate = '.'; // Templates are found in the current directory.

// Create a new Templum template engine.
$templum = new Templum($pathTemplate);

// Retrieve the template that is the contents.
$tpl_contents = $templum->template('contents');
$contents = $tpl_contents->render();

// Retrieve the template that wraps the contents.
$tpl_main = $templum->template('main');
print($tpl_main->render(compact('contents')));

?>
