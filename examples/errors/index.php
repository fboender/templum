<?php

require_once('../../src/templum.php');

$templum = new Templum('.');
$tpl = $templum->template('notice');
try {
	$tpl->render();
} catch (TemplumTemplateError $e) {
	var_dump($e->getTemplate()->contents);
}

?>
