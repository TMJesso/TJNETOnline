<?php
// if it's going to need the database, this it's
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class User extends DatabaseObject {
	protected static $table_name="users";
	protected static $db_fields = array('id', 'username', 'password', 'first_name', 'last_name');
	
	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;
	
	public function full_name() {
		if (isset($this->first_name) && isset($this->last_name)) {
			return $this->first_name . " " . $this->last_name;
		} else {
			return "";
		}
	}
	
	public static function authenticate($username="", $password="") {
		global $db;
		$username = $db->prevent_injection($username);
		$password = $db->prevent_injection($password);
		
		$sql  = "SELECT * FROM users ";
		$sql .= "WHERE username = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "LIMIT 1";
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	// Common Database Methods
	public static function find_all() {
		return self::find_by_sql("SELECT * FROM ".self::$table_name);
	}
	
	public static function find_by_id($id=0) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE id = {$id}";
		$sql .= " LIMIT 1";
		$result_array = self::find_by_sql($sql);
		return ($result_array) ? array_shift($result_array) : false;
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