<?php

require_once('templum.php');

$templum = new Templum('.');
$tpl_index = $templum->template('index');
print($tpl_index->render());
?>
