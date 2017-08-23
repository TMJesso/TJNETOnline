<?php

function strip_zeros_from_date( $marked_string = "") {
	// first remove the marked zeros
	$no_zeros = str_replace('*-', '', $marked_string);
	// then remove any remaining marks
	$cleaned_string = str_replace('*', '', $no_zeros);
	return $cleaned_string;
}

function redirect_to($location = null) {
	if ($location != null) {
		header("Location: {$location}");
		exit;
	}
}

function output_message($message="") {
	if (!empty($message)) {
		return "<div class=\"message\">{$message}</div>";
	} else {
		return "";
	}
}

function output_errors($errors="") {
	if (!empty($errors)) {
		return "<div class=\"errors\">{$errors}</div>";
	} else {
		return "";
	}
}


function __autoload($class_name) {
	$class_name = strtolower($class_name);
	switch ($class_name) {
		case "user":
			$path = LIB_PATH.DS."{$class_name}.php";
			if (file_exists($path)) {
				require_once($path);
			} else {
				die("The file {$class_name}.php cound not be found.");
			}
			break;
	}
}

function include_layout_template($template="") {
	//
	//

	include(PUBLIC_PATH.'layouts'.DS."{$template}");
}

function log_action($action, $message="") {
	$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
	$new = file_exists($logfile) ? false : true;
	if ($handle = fopen($logfile, 'a')) { // append
		$timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
		$content = "{$timestamp} | {$action}: {$message}\n";
		fwrite($handle, $content);
		fclose($handle);
		if ($new) { chmod($logfile, 0755); }
	} else {
		echo "Could not open log file for writing.";
	}
}

function datetime_to_text($datetime="") {
	$unixdatetime = strtotime($datetime);
	return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
}

function find_all_subjects() {
	global $connection;
	$sql  = "SELECT * ";
	$sql .= "FROM subjects ";
	// $sql .= "WHERE visible=1 ";
	$sql .= "ORDER BY position";
	$result_set = Subject::menu_find_by_sql($sql);
	return $result_set;
}

function find_pages_for_subject($subject_id) {
	global $connection;
	//$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);
	
	$sql = "SELECT * FROM pages WHERE subject_id = {$subject_id} ORDER BY position";
	$result_set = Subject::menu_find_by_sql($sql);
	return $result_set;
}

// navigation takes 2 arguments
// - the current subject array or null
// - the current page array or null
function navigation($subject_array, $page_array) {
	global $breadcrum;
	
	$output  = "<ul class=\"subjects\">";
	$menus = find_all_subjects();
	$breadcrum = "<a href=\"index.php\" class=\"btnLooker\">Admin</a> &NestedLessLess; Content";
	foreach ($menus as $menu):
		if ($subject_array && $menu->id == $subject_array["id"]) {
			$breadcrum = "<a href=\"index.php\" class=\"btnLooker\">Admin</a> &NestedLessLess; <a href=\"manage_content.php\" class=\"btnLooker\">Content</a> &NestedLessLess; {$menu->menu_name}";
		}
		if ($menu->level == 2) {
			$output .= "<br/>";
		}
		$output .= "<li";
		if ($subject_array && $menu->id == $subject_array["id"]) {
			$output .= " class=\"selected\" style=\"color: red;\"";
		}
		$output .= ">"; 
		
		$output .= "<a href=\"manage_content.php?subject=";
		$output .= urlencode($menu->id);
		$output .= "\"";
		if ($subject_array && $menu->id == $subject_array["id"]) { 
			$output .= "\" style=\"color: red; background-color: yellow;\""; 
		}
		$output .= ">";
		if ($subject_array && $menu->id == $subject_array["id"]) {
			$output .= "&NestedLessLess; {$menu->menu_name} &NestedGreaterGreater;"; 
		} else {
			$output .= $menu->menu_name;
		}
		$output .= "</a></li>";
		$pages = find_pages_for_subject($menu->id);
		$output .= "<ul class=\"pages\">";
		foreach ($pages as $page):
			if ($page_array && $page->id == $page_array["id"]) {
				$breadcrum = "<a href=\"index.php\" class=\"btnLooker\">Admin</a> &NestedLessLess; <a href=\"manage_content.php\" class=\"btnLooker\">Content</a> &NestedLessLess; <a href=\"{$menu->loc}\" class=\"btnLooker\">{$menu->menu_name}</a> &NestedLessLess; {$page->menu_name}";
			}
			if ($page->level == 2) {
				$output .= "<br/>";
			}
			$output .= "<li";
			if ($page_array && $page->id == $page_array["id"]) {
				$output .= " class=\"selected\"";
			}
			$output .= ">";
			$output .= "<a href=\"manage_content.php?page=";
			$output .= urldecode($page->id);
			$output .= "\"";
			if ($page_array && $page->id == $page_array["id"]) {
				$output .= " style=\"color: red; background-color: yellow;\""; 
			}
			$output .= ">";
			if ($page_array && $page->id == $page_array["id"]) {
				$output .= "&NestedLessLess; {$page->menu_name} &NestedGreaterGreater;"; 
			} else {
				$output .= "{$page->menu_name}"; 
			} 
			$output .= "</a></li>";
		endforeach;
		$output .= "</ul>";
	endforeach;
	$output .= "</ul>";
	return $output;
}

function password_encript($username, $password) {
	return password_hash($username.$password, PASSWORD_DEFAULT);
}

function password_check($username, $password, $hash) {
	return password_verify($username.$password, $hash);
}
?>
