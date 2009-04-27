<?php

require_once('templum.php');

// Configuration.
$pathTemplates = 'view';    // Path where templates live.
$appTitle = 'Hello World';  // Application title.
$author = 'Ferry Boender';  // Application author.
$username = 'jjohnson';     // Currently logged in user (normally from session).

// Create the Templum template engine. The templates live in the 'view'
// directory. We pass one universal variable: the application title.
$templum = new Templum($pathTemplates, compact('appTitle'));

// Turn off automatic escaping of {{ }} contents using htmlspecialchars(). 
$templum->setAutoEscape(False);

// Set another universal variable.
$templum->setVar('author', $author);

// Retrieve the template with path 'view/account/list.tpl'. We pass the
// $username as a global variable to the template and turn on automatic
// escaping for this template.
$tpl = $templum->template('account/list', compact('username'), True);

// Set another global variable.
$templum->setVar('username', $username);

// Define some data. This would normally come from a database or something.
$accounts = array(
   array('id'=>1, 'username'=>'jjohnson',  'realname'=>'John Johnson'),
   array('id'=>2, 'username'=>'ppeterson', 'realname'=>'Pete Peterson'),
   array('id'=>3, 'username'=>'jdoe',      'realname'=>'John Doe'),
);

// Render the template with the accounts data.
$output = $tpl->render(compact('accounts'));

print($output);
?>
