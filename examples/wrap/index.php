<?php

require_once('templum.php');

$templum = new Templum('.');

$tpl_contents = $templum->template('contents');
$contents = $tpl_contents->render();

$tpl_main = $templum->template('main');

print($tpl_main->render(compact('contents')));
?>
