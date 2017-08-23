<?php
require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'] . '../../includes/initialize.php';
	
	
	
	// if (!$session->is_logged_in()) { redirect_to("login.php"); }
	
	$db->find_selected_photo();
	
	if (!$current_photo) {
		// page ID was missing or invalid or
		// page couldn't be found in database
		redirect_to("manage_content.php");
	}
	
	$id = urlencode($current_photo["id"]);
	$photo = Photo::find_by_id($id);

	if ($photo && $photo->destroy()) {
		$session->message("The photo {$photo->filename} was deleted.");
		redirect_to("edit_page.php?page={$current_photo["page_id"]}");
	} else {
		$session->message("The photo could not be deleted.");
		redirect_to("edit_page.php?page={$current_photo["page_id"]}");
	}
	?>
	<?php 
		if (isset($db)) { 
			$db->close_connection(); 
		} 
	?>
		

