<?php
require_once(LIB_PATH.DS.'database.php');

class Subject extends DatabaseObject {
	protected static $table_name = "subjects";
	protected static $db_fields = array('id', 'menu_name', 'loc', 'position', 'level', 'visible', 'admin');
	
	public $id;
	public $menu_name;
	public $loc;
	public $position;
	public $level;
	public $visible;
	public $admin;
	
	public static function find_all() {
		return self::find_by_sql("SELECT * FROM ".self::$table_name);
	}
	
	public static function find_by_id($id=0) {
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id={$id} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	public static function find_by_menu_name($menu="") {
		if (empty($menu)) {
			return false;
		}
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE menu_name = '{$menu}'";
		$sql .= " LIMIT 1";
		$row = self::find_by_sql($sql);
		return array_shift($row);
	}
	
	public static function count_all() {
		global $db;
		$sql = "SELECT COUNT(*) FROM " . self::$table_name;
		$result_set = $db->query($sql);
		$row = $db->fetch_array($result_set);
		return array_shift($row);
	}
	
	public function order() {
		global $db;
		$sql  = "SELECT FROM ".self::$table_name;
		$sql .= " WHERE position = 1";
		$sql .= " LIMIT 1";
		$db->query($sql);
		return ($db->affected_rows() == 1) ? true : false;
	}
}

?>
