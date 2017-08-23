<?php
// REDESIGN
$host = $_SERVER["SERVER_NAME"];

if ($host === 'theraljessop.net' || $host === 'www.theraljessop.net') {
	$online = true;
} else {
	$online = false;
}

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

if ($online) {
	defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER["DOCUMENT_ROOT"] . DS . "tjnet" . DS);
	defined('SITE_HTTP') 	? null : define('SITE_HTTP', 	DS . 'tjnet' . DS . 'public' . DS);
} else {
	defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER["DOCUMENT_ROOT"] . DS . "tjnetonline" . DS);
	defined('SITE_HTTP') 	? null : define('SITE_HTTP', 	DS . 'tjnetonline' . DS . 'public' . DS);
}

// define the core paths
// define them as absolute paths to make sure that require_once
// works as expected

defined('DS') 			? null : define('DS', 			DIRECTORY_SEPARATOR);

defined('LIB_PATH') 	? null : define('LIB_PATH', 	SITE_ROOT.DS.'includes');
defined('ADMIN_PATH') 	? null : define('ADMIN_PATH', 	SITE_HTTP.'admin');
defined('PUBLIC_PATH') 	? null : define('PUBLIC_PATH', 	SITE_ROOT.'public' . DS);
defined('CSS_PATH') 	? null : define('CSS_PATH', 	SITE_HTTP . 'stylesheets' . DS);
defined('JS_PATH') 		? null : define('JS_PATH', 		SITE_HTTP . 'javascripts' . DS);

defined('LIB_BACK') 	? null : define('LIB_BACK', 	SITE_ROOT . DS . 'backup');

// Load config file first
require_once LIB_PATH.DS.'config.php';
// load basic functions next so that everything after can them
require_once LIB_PATH.DS.'functions.php';

// load core objects
require_once LIB_PATH.DS.'session.php';
require_once LIB_PATH.DS.'database_object.php';
require_once LIB_PATH.DS.'database.php';
// require_once(LIB_PATH.DS."phpMailer".DS."class.phpmailer.php");
// require_once(LIB_PATH.DS."phpMailer".DS."class.smtp.php");
// require_once(LIB_PATH.DS."phpMailer".DS."language".DS."phpmailer.lang-en.php");

// Load database-related classes
require_once LIB_PATH.DS.'user.php';
require_once LIB_PATH.DS.'menu.php';
require_once LIB_PATH.DS.'pages.php';
require_once LIB_PATH.DS.'subjects.php';
require_once LIB_PATH.DS.'photo.php';

?>