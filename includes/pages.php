<?php
require_once(LIB_PATH.DS.'database.php');

class Page extends DatabaseObject {
	protected static $table_name = "pages";
	protected static $db_fields = array('id', 'subject_id', 'name', 'menu_name', 'loc', 'position', 'level', 'visible', 'content');
	
	public $id;
	public $subject_id;
	public $name;
	public $menu_name;
	public $loc;
	public $position;
	public $level;
	public $visible;
	public $content;
	
	// Class Database Methods - user defined methods
	
	public static function find_by_subject_id($id=0) {
		if ($id == 0) {
			return false;
		}
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE subject_id = {$id}";
		$sql .= " ORDER BY position";
		return self::find_by_sql($sql);
	}
	
	public static function find_all() {
		$sql  = "SELECT * FROM " . self::$table_name; 
		return self::find_by_sql($sql);
	}
	
	public static function find_by_id($id=0) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE id={$id} ";
		$sql .= " LIMIT 1";
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	public static function count_all() {
		global $db;
		$sql = "SELECT COUNT(*) FROM " . self::$table_name;
		$result_set = $db->query($sql);
		$row = $db->fetch_array($result_set);
		return array_shift($row);
	}

	// Common Database Methods
	
	public function order() {
		global $db;
		$sql  = "SELECT FROM ".self::$table_name;
		$sql .= " ORDER BY position";
		$sql .= " LIMIT 1";
		$db->query($sql);
		return ($db->affected_rows() == 1) ? true : false;
	}
	
	public static function count_by_page($id=0) {
		global $db;
		
		$sql = "SELECT COUNT(*) FROM " . self::$table_name . " WHERE subject_id = {$id}";
		$result_set = $db->query($sql);
		$row = $db->fetch_array($result_set);
		return array_shift($row);	
	}
}

?>
