<?php

require_once('templum.php');

$templum = new Templum('view');

$tpl = $templum->template('parent');
print($tpl->render());

?>
