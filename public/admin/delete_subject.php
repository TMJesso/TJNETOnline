<?php require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'] . '../../includes/initialize.php'; ?>

<?php 
	$current_subject = $db->find_subject_by_id($_GET["subject"]);
	if (!$current_subject) {
		// subject ID was missing or invalid or
		// subject couldn't be found in database
		redirect_to("manage_content.php");
	}
		
	$id = $current_subject["id"];
	
	$pages_set = $db->does_subject_have_pages($id);
	if ($db->num_rows($pages_set) > 0) {
		$_SESSION["message"] = "Cannot delete a subject with pages.";
		redirect_to("manage_content.php?subject={$id}");
	}
	
	$sql  = "DELETE FROM subjects ";
	$sql .= "WHERE id = {$id} ";
	$sql .= "LIMIT 1";
	$result = $db->query($sql);
	
	if ($result && $db->affected_rows() == 1) {
		// Success
		// Cannot send deleted subject id
		$_SESSION["message"] = "Subject deleted.";
		redirect_to("manage_content.php");
	} else {
		// Failure
		$_SESSION["message"] = "Subject deletion failed.";
		redirect_to("manage_content.php?subject={$id}");
	}
	
	
?>
