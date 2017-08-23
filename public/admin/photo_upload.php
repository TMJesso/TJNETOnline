
<?php require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'] . '../../includes/initialize.php';
// if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php 
	$db->find_selected_page();
	if (!isset($current_page)) {
		redirect_to("manage_content.php");
	}

	$max_file_size = 1048576;	// expressed in bytes
								// 		1024 =   1 KB
								// 	   10240 =  10 KB
								// 	  102400 = 100 KB
								// 	 1048576 =   1 MB
								//  10485760 =  10 MB
	if (isset($_POST['submit'])) {
		$photo = new Photo();
		$photo->caption = $db->prevent_injection($_POST['caption']);
		$photo->page_id = urlencode($current_page["id"]);
		$photo->position = $_POST["position"];
		if ($photo->attach_file($_FILES['file_upload'])) {
			if ($photo->save() && $db->affected_rows() == 1) {
				// success
				$session->message("Photograph uploaded successfully.");
				redirect_to("edit_page.php?page={$current_page["id"]}");
			} else {
				// failure
				$session->errors($photo->errors);
				redirect_to("manage_content.php?page={$current_page["id"]}");
			}
		} else {
			$session->errors($photo->errors);
			redirect_to("manage_content.php?page={$current_page["id"]}");
		}
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

<h2 style="text-align: center;">Photo Upload for <?php echo $current_page["menu_name"]; ?></h2>

<?php echo output_message($message); ?>

<form action="photo_upload.php?page=<?php echo $current_page["id"]; ?>" enctype="multipart/form-data" method="POST">
	<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>" />
	<p><input type="file" size="50" name="file_upload" /></p>
	<p>
		<?php $photo_count = Photo::count_by_page($current_page["id"]); ?>
		<select name="position">
		<?php
			for ($count = 1; $count <= ($photo_count + 1); $count++) {
				echo "<option value=\"{$count}\"";
				if ($count == 1) {
					echo " selected";
				} 
				echo ">{$count}</option>";
			} 
		?>
		</select>
	<p>Caption: <input type="text" size="50" name="caption" value="" /></p>
	<input type="submit" name="submit" value="Upload" />
</form>


<?php include_layout_template('admin_footer.php'); ?>

