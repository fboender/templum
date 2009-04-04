<pre class="code">&lt;?php

require_once('../../src/templum.php');

<font color="#C0C0C0">// Dafine some data. This might as well have come from a database.</font>
$username = 'jjohnson';
$accounts = array(
   array('id'=&gt;1, 'username'=&gt;'jjohnson',  'realname'=&gt;'John Johnson'),
   array('id'=&gt;2, 'username'=&gt;'ppeterson', 'realname'=&gt;'Pete Peterson'),
   array('id'=&gt;3, 'username'=&gt;'jdoe',      'realname'=&gt;'John Doe'),
);

<font color="#C0C0C0">// Create the Template engine with the base path for the templates.</font>
$templum = new Templum('view');

<font color="#C0C0C0">// Set a universal variable which will be available in every template created</font>
<font color="#C0C0C0">// using the template engine.</font>
$templum-&gt;setVar('username', $username);

<font color="#C0C0C0">// Retrieve and render a template with the data in $accounts as a local</font>
<font color="#C0C0C0">// variable and $username as a universal variable.</font>
$tpl = $templum-&gt;template('account/list');
print($tpl-&gt;render(compact('accounts')));
?&gt;
</pre>