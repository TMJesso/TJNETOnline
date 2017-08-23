<?php
// grant all privileges on milage.* to 'afr10w'@'localhost' identified by 'p6iFQRnx8B976CQv';

// https://wiki.filezilla-project.org/Network_Configuration
$host = $_SERVER["SERVER_NAME"];
$online = "theraljessop.net";
$prefix = "www.";

// database constants
if ($host === $online || $host === $prefix.$online) { 
	defined('DB_SERVER')	? null : define("DB_SERVER", 'theraljessopnet.ipagemysql.com');
	defined('DB_USER')		? null : define("DB_USER", 'tjphp2015now');
	defined('DB_PASS')		? null : define("DB_PASS", 'g6X9MG6ykH6C8ZHk');
	defined('DB_NAME')		? null : define("DB_NAME", 'tjnet_16');
	defined('DB_PORT') 		? null : define("DB_PORT", 3306);
	defined('DB_SOCKET') 	? null : define("DB_SOCKET", null);
} else {
	defined('DB_SERVER')	? null : define("DB_SERVER", 'localhost');
	defined('DB_USER')		? null : define("DB_USER", 'tjnethostc15');
	defined('DB_PASS')		? null : define("DB_PASS", '9Eo(KU!a0)58MT5X');
	defined('DB_NAME')		? null : define("DB_NAME", 'tjnet');
	defined('DB_PORT') 		? null : define("DB_PORT", 3306);
	defined('DB_SOCKET') 	? null : define("DB_SOCKET", null);
}
?>
<?php
// copied 4/1/2017
// $link = mysql_connect('theraljessopnet.ipagemysql.com', 'tjphp2015now', '*password*');
// if (!$link) {
// 	die('Could not connect: ' . mysql_error());
// }
// echo 'Connected successfully';
// mysql_select_db(tjnet_16);
?> 
<?php 
/*
 * 
 * 
 *  
$link = mysql_connect('theraljessopnet.ipagemysql.com', 'tjphp2015now', 'g6X9MG6ykH6C8ZHk'); 
if (!$link) { 
    die('Could not connect: ' . mysql_error()); 
} 
echo 'Connected successfully'; 
mysql_select_db(tjnet_16); 
-------------------------------------------------------------------
$link = mysql_connect('theraljessopnet.ipagemysql.com', 'tjphp2015now', '*password*');
if (!$link) {
	die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully';
mysql_select_db(tjnet_16);
*/

/*
defined('DB_PORT') 		? null : define("DB_PORT", 3306);
defined('DB_SOCKET') 	? null : define("DB_SOCKET", "");



/* 
 * 
 <?php 
$link = mysql_connect('theraljessopnet.ipagemysql.com', 'tjphp2015now', '*password*'); 
if (!$link) { 
    die('Could not connect: ' . mysql_error()); 
} 
echo 'Connected successfully'; 
mysql_select_db(tjnet_16); 
?> */

/*




CREATE TABLE IF NOT EXISTS `tjnet`.`menu` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`loc` VARCHAR(100) NOT NULL,
		`descript` VARCHAR(250) NOT NULL,
		`order` INT(3) NOT NULL,
		`level` INT(3) NOT NULL,
		PRIMARY KEY (`id`),
		INDEX `menu` (`order` ASC, `level` ASC))
		ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1

CREATE TABLE IF NOT EXISTS `tjnet`.`users` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`username` VARCHAR(50) NOT NULL,
		`password` VARCHAR(40) NOT NULL,
		`first_name` VARCHAR(30) NOT NULL,
		`last_name` VARCHAR(30) NOT NULL,
		PRIMARY KEY (`id`))
		ENGINE = InnoDB
		AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1






$host="localhost";
$port=3306;
$socket="";
$user="root";
$password="";
$dbname="";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

//$con->close();
*/

?>
 <?php /*
$link = mysql_connect('theraljessopnet.ipagemysql.com', 'tjphp2015now', 'g6X9MG6ykH6C8ZHk'); 
if (!$link) { 
    die('Could not connect: ' . mysql_error()); 
} 
echo 'Connected successfully'; 
mysql_select_db(tjnet_16); 
*/
?> 
