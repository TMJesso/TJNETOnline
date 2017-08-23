<?php
require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'] . '../../includes/initialize.php';
	global $db;
	
	if ($db->backuptables()) {
		echo "<br/>Tables have been backed up!";
	} else {
		echo "<br/>Tables FAILED to backup!";
	}


?>