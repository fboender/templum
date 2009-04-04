<?php

require_once('templum.php');

// Dafine some data. This might as well have come from a database.
$username = 'jjohnson';
$accounts = array(
   array('id'=>1, 'username'=>'jjohnson',  'realname'=>'John Johnson'),
   array('id'=>2, 'username'=>'ppeterson', 'realname'=>'Pete Peterson'),
   array('id'=>3, 'username'=>'jdoe',      'realname'=>'John Doe'),
);

// Create the Template engine with the base path for the templates.
$templum = new Templum('view');

// Set a universal variable which will be available in every template created
// using the template engine.
$templum->setVar('username', $username);

// Retrieve and render a template with the data in $accounts as a local
// variable and $username as a universal variable.
$tpl = $templum->template('account/list');
print($tpl->render(compact('accounts')));
?>
