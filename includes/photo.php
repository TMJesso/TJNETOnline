<?php
// if it's going to need the database, then it's
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class Photo extends DatabaseObject {

	protected static $table_name="photos";
	protected static $db_fields=array('id', 'page_id', 'filename', 'type', 'size', 'caption', 'position');

	public $id;
	public $page_id;
	public $filename;
	public $type;
	public $size;
	public $caption;
	public $position;

	private $temp_path;
	protected $upload_dir="media";
	public $errors=array();

	protected $upload_errors = array(
		// http://www.php.net/manual/en/features.file-upload.errors.php
		UPLOAD_ERR_OK			=> "No errors.",
		UPLOAD_ERR_INI_SIZE		=> "Larger than upload_max_filesize.",
		UPLOAD_ERR_FORM_SIZE	=> "Larger than form MAX_FILE_SIZE.",
		UPLOAD_ERR_PARTIAL		=> "Partial upload.",
		UPLOAD_ERR_NO_FILE		=> "No file.",
		UPLOAD_ERR_NO_TMP_DIR	=> "No temporary directory.",
		UPLOAD_ERR_CANT_WRITE	=> "Can't write to disk.",
		UPLOAD_ERR_EXTENSION	=> "File upload stopped by extension."
	);

	public function attach_file($file) {

		// perform error checking on the form parameters

		if (!$file || empty($file) || !is_array($file)) {
			// error: nothing uploaded or wrong argument usage
			$this->errors[] = "No file was uploaded.";
			return false;
		} elseif ($file['error'] != 0) {
			// error: report what PHP says went wrong
			$this->errors[] = $this->upload_errors[$file['error']];
			return false;
		} else {
			// set object attributes to the form parameters
				$this->temp_path	= $file['tmp_name'];
				$this->filename 	= basename($file['name']);
				$this->type 		= $file['type'];
				$this->size 		= (int) $file['size'];

			// don't worry about saving anything tothe database yet.
			return true;
		}
	}

	public function save() {
		// a new record won't have an id yet.
		if(isset($this->id)) {
			// really just to update the caption
			$this->update();
		} else {
			
			// make sure it has a page id
			if (empty($this->page_id)) { 
				$this->errors[] = "Page id was not found.";
				return false; }
			
			// make sure the caption is not too long for the DB
			if (strlen($this->caption) > 255) {
				$this->errors[] = "The caption can only be 255 characters long.";
				return false;
			}

			// can't save without filename and temp location
			if (empty($this->filename) || empty($this->temp_path)) {
				$this->errors[] = "The file location was not available.";
				return false;
			}

			// determine the target_path
			$target_path = SITE_ROOT .DS. 'public' .DS. $this->upload_dir .DS. $this->filename;

			// make sure a file doesn't already exist in the target location
			if (file_exists($target_path)) {
				$this->errors[] = "The file {$this->filename} already exists.";
				return false;
			}
			
			// make sure there are no errors
			// can't save if there are pre-existing errors
			if(!empty($this->errors)) { return false; }

			// attempt to move the file
			if (move_uploaded_file($this->temp_path, $target_path)) {
				// success

				// save a corresponding entry to the database
				if ($this->create()) {
					// we are done with temp_path, the file isn't there anymore
					unset($this->temp_path);
					return true;
				}
			} else {
				// failure

				// file was not moved
				$this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
				return false;
			}

		}
	}

	public function destroy() {
		// first remove the database entry
		if ($this->delete()) {
			// then remove the file
			$target_path = SITE_ROOT.DS.'public'.DS.$this->image_path();
			return unlink($target_path) ? true : false;
		} else {
			// database delete failed
			return false;
		}
	}

	public function image_path() {
		//
		//
		//

		return $this->upload_dir .DS.$this->filename;
	}

	public function size_as_text() {
		if ($this->size < 1024) {
			return "{$this->size} bytes";
		} elseif ($this->size< 1048576) {
			$size_kb = round($this->size/1024);
			return "{$size_kb} KB";
		} else {
			$size_mb = round($this->size/1048576, 1);
			return "{$size_mb} MB";
		}
	}
	
	public function input_size_as_text($size=0) {
		if ($size < 1024) {
			return "{$size} bytes";
		} elseif ($size < 1048576) {
			$size_kb = round($size/1024);
			return "{$size_kb} KB";
		} else {
			$size_mb = round($size/1048576);
			return "{$size_mb} MB";
		}
		
	}

	// Common Database Methods
	public static function find_all() {
		//
		//

		return self::find_by_sql("SELECT * FROM ".self::$table_name);
  	}
  
	public static function find_by_id($id=0) {
		global $db;
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE id = " . $db->prevent_injection($id);
		$sql .= " LIMIT 1";
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}
  
	public static function find_page_photo($page) {
		return self::find_by_id($page);
	}
	
	public static function count_all() {
		// global $db;
		$sql = "SELECT COUNT(*) FROM " . self::$table_name;
		$result_set = $db->query($sql);
		$row = $db->fetch_array($result_set);
		return array_shift($row);
	}
	
	public static function count_by_page($id) {
		global $db;
		$sql = "SELECT COUNT(*) FROM " . self::$table_name . " WHERE page_id = {$id}";
		$result_set = $db->query($sql);
		$row = $db->fetch_array($result_set);
		return array_shift($row);
	}

}

?>
