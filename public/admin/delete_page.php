<?php require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'] . "../../includes/initialize.php"; ?>

<?php 
	$db->find_selected_page($_GET["page"]);
	if (!$current_page) {
		// subject ID was missing or invalid or
		// subject couldn't be found in database
		redirect_to("manage_content.php");
	}
		
	$id = $current_page["id"];
	
	$photo_set = $db->does_page_have_photos($id);
	if ($db->num_rows($pages_set) > 0) {
		$_SESSION["message"] = "Cannot delete a page with photos.";
		redirect_to("manage_content.php?page={$id}");
	}
	
	$sql  = "DELETE FROM pages ";
	$sql .= "WHERE id = {$id} ";
	$sql .= "LIMIT 1";
	$result = $db->query($sql);
	
	if ($result && $db->affected_rows() == 1) {
		// Success
		// Cannot send deleted subject id
		$_SESSION["message"] = "Page deleted.";
		redirect_to("manage_content.php");
	} else {
		// Failure
		$_SESSION["message"] = "Page deletion failed.";
		redirect_to("manage_content.php?page={$id}");
	}
	
	
?>
