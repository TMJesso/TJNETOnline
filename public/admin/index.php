<?php //require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/initialize.php';
$test = explode("/", dirname($_SERVER['REQUEST_URI']));
$num = -1;
for ($x = 0; $x < count($test); $x++) {
 	if ($test[$x] === "tjnet") {
  		$num = $x;
 		continue;
 	} else {
 		continue;
 	}
	echo "{$x}. " . $test[$x] . "<br/>";
}
if ($num > 0) {
	echo $num . ". " . $_SERVER['DOCUMENT_ROOT'] . '/tjnet/includes/initialize.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/tjnet/includes/initialize.php';
} else {
	echo $num . ". " . $_SERVER['DOCUMENT_ROOT'] . '/includes/initialize.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/initialize.php';
}

$menus = Menu::find_admin_order_by_postion();

//$sql = "SELECT * FROM subjects WHERE admin=0 ORDER BY position";

//$subjects = Subject::menu_find_by_sql($sql);
$breadcrum = "Admin";
?>

<?php include_layout_template('admin_header.php'); ?>
		<div id="menu">
			<nav title="Administration Site Menu">
				<?php foreach ($menus as $menu): ?>
					<?php if ($menu->level == 2) { ?>
						<br/>
					<?php } ?>
					<a href="<?php echo $menu->loc; ?>" style="font-size:.75em;"><?php echo $menu->menu_name; ?></a>
				<?php endforeach; ?>
			</nav>
		</div>
		<div id="mainbody">
			<header><h1 title="Administration">Admin</h1><h2><script type="text/javascript">/*full*/var genheading="242f762qC7ZLWCM74rW3504NqZq892CVG74Pg3J36247u693Fe4F7aQPdip360626W6GPbzj43b8aKC36849W8BX7ZPt7Y26qEH3582W3b67ce8FW3sv9Pp36484s34A9ZTno6HDF8x3192cLgw8nw29Hn632jE3462pLJ76G34gab97CpW3276W3b67ce8FW3sv9Pp319268PRM87WLZmGd82f3444D2bhQ4oQR9r696mg3606D2bhQ4oQR9r696mg3690W3b67ce8FW3sv9Pp3690zUL38p8dbt39nX3K36669W8BX7ZPt7Y26qEH3672";document.write(getPass(genheading));</script></h2>
			<span id="smalltxt" class="cell"><script type="text/javascript">var mai1="228f762qC7ZLWCM74rW3296W3b67ce8FW3sv9Pp34049R7Y8s3yRZx47LJe34609v2ib2s6f38RNsUq34609642wzn2DPVQ2huy3380";var mai2="25626W6GPbzj43b8aKC3576D2bhQ4oQR9r696mg3888cLgw8nw29Hn632jE3912xd4a783kVB4bA3Md3928D2bhQ4oQR9r696mg3760";var mai3="2637u693Fe4F7aQPdip3630cLgw8nw29Hn632jE387373vi689xjN9PvfAR41026876WvUNpk8Y8TF4V3981cLgw8nw29Hn632jE41035";var hst="2429W8BX7ZPt7Y26qEH3654NqZq892CVG74Pg3J35829642wzn2DPVQ2huy36307u693Fe4F7aQPdip3648D2bhQ4oQR9r696mg32767u693Fe4F7aQPdip3594876WvUNpk8Y8TF4V36669642wzn2DPVQ2huy3654";document.write('<a href="mailto: '+getPass(mai1)+getPass(mai2)+getPass(mai3)+'@'+getPass(hst)+'" style="color:black;text-decoration:none;background-color:transparent;" class="cell" title="My Email">'+getPass(mai1)+getPass(mai2)+getPass(mai3)+'@'+getPass(hst)+'</a>');</script></span></header>
			<hr/>
			<div><?php echo $breadcrum; ?></div><hr/>
			<h2 style="text-align: center;">Main Menu</h2>
			<br/>
			Please make your choice from the menu at the left!
			<br/><br/>
		
		
		
		
<?php include_layout_template('admin_footer.php'); ?>
