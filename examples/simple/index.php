<?php

require_once('../../src/templum.php');

$templum = new Templum('.');
$tpl_index = $templum->template('index');
print($tpl_index->render());
?>
