<?php
require_once(LIB_PATH.DS.'database.php');

class Menu extends DatabaseObject {
	protected static $table_name = "menu";
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
		$sql  = "SELECT * FROM ".self::$table_name;
		return self::find_by_sql($sql);
	}
	
	public static function find_all_order_by_m_order() {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " ORDER BY position";
		return self::find_by_sql($sql);
	}
	
	public static function find_by_id($id=0) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE id = {$id} LIMIT 1";
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
	
	public function order() {
		global $db;
		$sql  = "SELECT FROM ".self::$table_name;
		$sql .= " WHERE m_order=1";
		$sql .= " LIMIT 1";
		$db->query($sql);
		return ($db->affected_rows() == 1) ? true : false;
	}
	
	public static function find_admin_order_by_postion(){
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE admin=1";
		$sql .= " ORDER BY position";
		return self::find_by_sql($sql);
	}
}




?>
