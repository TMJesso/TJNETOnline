<?php
require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'] . '../../includes/initialize.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'] . '../../includes/validation_functions.php'; 

// if (!$session->is_logged_in()) { redirect_to("login.php"); }

$breadcrum = null;
$db->find_selected_page();
$id = null;

if (!$current_subject) {
	// page ID was missing or invalid or
	// page couldn't be found in database
	redirect_to("manage_content.php?subject={$current_subject["id"]}");
} else {
	$id = $current_subject["id"];
}

if (isset($_POST['submit'])) {
	// Process the form
	
	$subject_id = urlencode($id);
	$menu_name = $db->prevent_injection($_POST["menu_name"]);
	$loc = $db->prevent_injection($_POST["loc"]);
	$position = (int) $_POST["position"];
	$level = (int) $_POST["level"];
	$content = htmlentities($_POST["content"], ENT_QUOTES);
	
	if (isset($_POST["visible"])) {
		$visible = (int) $_POST["visible"];
	} else {
		$visible = 0;
	}
	
	if (!isset($_POST["name"])) {
		$name = null;
	} else {
		$name = $db->prevent_injection($_POST["name"]);
	}
	
	// validations
	$required_fields = array("subject_id", "menu_name", "loc", "position", "level", "visible");
	validate_presences($required_fields);
	
	$fields_with_max_lengths = array("menu_name" => 30, "loc" => 30 );
	validate_max_lengths($fields_with_max_lengths);
	
	if (!empty($errors)) {
		$session->errors($errors);
		redirect_to("new_page.php?subject={$current_subject["id"]}");
	}
	
	// 2. Perform database query
	$newPage = new Page();
	$newPage->id = null;
	$newPage->subject_id = $subject_id;
	$newPage->name = $name;
	$newPage->menu_name = $menu_name;
	$newPage->loc = $loc;
	$newPage->position = $position;
	$newPage->level = $level;
	$newPage->visible = $visible;
	$newPage->content = $content;
	
	if ($newPage->save() && $db->affected_rows() == 1) {
		// Success
		$session->message("Page created.");
		redirect_to("manage_content.php?subject={$current_subject["id"]}");
	} else {
		// Failure
		$session->message("Page creation failed");
		redirect_to("new_page.php?subject={$current_subject["id"]}");
	}
	
} else {
	// redisplay the form again
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
			<?php echo output_errors($session->errors);?>
			<h2 style="text-align: center;">Create Page for <?php echo $current_subject["menu_name"]; ?></h2>
			<form action="new_page.php?subject=<?php echo $current_subject["id"]; ?>" method="post">
				<fieldset>
					<legend title="Page name will be the menu name for this page">Page Name</legend>
					<input type="text" name="menu_name" size="50" value="" placeholder="30 characters max" title="Enter a page name" />
				</fieldset>
				<input type="hidden" name="subject_id" value="<?php echo $id; ?>" />
				<fieldset>
					<legend title="Title of page or name of institution">Title or Institution</legend>
					<input type="text" name="name" size="50" value="" placeholder="30 characters max" title="Enter page title or name of the institution" />
				</fieldset>
				<fieldset>
					<legend title="Where this page will go">Link</legend>
					<input type="text" name="loc" value="" size="50" placeholder="30 characters max" title="Example: test.php" />
				</fieldset>
				<p>Position:
					<select name="position" title="Unused position is automatically selected or currently selected value">
					<?php 
						$page_count = Page::count_by_page($current_subject["id"]);
						for ($count=1; $count <= ($page_count + 1); $count++) {
							if ($count == ($page_count+1)) {
								echo "<option value=\"{$count}\" selected>{$count}</option>";
							} else {
								echo "<option value=\"{$count}\">{$count}</option>";
							}
						}
					?>
					</select>
				</p>

				<p>Level:
					<select name="level" title="Level determines whether there is a space between last page and this page">
						<option value="1" selected>No Space</option>
						<option value="2">One Space</option>
						<?php //<option value="3">Double Space</option> ?>
					</select>
				</p>
				<p>Visible:
					<input type="radio" name="visible" value="0" title="Page will not be visible to public" /> No &nbsp;
					<input type="radio" name="visible" value="1" title="Page will be visible to public" /> Yes
				</p>
				<p>Content<br/>
				<textarea rows="12" cols="80" name="content" placeholder="Enter content of the page"></textarea>
				</p>
				<input type="submit" name="submit" class="button" value="Create Page" title="Click to validate and/or create page" />
			</form>
			<br/>
			<a href="manage_content.php?subject=<?php echo $current_subject["id"]; ?>" class="btnLooker" title="Return to Manage Subject <?php echo $current_subject["menu_name"]; ?>">Cancel</a>
		
<?php include_layout_template('admin_footer.php'); ?>
