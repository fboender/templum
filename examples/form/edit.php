<?php

$firstname = "ferry";
$lastname = "boender";

$tpl = $templum->template('edit');
print($tpl->render(compact('firstname', 'lastname')));

?>
