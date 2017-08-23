<?php
require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'] . '../../includes/initialize.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'] . '../../includes/validation_functions.php';

// if (!$session->is_logged_in()) { redirect_to("login.php"); }

$breadcrum = null;
$db->find_selected_page();
if (!$current_subject) {
	// subject ID was missing or invalid or
	// subject couldn't be found in database
	redirect_to("manage_content.php");
}

if (isset($_POST['submit'])) {
	// Process the form
	
	// validations
	$required_fields = array("menu_name", "loc", "position", "level", "visible", "admin");
	validate_presences($required_fields);
	
	$fields_with_max_lengths = array("menu_name" => 30, "loc" =>30 );
	validate_max_lengths($fields_with_max_lengths);
	
	if (empty($errors)) {

		// Perform Update
		$id = $current_subject["id"];
		$menu_name = $db->prevent_injection($_POST["menu_name"]);
		$loc = $db->prevent_injection($_POST["loc"]);
		$position = (int) $_POST["position"];
		$level = (int) $_POST["level"];
		
		if (isset($_POST["visible"]) && isset($_POST["admin"])) {
			$visible = (int) $_POST["visible"];
			$admin = (int) $_POST["admin"];
		} else {
			$visible = null;
			$admin = null;
		}
		
		// 2. Perform database query
		$subject = Subject::find_by_id($id);
		$subject->menu_name = $menu_name;
		$subject->loc = $loc;
		$subject->position = $position;
		$subject->level = $level;
		$subject->visible = $visible;
		$subject->admin = $admin;
		$result = $subject->save();
	
		if ($result && $db->affected_rows() == 1) {
			// Success
			$session->message("Subject updated.");
			redirect_to("manage_content.php");
		} else {
			// Failure
			$session->message("Subject update failed");
			redirect_to("new_subject.php");
		}
	}
	
} else {
	// Display the form again
}
?>

<?php include_layout_template('admin_header.php'); ?>
		<div id="menu">
			<nav title="Administration Site Menu">
				<?php echo navigation($current_subject, $current_page); ?>
			</nav>
		</div>
		<div id="mainbody">
			<header><h1 title="Administration">Admin</h1><h2><script type="text/javascript">/*full*/var genheading="242f762qC7ZLWCM74rW3504NqZq892CVG74Pg3J36247u693Fe4F7aQPdip360626W6GPbzj43b8aKC36849W8BX7ZPt7Y26qEH3582W3b67ce8FW3sv9Pp36484s34A9ZTno6HDF8x3192cLgw8nw29Hn632jE3462pLJ76G34gab97CpW3276W3b67ce8FW3sv9Pp319268PRM87WLZmGd82f3444D2bhQ4oQR9r696mg3606D2bhQ4oQR9r696mg3690W3b67ce8FW3sv9Pp3690zUL38p8dbt39nX3K36669W8BX7ZPt7Y26qEH3672";document.write(getPass(genheading));</script></h2>
			<span id="smalltxt" class="cell"><script type="text/javascript">var mai1="228f762qC7ZLWCM74rW3296W3b67ce8FW3sv9Pp34049R7Y8s3yRZx47LJe34609v2ib2s6f38RNsUq34609642wzn2DPVQ2huy3380";var mai2="25626W6GPbzj43b8aKC3576D2bhQ4oQR9r696mg3888cLgw8nw29Hn632jE3912xd4a783kVB4bA3Md3928D2bhQ4oQR9r696mg3760";var mai3="2637u693Fe4F7aQPdip3630cLgw8nw29Hn632jE387373vi689xjN9PvfAR41026876WvUNpk8Y8TF4V3981cLgw8nw29Hn632jE41035";var hst="2429W8BX7ZPt7Y26qEH3654NqZq892CVG74Pg3J35829642wzn2DPVQ2huy36307u693Fe4F7aQPdip3648D2bhQ4oQR9r696mg32767u693Fe4F7aQPdip3594876WvUNpk8Y8TF4V36669642wzn2DPVQ2huy3654";document.write('<a href="mailto: '+getPass(mai1)+getPass(mai2)+getPass(mai3)+'@'+getPass(hst)+'" style="color:black;text-decoration:none;background-color:transparent;" class="cell" title="My Email">'+getPass(mai1)+getPass(mai2)+getPass(mai3)+'@'+getPass(hst)+'</a>');</script></span></header>
			<?php if ($breadcrum) { ?>
				<hr/>
				<div><?php echo $breadcrum; ?></div><hr/>
			<?php }?>
			<?php echo output_message($session->message); ?>
			<?php echo output_errors($session->errors); ?>
			<h2 style="text-align: center;">Edit Subject: <?php echo $current_subject["menu_name"]; ?></h2>
			<form action="edit_subject.php?subject=<?php echo $current_subject["id"]; ?>" method="post">
				<fieldset>
					<legend>Subject Name</legend>
					<input type="text" name="menu_name" size="50" value="<?php echo $current_subject["menu_name"]; ?>" placeholder="30 characters max" title="Enter a subject name" />
				</fieldset>
				<fieldset>
					<legend>Link</legend>
					<input type="text" name="loc" size="50" value="<?php echo $current_subject["loc"]; ?>" placeholder="30 characters max" title="Example: test.php" />
				</fieldset>
				<p>Position:
					<select name="position" title="Current position is automatically selected">
					<?php 
						//$subject_set = find_all_subjects();
						$subject_count = Subject::menu_count_all();
						for ($count=1; $count <= $subject_count + 1; $count++) {
							//if ($count == ($subject_count+1)) {
								echo "<option value=\"{$count}\"";
								if ($count == $current_subject["position"]) {
									echo " selected";
								}
								echo ">{$count}</option>";
							//} else {
								//echo "<option value=\"{$count}\">{$count}</option>";
							//}
						}
					?>
					</select>
				</p>
				<p>Level:
					<select name="level" title="Level determines whether there is a space between last subject and this subject">
						<option value="1" <?php if ($current_subject["level"] == 1) { echo " selected"; } ?>>No Space</option>
						<option value="2" <?php if ($current_subject["level"] == 2) { echo " selected"; } ?>>One Space</option>
						<?php //<option value="3">Double Space</option> ?>
					</select>
				</p>
				<p>Visible:
					<input type="radio" name="visible" value="0" title="Subject will not be visible to public" <?php if ($current_subject["visible"] == 0) { echo " checked"; } ?> /> No &nbsp;
					<input type="radio" name="visible" value="1" title="Subject will be visible to public" <?php if ($current_subject["visible"] == 1) { echo " checked"; } ?> /> Yes
				</p>
				<p>Admin:
					<input type="radio" name="admin" value="0" title="Subject is not for admin only" <?php if ($current_subject["admin"] == 0) { echo " checked"; } ?> /> No &nbsp;
					<input type="radio" name="admin" value="1" title="Subject is for admin only" <?php if ($current_subject["admin"] == 1) { echo " checked"; } ?> /> Yes
				</p>
				<input type="submit" name="submit" value="Edit Subject" title="Click to validate and/or save subject" />
			</form>
			<br/>
			<a href="manage_content.php" title="Return to Manage Subject">Cancel</a>
			&nbsp;
			&nbsp;
			<a href="delete_subject.php?subject=<?php echo $current_subject["id"]; ?>" onclick="return confirm('Are you sure?');">Delete subject</a>
		
<?php include_layout_template('admin_footer.php'); ?>
