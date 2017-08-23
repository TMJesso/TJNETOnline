<?php
require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'] . '../../includes/initialize.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'] . '../../includes/validation_functions.php';



// if (!$session->is_logged_in()) { redirect_to("login.php"); }

$breadcrum = null;
$db->find_selected_page();

if (!$current_page) {
	// page ID was missing or invalid or
	// page couldn't be found in database
	redirect_to("manage_content.php");
}


if (isset($_POST['submit'])) {
	// Process the form
	
	// validations
	$required_fields = array("subject_id", "menu_name", "loc", "position", "level", "visible");
	validate_presences($required_fields);
	
	$fields_with_max_lengths = array("menu_name" => 30, "loc" => 30 );
	validate_max_lengths($fields_with_max_lengths);
	
	if (empty($errors)) {

		// Perform Update
		$id = $current_page["id"];
		$subject_id = $current_page["subject_id"];
		$name = " ";
		$menu_name = $db->prevent_injection($_POST["menu_name"]);
		$loc = $db->prevent_injection($_POST["loc"]);
		$position = (int) $_POST["position"];
		$level = (int) $_POST["level"];
		$content = " ";
		
		if (isset($_POST["content"])) {
			$content = htmlentities($_POST["content"], ENT_QUOTES);
		}
		
		if (isset($_POST["visible"])) {
			$visible = (int) $_POST["visible"];
		} else {
			$visible = 0;
		}
		
		if (isset($_POST["name"])) {
			$name = $db->prevent_injection($_POST["name"]);
		}
		
		// 2. Perform database query
		$page = Page::find_by_id($id);
		$page->content = $content;
		$page->level = $level;
		$page->loc = $loc;
		$page->menu_name = $menu_name;
		$page->name = $name;
		$page->position = $position;
		$page->subject_id = $subject_id;
		$page->visible = $visible;
		$result = $page->save();
		
		if ($result && $db->affected_rows() == 1) {
			// Success
			$session->message("Page updated.");
			redirect_to("manage_content.php?page={$id}");
		} else {
			// Failure
			$session->message("Page update failed.");
			redirect_to("edit_page.php?page={$id}");
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
				<div class="breadcrum"><?php echo $breadcrum; ?></div><hr/>
			<?php }?>
			<?php echo output_message($session->message); ?>
			<?php echo output_errors($session->errors); ?>
			<h2 class="info">Edit Page: <?php echo $current_page["menu_name"]; ?></h2>
			<form action="edit_page.php?page=<?php echo $current_page["id"]; ?>" method="post">
				<fieldset>
					<legend>Page Name</legend>
					<input type="text" name="menu_name" size="50" value="<?php echo $current_page["menu_name"]; ?>" placeholder="30 characters max" title="Enter a page name" />
				</fieldset>
				<input type="hidden" name="subject_id" value="<?php echo $current_page["subject_id"]; ?>" />
				<fieldset>
					<legend title="Title of page or name of institution">Institution</legend>
					<input type="text" name="name" size="50" value="<?php echo $current_page["name"]; ?>" placeholder="30 characters max" />
				</fieldset>
				<fieldset>
					<legend>Link</legend>
					<input type="text" name="loc" size="50" value="<?php echo $current_page["loc"]; ?>" placeholder="30 characters max" title="Example: test.php" />
				</fieldset>
				<p>Position:
					<select name="position" title="Current position is automatically selected">
					<?php 
						//$subject_set = find_all_subjects();
						$page_count = Page::count_by_page($current_page["subject_id"]);
						for ($count=1; $count <= $page_count; $count++) {
							//if ($count == ($subject_count+1)) {
								echo "<option value=\"{$count}\"";
								if ($count == $current_page["position"]) {
									echo " selected";
								}
								echo ">{$count}</option>";
						}
					?>
					</select>
				</p>
				<p>Level:
					<select name="level" title="Level determines whether there is a space between last subject and this subject">
						<option value="1" <?php if ($current_page["level"] == 1) { echo " selected"; } ?>>No Space</option>
						<option value="2" <?php if ($current_page["level"] == 2) { echo " selected"; } ?>>One Space</option>
						<?php //<option value="3">Double Space</option> ?>
					</select>
				</p>
				<p>Visible:
					<input type="radio" name="visible" value="0" title="Subject will not be visible to public" <?php if ($current_page["visible"] == 0) { echo " checked"; } ?> /> No &nbsp;
					<input type="radio" name="visible" value="1" title="Subject will be visible to public" <?php if ($current_page["visible"] == 1) { echo " checked"; } ?> /> Yes
				</p>
				<p>Content<br/>
				<textarea name="content" rows="12" cols="80"><?php echo $current_page["content"]; ?></textarea>
				</p><br/>
				<input type="submit" name="submit" class="button" value="Update Page" title="Click to validate and/or save subject" />
			</form>
			<br/>
			<a href="manage_content.php?page=<?php echo $current_page["id"]; ?>" class="btnLooker" title="Return to Manage Subject">Cancel</a>
			&nbsp;
			&nbsp;
			<a href="photo_upload.php?page=<?php echo $current_page["id"]; ?>" class="btnLooker" title="Add photo to page">Add Photo</a>
			&nbsp;
			&nbsp;
			<a href="delete_page.php?page=<?php echo $current_page["id"]; ?>" class="btnLooker" onclick="return confirm('Are you sure?');">Delete Page</a>
			<br/>
			<?php Photo::find_page_photo($current_page["id"]); ?>
			<?php if ($current_photo) { ?>
			<h2 class="info">Photos accompany this page!</h2><br/><br/>
			
				<?php 
					$sql = "SELECT * FROM photo where page_id={$current_page["id"]} ORDER BY position";
					$photo_set = Photo::find_by_sql($sql);
					if ($photo_set) {
				?>
					<?php foreach ($photo_set as $pho) :?>
						<fieldset>
							<legend><?php echo $pho->caption; ?></legend>
						<div><img src="../media/<?php echo $pho->filename; ?>" width="175" height="200" title="<?php echo $pho->caption; ?>"><br/>
						<br/>
						</div>
						<div>
						Filename: <input type="text" size="30" value="<?php echo $pho->filename; ?>" readonly /><br/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Type: <input type="text" size="30" value="<?php echo $pho->type; ?>" readonly /><br/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;size: <input type="text" size="30" value="<?php echo $pho->input_size_as_text($pho->size); ?>" readonly /><br/>
						&nbsp;&nbsp;Position: <input type="text" size="30" value="<?php echo $pho->position; ?>" readonly /><br/>
						<br/>
						</div>
						<p>
						<a href="edit_photo.php?photo=<?php echo $pho->id; ?>" class="btnLooker">Edit Caption and Position</a>&nbsp;&nbsp;&nbsp;
						<a href="delete_photo.php?photo=<?php echo $pho->id; ?>" class="btnLooker" onclick="return confirm('Are you sure?');">Delete Photo</a>
						</p>
						</fieldset>
						<br/><br/><br/>
					<?php endforeach;?>
				<?php }?>
				<br/>
			<?php }?>
			
<?php include_layout_template('admin_footer.php'); ?>
