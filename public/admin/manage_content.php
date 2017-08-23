<?php require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'] . '../../includes/initialize.php';
return;
// if (!$session->is_logged_in()) { redirect_to("login.php"); }

$breadcrum = "";
$db->find_selected_page();
?>
<?php include_layout_template('admin_header.php'); ?>
		<div id="menu">
			<nav title="Administration Site Menu">
			<?php echo navigation($current_subject, $current_page); ?>
			<br/>
			<a href="<?php echo PUBLIC_PATH.DS; ?>index.php">Admin</a>
			<br/>
			<a href="new_subject.php">+ Add a subject</a>
			</nav>
		</div>
		<div id="mainbody">
			<header><h1 title="Administration">Admin</h1><h2><script type="text/javascript">/*full*/var genheading="242f762qC7ZLWCM74rW3504NqZq892CVG74Pg3J36247u693Fe4F7aQPdip360626W6GPbzj43b8aKC36849W8BX7ZPt7Y26qEH3582W3b67ce8FW3sv9Pp36484s34A9ZTno6HDF8x3192cLgw8nw29Hn632jE3462pLJ76G34gab97CpW3276W3b67ce8FW3sv9Pp319268PRM87WLZmGd82f3444D2bhQ4oQR9r696mg3606D2bhQ4oQR9r696mg3690W3b67ce8FW3sv9Pp3690zUL38p8dbt39nX3K36669W8BX7ZPt7Y26qEH3672";document.write(getPass(genheading));</script></h2>
			<span id="smalltxt" class="cell"><script type="text/javascript">var mai1="228f762qC7ZLWCM74rW3296W3b67ce8FW3sv9Pp34049R7Y8s3yRZx47LJe34609v2ib2s6f38RNsUq34609642wzn2DPVQ2huy3380";var mai2="25626W6GPbzj43b8aKC3576D2bhQ4oQR9r696mg3888cLgw8nw29Hn632jE3912xd4a783kVB4bA3Md3928D2bhQ4oQR9r696mg3760";var mai3="2637u693Fe4F7aQPdip3630cLgw8nw29Hn632jE387373vi689xjN9PvfAR41026876WvUNpk8Y8TF4V3981cLgw8nw29Hn632jE41035";var hst="2429W8BX7ZPt7Y26qEH3654NqZq892CVG74Pg3J35829642wzn2DPVQ2huy36307u693Fe4F7aQPdip3648D2bhQ4oQR9r696mg32767u693Fe4F7aQPdip3594876WvUNpk8Y8TF4V36669642wzn2DPVQ2huy3654";document.write('<a href="mailto: '+getPass(mai1)+getPass(mai2)+getPass(mai3)+'@'+getPass(hst)+'" style="color:black;text-decoration:none;background-color:transparent;" class="cell" title="My Email">'+getPass(mai1)+getPass(mai2)+getPass(mai3)+'@'+getPass(hst)+'</a>');</script></span></header>
			<hr/>
			<div><?php echo $breadcrum; ?></div><hr/>
			<?php echo output_message($session->message); ?> <br/>
			<?php if ($current_subject) { ?>
				<h2 style="text-align: center;">Manage Subject</h2>
				Subject Name: <input type="text" size="50" value="<?php echo ($current_subject["menu_name"]); ?>" readonly /><br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Link: <input type="text" size="50" value="<?php echo $current_subject["loc"]; ?>" readonly /><br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Position: <input type="text" size="50" value="<?php echo $current_subject["position"]; ?>" readonly /><br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Level: <input type="text" size="50" value="<?php echo $current_subject["level"] == 2 ? 'One Space' : 'No Space'; ?>" readonly /><br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Visible: <input type="text" size="50" value="<?php echo $current_subject["visible"] == 1 ? 'Yes' : 'No'; ?>" readonly /><br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Admin: <input type="text" size="50" value="<?php echo $current_subject["admin"] == 1 ? 'Yes' : 'No'; ?>" readonly /><br/><br/>
				<a href="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" class="btnLooker">Edit Subject</a>
				&nbsp;&nbsp;&nbsp;
				<a href="new_page.php?subject=<?php echo urlencode($current_subject["id"]); ?>" class="btnLooker">Add Page</a>
			<?php } elseif ($current_page) { ?>
				<h2 style="text-align: center;">Manage Page</h2>
				&nbsp;&nbsp;&nbsp;&nbsp;Page Name: <strong><?php echo ($current_page["menu_name"]) ?></strong><br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Institution: <?php echo empty($current_page["name"]) ? "BLANK" : "<strong>".$current_page["name"]."</strong>"; ?><br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Link: <strong><?php echo $current_page["loc"]; ?></strong><br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Position: <strong><?php echo $current_page["position"]; ?></strong><br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Level: <strong><?php echo $current_page["level"] == 2 ? 'One Space' : 'No Space'; ?></strong><br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Visible: <strong><?php echo $current_page["visible"] == 1 ? 'Yes' : 'No'; ?></strong><br/>
				Content<br/><textarea rows="12" cols="80" readonly><?php echo html_entity_decode($current_page["content"], ENT_QUOTES); ?></textarea><br/><br/>
				<br/>
				<?php echo html_entity_decode($current_page["content"], ENT_QUOTES); ?>
				<br/>
				<br/>
				<?php $current_photo = Photo::find_by_id($current_page["id"]); ?>
				<?php if ($current_photo) { ?>
				<h2 class="info">Photos accompany this page!</h2>
				<?php } ?>
				<a href="edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>" class="btnLooker">Edit Page</a>
				
			<?php } else { ?>
				Please select a subject or a page.
			<?php } ?>
		
<?php include_layout_template('admin_footer.php'); ?>
