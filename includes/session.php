<?php
// a class to help work with Sessions
// in our case, primarily to manage logging users in and out

// keep in mind when working with sessions that it is generally
// inadvisable to store DB-related objects in sessions

class Session {
	private $logged_in=false;
	public $user_id;
	public $message;
	public $errors;
	
	function __construct() {
		session_start();
		$this->check_message();
		$this->check_errors();
		$this->check_login();
	}
	
	public function is_logged_in() {
		return $this->logged_in;
	}
	
	public function login($user) {
		// database should find user based on username/password
		if ($user) {
			$this->user_id = $_SESSION['user_id'] = $user->id;
			$this->logged_in = true;
		}
	}
	
	public function logout() {
		unset($_SESSION['user_id']);
		unset($this->user_id);
		$this->logged_in = true;
	}
	
	public function message($msg="") {
		if (!empty($msg)) {
			// then this is "set message"
			// make sure you understand why $this->message=$msg wouldn't work
			$_SESSION['message'] = $msg;
		} else {
			// then this is "get message"
			return $this->message;
		}
	}
	
	public function errors($err=array()) {
		if (!empty($err)) {
			// then this is "set error"
			$_SESSION["errors"] = $err;
		} else {
			return $this->errors;
		}
	}
	private function check_login() {
		if (isset($_SESSION['user_id'])) {
			$this->user_id = $_SESSION['user_id'];
			$this->logged_in = true;
		} else {
			unset($this->user_id);
			$this->logged_in = false;
		}
	}
	
	private function check_message() {
		// is there a message stored in the session?
		if (isset($_SESSION['message'])) {
			// add it as an attribute and erase the stored version
			$this->message = htmlentities($_SESSION['message']);
			unset($_SESSION['message']);
		} else {
			$this->message = "";
		}
	}
	
	private function check_errors() {
		// is there an error stored in the session?
		if (isset($_SESSION["errors"])) {
			// add it as an attribute and erase the stored version
			$this->errors = $this->form_errors($_SESSION["errors"]);
			unset($_SESSION["errors"]);
		} else {
			$this->errors = "";
		}
	}
	
	private function form_errors($errors=array()) {
		$output = "";
		if (!empty($errors)) {
			$output .= "<div class=\"error\">";
			$output .= "Please fix the following errors:";
			$output .= "<ul>";
			foreach ($errors as $key => $error) {
				$output .= "<li>{$error}</li>";
			}
			$output .= "</ul>";
			$output .= "</div>";
		}
		return $output;
	}
	
	
}

$session = new Session();
$message = $session->message();
$errors = $session->errors();

?>
