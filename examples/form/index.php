<?php

require_once('templum.php');

$templum = new Templum('view');

switch($_GET['action']) {
	case 'edit':
		require_once('edit.php');
		break;
	case 'save':
		require_once('save.php');
		break;
	default:
		require_once('edit.php');
		break;
}

?>
