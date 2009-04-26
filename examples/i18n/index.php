<?php

require_once('templum.php');

$fullname = 'John Johnson';

// Retrieve the default language version.
$templum = new Templum('view');
$tpl = $templum->template('helloworld');
print $tpl->render(compact('fullname'));

// Retrieve the Dutch language version.
$templum->setLocale('nl_NL');
$tpl = $templum->template('helloworld');
print $tpl->render(compact('fullname'));
?>
