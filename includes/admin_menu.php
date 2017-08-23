<?php
require_once(LIB_PATH.DS.'database.php');

class Admin_menu extends DatabaseObject {
	protected static $table_name = "subjects";
	protected static $db_fields = array('id', 'menu_name', 'loc', 'position', 'level', 'visible', 'admin');
	
	public $id;
	public $menu_name;
	public $loc;
	public $position;
	public $level;
	public $visible;
	public $admin;
	
	
	// Common Database Methods
	public static function find_all() {
		$sql  = "SELECT * FROM " . self::$table_name;
		return self::find_by_sql($sql);
	}
	
	public static function find_by_id($id=0) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE id={$id}";
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
	
}




?>
