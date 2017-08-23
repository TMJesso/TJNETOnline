<?php
defined('DB_SERVER')	? null : define("DB_SERVER", 'localhost');
defined('DB_USER')		? null : define("DB_USER", 'tjnethostc15');
defined('DB_PASS')		? null : define("DB_PASS", '9Eo(KU!a0)58MT5X');
defined('DB_NAME')		? null : define("DB_NAME", 'tjnet');
defined('DB_PORT') 		? null : define("DB_PORT", 3306);
defined('DB_SOCKET') 	? null : define("DB_SOCKET", null);
$base = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT, DB_SOCKET);

$load = false;
if (isset($_POST["submit"])) {
	$load = true;
	$text = $_POST["textarea_info"];
	$entities = htmlentities(htmlentities($text, ENT_QUOTES | ENT_HTML5));
	$safe_text = $entities; // prevent_injection($base, $entities);
	//echo $safe_text;
}

function prevent_injection($host, $string) {
	$my_escape_string = mysqli_real_escape_string($host, $string);
	return $my_escape_string;
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title>Convert HTML to entities</title>
	</head>
	<body>
		<?php if (!$load) { ?>
		<form action="convert_html.php" method="post">
			<textarea cols="60" rows="5" name="textarea_info"  required ></textarea>
			<br/><br/>
			<button type="submit" name="submit">Submit</button>
		</form>
		<?php } else { ?>
			<h5>Converted</h5>
			<textarea rows="5" cols="60"><?php echo $safe_text; ?></textarea>
			<br/><br/>
			<a href="convert_html.php">Reset</a>
		<?php } ?>
	</body>
</html>