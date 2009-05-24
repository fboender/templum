<?php

$apptitle = 'MyPage';                       // Application title
$pages = array('home', 'about', 'contact'); // List of available pages
$page = 'home';                             // Default page

require_once('templum.php');

$templum = new Templum('view', compact('apptitle'));

if (array_key_exists('page', $_GET) && in_array($_GET['page'], $pages)) {
	$page = $_GET['page'];
}

$tpl = $templum->template($page);
print($tpl->render());

?>
